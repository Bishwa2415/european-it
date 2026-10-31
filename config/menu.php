<?php

/*
|--------------------------------------------------------------------------
| Header menu data
|--------------------------------------------------------------------------
| Use it anywhere with config('menu.courses'), config('menu.media') etc.
| Later (Phase 5) the courses will come from the database instead.
| badge: 'advance' | 'popular' | 'new' | null
*/

return [

    // "All Course" mega menu: 4 columns, each column has one or more groups
    'courses' => [
        [
            'Development' => [
                ['label' => 'Advanced Shopify E-commerce Development with AI', 'url' => '/course/advanced-shopify-e-commerce-development-with-ai', 'badge' => 'advance'],
                ['label' => 'Laravel Framework', 'url' => '/course/best-laravel-course-in-bangladesh', 'badge' => 'popular'],
                ['label' => 'App Development with Flutter', 'url' => '/course/app-development-with-flutter', 'badge' => 'advance'],
                ['label' => 'Python Development with Django', 'url' => '/course/python-development', 'badge' => 'popular'],
                ['label' => 'Frontend Development with React Js', 'url' => '/course/react-js-course-in-bangladesh', 'badge' => 'popular'],
                ['label' => 'Node.js Full Stack Web Development', 'url' => '/course/best-node-js-course-in-bangladesh', 'badge' => 'popular'],
                ['label' => 'Web Design & Development with WordPress', 'url' => '/course/web-design-development-with-wordpress', 'badge' => 'popular'],
            ],
        ],
        [
            'Design' => [
                ['label' => 'Income and Ai Focus Graphic Design', 'url' => '/course/income-and-ai-focus-graphic-design', 'badge' => 'advance'],
                ['label' => '3D Animation', 'url' => '/course/3d-animation', 'badge' => 'new'],
                ['label' => 'Video Editing & Motion Graphics with AI Certificate', 'url' => '/course/video-editing-motion-graphics-with-ai-certificate', 'badge' => 'advance'],
                ['label' => 'Income and Ai Focus UI/UX Design', 'url' => '/course/income-and-ai-focus-ui-ux-design', 'badge' => 'advance'],
            ],
            'Marketing & Others' => [
                ['label' => 'Ai And Data Driven Digital Marketing', 'url' => '/course/ai-and-data-driven-digital-marketing', 'badge' => 'advance'],
                ['label' => 'Practical Facebook Ads Mastery', 'url' => '/course/practical-facebook-ads-mastery', 'badge' => 'advance'],
                ['label' => 'AI-Powered SEO Mastery Course', 'url' => '/course/ai-powered-seo-mastery-course', 'badge' => 'popular'],
                ['label' => 'Basic Computer With AI Mastery Course', 'url' => '/course/basic-computer-with-ai-mastery-course', 'badge' => 'popular'],
                ['label' => 'Communicative English', 'url' => '/course/communicative-english', 'badge' => 'popular'],
            ],
        ],
        [
            'Networking' => [
                ['label' => 'Networking', 'url' => '/course/networking', 'badge' => 'popular'],
                ['label' => 'MTCNA', 'url' => '/course/mtcna', 'badge' => null],
                ['label' => 'CCNA', 'url' => '/course/ccna-course-in-dhaka', 'badge' => 'popular'],
                ['label' => 'RHCSA/RHCE', 'url' => '/course/rhcsa-rhce', 'badge' => 'advance'],
                ['label' => 'AWS Solutions Architect Associate (SAA-C02)', 'url' => '/course/aws-solutions-architect-associate-saa-c02', 'badge' => 'advance'],
            ],
            'Bundles Course' => [
                ['label' => 'MERN Stack Development', 'url' => '/course/mern-stack-development', 'badge' => null],
                ['label' => 'Modern Software Development with Python', 'url' => '/course/modern-software-development-with-python', 'badge' => null],
            ],
            'Industrial Attachment' => [
                ['label' => 'Industrial Course Program', 'url' => '/industrial-attachment-for-polytechnic-students', 'badge' => 'popular'],
            ],
        ],
        [
            'Kids Learning' => [
                ['label' => 'Kids Programming', 'url' => 'https://kidsict.com', 'badge' => null],
                ['label' => 'Kids ICT & English', 'url' => 'https://kidsict.com', 'badge' => null],
            ],
            '4th Industrial Revolution' => [
                ['label' => 'Data Analytics & Machine Learning with Python', 'url' => '/course/data-analytics-machine-learning-with-python', 'badge' => 'popular'],
                ['label' => 'Blockchain', 'url' => '/course/blockchain-development', 'badge' => 'popular'],
                ['label' => 'Cloud Computing', 'url' => '/course/cloudcomputing', 'badge' => 'new'],
                ['label' => 'IoT – Internet of Things', 'url' => '/course/iot-internet-of-things', 'badge' => 'new'],
                ['label' => 'Cyber Security', 'url' => '/course/cyber-security', 'badge' => 'advance'],
            ],
        ],
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
