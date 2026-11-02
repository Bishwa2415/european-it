<?php

/*
|--------------------------------------------------------------------------
| Course detail page: parts that are the same for every course
|--------------------------------------------------------------------------
| Defaults for Dashboard → Course page (site_contents: type "setting", slug "course-page").
| Read through app(SiteContentService::class)->coursePage().
| The per-course parts (curriculum, projects, tools ...) live in courses.details.
| Titles: "before" text + blue "highlight" text (like "Project" in "Project You Will Do").
*/

return [

    'seminar_label' => 'Free Seminar',
    'seminar_url' => '/contact-us',
    'admission_label' => 'Admission Now',

    // Decoration and shared pictures
    'dots_image' => 'https://europeanit-inst.com/wp-content/uploads/2025/09/Group.svg',
    'certified_title' => 'Certified By',
    'certified_image' => 'https://europeanit-inst.com/wp-content/uploads/2025/09/Certified-By-Logo.png',
    'price_card_image' => 'https://europeanit-inst.com/wp-content/uploads/2025/09/image-72.png',

    'titles' => [
        'overview' => ['before' => 'Course Overview', 'highlight' => ''],
        'curriculum' => ['before' => 'Course Curriculum', 'highlight' => ''],
        'careers' => ['before' => 'Career Opportunity', 'highlight' => ''],
        'reasons' => ['before' => 'Why This course is', 'highlight' => 'best for you!'],
        'projects' => ['before' => '', 'highlight' => 'Project', 'after' => 'You Will Do'],
        'tools' => ['before' => '', 'highlight' => 'Tools', 'after' => 'You will Learn'],
        'why_european' => ['before' => 'Why', 'highlight' => 'European IT? !'],
        'videos' => ['before' => 'Success Stories', 'highlight' => ''],
        'glance' => ['before' => 'At a glance', 'highlight' => ''],
    ],

    // "Why European IT? !" cards (icons in public/images/course)
    'why_european' => [
        ['icon' => 'images/course/why-european-1.svg', 'title' => 'Industry-Relevant Curriculum', 'text' => 'Courses designed to match current market needs and future trends.'],
        ['icon' => 'images/course/why-european-2.svg', 'title' => 'Expert Mentors', 'text' => 'Learn directly from experienced professionals with real-world expertise.'],
        ['icon' => 'images/course/why-european-3.svg', 'title' => 'Hands-On Training', 'text' => 'Practical projects and exercises to build job-ready skills.'],
        ['icon' => 'images/course/why-european-4.svg', 'title' => 'Career Support', 'text' => 'Guidance on portfolios, interviews, and freelancing for career success.'],
        ['icon' => 'images/course/why-european-5.svg', 'title' => 'Flexible Learning Options', 'text' => 'Online and offline classes to fit different learning styles.'],
        ['icon' => 'images/course/why-european-6.svg', 'title' => 'Proven Track Record', 'text' => 'Thousands of successful graduates excelling in local and global industries.'],
    ],

    // Blue band above the footer
    'help_title' => 'Still Confused?',
    'help_text' => 'Share Your Query and Confusion With US',
    'help_phone' => '+88 0188-99 77 951',
    'community_title' => 'Join Our Community',
    'community_text' => 'Learn and grow together with people like you',
    'community_label' => 'Click Here',
    'community_url' => 'https://www.facebook.com/EuropeanIT/',

];
