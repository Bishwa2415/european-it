<?php

namespace App\Support;

use App\Http\Controllers\Admin\Concerns\ReadsRepeaterJson;
use Illuminate\Http\Request;

/**
 * Every section of a landing page and its fields (same order as on the live site).
 * One list drives three things:
 *   - the dashboard form (resources/views/admin/landing-pages/form.blade.php: one tab per section)
 *   - the validation (fromRequest below)
 *   - what resources/views/landing/sections/{key}.blade.php can read
 *
 * Field types:
 *   'Label'                                   → one line of text
 *   ['type' => 'textarea']                    → several lines
 *   ['type' => 'upload']                      → picture (path + upload button), 'accept' => 'video' for video files
 *   ['type' => 'select', 'options' => [...]]
 *   ['type' => 'list', 'fields' => [...]]     → repeater: rows with the given fields
 * Icons: a Bootstrap icon name (bi-laptop, see icons.getbootstrap.com) or a picture path.
 * 'only' => 'offline' / 'online' → the section exists only on that design.
 */
class LandingContent
{
    use ReadsRepeaterJson;

    private const VIDEO = ['type' => 'select', 'options' => CourseContent::VIDEO_SOURCES];

    public const SECTIONS = [
        'hero' => ['label' => 'Hero', 'fields' => [
            'title' => 'Title',
            'subtitle' => 'Subtitle (e.g. Online Course)',
            'students_text' => 'Students text',
            'reviews_text' => 'Reviews text',
            'reviews_url' => 'Reviews link',
            'intro' => ['label' => 'Intro', 'type' => 'textarea', 'rows' => 2],
            'button_label' => 'Button text',
            'video_label' => 'Label on the video (e.g. watch the demo class)',
            'video_source' => ['label' => 'Video source'] + self::VIDEO,
            'video_url' => 'Video link',
            'video_file' => ['label' => 'Video file', 'type' => 'upload', 'accept' => 'video'],
            'video_cover' => ['label' => 'Video cover picture', 'type' => 'upload'],
            'background' => ['label' => 'Background picture', 'type' => 'upload'],
            'avatars' => ['label' => 'Student photos', 'type' => 'list', 'fields' => ['image' => ['label' => 'Photo', 'type' => 'upload']]],
            'floating_icons' => ['label' => 'Small floating logos', 'type' => 'list', 'fields' => ['image' => ['label' => 'Logo', 'type' => 'upload']]],
        ]],
        'highlights' => ['label' => 'Certified + facts', 'fields' => [
            'certified_title' => 'Certified title',
            'certified_image' => ['label' => 'Certified logos', 'type' => 'upload'],
            'items' => ['label' => 'Facts bar', 'type' => 'list', 'fields' => ['icon' => 'Icon', 'label' => 'Label', 'value' => 'Value']],
        ]],
        'webinar' => ['label' => 'Free webinar', 'fields' => [
            'badge' => 'Red badge text',
            'title' => 'Webinar title',
            'note' => 'Note under the title',
            'form_title' => 'Form title',
            'button_label' => 'Form button text',
        ]],
        'learn' => ['label' => 'What you learn', 'fields' => [
            'title' => 'Title',
            'subtitle' => 'Subtitle',
            'items' => ['label' => 'Modules', 'type' => 'list', 'fields' => ['icon' => 'Icon', 'title' => 'Title', 'text' => ['label' => 'Text', 'type' => 'textarea', 'rows' => 2, 'span' => 2]]],
        ]],
        'outline' => ['label' => 'Course outline', 'fields' => [
            'title' => 'Title',
            'button_label' => 'Button text',
            'file' => 'Outline file link (PDF / Google Drive)',
        ]],
        'reasons' => ['label' => 'Why this course', 'fields' => [
            'title' => 'Title',
            'items' => ['label' => 'Cards', 'type' => 'list', 'fields' => ['icon' => 'Icon', 'title' => 'Title', 'text' => ['label' => 'Text', 'type' => 'textarea', 'rows' => 2, 'span' => 2]]],
            'button_label' => 'Button text',
        ]],
        'labs' => ['label' => 'Labs gallery', 'only' => 'offline', 'fields' => [
            'title' => 'Title',
            'images' => ['label' => 'Pictures', 'type' => 'list', 'fields' => ['image' => ['label' => 'Picture', 'type' => 'upload']]],
        ]],
        'mentor' => ['label' => 'Mentor', 'fields' => [
            'photo' => ['label' => 'Photo', 'type' => 'upload'],
            'name' => 'Name',
            'designation' => 'Designation',
            'company' => 'Company line',
            'facts' => ['label' => 'Facts (one per line)', 'type' => 'textarea', 'rows' => 2],
            'bio' => ['label' => 'Short text', 'type' => 'textarea', 'rows' => 2],
            'badges' => 'Badges (comma separated)',
            'url' => 'Portfolio link',
        ]],
        'projects' => ['label' => 'Projects', 'fields' => [
            'title' => 'Title',
            'items' => ['label' => 'Projects', 'type' => 'list', 'fields' => [
                'image' => ['label' => 'Picture', 'type' => 'upload'], 'title' => 'Title',
                'text' => ['label' => 'Text', 'type' => 'textarea', 'rows' => 3, 'span' => 2], 'icons' => 'Tool logos (paths, comma separated)',
            ]],
        ]],
        'tools' => ['label' => 'Tools', 'fields' => [
            'title' => 'Title',
            'items' => ['label' => 'Tools', 'type' => 'list', 'fields' => ['icon' => ['label' => 'Logo', 'type' => 'upload'], 'name' => 'Name']],
        ]],
        'stories' => ['label' => 'Success stories', 'fields' => [
            'title' => 'Title',
            'items' => ['label' => 'Videos', 'type' => 'list', 'fields' => [
                'name' => 'Student name', 'source' => ['label' => 'Source'] + self::VIDEO, 'url' => 'Link',
                'file' => ['label' => 'File', 'type' => 'upload', 'accept' => 'video'], 'cover' => ['label' => 'Cover', 'type' => 'upload'],
            ]],
        ]],
        'gifts' => ['label' => 'Early bird gifts', 'fields' => [
            'title' => 'Title (black part)',
            'title_red' => 'Title (red part – several words separated by a space rotate one by one)',
            'subtitle_red' => 'Subtitle (red start)',
            'subtitle' => 'Subtitle',
            'items' => ['label' => 'Gifts', 'type' => 'list', 'fields' => ['icon' => 'Icon', 'title' => 'Title', 'value' => 'Value line', 'text' => ['label' => 'Text', 'type' => 'textarea', 'rows' => 2, 'span' => 2]]],
            'note' => 'Note under the cards',
            'button_label' => 'Button text',
        ]],
        'offer' => ['label' => 'Guarantee + offer', 'fields' => [
            'badge_image' => ['label' => 'Guarantee badge picture', 'type' => 'upload'],
            'top_image' => ['label' => 'Small picture above the box', 'type' => 'upload'],
            'guarantee_text' => ['label' => 'Guarantee text', 'type' => 'textarea', 'rows' => 3],
            'guarantee_note' => 'Yellow line',
            'countdown_title' => 'Countdown title',
            'regular_text' => 'Regular price line (e.g. রেগুলার মূল্য)',
            'regular_price' => 'Regular price (crossed out)',
            'offer_text' => 'Offer line',
            'button_label' => 'Button text',
        ]],
        'students' => ['label' => 'Successful students', 'fields' => [
            'title' => 'Title',
            'items' => ['label' => 'Students', 'type' => 'list', 'fields' => ['photo' => ['label' => 'Photo', 'type' => 'upload'], 'name' => 'Name', 'role' => 'Job / company']],
        ]],
        'why' => ['label' => 'Why European IT', 'fields' => [
            'title' => 'Title',
            'items' => ['label' => 'Cards', 'type' => 'list', 'fields' => ['icon' => 'Icon', 'title' => 'Title', 'text' => ['label' => 'Text', 'type' => 'textarea', 'rows' => 3, 'span' => 2]]],
        ]],
        'testimonials' => ['label' => 'Student feedback', 'fields' => [
            'title' => 'Title',
            'items' => ['label' => 'Feedback', 'type' => 'list', 'fields' => [
                'photo' => ['label' => 'Photo', 'type' => 'upload'], 'name' => 'Name', 'course' => 'Course',
                'text' => ['label' => 'Text', 'type' => 'textarea', 'rows' => 3, 'span' => 2],
            ]],
        ]],
        'screenshots' => ['label' => 'Review screenshots', 'fields' => [
            'title' => 'Title',
            'images' => ['label' => 'Screenshots', 'type' => 'list', 'fields' => ['image' => ['label' => 'Picture', 'type' => 'upload']]],
        ]],
        'certificate' => ['label' => 'Certificate', 'fields' => [
            'title' => 'Title',
            'text' => 'Text',
            'image' => ['label' => 'Certificate picture', 'type' => 'upload'],
            'decoration' => ['label' => 'Decoration around the frame (confetti)', 'type' => 'upload'],
        ]],
        'glance' => ['label' => 'At a glance', 'fields' => [
            'title' => 'Title',
            'items' => ['label' => 'Items', 'type' => 'list', 'fields' => ['icon' => 'Icon', 'text' => 'Text']],
            'button_label' => 'Button text',
        ]],
        'confused' => ['label' => 'Still confused', 'fields' => [
            'title' => 'Title',
            'text' => 'Text',
            'phone' => 'WhatsApp / phone number',
            'background' => ['label' => 'Background picture (offline box)', 'type' => 'upload'],
        ]],
        'faq' => ['label' => 'FAQ', 'fields' => [
            'title' => 'Title',
            'items' => ['label' => 'Questions', 'type' => 'list', 'fields' => ['question' => 'Question', 'answer' => ['label' => 'Answer', 'type' => 'textarea', 'rows' => 3, 'span' => 2]]],
        ]],
        'accept' => ['label' => 'We accept', 'fields' => [
            'title' => 'Title',
            'image' => ['label' => 'Payment methods picture', 'type' => 'upload'],
        ]],
    ];

