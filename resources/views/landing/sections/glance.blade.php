{{-- "At a glance: what you get in this course": dark blue tiles, 4 per row; a row that is not full is centred,
     and a single last tile is a bit wider (like the live page). + Enroll button --}}
@php $items = $s['items'] ?? []; @endphp

<section class="bg-white px-5 py-[60px]">
    <div class="mx-auto max-w-[1310px]">
        <x-landing.title :offline="$offline">{{ $s['title'] }}</x-landing.title>
        <hr @class(['mt-2.5', 'border-[#C4C4C4]' => ! $offline, 'border-[#00ADEE]' => $offline])>

        <div @class(['mx-auto mt-12 flex flex-wrap justify-center gap-2.5', 'max-w-[1150px]' => ! $offline])>
            @foreach ($items as $i => $item)
                @php $lonely = $i === count($items) - 1 && count($items) % 4 === 1; @endphp
                <div @class([
                    'flex flex-col items-center justify-center gap-2.5 rounded-lg px-5 text-center lg:px-12',
                    'bg-[#00608D] py-8' => ! $offline,
                    'bg-[#0090D4] py-10 lg:min-h-[180px]' => $offline,
                    'w-[calc(50%-5px)] md:w-[calc(25%-7.5px)]' => ! $lonely,
                    'w-full md:w-[384px]' => $lonely,
                ])>
                    <x-landing.icon :icon="$item['icon'] ?: 'bi-check-circle'" class="text-[32px] text-white" size="h-8 w-8" />
                    <span @class(['text-base leading-7 text-white lg:text-lg', 'font-bold' => ! $offline, 'font-heading font-semibold lg:text-xl' => $offline])>{{ $item['text'] }}</span>
                </div>
            @endforeach
        </div>

        @if (! empty($s['button_label']))
            <div class="mt-12 text-center"><a href="{{ $enrollUrl }}" @class(['lp-btn-yellow' => ! $offline, 'inline-flex items-center gap-3 rounded-[44px] bg-[#00ADEE] px-[26px] py-[15px] font-heading text-[17px] leading-[17px] font-bold text-white transition hover:bg-[#065074]' => $offline])>{{ $s['button_label'] }} <i class="bi bi-arrow-right"></i></a></div>
        @endif
    </div>
</section>
