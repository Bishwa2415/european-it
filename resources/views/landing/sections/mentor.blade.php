{{-- Mentor card: photo, name, designation, company, facts with ticks, short text, badges, portfolio button --}}
<section class="bg-[#F2FBFF] px-5 py-[60px]">
    <div class="mx-auto flex max-w-[1062px] flex-col items-center gap-6 rounded-xl border border-[#00ADEE] bg-white px-6 py-8 md:flex-row md:gap-[26px] md:px-16 md:py-10">
        @if (! empty($s['photo']))
            <img src="{{ media_url($s['photo']) }}" alt="{{ $s['name'] }}" class="h-[253px] w-[253px] shrink-0 rounded-[100px] object-cover">
        @endif

        <div class="w-full">
            <h2 class="font-heading text-[28px] leading-10 font-bold text-[#04334D]">{{ $s['name'] }}</h2>
            <p class="mt-3 font-['Inter'] text-lg leading-5 font-semibold text-[#00ADEE]">{{ $s['designation'] }}</p>
            @if (! empty($s['company']))
                <p class="mt-1 font-['Inter'] text-base leading-5 font-semibold text-[#3C6382]">{{ $s['company'] }}</p>
            @endif

            @if (! empty($s['facts']))
                <ul class="mt-4 flex flex-wrap gap-x-5 gap-y-1">
                    @foreach (array_filter(preg_split('/\R/', $s['facts'])) as $fact)
                        <li class="flex items-center gap-1.5 text-base leading-6 font-medium text-[#212121]"><i class="bi bi-check-circle-fill text-sm text-[#00ADEE]"></i> {{ $fact }}</li>
                    @endforeach
                </ul>
            @endif

            @if (! empty($s['bio']))
                <p class="mt-3 font-['Inter'] text-base leading-6 text-[#636363]">{{ $s['bio'] }}</p>
            @endif

            @if (! empty($s['badges']))
                <div class="mt-3 flex flex-wrap gap-3">
                    @foreach (array_filter(array_map('trim', explode(',', $s['badges']))) as $badge)
                        <span class="rounded border border-[#00ADEE] bg-[#00ADEE] px-[13px] py-1.5 font-heading text-xs leading-3 font-bold text-white">{{ $badge }}</span>
                    @endforeach
                </div>
            @endif

            @if (! empty($s['url']))
                <a href="{{ url($s['url']) }}" target="_blank" rel="noopener" class="mt-4 inline-block rounded-[30px] border border-black bg-[#020202] px-[18px] py-[9px] font-heading text-sm leading-[14px] font-bold text-white transition hover:bg-white hover:text-black">View Portfolio</a>
            @endif
        </div>
    </div>
</section>
