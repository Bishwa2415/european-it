<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// Home page: HomeController reads courses from the database, then shows pages/home.blade.php
Route::get('/', [HomeController::class, 'index'])->name('home');
