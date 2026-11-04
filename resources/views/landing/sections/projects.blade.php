{{-- "Projects shown hands-on": 3 cards per row. Picture = top part of the website screenshot (300:165 like live),
     title, text, and the tool logos pinned to the bottom so every card in a row lines up. --}}
<section class="bg-white px-5 py-[60px]">
    <div class="mx-auto max-w-[1310px]">
        <x-landing.title :offline="$offline">{{ $s['title'] }}</x-landing.title>
        <hr @class(['mt-2.5', 'border-[#C4C4C4]' => ! $offline, 'border-[#3CB98A]' => $offline])>

        <div class="mx-auto mt-[58px] grid max-w-[1220px] gap-8 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($s['items'] ?? [] as $item)
                <div @class(['flex flex-col overflow-hidden rounded-xl pb-6', 'bg-linear-to-b from-[#F6F3FF] to-[#FAEDFF] shadow-[0_0_4px_rgba(0,0,0,0.25)]' => ! $offline, 'border border-[#EFEFEF] bg-white shadow-[0_0_4px_rgba(0,0,0,0.1)]' => $offline])>
                    @if (! empty($item['image']))
                        <img src="{{ media_url($item['image']) }}" alt="{{ $item['title'] }}" loading="lazy" @class(['w-full object-cover object-top', 'aspect-[300/165]' => ! $offline, 'aspect-square' => $offline])>
                    @endif
                    <h3 class="mt-[13px] px-[26px] font-heading text-xl leading-7 font-semibold text-black">{{ $item['title'] }}</h3>
                    <p class="mt-2.5 px-[26px] text-base leading-7 text-[#3D3D3D]">{{ $item['text'] }}</p>
                    @if (! empty($item['icons']))
                        <div class="mt-auto flex flex-wrap items-center gap-2.5 px-[26px] pt-4">
                            @foreach (array_filter(array_map('trim', explode(',', $item['icons']))) as $icon)
                                <img src="{{ media_url($icon) }}" alt="" loading="lazy" class="h-9 w-auto max-w-[140px] object-contain">
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>
