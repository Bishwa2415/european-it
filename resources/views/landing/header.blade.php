{{-- Landing page header: logo (the header logo from Dashboard → Header menu), offer countdown, two phone numbers. Blue bar, 74px. --}}
<header class="sticky top-0 z-50 bg-[#00ADEE] px-5">
    <div class="mx-auto flex max-w-[1310px] flex-wrap items-center justify-between gap-3 py-2.5 lg:h-[74px] lg:flex-nowrap lg:py-0">
        <a href="{{ url('/') }}" class="rounded-[3px] bg-white p-[5px]">
            <img src="{{ media_url($headerMenu['logo'] ?? 'images/logo.webp') }}" alt="{{ $siteData['name'] }}" class="h-8 w-auto rounded-[5px] lg:h-11 lg:w-[300px] lg:object-contain">
        </a>

        @if ($landing->content['settings']['countdown_hours'] ?? 0)
            <div class="order-last flex w-full items-center justify-center gap-3 lg:order-none lg:w-auto lg:gap-5">
                <span class="lp-countdown hidden text-2xl leading-10 font-medium text-white md:inline">{{ $landing->section('offer')['countdown_title'] ?? 'Limited Time Offer Ends In:' }}</span>
                @include('landing.countdown', ['size' => 'small'])
            </div>
        @endif

        <div class="flex items-center gap-2.5 text-white">
            <i class="bi bi-telephone-fill text-[28px] lg:text-[34px]"></i>
            <span class="font-sans text-sm leading-5 font-medium lg:text-lg lg:leading-[26px]">
                @foreach (array_slice($siteData['header_phones'], 0, 2) as $phone)
                    <a href="tel:{{ preg_replace('/[^\d+]/', '', $phone) }}" class="block">{{ preg_replace('/^\+88\s?/', '', $phone) }}</a>
                @endforeach
            </span>
        </div>
    </div>
</header>
