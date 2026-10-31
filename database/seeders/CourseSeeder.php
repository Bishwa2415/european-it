<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseCategory;
use Illuminate\Database\Seeder;

/**
 * Fills the database with the courses from europeanit-inst.com.
 * Run with: php artisan db:seed
 * (Later the admin panel will add/edit courses; this is the starting data.)
 */
class CourseSeeder extends Seeder
{
    public function run(): void
    {
        // [category name, mega menu column, courses]
        // course = [title, slug, badge, extra fields]
        $catalog = [
            ['Development', 1, [
                ['Advanced Shopify E-commerce Development with AI', 'advanced-shopify-e-commerce-development-with-ai', 'advance'],
                ['Laravel Framework', 'best-laravel-course-in-bangladesh', 'popular'],
                ['App Development with Flutter', 'app-development-with-flutter', 'advance'],
                ['Python Development with Django', 'python-development', 'popular', ['is_fourth_ir' => true]],
                ['Frontend Development with React Js', 'react-js-course-in-bangladesh', 'popular'],
                ['Node.js Full Stack Web Development', 'best-node-js-course-in-bangladesh', 'popular'],
                ['Web Design & Development with WordPress', 'web-design-development-with-wordpress', 'popular'],
            ]],
            ['Design', 2, [
                ['Income and Ai Focus Graphic Design', 'income-and-ai-focus-graphic-design', 'advance'],
                ['3D Animation', '3d-animation', 'new'],
                ['Video Editing & Motion Graphics with AI Certificate', 'video-editing-motion-graphics-with-ai-certificate', 'advance'],
                ['Income and Ai Focus UI/UX Design', 'income-and-ai-focus-ui-ux-design', 'advance'],
            ]],
            ['Marketing & Others', 2, [
                ['AI And Data Driven Digital Marketing', 'ai-and-data-driven-digital-marketing', 'advance'],
                ['Practical Facebook Ads Mastery', 'practical-facebook-ads-mastery', 'advance'],
                ['AI-Powered SEO Mastery Course', 'ai-powered-seo-mastery-course', 'popular'],
                ['Basic Computer with AI Mastery Course', 'basic-computer-with-ai-mastery-course', 'popular'],
                ['Communicative English', 'communicative-english', 'popular'],
            ]],
            ['Networking', 3, [
                ['Networking', 'networking', 'popular'],
                ['MTCNA', 'mtcna', null],
                ['CCNA', 'ccna-course-in-dhaka', 'popular'],
                ['RHCSA/RHCE', 'rhcsa-rhce', 'advance'],
                ['AWS Solutions Architect Associate (SAA-C02)', 'aws-solutions-architect-associate-saa-c02', 'advance'],
            ]],
            ['Bundles Course', 3, [
                ['MERN Stack Development', 'mern-stack-development', null],
                ['Modern Software Development with Python', 'modern-software-development-with-python', null],
            ]],
            ['Industrial Attachment', 3, [
                ['Industrial Course Program', 'industrial-course-program', 'popular', ['custom_url' => '/industrial-attachment-for-polytechnic-students']],
            ]],
            ['Kids Learning', 4, [
                ['Kids Programming', 'kids-programming', null, ['custom_url' => 'https://kidsict.com']],
                ['Kids ICT & English', 'kids-ict-english', null, ['custom_url' => 'https://kidsict.com']],
            ]],
            ['4th Industrial Revolution', 4, [
                ['Data Analytics & Machine Learning with Python', 'data-analytics-machine-learning-with-python', 'popular', ['is_fourth_ir' => true]],
                ['Blockchain', 'blockchain-development', 'popular', ['is_fourth_ir' => true]],
                ['Cloud Computing', 'cloudcomputing', 'new', ['is_fourth_ir' => true]],
                ['IoT – Internet of Things', 'iot-internet-of-things', 'new', ['is_fourth_ir' => true]],
                ['Cyber Security', 'cyber-security', 'advance', ['is_fourth_ir' => true]],
            ]],
        ];

        foreach ($catalog as $categoryOrder => [$name, $column, $courses]) {
            $category = CourseCategory::updateOrCreate(
                ['slug' => str($name)->slug()],
                ['name' => $name, 'menu_column' => $column, 'sort_order' => $categoryOrder],
            );

            foreach ($courses as $courseOrder => $row) {
                [$title, $slug, $badge] = $row;
                $extra = $row[3] ?? [];
                $thumbnail = "images/courses/{$slug}.webp";

                Course::updateOrCreate(['slug' => $slug], [
                    'course_category_id' => $category->id,
                    'title'              => $title,
                    'badge'              => $badge,
                    'thumbnail'          => file_exists(public_path($thumbnail)) ? $thumbnail : null,
                    'sort_order'         => $courseOrder,
                    ...$extra,
                ]);
            }
        }

        $this->seedPopularCourses();
    }

    /**
     * Home page "Popular Courses": order, review/student counts and Online/Offline prices
     * (numbers taken from the live site's course cards).
     */
    private function seedPopularCourses(): void
    {
        // slug => [reviews, students, online price, offline price]
        $popular = [
            'ai-powered-seo-mastery-course'                     => [1835, 2552, 8000, 11000],
            'basic-computer-with-ai-mastery-course'             => [3789, 4130, 1999, 5000],
            'video-editing-motion-graphics-with-ai-certificate' => [1351, 2110, 15000, 20000],
            'web-design-development-with-wordpress'             => [2347, 4591, 5000, 15000],
            'income-and-ai-focus-ui-ux-design'                  => [2589, 1719, 4999, 15000],
            'income-and-ai-focus-graphic-design'                => [4321, 2137, 4999, 15000],
            'advanced-shopify-e-commerce-development-with-ai'   => [514, 2414, 10000, 20000],
            'practical-facebook-ads-mastery'                    => [1101, 1244, 4999, 15000],
            'ai-and-data-driven-digital-marketing'              => [2125, 3534, 4999, 15000],
        ];

        $position = 0;
        foreach ($popular as $slug => [$reviews, $students, $online, $offline]) {
            $course = Course::where('slug', $slug)->firstOrFail();

            $course->update([
                'is_popular'    => true,
                'review_count'  => $reviews,
                'student_count' => $students,
                'sort_order'    => $position++,
            ]);

            $course->plans()->updateOrCreate(['type' => 'online'], ['regular_price' => $online]);
            $course->plans()->updateOrCreate(['type' => 'offline'], ['regular_price' => $offline]);
        }

        // WordPress course: full plan details from its detail page (others get filled in the course detail step)
        $wordpress = Course::where('slug', 'web-design-development-with-wordpress')->first();
        $wordpress->plans()->updateOrCreate(['type' => 'offline'], [
            'duration' => '4 Months', 'total_classes' => 32, 'classes_per_week' => 2,
            'regular_price' => 20000, 'sale_price' => 15000,
        ]);
        $wordpress->plans()->updateOrCreate(['type' => 'online'], [
            'duration' => '3 Months', 'total_classes' => 24, 'classes_per_week' => 2,
            'regular_price' => 10000, 'sale_price' => 5000,
        ]);
    }
}
