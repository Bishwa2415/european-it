{{-- Offline only: "Our modern labs" photo gallery. First photo big (2×2), the others fill the grid around it. --}}
<section class="bg-[#F2FBFF] px-5 py-20">
    <div class="mx-auto max-w-[1310px]">
        <x-landing.title :offline="$offline">{{ $s['title'] }}</x-landing.title>

        <div class="mt-[60px] grid grid-cols-2 gap-5 md:grid-cols-3">
            @foreach ($s['images'] ?? [] as $i => $image)
                <img src="{{ media_url($image['image']) }}" alt="{{ $s['title'] }}" loading="lazy"
                     @class(['h-full w-full rounded-xl object-cover', 'col-span-2 row-span-2 aspect-square' => $i === 0, 'aspect-square' => $i > 0])>
            @endforeach
        </div>
    </div>
</section>
