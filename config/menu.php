<?php

/*
|--------------------------------------------------------------------------
| Header menu data
|--------------------------------------------------------------------------
| Use it anywhere with config('menu.popular'), config('menu.dropdowns').
| The "All Course" mega menu comes from the database (app/View/Composers/NavigationComposer.php).
| badge: 'advance' | 'popular' | 'new' | null
*/

return [

    'logo' => 'images/logo.webp',
    'logo_alt' => 'European IT Institute',
    'admission_label' => 'Admission Now',
    'admission_url' => '/admission-now',
    'all_courses_label' => 'All Course',
    'all_courses_url' => '/european-it-courses',
    'popular_courses_label' => 'Popular Courses',

    // Simple header links (Dashboard → Menus → header).
    // placement: "before" = before the Success Stories / Media dropdowns, "after" = after them (like the live site)
    // mobile_label: optional different text in the mobile menu
    'links' => [
        ['label' => 'Freelancing', 'url' => '/freelancing', 'placement' => 'before'],
        ['label' => 'About', 'mobile_label' => 'About Us', 'url' => '/about-european-it-institute', 'placement' => 'after'],
        ['label' => 'Career', 'url' => '/career', 'placement' => 'after'],
        ['label' => 'Contact', 'mobile_label' => 'Contact Us', 'url' => '/contact-us', 'placement' => 'after'],
    ],

    // "Popular Courses" mega menu: 2 columns
    'popular' => [
        'Online Course' => [
            ['label' => 'Web Design & Development', 'url' => '/online-wordpress-web-design-development-course-bangladesh', 'badge' => 'advance'],
            ['label' => 'Graphic Design', 'url' => '/online-graphic-design-course-bangladesh', 'badge' => 'popular'],
            ['label' => 'Digital Marketing', 'url' => '/online-digital-marketing-course-bangladesh', 'badge' => 'advance'],
            ['label' => 'UI/UX Design', 'url' => '/online-ui-ux-course-bangladesh', 'badge' => 'popular'],
            ['label' => 'Video Editing', 'url' => '/online-video-editing-course-bangladesh', 'badge' => 'popular'],
            ['label' => 'Basic Computer', 'url' => '/online-basic-computer-course-bangladesh', 'badge' => 'popular'],
        ],
        'Offline Course' => [
            ['label' => 'Web Design & Development', 'url' => '/web-design-and-development-course-mirpur-dhaka', 'badge' => 'advance'],
            ['label' => 'Graphic Design', 'url' => '/graphic-design-course-mirpur-dhaka', 'badge' => 'popular'],
            ['label' => 'Digital Marketing', 'url' => '/digital-marketing-course-mirpur-dhaka', 'badge' => 'advance'],
            ['label' => 'UI/UX Design', 'url' => '/ui-ux-course-mirpur-dhaka', 'badge' => 'popular'],
            ['label' => 'Video Editing', 'url' => '/video-editing-course-mirpur-dhaka', 'badge' => 'popular'],
            ['label' => 'Basic Computer', 'url' => '/basic-computer-course-mirpur-dhaka', 'badge' => 'popular'],
        ],
    ],

    // Simple dropdowns
    'dropdowns' => [
        'Success Stories' => [
            ['label' => "Student's Success", 'url' => '/students-success'],
            ['label' => 'Video Feedback', 'url' => '/video-feedback'],
        ],
        'Media' => [
            ['label' => 'Office Gallery', 'url' => '/office-environment'],
            ['label' => 'Orientation Programme', 'url' => '/orientation-programme'],
            ['label' => 'MoU Signing Ceremony', 'url' => '/mou-signing-ceremony'],
            ['label' => 'Teacher Training', 'url' => '/teachers-training'],
            ['label' => 'Office Event', 'url' => '/event-gallery'],
            ['label' => 'Seminar', 'url' => '/seminar'],
            ['label' => 'Job Fair', 'url' => '/job-fair'],
            ['label' => 'Certificate Giving Ceremony', 'url' => '/certificate-giving-ceremony'],
        ],
    ],

];