    /** Header bar + buttons: not a section, saved under content.settings */
    public const SETTINGS = [
        'countdown_hours' => 'Countdown length in hours (restarts for each visitor)',
        'enroll_url' => 'Enroll button link (empty = the course admission link)',
    ];

    /** Sections of one design (online / offline), in page order */
    public static function sectionsFor(string $type): array
    {
        return array_filter(self::SECTIONS, fn ($section) => ! isset($section['only']) || $section['only'] === $type);
    }

    /** Normalised field definition: 'Title' → ['label' => 'Title', 'type' => 'text'] */
    public static function field(string|array $definition): array
    {
        return is_array($definition) ? $definition + ['type' => 'text'] : ['label' => $definition, 'type' => 'text'];
    }

    /** The whole content array from the dashboard form (content[...] inputs + lists[...] JSON strings) */
    public static function fromRequest(Request $request, string $type): array
    {
        return (new self)->build($request, $type);
    }

    private function build(Request $request, string $type): array
    {
        $content = ['settings' => [
            'countdown_hours' => max(0, (int) $request->input('settings.countdown_hours')),
            'enroll_url' => mb_substr((string) $request->input('settings.enroll_url'), 0, 255),
        ]];

        // All sections are saved (also the ones of the other design), so switching online ↔ offline loses nothing
        foreach (self::SECTIONS as $key => $section) {
            $data = ['show' => $request->boolean("content.{$key}.show")];
            $rules = [];

            foreach ($section['fields'] as $name => $definition) {
                $field = self::field($definition);

                if ($field['type'] === 'list') {
                    $rowFields = array_map(self::field(...), $field['fields']);
                    $rowRules = array_map(fn ($f) => ['nullable', 'string', $f['type'] === 'textarea' ? 'max:5000' : 'max:255'], $rowFields);
                    $choiceKeys = array_keys(array_filter($rowFields, fn ($f) => $f['type'] === 'select'));
                    $data[$name] = $this->rows($request->input("lists.{$key}.{$name}"),
                        collect($rowRules)->mapWithKeys(fn ($r, $k) => ["*.{$k}" => $r])->all(), $section['label'].' → '.$field['label'], $choiceKeys);

                    continue;
                }

                $rules["content.{$key}.{$name}"] = match ($field['type']) {
                    'select' => ['nullable', 'in:'.implode(',', array_keys($field['options']))],
                    'textarea' => ['nullable', 'string', 'max:5000'],
                    default => ['nullable', 'string', 'max:255'],
                };
            }

            $validated = $request->validate($rules);
            foreach ($section['fields'] as $name => $definition) {
                if (self::field($definition)['type'] !== 'list') {
                    $data[$name] = data_get($validated, "content.{$key}.{$name}");
                }
            }

            $content[$key] = $data;
        }

        return $content;
    }
}
