@php
    // Data of Dropdown menu data (pore eta database theke ashbe)
    $successLinks = [
        ['label' => "Student's Success", 'url' => '/students-success', 'icon' => 'bi-trophy'],
        ['label' => 'Video Feedback',    'url' => '/video-feedback',   'icon' => 'bi-play-circle'],
    ];

    $mediaLinks = [
        ['label' => 'Office Gallery',              'url' => '/office-environment',          'icon' => 'bi-images'],
        ['label' => 'Orientation Programme',       'url' => '/orientation-programme',       'icon' => 'bi-flag'],
        ['label' => 'MoU Signing Ceremony',        'url' => '/mou-signing-ceremony',        'icon' => 'bi-file-earmark-text'],
        ['label' => 'Teacher Training',            'url' => '/teachers-training',           'icon' => 'bi-person-workspace'],
        ['label' => 'Office Event',                'url' => '/event-gallery',               'icon' => 'bi-building'],
        ['label' => 'Seminar',                     'url' => '/seminar',                     'icon' => 'bi-mic'],
        ['label' => 'Job Fair',                    'url' => '/job-fair',                    'icon' => 'bi-briefcase'],
        ['label' => 'Certificate Giving Ceremony', 'url' => '/certificate-giving-ceremony', 'icon' => 'bi-award'],
    ];
@endphp

<nav class="sticky top-0 z-50 bg-white shadow-sm">
    <div class="max-w-7xl mx-auto px-4 h-20 flex items-center justify-between">

        {{-- Logo --}}
        <a href="{{ url('/') }}">
            <img src="{{ asset('images/logo.webp') }}" alt="European IT Institute" class="w-52">
        </a>

        {{-- Desktop menu --}}
        <div class="hidden lg:flex items-center gap-6 font-medium text-heading">

            <a href="{{ url('/european-it-courses') }}" class="flex items-center gap-1 hover:text-primary">
                All Course <i class="bi bi-chevron-down text-xs"></i>
            </a>
            <a href="#" class="flex items-center gap-1 hover:text-primary">
                Popular Courses <i class="bi bi-chevron-down text-xs"></i>
            </a>
            <a href="{{ url('/freelancing') }}" class="hover:text-primary">Freelancing</a>

            {{-- Dropdown: Success Stories --}}
            <div class="relative group">
                <button class="flex items-center gap-1 py-7 hover:text-primary">
                    Success Stories <i class="bi bi-chevron-down text-xs"></i>
                </button>
                <div class="invisible opacity-0 group-hover:visible group-hover:opacity-100 transition
                            absolute left-0 top-full w-60 bg-white rounded-lg shadow-lg py-2">
                    @foreach ($successLinks as $link)
                        <a href="{{ url($link['url']) }}" class="flex items-center gap-2 px-4 py-2 hover:bg-light hover:text-primary">
                            <i class="bi {{ $link['icon'] }}"></i> {{ $link['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Dropdown: Media --}}
            <div class="relative group">
                <button class="flex items-center gap-1 py-7 hover:text-primary">
                    Media <i class="bi bi-chevron-down text-xs"></i>
                </button>
                <div class="invisible opacity-0 group-hover:visible group-hover:opacity-100 transition
                            absolute left-0 top-full w-72 bg-white rounded-lg shadow-lg py-2">
                    @foreach ($mediaLinks as $link)
                        <a href="{{ url($link['url']) }}" class="flex items-center gap-2 px-4 py-2 hover:bg-light hover:text-primary">
                            <i class="bi {{ $link['icon'] }}"></i> {{ $link['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>

            <a href="{{ url('/about-european-it-institute') }}" class="hover:text-primary">About</a>
            <a href="{{ url('/career') }}" class="hover:text-primary">Career</a>
            <a href="{{ url('/contact-us') }}" class="hover:text-primary">Contact</a>
        </div>

        {{-- Admission button + mobile menu button --}}
        <div class="flex items-center gap-3">
            <a href="{{ url('/admission-now') }}"
               class="rounded-full bg-primary-btn px-6 py-3 text-xs font-bold uppercase text-white hover:bg-primary-hover transition">
                Admission Now
            </a>
            <button class="lg:hidden text-3xl text-heading" aria-label="Open menu">
                <i class="bi bi-list"></i>
            </button>
        </div>
    </div>
</nav>