<?php

namespace App\Services;

use App\Models\SiteContent;
use Illuminate\Support\Facades\Cache;

/**
 * One place that reads the editable site-wide content from the `site_contents` table:
 * settings, header/footer menu, home page data and the active popup.
 *
 * - Every value = defaults from config/*.php  +  what the admin saved in the dashboard.
 *   So the site never breaks if a dashboard row is empty or missing a key.
 * - Results are cached; SiteContent::booted() calls flush() on every save/delete,
 *   so dashboard changes show on the website immediately.
 *
 * Use it anywhere with: app(SiteContentService::class)->settings()
 * (or type-hint it in a controller method / constructor and Laravel passes it in)
 */
class SiteContentService
{
    private const CACHE_KEYS = [
        'site-content.settings',
        'site-content.menu.header',
        'site-content.menu.footer',
        'site-content.home',
        'site-content.popup',
        'site-content.course-page',
    ];

    /** Parts of the course detail page shared by every course (Dashboard → Course page) */
    public function coursePage(): array
    {
        $saved = Cache::rememberForever('site-content.course-page', fn () => $this->data('setting', 'course-page'));

        return $this->merge(config('course-page'), $saved);
    }

    /** Contact info, social links, floating buttons ... (Dashboard → Site settings, key "site") */
    public function settings(): array
    {
        $saved = Cache::rememberForever('site-content.settings', fn () => $this->data('setting', 'site'));
        $settings = $this->merge(config('site'), $saved);
        $settings['footer'] = $this->footer();

        return $settings;
    }

    /** Header + mobile menu (Dashboard → Menus, key "header") */
    public function headerMenu(): array
    {
        $saved = Cache::rememberForever('site-content.menu.header', fn () => $this->data('menu', 'header'));
        $menu = $this->merge(config('menu'), $saved);

        // "Title => links" groups: the saved version replaces the defaults completely,
        // otherwise a group deleted in the dashboard would come back from config/menu.php
        foreach (['popular', 'dropdowns'] as $key) {
            if (isset($saved[$key])) {
                $menu[$key] = $saved[$key];
            }
        }

        return $menu;
    }

    /** Footer texts and link lists (Dashboard → Menus, key "footer") */
    public function footer(): array
    {
        $saved = Cache::rememberForever('site-content.menu.footer', fn () => $this->data('menu', 'footer'));

        return $this->merge(config('site.footer'), $saved);
    }

    /** Home page: section order, hero texts, section titles (Dashboard → Home Page) */
    public function home(): array
    {
        $saved = Cache::rememberForever('site-content.home', fn () => SiteContent::type('home')->value('data') ?? []);

        return [
            'layout' => $saved['layout'] ?? array_map(fn ($key) => 'builtin:'.$key, array_keys(config('home.sections'))),
            'hero' => array_replace(config('home.hero'), $saved['hero'] ?? []),
            'titles' => array_replace(config('home.titles'), $saved['titles'] ?? []),
        ];
    }

    /** The first active popup, or null (Dashboard → Popups / modals) */
    public function popup(): ?SiteContent
    {
        // Cache stores false for "no popup", because null would mean "not cached yet"
        $popup = Cache::rememberForever('site-content.popup', fn () => SiteContent::type('popup')
            ->where('is_active', true)->orderBy('sort_order')->first() ?? false);

        return $popup ?: null;
    }

    public function flush(): void
    {
        foreach (self::CACHE_KEYS as $key) {
            Cache::forget($key);
        }
    }

    // ------------------------------------------------------------------

    private function data(string $type, string $slug): array
    {
        $data = SiteContent::type($type)->where('slug', $slug)->where('is_active', true)->value('data');

        return is_array($data) ? $data : [];
    }

    /**
     * Deep merge for "settings" style arrays: keyed arrays merge key by key,
     * lists (e.g. phones, links) are replaced completely by the saved version.
     */
    private function merge(array $defaults, array $saved): array
    {
        foreach ($saved as $key => $value) {
            $bothAssoc = is_array($value) && is_array($defaults[$key] ?? null)
                && ! array_is_list($value) && ! array_is_list($defaults[$key]);

            $defaults[$key] = $bothAssoc ? $this->merge($defaults[$key], $value) : $value;
        }

        return $defaults;
    }
}
