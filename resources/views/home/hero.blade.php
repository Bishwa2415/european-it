{{-- Home page: Hero section (included from pages/home.blade.php) --}}
<section class="relative bg-white bg-cover bg-center bg-no-repeat px-5"
         style="background-image: url('{{ asset('images/home/hero-bg.webp') }}')">

    {{-- Soft grid-line pattern on top of the background (class in app.css) --}}
    <div class="bg-grid-pattern absolute inset-0 pointer-events-none"></div>

    <div class="relative max-w-[1330px] mx-auto pt-10 pb-12 lg:pt-[60px] lg:pb-[74px]
                grid grid-cols-1 gap-10 lg:grid-cols-2 lg:gap-x-20 lg:gap-y-[74px]">

        {{-- Row 1, left: text --}}
        <div class="self-center order-2 lg:order-1">
            <p class="flex items-center gap-[5px] font-heading text-[22px] leading-[22px] font-bold text-heading">
                {{-- Gradient circle arrow icon (same SVG as the original site) --}}
                <svg width="22" height="22" viewBox="0 0 26 26" fill="none" aria-hidden="true">
                    <circle cx="12.55" cy="12.55" r="10.84" fill="url(#lead-gradient)" />
                    <path d="M7.8 17.2L16.98 8.11" stroke="#BFF5FF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M17.21 15.16L17.24 7.85L9.94 7.82" stroke="#BFF5FF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <defs>
                        <linearGradient id="lead-gradient" x1="5.46" y1="24.23" x2="18.39" y2="4.21" gradientUnits="userSpaceOnUse">
                            <stop offset="0.02" stop-color="#096FAB" />
                            <stop offset="1" stop-color="#01ADEE" />
                        </linearGradient>
                    </defs>
                </svg>
                {{ $hero['eyebrow'] }}
            </p>

            <h1 class="mt-[17px] font-heading text-[32px] leading-10 lg:text-[44px] lg:leading-[52px] font-extrabold text-heading">
                {{ $hero['title_before'] }} <span class="text-[#00ADEE]">{{ $hero['title_highlight'] }}</span><br class="hidden lg:block">
                {{ $hero['title_after'] }}
            </h1>

            <p class="mt-[30px] max-w-[590px] text-base leading-6 text-black">
                {{ $hero['description'] }}
            </p>

            <div class="mt-[35px] flex flex-wrap gap-4 lg:gap-7">
                @foreach ($hero['buttons'] as $button)
                    <a href="{{ url($button['url']) }}" class="hero-btn">{{ $button['label'] }}</a>
                @endforeach
            </div>
        </div>

        {{-- Row 1, right: image slider (Swiper, JavaScript in resources/js/slider.js) --}}
        <div class="relative min-w-0 self-center order-1 lg:order-2" data-slider='{"spaceBetween": 0}'>
            <div class="swiper rounded-[20px]">
                <div class="swiper-wrapper">
                    @foreach ($hero['slides'] as $slide)
                        <div class="swiper-slide">
                            <img src="{{ media_url($slide['image']) }}" alt="{{ $slide['alt'] }}" draggable="false"
                                 class="w-full aspect-[625/369] object-cover rounded-[20px] border-[6px] border-white shadow-[0_0_3px_rgba(0,0,0,0.25)]">
                        </div>
                    @endforeach
                </div>
            </div>

            <button data-slider-prev aria-label="Previous slide" class="slider-arrow -left-5">
                <i class="bi bi-arrow-left"></i>
            </button>
            <button data-slider-next aria-label="Next slide" class="slider-arrow -right-5">
                <i class="bi bi-arrow-right"></i>
            </button>
        </div>

        {{-- Row 2 (spans both columns): stat boxes on the left, certificates on the right --}}
        <div class="order-3 lg:col-span-2 flex flex-col lg:flex-row lg:items-end lg:justify-between gap-10">

        {{-- 3 stat boxes (numbers count up, JavaScript in app.js) --}}
        <div class="grid gap-[15px] sm:grid-cols-3 lg:grid-cols-[repeat(3,246px)]">
            @foreach ($hero['stats'] as $stat)
                <div class="flex items-center gap-[25px] rounded-[10px] bg-primary-hover px-5 py-[11px] min-h-[97px]">
                    <span class="flex h-[35px] w-[35px] shrink-0 items-center justify-center rounded-md bg-white text-xl text-[#00ADEE]">
                        <i class="bi {{ $stat['icon'] }}"></i>
                    </span>
                    <div class="text-[#FFFF00]">
                        <p class="font-heading text-[44px] leading-[45px] font-bold">
                            <span data-count="{{ $stat['count'] }}">0</span>{{ $stat['suffix'] }}
                        </p>
                        <p class="mt-2 font-roboto-slab text-[22px] leading-[22px]">{{ $stat['label'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Authorized and Certified By --}}
        <div class="mx-auto lg:mx-0 w-full max-w-[390px] text-center">
            <h2 class="font-heading text-[22px] leading-[22px] font-bold text-[#3C3636]">Authorized and Certified By</h2>
            <img src="{{ asset('images/home/certified-by.webp') }}" alt="ISO, BCS and BASIS certified"
                 class="mt-1.5 w-full">
        </div>
        </div>
    </div>
</section>
