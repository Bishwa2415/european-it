{{-- Home page: Our Partner and Collaboration — logos move non-stop (marquee) --}}
@if ($partners->isNotEmpty())
<section class="relative px-5 pt-[50px] pb-[210px]">
    <div class="bg-grid-pattern absolute inset-0 pointer-events-none"></div>
    <div class="max-w-[1330px] mx-auto">
        <x-section-title title="Our Partner and Collaboration" />

        <div class="marquee mt-10 min-w-0"
             data-slider='{"slidesPerView": "auto", "spaceBetween": 60, "speed": 4000, "autoplay": {"delay": 0, "disableOnInteraction": false, "pauseOnMouseEnter": false}}'>
            <div class="swiper">
                <div class="swiper-wrapper items-center">
                    @foreach ($partners as $partner)
                        <div class="swiper-slide !w-auto">
                            <img src="{{ $partner->logoUrl() }}" alt="{{ $partner->name }}" title="{{ $partner->name }}" loading="lazy"
                                 class="h-[150px] w-[150px] object-contain">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endif
