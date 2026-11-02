{{-- "Our students' feedback": carousel (Swiper via data-slider, drag + loop), 3 cards on desktop --}}
<section class="bg-[#F2FBFF] px-5 py-[60px]">
    <div class="mx-auto max-w-[1310px]">
        <x-landing.title :offline="$offline">{{ $s['title'] }}</x-landing.title>

        <div class="mt-[61px]" data-slider='{"spaceBetween": 30, "breakpoints": {"768": {"slidesPerView": 2}, "1024": {"slidesPerView": 3}}}'>
            <div class="swiper">
                <div class="swiper-wrapper">
                    @foreach ($s['items'] ?? [] as $item)
                        <div class="swiper-slide !h-auto">
                            <div class="h-full rounded-xl border border-black/25 bg-white p-6">
                                <div class="flex items-center gap-5">
                                    @if (! empty($item['photo']))
                                        <img src="{{ media_url($item['photo']) }}" alt="{{ $item['name'] }}" class="h-[60px] w-[58px] rounded-full object-cover">
                                    @endif
                                    <span>
                                        <span class="block font-heading text-base leading-[19px] font-semibold text-[#6EC1E4]">{{ $item['name'] }}</span>
                                        <span class="mt-1.5 block font-heading text-sm leading-[14px] text-[#7A7A7A]">{{ $item['course'] }}</span>
                                    </span>
                                </div>
                                <p class="mt-5 text-sm leading-6 text-[#020202]">{{ $item['text'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="swiper-pagination !relative mt-6" data-slider-pagination></div>
        </div>
    </div>
</section>
