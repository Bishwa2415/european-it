<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteContent;
use App\Services\SiteContentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Dashboard → Home Page: section order (built-in + reusable sections), hero content, section headings.
 * Saved as one row in site_contents (type "home"); read on the website through SiteContentService::home().
 */
class HomePageController extends Controller
{
    public function edit(SiteContentService $content): View
    {
        $home = $content->home();
        $layout = $home['layout'];

        // Every section that can be placed: built-in ones from config/home.php + reusable sections from the dashboard
        $items = collect(config('home.sections'))
            ->map(fn ($label, $key) => ['key' => 'builtin:'.$key, 'label' => $label, 'kind' => 'Built-in section'])
            ->merge(SiteContent::type('section')->published()->orderBy('sort_order')->orderBy('title')->get()
                ->map(fn (SiteContent $section) => ['key' => 'common:'.$section->id, 'label' => $section->title, 'kind' => 'Reusable section']))
            ->keyBy('key');

        // Sections in the saved order first, then the ones not used yet
        $orderedItems = collect($layout)->filter(fn ($key) => $items->has($key))->unique()
            ->map(fn ($key) => $items->get($key))
            ->concat($items->except($layout))
            ->values();

        return view('admin.home-page.edit', [
            'items'      => $orderedItems,
            'layout'     => $layout,
            'hero'       => $home['hero'],
            'homeTitles' => $home['titles'],
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $builtIn = implode('|', array_map('preg_quote', array_keys(config('home.sections'))));

        $validated = $request->validate([
            'layout'          => ['nullable', 'array'],
            'layout.*'        => ['string', "regex:/^(builtin:({$builtIn})|common:\d+)$/"],
            'eyebrow'         => ['required', 'string', 'max:120'],
            'title_before'    => ['required', 'string', 'max:180'],
            'title_highlight' => ['required', 'string', 'max:180'],
            'title_after'     => ['required', 'string', 'max:180'],
            'description'     => ['required', 'string', 'max:2000'],
            'buttons_json'    => ['required', 'json'],
            'slides_json'     => ['required', 'json'],
            'stats_json'      => ['required', 'json'],
            'titles'          => ['required', 'array'],
            'titles.*'        => ['nullable', 'string', 'max:500'],
        ]);

        // The repeaters send JSON; decode it and validate every row
        $hero = validator([
            'buttons' => json_decode($validated['buttons_json'], true) ?? [],
            'slides'  => json_decode($validated['slides_json'], true) ?? [],
            'stats'   => json_decode($validated['stats_json'], true) ?? [],
        ], [
            'buttons'         => ['array', 'min:1', 'max:4'],
            'buttons.*.label' => ['required', 'string', 'max:80'],
            'buttons.*.url'   => ['required', 'string', 'max:255'],
            'slides'          => ['array', 'min:1', 'max:10'],
            'slides.*.image'  => ['required', 'string', 'max:255'],
            'slides.*.alt'    => ['nullable', 'string', 'max:255'],
            'stats'           => ['array', 'max:6'],
            'stats.*.icon'    => ['required', 'string', 'max:80'],
            'stats.*.count'   => ['required', 'integer', 'min:0'],
            'stats.*.suffix'  => ['nullable', 'string', 'max:20'],
            'stats.*.label'   => ['required', 'string', 'max:80'],
        ])->validate();

        $hero['stats'] = array_map(fn ($stat) => ['count' => (int) $stat['count']] + $stat, $hero['stats']);

        // Drop reusable sections that were deleted in the meantime
        $layout = collect($validated['layout'] ?? [])->unique()->filter(
            fn (string $key) => str_starts_with($key, 'builtin:')
                || SiteContent::type('section')->whereKey((int) substr($key, strlen('common:')))->exists()
        )->values()->all();

        // Only keep heading keys we know (config/home.php), ignore anything else sent in the form
        $titles = Arr::only($validated['titles'], array_keys(config('home.titles')));

        $homePage = SiteContent::type('home')->first() ?? new SiteContent(['type' => 'home']);
        $homePage->fill([
            'title'     => 'Home page',
            'is_active' => true,
            'data'      => [
                'layout' => $layout,
                'titles' => array_filter($titles, fn ($value) => $value !== null && $value !== ''),
                'hero'   => Arr::only($validated, ['eyebrow', 'title_before', 'title_highlight', 'title_after', 'description']) + $hero,
            ],
        ])->save();   // saving clears the cache (SiteContent::booted)

        return back()->with('success', 'Home page saved.');
    }
}
