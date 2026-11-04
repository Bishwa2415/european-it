{{-- "Our students' feedback": carousel (Swiper via data-slider, drag + loop).
     Online: 3 cards on desktop. Offline: one wide card at a time, like live. --}}
@php $sliderOptions = $offline ? ['spaceBetween' => 30, 'slidesPerView' => 1] : ['spaceBetween' => 30, 'breakpoints' => ['768' => ['slidesPerView' => 2], '1024' => ['slidesPerView' => 3]]]; @endphp
<section class="bg-[#F2FBFF] px-5 py-[60px]">
    <div class="mx-auto max-w-[1310px]">
        <x-landing.title :offline="$offline">{{ $s['title'] }}</x-landing.title>
        <hr @class(['mt-2.5', 'border-[#C4C4C4]' => ! $offline, 'border-[#00ADEE]' => $offline])>

        <div @class(['mx-auto mt-[50px]', 'max-w-[1236px]' => ! $offline, 'max-w-[1300px]' => $offline]) data-slider='@json($sliderOptions)'>
            <div class="swiper">
                <div class="swiper-wrapper">
                    @foreach ($s['items'] ?? [] as $item)
                        <div class="swiper-slide !h-auto">
                            <div @class(['h-full rounded-xl border border-black/25 bg-white', 'p-6' => ! $offline, 'px-6 py-10 md:px-[22px]' => $offline])>
                                <div class="flex items-center gap-5">
                                    @if (! empty($item['photo']))
                                        <img src="{{ media_url($item['photo']) }}" alt="{{ $item['name'] }}" class="h-[60px] w-[58px] rounded-full object-cover">
                                    @endif
                                    <span>
                                        <span class="block font-heading text-base leading-[19px] font-semibold" @style(['color: #6EC1E4' => ! $offline, 'color: #035176' => $offline])>{{ $item['name'] }}</span>
                                        <span class="mt-1.5 block font-heading text-sm leading-[14px] text-[#7A7A7A]">{{ $item['course'] }}</span>
                                    </span>
                                </div>
                                <p @class(['text-sm leading-6 text-[#020202]', 'mt-5' => ! $offline, 'mt-8 md:text-[15px] md:leading-[26px]' => $offline])>{{ $item['text'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
