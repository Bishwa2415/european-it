<?php

/*
|--------------------------------------------------------------------------
| Site-wide contact info
|--------------------------------------------------------------------------
| One place for phone, email, address, social links.
| Use it anywhere with config('site.phones'), config('site.socials') etc.
| Change a number here and it changes in topbar, mobile menu and footer.
*/

return [

    'name' => 'European IT Solutions Institute',

    'phones' => ['+880 188 99 77 950', '+880 188 99 77 951', '+880 188 99 77 952'],

    'emails' => ['help@europeanit-inst.com', 'admission@europeanit-inst.com'],

    'address' => 'Noor Mansion (3rd Floor), Plot#04, Main Road#01, Mirpur-10, Dhaka-1216',

    'hours' => ['Monday - Sunday', '10AM - 09PM'],

    'whatsapp' => '8801889977952',

    'map_url' => 'https://goo.gl/maps/jfFYvtq2uWPm3hgz6',

    'reviews_url' => 'https://www.google.com/search?q=european+it',

    'tin' => '190905613413',

    // icon = Bootstrap Icons name
    'socials' => [
        ['label' => 'Facebook',  'icon' => 'bi-facebook',  'url' => 'https://www.facebook.com/EuropeanIT/'],
        ['label' => 'Linkedin',  'icon' => 'bi-linkedin',  'url' => 'https://www.linkedin.com/company/europeanIT'],
        ['label' => 'Instagram', 'icon' => 'bi-instagram', 'url' => 'https://www.instagram.com/europeanITINST/'],
        ['label' => 'Youtube',   'icon' => 'bi-youtube',   'url' => 'https://www.youtube.com/EuropeanIT'],
        ['label' => 'Pinterest', 'icon' => 'bi-pinterest', 'url' => 'https://www.pinterest.com/europeanIT'],
        ['label' => 'X-twitter', 'icon' => 'bi-twitter-x', 'url' => 'https://twitter.com/europeanITINST'],
    ],

    // Footer link lists
    'footer' => [
        'quick_links' => [
            [
                ['label' => 'Mentors',       'url' => '/mentors'],
                ['label' => 'Success Story', 'url' => '/students-success'],
                ['label' => 'RPL',           'url' => '/rpl-certification'],
                ['label' => 'Blog',          'url' => 'https://blog.europeanit-inst.com/'],
                ['label' => 'Reviews',       'url' => '/reviews'],
            ],
            [
                ['label' => 'Our Gallery',        'url' => '/office-environment'],
                ['label' => 'About Us',           'url' => '/old-about-us'],
                ['label' => 'Sitemap',            'url' => '/sitemap'],
                ['label' => 'Certificate Verify', 'url' => '#'],
            ],
        ],
        'popular_courses' => [
            ['label' => 'Web Development',                'url' => '/course/web-design-development-with-wordpress'],
            ['label' => 'Graphic Design',                 'url' => '/course/income-and-ai-focus-graphic-design'],
            ['label' => 'Digital Marketing',              'url' => '/course/ai-and-data-driven-digital-marketing'],
            ['label' => 'UI/UX Design',                   'url' => '/course/income-and-ai-focus-ui-ux-design'],
            ['label' => 'Video Editing & Motion Graphics', 'url' => '/course/video-editing-motion-graphics-with-ai-certificate'],
            ['label' => 'Basic Computer',                 'url' => '/course/basic-computer-with-ai-mastery-course'],
        ],
        'payments' => [
            ['image' => 'images/footer/bkash.webp',      'alt' => 'bKash',      'text' => '+8801889977951', 'class' => 'w-[115px]'],
            ['image' => 'images/footer/nagad.webp',      'alt' => 'Nagad',      'text' => '+8801889977951', 'class' => 'w-[115px]'],
            ['image' => 'images/footer/sslcommerz.webp', 'alt' => 'SSLCOMMERZ', 'text' => 'SSLCOMMERZ',     'class' => 'w-[209px] my-4'],
        ],
        'bottom_links' => [
            ['label' => 'Terms & Conditions',        'url' => '/terms-conditions'],
            ['label' => 'Refund and Returns Policy', 'url' => '/refund_returns'],
            ['label' => 'About Us',                  'url' => '/about-european-it-institute'],
        ],
    ],

];
