<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\DashboardController;
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
        Route::get('courses', [CourseController::class, 'index'])->name('courses.index');

        Route::post('logout', [AuthController::class, 'destroy'])->name('logout');
    });
});
