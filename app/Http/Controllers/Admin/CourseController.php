<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseController extends Controller
{
    // GET /admin/courses?search=seo&category=3 → list with search, filter and pages
    public function index(Request $request): View
    {
        $courses = Course::query()
            ->with(['category', 'plans'])
            // when() runs the closure only if the value is filled in
            ->when($request->search, fn ($q, $search) => $q->where('title', 'like', "%{$search}%"))
            ->when($request->category, fn ($q, $category) => $q->where('course_category_id', $category))
            ->orderBy('course_category_id')
            ->orderBy('sort_order')
            ->paginate(15)
            ->withQueryString();   // keep ?search=... in the page 2, 3 links

        return view('admin.courses.index', [
            'courses'    => $courses,
            'categories' => CourseCategory::orderBy('sort_order')->get(),
        ]);
    }
}
