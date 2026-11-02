<?php

namespace App\Providers;

use App\Models\SiteContent;
use App\View\Composers\NavigationComposer;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Header + footer get their course links from the database (app/View/Composers)
        View::composer(
            ['partials.navbar', 'partials.mobile-menu', 'partials.footer'],
            NavigationComposer::class,
        );

        View::composer(['layouts.app', 'partials.topbar', 'partials.navbar', 'partials.mobile-menu', 'partials.footer', 'partials.floating-buttons'], function ($view): void {
            $site = Cache::rememberForever('site-content.settings', fn () => SiteContent::type('setting')->where('slug', 'site')->where('is_active', true)->value('data') ?? []);
            $header = Cache::rememberForever('site-content.menu.header', fn () => SiteContent::type('menu')->where('slug', 'header')->where('is_active', true)->value('data') ?? []);
            $footer = Cache::rememberForever('site-content.menu.footer', fn () => SiteContent::type('menu')->where('slug', 'footer')->where('is_active', true)->value('data') ?? []);
            $popup = Cache::rememberForever('site-content.popup', fn () => SiteContent::type('popup')->where('is_active', true)->orderBy('sort_order')->first() ?? false);

            $siteData = $this->mergeContent(config('site'), is_array($site) ? $site : []);
            if (is_array($footer)) {
                $siteData['footer'] = $this->mergeContent($siteData['footer'], $footer);
            }

            $view->with([
                'siteData' => $siteData,
                'headerMenu' => $this->mergeContent(config('menu'), is_array($header) ? $header : []),
                'activePopup' => $popup,
            ]);
        });
    }

    private function mergeContent(array $defaults, array $overrides): array
    {
        foreach ($overrides as $key => $value) {
            if (is_array($value) && isset($defaults[$key]) && is_array($defaults[$key]) && ! array_is_list($value) && ! array_is_list($defaults[$key])) {
                $defaults[$key] = $this->mergeContent($defaults[$key], $value);

                continue;
            }

            $defaults[$key] = $value;
        }

        return $defaults;
    }
}
