<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HomeContentController;
use App\Http\Controllers\Admin\HomePageController;
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
        Route::get('home-page', [HomePageController::class, 'edit'])->name('home-page.edit');
        Route::put('home-page', [HomePageController::class, 'update'])->name('home-page.update');
        Route::get('home-content/{collection}', [HomeContentController::class, 'index'])->name('home-content.index');
        Route::get('home-content/{collection}/create', [HomeContentController::class, 'create'])->name('home-content.create');
        Route::post('home-content/{collection}', [HomeContentController::class, 'store'])->name('home-content.store');
        Route::get('home-content/{collection}/{id}/edit', [HomeContentController::class, 'edit'])->name('home-content.edit');
        Route::put('home-content/{collection}/{id}', [HomeContentController::class, 'update'])->name('home-content.update');
        Route::delete('home-content/{collection}/{id}', [HomeContentController::class, 'destroy'])->name('home-content.destroy');
        // One line = 7 routes: index, create, store, show, edit, update, destroy (we don't need show)
        Route::resource('courses', CourseController::class)->except('show');

        Route::get('content/{type}', [ContentController::class, 'index'])->name('content.index');
        Route::get('content/{type}/create', [ContentController::class, 'create'])->name('content.create');
        Route::post('content/{type}', [ContentController::class, 'store'])->name('content.store');
        Route::get('content/{type}/{item}/edit', [ContentController::class, 'edit'])->name('content.edit');
        Route::put('content/{type}/{item}', [ContentController::class, 'update'])->name('content.update');
        Route::delete('content/{type}/{item}', [ContentController::class, 'destroy'])->name('content.destroy');

        Route::post('logout', [AuthController::class, 'destroy'])->name('logout');
    });
});
