{{-- Guarantee box (badge picture + text) and the offer box (countdown, crossed-out regular price, offer price, button) --}}
<section @class(['px-5 pt-[80px] pb-[60px]', 'bg-[#065074]' => ! $offline, 'bg-white' => $offline])>
    <div class="relative mx-auto max-w-[800px] rounded-3xl bg-[#04334D] px-2.5 pt-[108px] pb-8 text-center">
        @if (! empty($s['top_image']))
            <img src="{{ media_url($s['top_image']) }}" alt="" class="absolute -top-[45px] left-1/2 w-[155px] -translate-x-1/2">
        @endif
        @if (! empty($s['badge_image']))
            <img src="{{ media_url($s['badge_image']) }}" alt="" class="mx-auto w-[220px] lg:w-[294px]">
        @endif
        <p class="mx-auto mt-7 max-w-[730px] text-lg leading-8 font-semibold text-white lg:text-xl">{{ $s['guarantee_text'] }}</p>
        @if (! empty($s['guarantee_note']))
            <p class="mt-7 font-heading text-xl leading-8 font-semibold text-[#FFDA0A] lg:text-2xl">{{ $s['guarantee_note'] }}</p>
        @endif
    </div>

    <div class="mx-auto mt-5 max-w-[873px] rounded-3xl bg-[#0090D4] px-2.5 py-8 text-center">
        <div class="flex flex-wrap items-center justify-center gap-5 lg:justify-between lg:px-[60px]">
            <h2 class="font-['Inter'] text-2xl leading-10 font-medium text-[#F2FBFF] lg:text-[28px]">{{ $s['countdown_title'] }}</h2>
            @include('landing.countdown', ['size' => 'large'])
        </div>

        @if (! empty($s['regular_text']) || ! empty($s['regular_price']))
            <p class="mt-11 inline-block rounded-[40px] border border-white px-6 py-4 lg:min-w-[556px] text-xl leading-8 font-bold text-white lg:px-12 lg:text-2xl">
                {{ $s['regular_text'] }} <span class="text-[#FFDA0A] line-through">{{ $s['regular_price'] }}</span>
            </p>
        @endif
        <h3 class="mt-7 text-2xl leading-10 font-bold text-white lg:text-[29px]">{{ $s['offer_text'] }}</h3>
        <a href="{{ $enrollUrl }}" class="lp-btn-yellow mt-7">{{ $s['button_label'] ?: 'Get Your Offer' }} <i class="bi bi-arrow-right"></i></a>
    </div>
</section>
