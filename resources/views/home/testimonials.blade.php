{{-- Home page: Student's Feedback slider
     Content: `testimonials` table, first filled by database/seeders/HomeContentSeeder.php
     Same as live: 1 card below 1024px, 2 cards above, 7px gap, no autoplay, dots, fades in on scroll --}}
@if ($testimonials->isNotEmpty())
<section class="relative px-5 pt-[50px] pb-[29px]">
    <div class="bg-grid-pattern absolute inset-0 pointer-events-none"></div>

    <div class="relative max-w-[1188px] mx-auto">
        <x-section-title :title="$homeTitles['testimonials_title']" />

        <div class="mt-[110px] min-w-0" data-reveal
             data-slider='{"spaceBetween": 10, "speed": 500, "autoplay": false, "breakpoints": {"1024": {"slidesPerView": 2, "spaceBetween": 7}}}'>
            <div class="swiper !pb-10">
                <div class="swiper-wrapper">
                    @foreach ($testimonials as $testimonial)
                        <div class="swiper-slide p-5 text-center">
                            {{-- Text sits in the middle of a box that is at least 380px tall (no scrollbar) --}}
                            <div class="flex min-h-[380px] border-clip items-center justify-center rounded-[30px] bg-[#F9FAFA] p-5 shadow-[6px_2px_16px_rgba(136,165,191,0.48)]">
                                <p class="whitespace-pre-line font-opensans text-sm leading-7 md:text-base md:leading-[30px] text-black">{{ $testimonial->message }}</p>
                            </div>
                            <div class="px-5 pt-5">
                                <img src="{{ $testimonial->photoUrl() }}" alt="{{ $testimonial->name }}" loading="lazy"
                                     class="mx-auto mt-[15px] h-[114px] w-[114px] rounded-full border-[7px] border-[#DDDDDD] object-cover">
                                <p class="mt-2 font-menu text-lg leading-[30px] font-semibold text-[#00AEEF]">{{ $testimonial->name }}</p>
                                <p class="font-roboto-slab text-sm leading-[21px] font-medium text-[#54595F]">
                                    @if ($testimonial->course_name) Course: {{ $testimonial->course_name }}<br> @endif
                                    @if ($testimonial->batch) Batch: {{ $testimonial->batch }} @endif
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination" data-slider-pagination></div>
            </div>
        </div>
    </div>
</section>
@endif
