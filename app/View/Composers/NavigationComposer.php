<?php

namespace App\View\Composers;

use App\Models\Course;
use App\Models\CourseCategory;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

/**
 * Gives the header and footer their course links from the database.
 * Registered in AppServiceProvider: every time navbar / mobile menu / footer is drawn,
 * compose() runs first and adds the variables below to that view.
 *
 * The lists are cached (saved once, reused on every page) and the cache is
 * cleared automatically when a course or category is saved or deleted (see the models).
 */
class NavigationComposer
{
    public const CACHE_KEY = 'navigation.courses';

    public function compose(View $view): void
    {
        $data = Cache::rememberForever(self::CACHE_KEY, fn () => [
            'courseMenu'    => $this->courseMenu(),
            'footerCourses' => $this->footerCourses(),
        ]);

        $view->with($data);
    }

    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * "All Course" mega menu: [column => [category name => [links]]]
     */
    private function courseMenu(): array
    {
        return CourseCategory::query()
            ->with(['courses' => fn ($q) => $q->active()])
            ->orderBy('sort_order')
            ->get()
            ->filter(fn ($category) => $category->courses->isNotEmpty())
            ->groupBy('menu_column')
            ->sortKeys()
            ->map(fn ($categories) => $categories->mapWithKeys(fn ($category) => [
                $category->name => $category->courses->map(fn (Course $course) => [
                    'label' => $course->title,
                    'url'   => $course->url(),
                    'badge' => $course->badge,
                ])->all(),
            ])->all())
            ->values()
            ->all();
    }

    /**
     * Footer "Popular Courses": courses with "Show in footer" on
     */
    private function footerCourses(): array
    {
        return Course::active()
            ->where('in_footer', true)
            ->orderBy('home_order')
            ->get()
            ->map(fn (Course $course) => ['label' => $course->shortTitle(), 'url' => $course->url()])
            ->all();
    }
}
