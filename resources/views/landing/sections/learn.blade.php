{{-- "What you will learn": a timeline. Odd modules on the left (text right-aligned), even ones on the right,
     each with a round blue icon on the middle line. On phones: one column with the icons on the left. --}}
<section class="bg-[#F2FBFF] px-5 py-[70px]">
    <div class="mx-auto max-w-[1310px]">
        <x-landing.title :offline="$offline" class="!text-[#04334D]">{{ $s['title'] }}</x-landing.title>
        @if (! empty($s['subtitle']))
            <p class="mt-2.5 text-center text-lg leading-7 font-semibold text-[#00608D] lg:text-xl">{{ $s['subtitle'] }}</p>
        @endif
        <hr class="mt-5 border-[#C4C4C4]">

        @php $items = $s['items'] ?? []; @endphp
        <div class="relative mt-[92px] grid gap-6 md:grid-cols-2 md:gap-x-[130px] md:gap-y-9">
            {{-- the middle line --}}
            <span class="absolute top-0 bottom-0 left-[29px] w-1 rounded bg-[#054162] md:left-1/2 md:-translate-x-1/2"></span>

            @foreach ($items as $i => $item)
                @php $left = $i % 2 === 0; @endphp
                <div @class(['relative pl-[84px] md:pl-0 md:[grid-row:var(--row)]', 'md:col-start-1 md:text-right' => $left, 'md:col-start-2 md:translate-y-[86px]' => ! $left])
                     style="--row: {{ intdiv($i, 2) + 1 }}">   {{-- md:[grid-row:var(--row)] → pairs side by side only on wide screens --}}
                    <span @class([
                        'absolute top-8 left-0 z-10 flex h-[60px] w-[60px] items-center justify-center rounded-full bg-[#00ADEE] p-[15px] text-[30px] text-white',
                        'md:left-auto md:-right-[95px]' => $left, 'md:-left-[95px]' => ! $left,
                    ])>
                        <x-landing.icon :icon="$item['icon'] ?: 'bi-check-circle'" img-class="h-[30px] w-[30px] brightness-0 invert" />
                    </span>
                    <div class="rounded-lg border border-[#EDFAFF] bg-white p-5 shadow-[0_0_2px_rgba(0,0,0,0.25)]">
                        <h3 class="text-xl leading-7 font-semibold text-[#020202] lg:text-2xl">{{ $item['title'] }}</h3>
                        <p class="mt-1 text-base leading-6 text-[#04334D]">{{ $item['text'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
        {{-- room for the shifted right column --}}
        <div class="hidden h-[86px] md:block"></div>
    </div>
</section>
