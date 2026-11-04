{{-- "Certified By" logos + a blue bar of 4 facts (classes, projects, duration, recordings) --}}
<section @class(['px-5 pt-8 pb-[60px]', 'bg-[#DEF3FF]' => ! $offline, 'bg-[#F2FBFF]' => $offline])>
    <div class="mx-auto max-w-[1310px] text-center">
        <h3 class="font-heading text-[21px] leading-6 font-semibold text-[#020202]">{{ $s['certified_title'] }}</h3>
        @if (! empty($s['certified_image']))
            <img src="{{ media_url($s['certified_image']) }}" alt="{{ $s['certified_title'] }}" @class(['mx-auto w-full max-w-[321px]', 'mt-3' => ! $offline, 'mt-[45px]' => $offline])>
        @endif

        @if ($items = $s['items'] ?? [])
            <div @class(['mx-auto mt-[30px] grid grid-cols-2 gap-4 rounded-xl bg-[#0090D4] px-6 py-3 text-left md:flex md:justify-between', 'max-w-[1035px]' => ! $offline, 'max-w-[1191px]' => $offline])>
                @foreach ($items as $item)
                    <div class="flex items-center gap-3 text-white">
                        <x-landing.icon :icon="$item['icon'] ?: 'bi-check-circle'" class="text-[28px]" size="h-7 w-7" />
                        <span>
                            <span class="block text-lg leading-[29px] font-semibold">{{ $item['label'] }}</span>
                            <span class="block text-base leading-6">{{ $item['value'] }}</span>
                        </span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
