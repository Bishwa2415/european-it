<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\SiteContentController;
use Illuminate\Support\Facades\Route;

// Home page: HomeController reads courses from the database, then shows pages/home.blade.php
Route::get('/', [HomeController::class, 'index'])->name('home');

// Dashboard / CMS
require __DIR__.'/admin.php';

Route::get('/blog/{slug}', [SiteContentController::class, 'post'])->name('posts.show');
Route::get('/{slug}', [SiteContentController::class, 'page'])->name('pages.show');
