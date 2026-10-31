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
