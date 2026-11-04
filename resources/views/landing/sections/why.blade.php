{{-- "Why European IT is best for you?": cards with icon, title, text, 3 per row.
     Online: white cards with a blue border. Offline: wider rows, divider line, rounded dark-blue border with a soft
     blue glow and Noto text in #035176 like live (live's titles are white on white – here they stay readable). --}}
<section @class(['px-5 py-[60px]', 'bg-[#DEF3FF]' => ! $offline, 'bg-[#F2FBFF] py-20' => $offline])>
    <div @class(['mx-auto', 'max-w-[1124px]' => ! $offline, 'max-w-[1310px]' => $offline])>
        <x-landing.title :offline="$offline" :class="$offline ? 'mx-auto' : 'mx-auto max-w-[600px]'">{{ $s['title'] }}</x-landing.title>
        @if ($offline)
            <hr class="mt-2.5 border-[#00ADEE]">
        @endif

        <div @class(['grid md:grid-cols-2 lg:grid-cols-3', 'mt-[52px] gap-[45px]' => ! $offline, 'mt-[50px] gap-7' => $offline])>
            @foreach ($s['items'] ?? [] as $item)
                <div @class(['bg-white text-center', 'rounded-lg border border-[#00ADEE] px-[30px] py-6' => ! $offline, 'rounded-2xl border border-[#035176] px-5 py-[30px] shadow-[0_0_10px_rgba(0,173,238,0.35)]' => $offline])>
                    <x-landing.icon :icon="$item['icon'] ?: 'bi-award'" class="text-[50px] text-[#00ADEE]" size="mx-auto block h-[50px] w-[50px]" />
                    <h3 @class(['mt-[15px] text-lg leading-5 font-bold', 'font-heading text-black' => ! $offline, 'lp-noto text-[#035176] lg:text-xl lg:leading-7' => $offline])>{{ $item['title'] }}</h3>
                    <p @class(['mt-[18px] text-base', 'leading-[22px] text-black' => ! $offline, 'lp-noto leading-6 text-[#035176]' => $offline])>{{ $item['text'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
