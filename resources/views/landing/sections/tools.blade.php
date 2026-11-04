{{-- "Technologies and tools you will learn": 6 small tiles per row --}}
<section @class(['px-5 py-[60px]', 'bg-white' => ! $offline, 'bg-[#F2FBFF]' => $offline])>
    <div class="mx-auto max-w-[1310px]">
        <x-landing.title :offline="$offline">{{ $s['title'] }}</x-landing.title>
        <hr @class(['mt-2.5', 'border-[#C4C4C4]' => ! $offline, 'border-[#00ADEE]' => $offline])>

        <div class="mx-auto mt-[58px] grid grid-cols-2 max-w-[1310px] lg:gap-5 gap-4 sm:grid-cols-3 lg:grid-cols-6">
            @foreach ($s['items'] ?? [] as $tool)
                <div @class(['flex flex-col items-center gap-[15px] rounded-lg px-4 py-6', 'border border-[#0090D4] bg-white' => ! $offline, 'border-2 border-white bg-[#F2FBFF] shadow-[0_0_4px_rgba(0,0,0,0.08)]' => $offline])>
                    @if (! empty($tool['icon']))
                        <img src="{{ media_url($tool['icon']) }}" alt="{{ $tool['name'] }}" loading="lazy" @class(['w-auto max-w-[150px] object-contain', 'h-10' => ! $offline, 'h-[60px]' => $offline])>
                    @endif
                    <h3 class="text-center font-heading text-lg leading-5 font-semibold text-black">{{ $tool['name'] }}</h3>
                </div>
            @endforeach
        </div>
    </div>
</section>
