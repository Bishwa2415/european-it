<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CourseRequest;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Support\CourseContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/**
 * Dashboard → Courses: list, add, edit, delete.
 * Routes: Route::resource('courses', ...) in routes/admin.php
 */
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
            'courses' => $courses,
            'categories' => CourseCategory::orderBy('sort_order')->get(),
        ]);
    }

    // GET /admin/courses/create → empty form
    public function create(): View
    {
        return view('admin.courses.create', [
            'course' => new Course(['is_active' => true]),   // empty course so the form can use $course->...
            'categories' => CourseCategory::orderBy('sort_order')->get(),
        ]);
    }

    // POST /admin/courses → save a new course
    public function store(CourseRequest $request): RedirectResponse
    {
        $course = DB::transaction(function () use ($request) {
            $course = Course::create($this->courseData($request));
            $this->savePlans($course, $request->input('plans', []));

            return $course;
        });

        return redirect()
            ->route('admin.courses.edit', $course)
            ->with('success', "“{$course->title}” has been added.");
    }

    // GET /admin/courses/{course}/edit → form filled with this course
    // {course} in the URL is an id; Laravel loads the Course for us ("route model binding")
    public function edit(Course $course): View
    {
        return view('admin.courses.edit', [
            'course' => $course->load('plans'),
            'categories' => CourseCategory::orderBy('sort_order')->get(),
        ]);
    }

    // PUT /admin/courses/{course} → save changes
    public function update(CourseRequest $request, Course $course): RedirectResponse
    {
        DB::transaction(function () use ($request, $course) {
            $course->update($this->courseData($request, $course));
            $this->savePlans($course, $request->input('plans', []));
        });

        return back()->with('success', "“{$course->title}” has been updated.");
    }

    // DELETE /admin/courses/{course}
    public function destroy(Course $course): RedirectResponse
    {
        $this->deleteUploadedThumbnail($course->thumbnail);
        $course->delete();   // its plans are deleted too (cascadeOnDelete in the migration)

        return redirect()
            ->route('admin.courses.index')
            ->with('success', "“{$course->title}” has been deleted.");
    }

    // ------------------------------------------------------------------

    /**
     * Validated course fields + the uploaded thumbnail path.
     */
    private function courseData(CourseRequest $request, ?Course $course = null): array
    {
        // Course page content tabs (curriculum, projects, tools ...) → courses.details. First, so a mistake there
        // stops the save before a new thumbnail is stored.
        $details = CourseContent::fromRequest($request, $course);

        // validated() = only the fields that passed the rules (never trust raw input)
        $data = collect($request->validated())->except(['thumbnail', 'plans'])->all() + ['details' => $details];

        if ($request->hasFile('thumbnail')) {
            $this->deleteUploadedThumbnail($course?->thumbnail);
            // Saves to storage/app/public/courses/random-name.webp and returns "courses/random-name.webp"
            $data['thumbnail'] = $request->file('thumbnail')->store('courses', 'public');
        }

        return $data;
    }

    /**
     * Create, update or remove the Online / Offline plans.
     */
    private function savePlans(Course $course, array $plans): void
    {
        foreach (['online', 'offline'] as $type) {
            $plan = $plans[$type] ?? [];

            if (empty($plan['enabled'])) {
                $course->plans()->where('type', $type)->delete();

                continue;
            }

            $course->plans()->updateOrCreate(['type' => $type], [
                'duration' => $plan['duration'] ?? null,
                'total_classes' => $plan['total_classes'] ?? null,
                'classes_per_week' => $plan['classes_per_week'] ?? null,
                'regular_price' => $plan['regular_price'],
                'sale_price' => $plan['sale_price'] ?? null,
                'is_active' => true,
            ]);
        }
    }

    /**
     * Remove an old uploaded image. Seeded images in public/images/ are never deleted.
     */
    private function deleteUploadedThumbnail(?string $path): void
    {
        if ($path && ! str_starts_with($path, 'images/')) {
            Storage::disk('public')->delete($path);
        }
    }
}
