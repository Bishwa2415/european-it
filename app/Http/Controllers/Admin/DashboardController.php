<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseCategory;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Numbers for the cards on the dashboard home page
        $stats = [
            ['label' => 'Total Courses',   'value' => Course::count(),            'icon' => 'bi-journal-bookmark'],
            ['label' => 'Active Courses',  'value' => Course::active()->count(),  'icon' => 'bi-check-circle'],
            ['label' => 'Popular Courses', 'value' => Course::popular()->count(), 'icon' => 'bi-star'],
            ['label' => 'Categories',      'value' => CourseCategory::count(),    'icon' => 'bi-grid'],
        ];

        return view('admin.dashboard', [
            'stats'         => $stats,
            'latestCourses' => Course::with('category')->latest('updated_at')->take(5)->get(),
        ]);
    }
}
