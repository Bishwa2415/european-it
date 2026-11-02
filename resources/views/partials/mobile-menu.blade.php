{{-- Full-screen mobile menu. Opened/closed by resources/js/app.js --}}
<div id="mobile-menu" class="hidden lg:hidden fixed inset-0 z-[60] overflow-y-auto bg-white px-5 pt-4 pb-10">

    {{-- Close button --}}
    <div class="flex justify-end">
        <button data-mobile-menu-close class="text-3xl text-primary" aria-label="Close menu">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    {{-- Logo + Admission --}}
    <div class="mt-4 flex items-center justify-between gap-3">
        <a href="{{ url('/') }}"><img src="{{ media_url($headerMenu['logo']) }}" alt="{{ $headerMenu['logo_alt'] }}" class="w-[175px]"></a>
        <a href="{{ url($headerMenu['admission_url']) }}"
           class="rounded-full border border-[#00AAFF] bg-primary-btn px-4 py-3 text-xs leading-none font-bold uppercase text-white">
            {{ $headerMenu['admission_label'] }}
        </a>
    </div>

    {{-- Menu items --}}
    <ul class="mt-8 font-heading text-base font-medium text-[#020202]">

        {{-- All Course (accordion) --}}
        <li class="border-b border-[#00ADEE]">
            <button data-accordion-toggle class="flex w-full items-center gap-2 px-2.5 py-[13px] text-left">
                {{ $headerMenu['all_courses_label'] }} <i class="bi bi-chevron-down text-xs transition"></i>
            </button>
            <div class="hidden pb-3 space-y-4">
                @foreach ($courseMenu as $column)
                    @foreach ($column as $group => $links)
                        <div>
                            <h2 class="mb-2 px-2.5 font-menu text-sm font-bold uppercase text-steel">{{ $group }}</h2>
                            <ul class="space-y-2">
                                @foreach ($links as $link)
                                    <li><x-menu-link :link="$link" /></li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                @endforeach
            </div>
        </li>

        {{-- Popular Courses (accordion) --}}
        <li class="border-b border-[#00ADEE]">
            <button data-accordion-toggle class="flex w-full items-center gap-2 px-2.5 py-[13px] text-left">
                {{ $headerMenu['popular_courses_label'] }} <i class="bi bi-chevron-down text-xs transition"></i>
            </button>
            <div class="hidden pb-3 space-y-4">
                @foreach ($headerMenu['popular'] as $group => $links)
                    <div>
                        <h2 class="mb-2 px-2.5 font-menu text-sm font-bold uppercase text-steel">{{ $group }}</h2>
                        <ul class="space-y-2">
                            @foreach ($links as $link)
                                <li><x-menu-link :link="$link" /></li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </li>

        @foreach (collect($headerMenu['links'] ?? [])->where('placement', 'before') as $link)
            <li class="border-b border-[#00ADEE]"><a href="{{ url($link['url']) }}" class="block px-2.5 py-[13px]">{{ $link['mobile_label'] ?? $link['label'] }}</a></li>
        @endforeach

        {{-- Success Stories, Media (accordion) --}}
        @foreach ($headerMenu['dropdowns'] as $title => $links)
            <li class="border-b border-[#00ADEE]">
                <button data-accordion-toggle class="flex w-full items-center gap-2 px-2.5 py-[13px] text-left">
                    {{ $title }} <i class="bi bi-chevron-down text-xs transition"></i>
                </button>
                <ul class="hidden">
                    @foreach ($links as $link)
                        <li><a href="{{ url($link['url']) }}" class="block pl-4 pr-2.5 py-[13px]">{{ $link['label'] }}</a></li>
                    @endforeach
                </ul>
            </li>
        @endforeach

        @foreach (collect($headerMenu['links'] ?? [])->where('placement', '!=', 'before') as $link)
            <li class="border-b border-[#00ADEE]"><a href="{{ url($link['url']) }}" class="block px-2.5 py-[13px]">{{ $link['mobile_label'] ?? $link['label'] }}</a></li>
        @endforeach
    </ul>

    {{-- Demo class + Discount --}}
    <div class="mt-8 grid grid-cols-2 gap-4">
        <a href="{{ url($siteData['demo_class_url']) }}" class="rounded-full border border-[#00AAFF] bg-[#00ADEE] py-3 text-center text-[13px] leading-4 font-bold uppercase text-white">{{ $siteData['demo_class_label'] }}</a>
        <a href="{{ url($siteData['discount_url']) }}" class="rounded-full border border-[#00AAFF] bg-[#00ADEE] py-3 text-center text-[13px] leading-4 font-bold uppercase text-white">{{ $siteData['discount_label'] }}</a>
    </div>

    {{-- Contact card --}}
    <div class="mt-6 rounded-2xl bg-linear-to-r from-[#00ADEE] to-[#0D9FD5] px-5 py-6 text-center text-sm font-semibold text-white space-y-3">
        <div class="flex flex-wrap justify-center gap-x-5 gap-y-1 text-[13px]">
            @foreach ($siteData['header_phones'] ?? array_slice($siteData['phones'], 1, 2) as $phone)<a href="tel:{{ str_replace(' ', '', $phone) }}"><i class="bi bi-telephone-fill"></i> {{ $phone }}</a>@endforeach
        </div>
        <a href="mailto:{{ $siteData['emails'][0] }}" class="block"><i class="bi bi-envelope"></i> {{ $siteData['emails'][0] }}</a>
        <p>{{ $siteData['address'] }}</p>
    </div>
</div>
