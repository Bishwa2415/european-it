{{-- "Projects shown hands-on": 3 cards per row (square picture, title, text, tool logos) --}}
<section class="bg-white px-5 py-[60px]">
    <div class="mx-auto max-w-[1310px]">
        <x-landing.title :offline="$offline">{{ $s['title'] }}</x-landing.title>
        <hr class="mt-2.5 border-[#C4C4C4]">

        <div class="mx-auto mt-[58px] grid max-w-[1220px] gap-8 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($s['items'] ?? [] as $item)
                <div class="overflow-hidden rounded-xl bg-[#FAEDFF] pb-6 shadow-[0_0_4px_rgba(0,0,0,0.25)]">
                    @if (! empty($item['image']))
                        <img src="{{ media_url($item['image']) }}" alt="{{ $item['title'] }}" loading="lazy" class="aspect-square w-full object-cover">
                    @endif
                    <h3 class="mt-[13px] px-[26px] font-heading text-xl leading-7 font-semibold text-black">{{ $item['title'] }}</h3>
                    <p class="mt-2.5 px-[26px] text-base leading-7 text-[#3D3D3D]">{{ $item['text'] }}</p>
                    @if (! empty($item['icons']))
                        <div class="mt-3 flex flex-wrap gap-2.5 px-[26px]">
                            @foreach (array_filter(array_map('trim', explode(',', $item['icons']))) as $icon)
                                <img src="{{ media_url($icon) }}" alt="" loading="lazy" class="h-9 w-9 object-contain">
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>
