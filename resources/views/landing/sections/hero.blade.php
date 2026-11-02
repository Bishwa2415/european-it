{{-- Hero. Online: text left + video right on a background picture. Offline: everything centred, big video below,
     soft rings with tool logos on both sides. Video: cover picture + play button, the player loads on click (countdown.js). --}}
@php
    $video = video_embed(['source' => $s['video_source'] ?? 'youtube', 'url' => $s['video_url'] ?? '', 'file' => $s['video_file'] ?? '']);
    $avatars = array_slice(array_column($s['avatars'] ?? [], 'image'), 0, 6);
    $icons = array_column($s['floating_icons'] ?? [], 'image');
@endphp

@if (! $offline)
    <section class="bg-[#E9F7FF] bg-cover bg-center px-5 lg:px-[30px]"
             @if (! empty($s['background'])) style="background-image: url('{{ media_url($s['background']) }}')" @endif>
        <div class="mx-auto flex max-w-[1310px] flex-col-reverse items-center gap-10 py-12 lg:min-h-[586px] lg:flex-row lg:items-start lg:justify-between lg:pt-[118px] lg:pb-[60px]">
            <div class="w-full lg:max-w-[605px] lg:pt-[30px]">
                <h1 class="lp-gradient-text font-heading text-[30px] leading-9 font-bold lg:text-[42px] lg:leading-[49px]">{{ $s['title'] }}</h1>
                @if (! empty($s['subtitle']))
                    <p class="mt-3 font-heading text-[26px] leading-8 font-semibold text-[#5F0692] lg:text-4xl lg:leading-10">{{ $s['subtitle'] }}</p>
                @endif
                @include('landing.partials.proof', ['center' => false])
                @if (! empty($s['intro']))
                    <p class="mt-5 text-lg leading-7 font-semibold text-[#04334D] lg:text-xl">{{ $s['intro'] }}</p>
                @endif
                <a href="{{ $enrollUrl }}" class="mt-5 inline-flex items-center gap-2.5 rounded-[44px] bg-[#065074] px-6 py-[15px] font-heading text-[17px] leading-[17px] font-bold text-white transition hover:bg-[#00ADEE]">
                    {{ $s['button_label'] ?: 'Enroll Now' }} <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <div class="relative w-full lg:w-[605px] lg:shrink-0">
                @include('landing.partials.video', ['video' => $video, 'cover' => $s['video_cover'] ?? '', 'label' => $s['video_label'] ?? '', 'ratio' => 'aspect-[605/408]'])
                {{-- Small logos floating around the video --}}
                @foreach (array_slice($icons, 0, 3) as $i => $icon)
                    <img src="{{ media_url($icon) }}" alt="" @class([
                        'pointer-events-none absolute hidden h-12 w-12 object-contain lg:block',
                        '-left-20 top-16' => $i === 0, '-right-4 -top-6 h-9 w-9' => $i === 1, '-bottom-6 left-1/2' => $i === 2,
                    ])>
                @endforeach
            </div>
        </div>
    </section>
@else
    <section class="relative overflow-hidden bg-[#F2FBFF] px-5">
        {{-- Soft rings on the left and right with tool logos in white circles --}}
        <div class="pointer-events-none absolute top-[345px] -left-[371px] hidden h-[530px] w-[530px] rounded-full border-[15px] border-[#67D5FF]/22 xl:block"></div>
        <div class="pointer-events-none absolute top-[345px] -right-[371px] hidden h-[530px] w-[530px] rounded-full border-[15px] border-[#67D5FF]/22 xl:block"></div>
        @foreach (array_slice($icons, 0, 8) as $i => $icon)
            <img src="{{ media_url($icon) }}" alt="" class="pointer-events-none absolute hidden h-[60px] w-[60px] rounded-full bg-white object-contain p-2.5 shadow-[10px_10px_25px_rgba(0,0,0,0.25)] xl:block"
                 style="top: {{ 436 + ($i % 4) * 107 }}px; {{ $i < 4 ? 'left' : 'right' }}: {{ [78, 128, 128, 78][$i % 4] }}px">
        @endforeach

        <div class="relative mx-auto max-w-[1310px] py-10 text-center">
            <h1 class="lp-gradient-text font-sans text-[34px] leading-tight font-bold lg:text-[66px] lg:leading-[79px]">{{ $s['title'] }}</h1>
            @include('landing.partials.proof', ['center' => true])
            @if (! empty($s['intro']))
                <p class="mx-auto mt-5 max-w-[800px] rounded-xl border border-[#00ADEE] px-5 py-2.5 font-['Noto_Sans_Bengali'] text-lg leading-[31px] text-[#04334D] lg:px-[30px] lg:text-[22px]">{{ $s['intro'] }}</p>
            @endif
            <a href="{{ $enrollUrl }}" class="mt-4 inline-flex items-center gap-2.5 rounded-[44px] border-2 border-[#00ADEE] bg-[#00ADEE] px-6 py-[15px] font-heading text-[17px] leading-[17px] font-bold text-white transition hover:bg-transparent hover:text-[#00ADEE]">
                {{ $s['button_label'] ?: 'Enroll Now' }} <i class="bi bi-arrow-right"></i>
            </a>

            <div class="mx-auto mt-[50px] max-w-[786px] rounded-xl border-2 border-[#07B0FF] shadow-[0_0_10px_10px_rgba(7,176,255,0.19)]">
                @include('landing.partials.video', ['video' => $video, 'cover' => $s['video_cover'] ?? '', 'label' => $s['video_label'] ?? '', 'ratio' => 'aspect-square'])
            </div>
        </div>
    </section>
@endif
