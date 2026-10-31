<?php

namespace App\Providers;

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
    }
}
