<?php

namespace App\Providers;

use App\Services\SiteContentService;
use App\View\Composers\NavigationComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // One shared instance per request
        $this->app->singleton(SiteContentService::class);
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

        // Every public layout part gets the dashboard-editable settings, menus and popup
        View::composer(
            ['layouts.app', 'partials.topbar', 'partials.navbar', 'partials.mobile-menu', 'partials.footer', 'partials.floating-buttons', 'partials.popup'],
            function ($view): void {
                $content = app(SiteContentService::class);

                $view->with([
                    'siteData'    => $content->settings(),
                    'headerMenu'  => $content->headerMenu(),
                    'activePopup' => $content->popup(),
                ]);
            },
        );
    }
}
