<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Home page: get the data from the database, then hand it to the view.
     */
    public function index(): View
    {
        $popularCourses = Course::active()
            ->popular()
            ->with('plans')          // load all prices in 1 extra query (not 1 per course)
            ->orderBy('sort_order')
            ->get();

        return view('pages.home', [
            'popularCourses' => $popularCourses,
        ]);
    }
}
