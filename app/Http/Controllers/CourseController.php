<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CoursePlan;
use App\Services\SiteContentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Website: course detail page  /course/{slug}
 * Content: courses.details (per course) + SiteContentService::coursePage() (same for every course).
 */
class CourseController extends Controller
{
    // {course:slug} in the route → Laravel finds the Course by its slug (404 if none)
    public function show(Course $course, SiteContentService $content): View|RedirectResponse
    {
        abort_unless($course->is_active, 404);

        // Courses that live on another website (e.g. kidsict.com) go there
        if ($course->custom_url) {
            return redirect()->away($course->url());
        }

        // Offline card first, like the live site
        $plans = $course->plans()->where('is_active', true)->get()
            ->sortBy(fn (CoursePlan $plan) => $plan->type === 'offline' ? 0 : 1)->values();

        // Curriculum: first half in the left column, the rest in the right column
        $curriculum = $course->detail('curriculum', []);
        $half = (int) ceil(count($curriculum) / 2);

        return view('courses.show', [
            'course' => $course,
            'plans' => $plans,
            'page' => $content->coursePage(),
            'admissionUrl' => $content->settings()['admission_url'],
            'curriculumCols' => array_filter([array_slice($curriculum, 0, $half), array_slice($curriculum, $half)]),
        ]);
    }
}
