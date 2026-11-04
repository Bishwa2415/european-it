{{-- Mentor – OFFLINE design: light blue card on the left (name, designation, text, button; right-aligned),
     photo on the right with the badges as white chips floating around it (positions like the live page). --}}
@php
    $badges = array_values(array_filter(array_map('trim', explode(',', $s['badges'] ?? ''))));
    // [left or right side, distance from that side, distance from the top] for chip 1, 2, 3, 4
    $spots = [['left', '18%', '8%'], ['right', '0%', '24%'], ['left', '11%', '58%'], ['right', '6%', '88%']];
@endphp

<section class="bg-[#F2FBFF] px-5 py-[100px]">
    <div class="mx-auto grid max-w-[1310px] items-center gap-10 md:grid-cols-[590px_1fr] md:gap-20">
        <div class="rounded-xl bg-[#DEF3FF] px-8 py-[26px] text-center md:px-[48px] md:text-right">
            <h2 class="font-['Noto_Sans_Bengali'] text-[32px] leading-[46px] font-semibold text-black">{{ $s['name'] }}</h2>
            <p class="mt-1.5 font-['Noto_Sans_Bengali'] text-lg leading-7 text-[#0A7CB4]">{{ $s['designation'] }}</p>
            @if (! empty($s['bio']))
                <p class="mt-[50px] font-['Noto_Sans_Bengali'] text-lg leading-[27px] text-black">{{ $s['bio'] }}</p>
            @endif
            @if (! empty($s['url']))
                <a href="{{ url($s['url']) }}" target="_blank" rel="noopener"
                   class="mt-[50px] inline-flex items-center gap-3 rounded-[44px] bg-[#00ADEE] px-[26px] py-[15px] font-heading text-[17px] leading-[17px] font-bold text-white transition hover:bg-[#065074]">
                    View Portfolio <i class="bi bi-arrow-right"></i>
                </a>
            @endif
        </div>

        <div class="relative mx-auto h-[420px] w-full max-w-[640px]">
            @if (! empty($s['photo']))
                <img src="{{ media_url($s['photo']) }}" alt="{{ $s['name'] }}" class="absolute top-1/2 left-1/2 h-[340px] w-[340px] -translate-1/2 object-contain">
            @endif
            @foreach (array_slice($badges, 0, 4) as $i => $badge)
                @php [$side, $x, $y] = $spots[$i]; @endphp
                <span class="absolute rounded-[5px] bg-white px-2.5 py-1.5 font-['Noto_Sans_Bengali'] text-lg leading-7 font-medium whitespace-nowrap text-black shadow-[0_2px_4px_rgba(0,0,0,0.2)] lg:text-xl"
                      style="{{ $side }}: {{ $x }}; top: {{ $y }}">{{ $badge }}</span>
            @endforeach
        </div>
    </div>
</section>
