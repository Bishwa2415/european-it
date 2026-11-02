<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HomeContentController;
use App\Http\Controllers\Admin\HomePageController;
use App\Http\Controllers\Admin\MediaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin dashboard routes   (loaded from routes/web.php)
|--------------------------------------------------------------------------
| Every URL starts with /admin and every route name with "admin."
*/

Route::prefix('admin')->name('admin.')->group(function () {

    // Only for visitors who are NOT logged in
    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'create'])->name('login');
        Route::post('login', [AuthController::class, 'store'])
            ->middleware('throttle:5,1')   // max 5 tries per minute → stops password guessing
            ->name('login.store');
    });

    // Only for logged-in admins
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Home page: section order, hero, headings
        Route::get('home-page', [HomePageController::class, 'edit'])->name('home-page.edit');
        Route::put('home-page', [HomePageController::class, 'update'])->name('home-page.update');

        // Home collections: features, content-blocks, success-videos, testimonials, team-members, partners
        Route::controller(HomeContentController::class)
            ->prefix('home-content/{collection}')->name('home-content.')
            ->where(['collection' => '[a-z-]+', 'id' => '[0-9]+'])
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('{id}/edit', 'edit')->name('edit');
                Route::put('{id}', 'update')->name('update');
                Route::delete('{id}', 'destroy')->name('destroy');
            });

        // One line = 7 routes: index, create, store, show, edit, update, destroy (we don't need show)
        Route::resource('courses', CourseController::class)->except('show');

        // Pages, posts, reusable sections, popups, menus, site settings (site_contents table)
        Route::controller(ContentController::class)
            ->prefix('content/{type}')->name('content.')
            ->where(['type' => '[a-z]+'])
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('{item}/edit', 'edit')->name('edit');
                Route::put('{item}', 'update')->name('update');
                Route::delete('{item}', 'destroy')->name('destroy');
            });

        // Media library
        Route::get('media', [MediaController::class, 'index'])->name('media.index');
        Route::post('media', [MediaController::class, 'store'])->name('media.store');
        Route::delete('media', [MediaController::class, 'destroy'])->name('media.destroy');

        Route::post('logout', [AuthController::class, 'destroy'])->name('logout');
    });
});
