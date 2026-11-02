<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\View\View;

class HomePageController extends Controller
{
    public const SECTIONS = [
        'hero' => 'Hero banner',
        'popular-courses' => 'Popular courses',
        'fourth-ir' => '4th Industrial courses',
        'why-choose' => 'Why choose us',
        'specialty' => 'Specialty and trusted service',
        'success-stories' => 'Success stories',
        'testimonials' => 'Testimonials',
        'team' => 'Support team',
        'partners' => 'Partners',
    ];

    public function edit(): View
    {
        $homePage = SiteContent::type('home')->first();
        $layout = $homePage?->data['layout'] ?? array_map(fn ($key) => 'builtin:'.$key, array_keys(self::SECTIONS));
        $commonSections = SiteContent::type('section')->where('is_active', true)->orderBy('sort_order')->orderBy('title')->get();
        $items = collect(self::SECTIONS)->map(fn ($label, $key) => [
            'key' => 'builtin:'.$key,
            'label' => $label,
            'kind' => 'Built-in section',
        ])->merge($commonSections->map(fn (SiteContent $section) => [
            'key' => 'common:'.$section->id,
            'label' => $section->title,
            'kind' => 'Reusable section',
        ]));

        $byKey = $items->keyBy('key');
        $orderedItems = collect($layout)
            ->filter(fn ($key) => $byKey->has($key))
            ->unique()
            ->map(fn ($key) => $byKey->get($key))
            ->concat($items->reject(fn ($item) => in_array($item['key'], $layout, true)))
            ->values();

        return view('admin.home-page.edit', [
            'items' => $orderedItems,
            'layout' => $layout,
            'hero' => array_replace(config('home.hero'), $homePage?->data['hero'] ?? []),
            'homeTitles' => array_replace(config('home.titles'), $homePage?->data['titles'] ?? []),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'layout' => ['nullable', 'array'],
            'layout.*' => ['string', 'max:255', 'regex:/^(builtin:(hero|popular-courses|fourth-ir|why-choose|specialty|success-stories|testimonials|team|partners)|common:\d+)$/'],
            'eyebrow' => ['required', 'string', 'max:120'],
            'title_before' => ['required', 'string', 'max:180'],
            'title_highlight' => ['required', 'string', 'max:180'],
            'title_after' => ['required', 'string', 'max:180'],
            'description' => ['required', 'string', 'max:2000'],
            'buttons_json' => ['required', 'json'],
            'slides_json' => ['required', 'json'],
            'stats_json' => ['required', 'json'],
            'section_titles_json' => ['required', 'json'],
        ]);

        $heroArrays = [
            'buttons' => json_decode($validated['buttons_json'], true),
            'slides' => json_decode($validated['slides_json'], true),
            'stats' => json_decode($validated['stats_json'], true),
            'section_titles' => json_decode($validated['section_titles_json'], true),
        ];
        validator($heroArrays, [
            'buttons' => ['array', 'min:1', 'max:4'],
            'buttons.*.label' => ['required', 'string', 'max:80'],
            'buttons.*.url' => ['required', 'string', 'max:255'],
            'slides' => ['array', 'max:10'],
            'slides.*.image' => ['required', 'string', 'max:255'],
            'slides.*.alt' => ['nullable', 'string', 'max:255'],
            'stats' => ['array', 'max:6'],
            'stats.*.icon' => ['required', 'string', 'max:80'],
            'stats.*.count' => ['required', 'integer', 'min:0'],
            'stats.*.suffix' => ['nullable', 'string', 'max:20'],
            'stats.*.label' => ['required', 'string', 'max:80'],
            'section_titles' => ['array'],
            'section_titles.*' => ['required', 'string', 'max:255'],
        ])->validate();

        $layout = collect($validated['layout'] ?? [])->unique()->filter(function (string $key): bool {
            if (str_starts_with($key, 'builtin:')) {
                return true;
            }

            $id = (int) substr($key, strlen('common:'));

            return SiteContent::type('section')->whereKey($id)->exists();
        })->values()->all();

        $homePage = SiteContent::type('home')->first() ?? new SiteContent(['type' => 'home']);
        $data = $homePage->data ?? [];
        $data['layout'] = $layout;
        $data['titles'] = $heroArrays['section_titles'];
        unset($heroArrays['section_titles']);
        $data['hero'] = [
            ...Arr::only($validated, ['eyebrow', 'title_before', 'title_highlight', 'title_after', 'description']),
            ...$heroArrays,
        ];
        $homePage->fill([
            'type' => 'home',
            'title' => 'Home page layout',
            'data' => $data,
            'is_active' => true,
        ])->save();

        return back()->with('success', 'Home page layout saved.');
    }
}
