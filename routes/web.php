<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\SiteContentController;
use Illuminate\Support\Facades\Route;

// Home page: HomeController reads every section from the database, then shows pages/home.blade.php
Route::get('/', [HomeController::class, 'index'])->name('home');

// Dashboard / CMS
require __DIR__.'/admin.php';

// Posts written in Dashboard → Posts
Route::get('/blog/{slug}', [SiteContentController::class, 'post'])->name('posts.show')->where('slug', '[A-Za-z0-9_-]+');

// ⚠️ KEEP THIS LAST: pages written in Dashboard → Pages (/about-us, /refund-policy ...).
// "/{slug}" matches almost every URL, so any new route (e.g. /course/{slug}) must be added ABOVE this line.
Route::get('/{slug}', [SiteContentController::class, 'page'])->name('pages.show')->where('slug', '[A-Za-z0-9_-]+');
