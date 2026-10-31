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
        <a href="{{ url('/') }}"><img src="{{ asset('images/logo.webp') }}" alt="European IT Institute" class="w-[175px]"></a>
        <a href="{{ url('/admission-now') }}"
           class="rounded-full border border-[#00AAFF] bg-primary-btn px-4 py-3 text-xs leading-none font-bold uppercase text-white">
            Admission Now
        </a>
    </div>

    {{-- Menu items --}}
    <ul class="mt-8 font-heading text-base font-medium text-[#020202]">

        {{-- All Course (accordion) --}}
        <li class="border-b border-[#00ADEE]">
            <button data-accordion-toggle class="flex w-full items-center gap-2 px-2.5 py-[13px] text-left">
                All Course <i class="bi bi-chevron-down text-xs transition"></i>
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
                Popular Courses <i class="bi bi-chevron-down text-xs transition"></i>
            </button>
            <div class="hidden pb-3 space-y-4">
                @foreach (config('menu.popular') as $group => $links)
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

        <li class="border-b border-[#00ADEE]">
            <a href="{{ url('/freelancing') }}" class="block px-2.5 py-[13px]">Freelancing</a>
        </li>

        {{-- Success Stories, Media (accordion) --}}
        @foreach (config('menu.dropdowns') as $title => $links)
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

        <li class="border-b border-[#00ADEE]"><a href="{{ url('/about-european-it-institute') }}" class="block px-2.5 py-[13px]">About Us</a></li>
        <li class="border-b border-[#00ADEE]"><a href="{{ url('/career') }}" class="block px-2.5 py-[13px]">Career</a></li>
        <li class="border-b border-[#00ADEE]"><a href="{{ url('/contact-us') }}" class="block px-2.5 py-[13px]">Contact Us</a></li>
    </ul>

    {{-- Demo class + Discount --}}
    <div class="mt-8 grid grid-cols-2 gap-4">
        <a href="#" class="rounded-full border border-[#00AAFF] bg-[#00ADEE] py-3 text-center text-[13px] leading-4 font-bold uppercase text-white">Demo Class</a>
        <a href="{{ url('/discount') }}" class="rounded-full border border-[#00AAFF] bg-[#00ADEE] py-3 text-center text-[13px] leading-4 font-bold uppercase text-white">Get Discount</a>
    </div>

    {{-- Contact card --}}
    <div class="mt-6 rounded-2xl bg-linear-to-r from-[#00ADEE] to-[#0D9FD5] px-5 py-6 text-center text-sm font-semibold text-white space-y-3">
        <div class="flex flex-wrap justify-center gap-x-5 gap-y-1 text-[13px]">
            <a href="tel:+8801889977951"><i class="bi bi-telephone-fill"></i> +880 188 99 77 951</a>
            <a href="tel:+8801889977952"><i class="bi bi-telephone-fill"></i> +880 188 99 77 952</a>
        </div>
        <a href="mailto:help@europeanit-inst.com" class="block"><i class="bi bi-envelope"></i> help@europeanit-inst.com</a>
        <p>Noor Mansion (3rd Floor), Plot#04, Main Road#01, Mirpur-10, Dhaka-1216</p>
    </div>
</div>
