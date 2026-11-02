<?php

namespace App\Support;

use App\Http\Controllers\Admin\Concerns\ReadsRepeaterJson;
use App\Models\Course;
use Illuminate\Http\Request;

/**
 * The course page content that lives in courses.details (JSON):
 * hero texts + banner, and the lists Curriculum, Careers, Reasons, Mentors, Projects, Tools, Videos, At a glance.
 *
 * LISTS drives the dashboard form (one tab + one <x-admin.repeater> per list, resources/views/admin/courses/_content.blade.php)
 * and the validation in fromRequest(). Shown on the website by resources/views/courses/partials/*.
 *   'key' => [tab title, help text, fields, required field keys]
 */
class CourseContent
{
    use ReadsRepeaterJson;

    private const TEXT = ['string', 'max:255'];

    private const LONG = ['string', 'max:5000'];

    public const VIDEO_SOURCES = ['youtube' => 'YouTube', 'upload' => 'Uploaded file', 'url' => 'Other link (Vimeo, Facebook, .mp4)'];

    public const LISTS = [
        'curriculum' => ['Curriculum', 'Topics: one per line. Start a line with "- " for a bullet; other lines become small headings.', [
            'title' => 'Class title',
            'details' => ['label' => 'Topics', 'type' => 'textarea', 'rows' => 5, 'span' => 2],
        ], ['title']],
        'careers' => ['Career', null, [
            'title' => 'Job title',
            'text' => ['label' => 'Text', 'type' => 'textarea', 'rows' => 2, 'span' => 2],
        ], ['title']],
        'reasons' => ['Why this course', null, [
            'icon' => ['label' => 'Icon', 'type' => 'upload'],
            'title' => 'Title',
            'text' => ['label' => 'Text', 'type' => 'textarea', 'rows' => 3, 'span' => 2],
        ], ['title']],
        'mentors' => ['Mentor / Trainer', 'Badges: separate with commas, e.g. "NSDA Certified, Upwork Verified".', [
            'photo' => ['label' => 'Photo', 'type' => 'upload'],
            'name' => 'Name',
            'designation' => 'Designation',
            'badges' => 'Badges',
            'url' => 'Portfolio link',
        ], ['name']],
        'projects' => ['Projects', 'Tools: names from the Tools tab separated by commas (their logos are shown), or image paths.', [
            'image' => ['label' => 'Picture', 'type' => 'upload'],
            'title' => 'Title',
            'text' => ['label' => 'Text', 'type' => 'textarea', 'rows' => 3, 'span' => 2],
            'icons' => 'Tools used',
        ], ['title']],
        'tools' => ['Tools', null, [
            'icon' => ['label' => 'Logo', 'type' => 'upload'],
            'name' => 'Name',
        ], ['name']],
        'videos' => ['Videos', 'YouTube / other link: paste the link. Uploaded file: click the upload button (mp4 or webm).', [
            'title' => 'Title (optional)',
            'source' => ['label' => 'Source', 'type' => 'select', 'options' => self::VIDEO_SOURCES],
            'url' => 'Link',
            'file' => ['label' => 'File', 'type' => 'upload', 'accept' => 'video'],
        ], []],
        'glance' => ['At a glance', null, [
            'icon' => ['label' => 'Icon', 'type' => 'upload'],
            'text' => 'Text',
        ], ['text']],
    ];

    /** Hero student photos (small round pictures next to the student count) */
    public const AVATAR_FIELDS = ['photo' => ['label' => 'Photo', 'type' => 'upload']];

    /** Validated details array from the course form. $course = the course being edited (null when adding) */
    public static function fromRequest(Request $request, ?Course $course = null): array
    {
        return (new self)->build($request, $course);
    }

    private function build(Request $request, ?Course $course): array
    {
        $validated = $request->validate([
            'tagline' => ['nullable', ...self::TEXT],
            'intro' => ['nullable', 'string', 'max:500'],
            'banner' => ['nullable', ...self::TEXT],
            'banner_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $details = [
            'tagline' => $validated['tagline'] ?? null,
            'intro' => $validated['intro'] ?? null,
            'banner' => $this->storeImage($request->file('banner_file'), $validated['banner'] ?? null, $course?->detail('banner'), 'uploads/courses'),
            'avatars' => array_column($this->rows($request->input('avatars_json'), ['*.photo' => ['required', ...self::TEXT]], 'Student photos'), 'photo'),
        ];

        foreach (self::LISTS as $key => [$label, $hint, $fields, $required]) {
            $rules = [];
            foreach ($fields as $field => $definition) {
                $type = is_array($definition) ? $definition['type'] : 'text';
                $rules["*.{$field}"] = match (true) {
                    $type === 'select' => ['nullable', 'in:'.implode(',', array_keys($definition['options']))],
                    in_array($field, $required, true) => ['required', ...($type === 'textarea' ? self::LONG : self::TEXT)],
                    default => ['nullable', ...($type === 'textarea' ? self::LONG : self::TEXT)],
                };
            }
            // Dropdown columns always have a value, so they don't count when checking for empty rows
            $choiceKeys = array_keys(array_filter($fields, fn ($f) => is_array($f) && $f['type'] === 'select'));
            $details[$key] = $this->rows($request->input("{$key}_json"), $rules, $label, $choiceKeys);
        }

        return $details;
    }
}
