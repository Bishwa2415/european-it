{{-- Site footer. Data comes from config/site.php --}}
@php
    $site   = config('site');
    $footer = $site['footer'];

    // Same style is used by every footer box, so keep it in one variable
    $box  = 'rounded-[20px] border border-[#4B5563] bg-[#374151]';
    $pill = 'inline-flex items-center gap-1.5 rounded-[30px] bg-[#4B5563] px-5 py-2 shadow-[0_0_10px_#374151]';
@endphp

<footer class="mt-0 bg-[#202A38] px-5 text-white">
    <div class="max-w-[1330px] mx-auto">

        {{-- 1. Contact card (pulled 125px up so it overlaps the section above) --}}
        <div class="relative -top-[125px] -mb-[125px] max-w-[1184px] mx-auto rounded-[20px] bg-[#374151] px-[30px] py-[50px]">
            <div class="text-center">
                <a href="{{ url('/contact-us') }}"
                   class="inline-block rounded-[3px] border-2 border-white px-6 py-3 font-heading text-[22px] leading-[22px] font-bold">
                    Contact Us
                </a>
            </div>

            <div class="mt-[27px] grid gap-8 sm:grid-cols-2 lg:gap-0 lg:grid-cols-[335px_296px_320px_1fr] font-heading text-base leading-7 lg:text-lg">
                <div>
                    <span class="footer-icon"><i class="bi bi-geo-alt"></i></span>
                    <p class="mt-3 max-w-[260px]">{{ $site['address'] }}</p>
                </div>
                <div>
                    <span class="footer-icon"><i class="bi bi-telephone"></i></span>
                    <div class="mt-3">
                        @foreach ($site['phones'] as $phone)
                            <a href="tel:{{ str_replace(' ', '', $phone) }}" class="block hover:text-primary">{{ $phone }}</a>
                        @endforeach
                    </div>
                </div>
                <div>
                    <span class="footer-icon"><i class="bi bi-envelope"></i></span>
                    <div class="mt-3">
                        @foreach ($site['emails'] as $email)
                            <a href="mailto:{{ $email }}" class="block hover:text-primary">{{ $email }}</a>
                        @endforeach
                    </div>
                </div>
                <div>
                    <span class="footer-icon"><i class="bi bi-clock"></i></span>
                    <p class="mt-3 font-bold">{{ $site['hours'][0] }}<br>{{ $site['hours'][1] }}</p>
                </div>
            </div>
        </div>

        {{-- 2. Footer logo --}}
        <a href="{{ url('/') }}" class="mt-10 block w-fit mx-auto">
            <img src="{{ asset('images/footer/footer-logo.svg') }}" alt="European IT Institute" class="w-[300px]">
        </a>

        {{-- 3. Follow Us bar --}}
        <div class="{{ $box }} mt-5 max-w-[798px] mx-auto p-[30px] flex flex-wrap items-center justify-center gap-[13px]">
            <span class="flex items-center gap-1.5 font-heading text-xl">
                <i class="bi bi-lightning-charge-fill text-primary"></i> Follow Us:
            </span>
            @foreach ($site['socials'] as $social)
                <a href="{{ $social['url'] }}" target="_blank" rel="noopener" aria-label="{{ $social['label'] }}"
                   class="flex h-10 w-10 items-center justify-center rounded-full bg-[#4B5563] text-xl shadow-[0_3px_12px_rgba(0,0,0,0.24)] hover:bg-primary transition">
                    <i class="bi {{ $social['icon'] }}"></i>
                </a>
            @endforeach
        </div>

        {{-- 4. Three boxes: Quick Links, Popular Courses, Google map --}}
        <div class="mt-[70px] grid gap-[30px] lg:grid-cols-3 lg:min-h-[425px]">

            {{-- Quick Links (2 columns) --}}
            <div class="{{ $box }} px-5 py-[30px]">
                <div class="text-center">
                    <h3 class="{{ $pill }} font-roboto text-xl font-medium">
                        <i class="bi bi-box-arrow-up-right text-primary text-base"></i> Quick Links
                    </h3>
                </div>
                <div class="mt-10 grid grid-cols-2 pl-8">
                    @foreach ($footer['quick_links'] as $column)
                        <ul class="space-y-[31px]">
                            @foreach ($column as $link)
                                <li class="leading-4"><a href="{{ url($link['url']) }}" class="pl-[5px] hover:text-primary">{{ $link['label'] }}</a></li>
                            @endforeach
                        </ul>
                    @endforeach
                </div>
            </div>

            {{-- Popular Courses --}}
            <div class="{{ $box }} p-[30px]">
                <div class="text-center">
                    <h3 class="{{ $pill }} font-roboto text-xl font-medium">
                        <i class="bi bi-pencil-fill text-primary text-base"></i> Popular Courses
                    </h3>
                </div>
                <ul class="mt-10 space-y-[31px] pl-8">
                    @foreach ($footerCourses as $link)
                        <li class="leading-4"><a href="{{ url($link['url']) }}" class="pl-[5px] hover:text-primary">{{ $link['label'] }}</a></li>
                    @endforeach
                </ul>
            </div>

            {{-- Google map --}}
            <div class="{{ $box }} p-[30px]">
                <div class="text-center">
                    <h3 class="{{ $pill }} font-roboto text-xl font-medium">
                        <i class="bi bi-geo-alt-fill text-primary text-base"></i> Google map
                    </h3>
                </div>
                <a href="{{ $site['map_url'] }}" target="_blank" rel="noopener" class="mt-[50px] block">
                    <img src="{{ asset('images/footer/location-map.webp') }}" alt="European IT location on Google map"
                         class="w-full max-w-[346px] mx-auto rounded-xl">
                </a>
                <a href="{{ $site['map_url'] }}" target="_blank" rel="noopener"
                   class="mt-[30px] mx-auto flex max-w-[346px] items-center justify-center gap-2 rounded-lg bg-[#00ADEE] py-0.5 leading-6 font-semibold uppercase hover:bg-primary-hover transition">
                    <i class="bi bi-geo-alt-fill"></i> Click here
                </a>
            </div>
        </div>

        {{-- 5. Payment merchants --}}
        <div class="{{ $box }} mt-10 px-[30px] py-10 text-center">
            <h3 class="inline-block rounded-[30px] bg-[#4B5563] px-[30px] py-2 font-heading text-xl leading-10 font-medium lg:text-[26px]">
                Our Payment Merchant
            </h3>
            <div class="mt-[30px] flex flex-wrap justify-center gap-6">
                @foreach ($footer['payments'] as $payment)
                    <a href="{{ url('/admission-now') }}"
                       class="flex w-[253px] flex-col items-center justify-center rounded-xl border-2 border-[#374151] bg-white p-5 shadow-[0_0_8px_#374151]">
                        <img src="{{ asset($payment['image']) }}" alt="{{ $payment['alt'] }}" class="{{ $payment['class'] }}">
                        <span class="font-roboto text-2xl text-heading">{{ $payment['text'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- 6. Bottom bar --}}
    <div class="-mx-5 mt-[60px] bg-[#00ADEE] px-5 text-sm">
        <div class="max-w-[1330px] mx-auto py-5 flex flex-col md:flex-row items-center justify-between gap-2 text-center">
            <p>Copyright ©{{ date('Y') }} <a href="{{ url('/') }}" class="hover:underline">{{ $site['name'] }}</a>. All Rights Reserved.</p>
            <div class="flex flex-wrap justify-center gap-x-5 gap-y-1">
                @foreach ($footer['bottom_links'] as $link)
                    <a href="{{ url($link['url']) }}" class="hover:underline">{{ $link['label'] }}</a>
                @endforeach
                <span>TIN: {{ $site['tin'] }}</span>
            </div>
        </div>
    </div>
</footer>
