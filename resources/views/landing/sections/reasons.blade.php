{{-- "Why this course is best for you?": 3 cards per row.
     Online: dark blue section, blue cards, yellow titles. Offline: light section, white glowing cards. --}}
<section @class(['px-5 py-[60px]', 'bg-[#00608D]' => ! $offline, 'bg-[#F2FBFF] lg:py-20' => $offline])>
    <div class="mx-auto max-w-[1310px]">
        <x-landing.title :offline="$offline" :light="! $offline">{{ $s['title'] }}</x-landing.title>

        <div class="mt-[50px] grid gap-[26px] md:grid-cols-2 lg:grid-cols-3 lg:gap-[29px]">
            @foreach ($s['items'] ?? [] as $item)
                @if (! $offline)
                    <div class="rounded-lg border border-[#2CCAFF] bg-[#0072AB] px-3 py-6 text-center">
                        <x-landing.icon :icon="$item['icon'] ?: 'bi-check-circle'" class="text-[39px] text-white" img-class="mx-auto h-[39px] w-[39px]" />
                        <h3 class="mt-[22px] text-xl leading-5 font-bold text-[#FFDA0A]">{{ $item['title'] }}</h3>
                        <p class="mt-[15px] text-base leading-6 font-medium text-white">{{ $item['text'] }}</p>
                    </div>
                @else
                    <div class="rounded-2xl border border-[#035176] bg-white px-5 py-[30px] text-center shadow-[0_0_15px_#73D4F9]">
                        <x-landing.icon :icon="$item['icon'] ?: 'bi-check-circle'" class="text-[50px] text-[#035176]" img-class="mx-auto h-[50px] w-[50px]" />
                        <h3 class="mx-auto mt-6 inline-block rounded-full bg-[#035176] px-5 py-1 font-['Noto_Sans_Bengali'] text-lg leading-[22px] font-semibold text-white">{{ $item['title'] }}</h3>
                        <p class="mt-5 font-['Noto_Sans_Bengali'] text-base leading-6 text-[#00608D]">{{ $item['text'] }}</p>
                    </div>
                @endif
            @endforeach
        </div>

        @if (! empty($s['button_label']))
            <div class="mt-[50px] text-center"><a href="{{ $enrollUrl }}" class="lp-btn-yellow">{{ $s['button_label'] }} <i class="bi bi-arrow-right"></i></a></div>
        @endif
    </div>
</section>
