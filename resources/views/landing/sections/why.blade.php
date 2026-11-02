{{-- "Why European IT is best for you?": white cards with a blue border, 3 per row --}}
<section @class(['px-5 py-[60px]', 'bg-[#DEF3FF]' => ! $offline, 'bg-[#F2FBFF]' => $offline])>
    <div class="mx-auto max-w-[1124px]">
        <x-landing.title :offline="$offline" class="mx-auto max-w-[600px]">{{ $s['title'] }}</x-landing.title>

        <div class="mt-[52px] grid gap-[45px] md:grid-cols-2 lg:grid-cols-3">
            @foreach ($s['items'] ?? [] as $item)
                <div class="rounded-lg border border-[#00ADEE] bg-white px-[30px] py-6 text-center">
                    <x-landing.icon :icon="$item['icon'] ?: 'bi-award'" class="text-[50px] text-[#00ADEE]" img-class="mx-auto h-[50px] w-[50px]" />
                    <h3 class="mt-[15px] font-heading text-lg leading-5 font-bold text-black">{{ $item['title'] }}</h3>
                    <p class="mt-[18px] text-base leading-[22px] text-black">{{ $item['text'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
