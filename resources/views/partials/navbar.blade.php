@php
    // Dropdown menu er data (pore eta database theke ashbe)
    $successLinks = [
        ['label' => "Student's Success", 'url' => '/students-success'],
        ['label' => 'Video Feedback',    'url' => '/video-feedback'],
    ];

    $mediaLinks = [
        ['label' => 'Office Gallery',              'url' => '/office-environment'],
        ['label' => 'Orientation Programme',       'url' => '/orientation-programme'],
        ['label' => 'MoU Signing Ceremony',        'url' => '/mou-signing-ceremony'],
        ['label' => 'Teacher Training',            'url' => '/teachers-training'],
        ['label' => 'Office Event',                'url' => '/event-gallery'],
        ['label' => 'Seminar',                     'url' => '/seminar'],
        ['label' => 'Job Fair',                    'url' => '/job-fair'],
        ['label' => 'Certificate Giving Ceremony', 'url' => '/certificate-giving-ceremony'],
    ];

    $dropdowns = [
        'Success Stories' => $successLinks,
        'Media'           => $mediaLinks,
    ];
@endphp

<nav class="sticky top-0 z-50 bg-white">
    <div class="max-w-[1386px] mx-auto px-7 h-24 flex items-center justify-between">

        {{-- Logo --}}
        <a href="{{ url('/') }}" class="shrink-0">
            <img src="{{ asset('images/logo.webp') }}" alt="European IT Institute" class="w-[210px]">
        </a>

        {{-- Desktop menu --}}
        <div class="hidden lg:flex items-center h-full text-[15px] font-medium text-black">

            <a href="{{ url('/european-it-courses') }}" class="flex items-center gap-1 px-4 hover:text-primary">
                All Course <i class="bi bi-chevron-down text-[13px]"></i>
            </a>
            <a href="#" class="flex items-center gap-1 px-4 hover:text-primary">
                Popular Courses <i class="bi bi-chevron-down text-[13px]"></i>
            </a>
            <a href="{{ url('/freelancing') }}" class="px-4 hover:text-primary">Freelancing</a>

            {{-- Dropdowns: Success Stories, Media --}}
            @foreach ($dropdowns as $title => $links)
                <div class="relative group h-full flex items-center">
                    <button class="flex items-center gap-1 px-4 group-hover:text-[#00ADEE]">
                        {{ $title }}
                        <i class="bi bi-chevron-down text-[13px] transition group-hover:rotate-180"></i>
                    </button>

                    <div class="invisible opacity-0 group-hover:visible group-hover:opacity-100 transition
                                absolute left-1/2 -translate-x-1/2 top-full w-80
                                bg-white border-t-[3px] border-primary shadow-[0_11px_20px_rgba(0,0,0,0.05)]
                                py-[30px] px-[25px]">
                        <ul class="space-y-2">
                            @foreach ($links as $link)
                                <li>
                                    <a href="{{ url($link['url']) }}"
                                       class="block border border-[#EDEDED] p-2.5 font-menu text-sm leading-5 text-[#707070]
                                              hover:border-primary hover:text-primary transition">
                                        {{ $link['label'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endforeach

            <a href="{{ url('/about-european-it-institute') }}" class="px-4 hover:text-primary">About</a>
            <a href="{{ url('/career') }}" class="px-4 hover:text-primary">Career</a>
            <a href="{{ url('/contact-us') }}" class="px-4 hover:text-primary">Contact</a>
        </div>

        {{-- Admission button + mobile menu button --}}
        <div class="flex items-center gap-3">
            <a href="{{ url('/admission-now') }}"
               class="rounded-full border border-[#00AAFF] bg-primary-btn px-5 py-3.5 text-[13px] leading-none font-bold uppercase text-white hover:bg-primary-hover transition">
                Admission Now
            </a>
            <button class="lg:hidden text-3xl text-heading" aria-label="Open menu">
                <i class="bi bi-list"></i>
            </button>
        </div>
    </div>
</nav>
