{{-- Menu data comes from config/menu.php --}}
<nav class="sticky top-0 z-50 bg-white">
    <div class="relative max-w-[1386px] mx-auto px-5 lg:px-7 h-20 lg:h-24 flex items-center justify-between">

        {{-- Logo --}}
        <a href="{{ url('/') }}" class="shrink-0">
            <img src="{{ asset('images/logo.webp') }}" alt="European IT Institute" class="w-[190px] lg:w-[210px]">
        </a>

        {{-- Desktop menu --}}
        <div class="hidden lg:flex items-center h-full text-[15px] font-medium text-black">

            {{-- Mega menu: All Course (positioned against the whole container) --}}
            <div class="group h-full flex items-center">
                <a href="{{ url('/european-it-courses') }}" class="flex items-center gap-1 px-4 group-hover:text-[#00ADEE]">
                    All Course <i class="bi bi-chevron-down text-[13px] transition group-hover:rotate-180"></i>
                </a>

                <div class="invisible opacity-0 group-hover:visible group-hover:opacity-100 transition
                            absolute left-7 right-7 max-w-[1310px] top-full
                            bg-white border-t-[3px] border-primary shadow-[0_11px_20px_rgba(0,0,0,0.05)]
                            py-[30px] px-[15px] grid grid-cols-4">
                    @foreach ($courseMenu as $column)
                        <div class="px-2.5 space-y-5">
                            @foreach ($column as $group => $links)
                                <div>
                                    <h2 class="mb-5 font-menu text-lg leading-6 font-bold uppercase text-steel">{{ $group }}</h2>
                                    <ul class="space-y-2">
                                        @foreach ($links as $link)
                                            <li><x-menu-link :link="$link" /></li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Mega menu: Popular Courses --}}
            <div class="group h-full flex items-center">
                <button class="flex items-center gap-1 px-4 group-hover:text-[#00ADEE]">
                    Popular Courses <i class="bi bi-chevron-down text-[13px] transition group-hover:rotate-180"></i>
                </button>

                <div class="invisible opacity-0 group-hover:visible group-hover:opacity-100 transition
                            absolute left-[267px] top-full w-[852px]
                            bg-white border-t-[3px] border-primary shadow-[0_11px_20px_rgba(0,0,0,0.05)]
                            py-[30px] px-[15px] grid grid-cols-2">
                    @foreach (config('menu.popular') as $group => $links)
                        <div class="px-2.5">
                            <h2 class="mb-5 font-menu text-lg leading-6 font-bold uppercase text-steel">{{ $group }}</h2>
                            <ul class="space-y-2">
                                @foreach ($links as $link)
                                    <li><x-menu-link :link="$link" /></li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>

            <a href="{{ url('/freelancing') }}" class="px-4 hover:text-primary">Freelancing</a>

            {{-- Dropdowns: Success Stories, Media --}}
            @foreach (config('menu.dropdowns') as $title => $links)
                <div class="relative group h-full flex items-center">
                    <button class="flex items-center gap-1 px-4 group-hover:text-[#00ADEE]">
                        {{ $title }}
                        <i class="bi bi-chevron-down text-[13px] transition group-hover:rotate-180"></i>
                    </button>

                    <div class="invisible opacity-0 group-hover:visible group-hover:opacity-100 transition
                                absolute left-1/2 -translate-x-1/2 top-full w-80
                                bg-white border-t-[3px] border-primary shadow-[0_11px_20px_rgba(0,0,0,0.05)]
                                py-[30px] px-[25px]">
                        <ul class="space-y-2">
                            @foreach ($links as $link)
                                <li>
                                    <a href="{{ url($link['url']) }}"
                                       class="block border border-[#EDEDED] p-2.5 font-menu text-sm leading-5 text-[#707070]
                                              hover:border-primary hover:text-primary transition">
                                        {{ $link['label'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endforeach

            <a href="{{ url('/about-european-it-institute') }}" class="px-4 hover:text-primary">About</a>
            <a href="{{ url('/career') }}" class="px-4 hover:text-primary">Career</a>
            <a href="{{ url('/contact-us') }}" class="px-4 hover:text-primary">Contact</a>
        </div>

        {{-- Admission button (desktop) + mobile menu button --}}
        <div class="flex items-center gap-3">
            <a href="{{ url('/admission-now') }}"
               class="hidden lg:inline-block rounded-full border border-[#00AAFF] bg-primary-btn px-5 py-3.5 text-[13px] leading-none font-bold uppercase text-white hover:bg-primary-hover transition">
                Admission Now
            </a>
            <button data-mobile-menu-open class="lg:hidden text-4xl text-primary" aria-label="Open menu">
                <i class="bi bi-list"></i>
            </button>
        </div>
    </div>
</nav>

@include('partials.mobile-menu')
