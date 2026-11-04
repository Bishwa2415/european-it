{{-- "Early Bird gift (for the first 15)": 3 gift cards + a note + Enroll button.
     Red title part with several words (গিফট! অফার! গিফট!) = rotating words like the live animated headline
     (countdown.js → data-rotate). Offline uses lighter colours and a divider line. --}}
@php $words = array_values(array_filter(preg_split('/\s+/u', trim($s['title_red'] ?? '')))); @endphp
<section @class(['px-5 pt-[76px] pb-[60px]', 'bg-white' => ! $offline, 'bg-[#F5FBFF]' => $offline])>
    <div class="mx-auto max-w-[1310px] text-center">
        <h2 @class(['text-[30px] leading-10 font-semibold lg:text-[39px] lg:leading-[49px]', 'text-[#00608D]' => ! $offline, 'lp-noto text-black lg:text-[42px] lg:leading-[60px]' => $offline])>
            {{ $s['title'] }}
            @if (count($words) > 1)
                <span @class(['inline-block', 'text-[#DB1C1C]' => ! $offline, 'text-[#A80508]' => $offline]) data-rotate='@json($words)'>{{ $words[0] }}</span>
            @else
                <span @class(['text-[#DB1C1C]' => ! $offline, 'text-[#A80508]' => $offline])>{{ $s['title_red'] ?? '' }}</span>
            @endif
        </h2>
        @if ($offline)
            <hr class="mt-2.5 border-[#00ADEE]">
        @endif
        @if (! empty($s['subtitle']) || ! empty($s['subtitle_red']))
            <p @class(['mt-2.5 text-lg leading-[33px] font-bold lg:text-[22px]', 'text-[#00608D]' => ! $offline, 'lp-noto text-[#035176] lg:text-[28px] lg:leading-[42px]' => $offline])>
                @if (! empty($s['subtitle_red']))<span class="text-[#A80508]">{{ $s['subtitle_red'] }}</span>@endif
                {{ $s['subtitle'] }}
            </p>
        @endif

        <div class="mt-[54px] grid gap-[38px] md:grid-cols-3">
            @foreach ($s['items'] ?? [] as $gift)
                <div @class(['rounded-lg', 'border border-[#2CCAFF] bg-[#0072AB] px-3 py-6' => ! $offline, 'bg-[#00ADEE] px-6 pt-9 pb-8 shadow-[0_0_4px_rgba(0,0,0,0.1)]' => $offline])>
                    <x-landing.icon :icon="$gift['icon'] ?: 'bi-gift'" class="text-[50px] text-white" size="mx-auto block h-[50px] w-[50px]" />
                    <h3 class="mt-1.5 font-heading text-lg leading-5 font-bold text-white">{{ $gift['title'] }}</h3>
                    @if (! empty($gift['value']))
                        <p class="mt-2 font-heading text-lg leading-5 font-bold text-[#FFDA0A]">{{ $gift['value'] }}</p>
                    @endif
                    <p @class(['text-base leading-6 text-white', 'mt-[30px] font-bold' => ! $offline, 'lp-noto mt-7 font-medium' => $offline])>{{ $gift['text'] }}</p>
                </div>
            @endforeach
        </div>

        @if (! empty($s['note']))
            <p class="mt-[38px] flex items-center justify-center gap-1.5 text-lg leading-[27px] font-medium text-[#1E3A8A]"><i class="bi bi-check-square-fill text-[#3FB950]"></i> {{ $s['note'] }}</p>
        @endif
        @if (! empty($s['button_label']))
            @if ($offline)
                <a href="{{ $enrollUrl }}" class="mt-8 inline-flex items-center gap-3 rounded-[44px] bg-[#00ADEE] px-[26px] py-[15px] font-heading text-[17px] leading-[17px] font-bold text-white transition hover:bg-[#065074]">{{ $s['button_label'] }} <i class="bi bi-arrow-right"></i></a>
            @else
                <a href="{{ $enrollUrl }}" class="lp-btn-yellow mt-2.5">{{ $s['button_label'] }} <i class="bi bi-arrow-right"></i></a>
            @endif
        @endif
    </div>
</section>
