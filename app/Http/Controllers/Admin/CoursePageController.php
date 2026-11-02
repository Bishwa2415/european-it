<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ReadsRepeaterJson;
use App\Http\Controllers\Controller;
use App\Models\SiteContent;
use App\Services\SiteContentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\View\View;

/**
 * Dashboard → Course page: the parts of /course/{slug} that are the same for every course
 * (buttons, Certified By, section titles, "Why European IT? !" cards, "Still Confused?" band).
 * Saved in site_contents (type "setting", slug "course-page"); defaults in config/course-page.php.
 */
class CoursePageController extends Controller
{
    use ReadsRepeaterJson;

    // key => [label, rules]
    public const FIELDS = [
        'admission_label' => ['Admission button text', ['required', 'string', 'max:40']],
        'seminar_label' => ['Seminar button text', ['required', 'string', 'max:40']],
        'seminar_url' => ['Seminar button link', ['required', 'string', 'max:255']],
        'certified_title' => ['"Certified By" title', ['required', 'string', 'max:60']],
        'certified_image' => ['"Certified By" logos (image path)', ['nullable', 'string', 'max:255']],
        'price_card_image' => ['Price card background (image path)', ['nullable', 'string', 'max:255']],
        'dots_image' => ['Dots behind the banner (image path)', ['nullable', 'string', 'max:255']],
        'help_title' => ['Help box title', ['required', 'string', 'max:60']],
        'help_text' => ['Help box text', ['required', 'string', 'max:150']],
        'help_phone' => ['Help box phone', ['required', 'string', 'max:30']],
        'community_title' => ['Community box title', ['required', 'string', 'max:60']],
        'community_text' => ['Community box text', ['required', 'string', 'max:150']],
        'community_label' => ['Community button text', ['required', 'string', 'max:40']],
        'community_url' => ['Community button link', ['required', 'url', 'max:255']],
    ];

    public function edit(SiteContentService $content): View
    {
        return view('admin.course-page.edit', ['page' => $content->coursePage()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $titleKeys = array_keys(config('course-page.titles'));

        $validated = $request->validate(array_map(fn ($field) => $field[1], self::FIELDS) + [
            'titles' => ['required', 'array'],
            'titles.*.*' => ['nullable', 'string', 'max:120'],
        ]);

        $data = Arr::only($validated, array_keys(self::FIELDS)) + [
            // Only known sections, only before / highlight / after
            'titles' => collect(Arr::only($validated['titles'], $titleKeys))
                ->map(fn ($title) => array_map(fn ($v) => (string) $v, Arr::only($title, ['before', 'highlight', 'after'])))
                ->all(),
            'why_european' => $this->rows($request->input('why_european_json'), [
                '*.icon' => ['nullable', 'string', 'max:255'],
                '*.title' => ['required', 'string', 'max:120'],
                '*.text' => ['nullable', 'string', 'max:500'],
            ], 'Why European IT cards'),
        ];

        SiteContent::updateOrCreate(
            ['type' => 'setting', 'slug' => 'course-page'],
            ['title' => 'Course page', 'is_active' => true, 'data' => $data],
        );   // saving clears the cache (SiteContent::booted)

        return back()->with('success', 'Course page saved.');
    }
}
