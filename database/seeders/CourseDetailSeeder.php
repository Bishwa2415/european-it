<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

/**
 * Fills the course detail pages from database/data/course-details/{slug}.json
 * (content copied from the live europeanit-inst.com course pages).
 * Run: php artisan db:seed --class=CourseDetailSeeder   (safe to run again: it overwrites)
 */
class CourseDetailSeeder extends Seeder
{
    // Lists kept in courses.details (the JSON also has title / plans / counts, saved elsewhere)
    private const DETAIL_KEYS = [
        'tagline', 'intro', 'banner', 'avatars',
        'curriculum', 'careers', 'reasons', 'mentors', 'projects', 'tools', 'videos', 'glance',
    ];

    public function run(): void
    {
        foreach (File::glob(database_path('data/course-details/*.json')) as $file) {
            $course = Course::where('slug', basename($file, '.json'))->first();
            if (! $course) {
                continue;
            }

            $data = json_decode(File::get($file), true);

            $course->update(array_filter([
                'details' => Arr::only($data, self::DETAIL_KEYS),
                'overview' => $data['overview'] ?: null,
                'student_count' => $data['student_count'] ?: null,
                'review_count' => $data['review_count'] ?: null,
            ]));

            $this->savePlans($course, $data['plans'] ?? []);
        }
    }

    /** "4 Months", "32 Classes", "2 Day", prices [regular, sale] → course_plans row */
    private function savePlans(Course $course, array $plans): void
    {
        foreach ($plans as $type => $plan) {
            $info = $plan['info'] ?? [];
            [$regular, $sale] = array_pad($plan['prices'] ?? [], 2, null);

            $course->plans()->updateOrCreate(['type' => $type], array_filter([
                'duration' => $info['Duration'] ?? null,
                'total_classes' => (int) ($info['Total'] ?? 0) ?: null,
                'classes_per_week' => (int) ($info['Class Per Week'] ?? 0) ?: null,
                'regular_price' => $regular,
                'sale_price' => $sale,
                'is_active' => true,
            ], fn ($value) => $value !== null));
        }
    }
}
