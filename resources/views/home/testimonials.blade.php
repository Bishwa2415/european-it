{{-- Home page: Student's Feedback slider (testimonials table) --}}
@if ($testimonials->isNotEmpty())
<section class="relative px-5 pt-[50px] pb-[29px]">
    <div class="bg-grid-pattern absolute inset-0 pointer-events-none"></div>

    <div class="relative max-w-[1188px] mx-auto">
        <x-section-title title="Student's Feedback" />

        {{-- 1 card on phones, 2 from tablet up; dots under the slider --}}
        <div class="mt-[110px] min-w-0" data-slider='{"spaceBetween": 6, "breakpoints": {"768": {"slidesPerView": 2}}}'>
            <div class="swiper !pb-10">
                <div class="swiper-wrapper">
                    @foreach ($testimonials as $testimonial)
                        <div class="swiper-slide p-5 text-center">
                            <div class="rounded-[30px] bg-[#F9FAFA] p-5 shadow-[6px_2px_16px_rgba(136,165,191,0.48)]">
                                <p class="h-[360px] overflow-y-auto whitespace-pre-line font-opensans text-base leading-[30px] text-black">{{ $testimonial->message }}</p>
                            </div>
                            <img src="{{ $testimonial->photoUrl() }}" alt="{{ $testimonial->name }}" loading="lazy"
                                 class="mx-auto mt-[35px] h-[114px] w-[114px] rounded-full border-[7px] border-[#DDDDDD] object-cover">
                            <p class="mt-2 font-menu text-lg leading-[30px] font-semibold text-[#00AEEF]">{{ $testimonial->name }}</p>
                            <p class="font-roboto-slab text-sm leading-[21px] font-medium text-[#54595F]">
                                @if ($testimonial->course_name) Course: {{ $testimonial->course_name }}<br> @endif
                                @if ($testimonial->batch) Batch: {{ $testimonial->batch }} @endif
                            </p>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination" data-slider-pagination></div>
            </div>
        </div>
    </div>
</section>
@endif
