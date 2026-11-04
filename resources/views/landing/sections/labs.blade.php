{{-- Offline only: "Our modern labs" photo gallery. Photos in white frames with a light shadow, 330px high;
     the first (wide) photo takes two of the three columns so it keeps its shape and stays sharp. --}}
<section class="bg-[#F2FBFF] px-5 py-20">
    <div class="mx-auto max-w-[1310px]">
        <x-landing.title :offline="$offline">{{ $s['title'] }}</x-landing.title>
        <hr class="mt-2.5 border-[#00ADEE]">

        <div class="mt-[55px] grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($s['images'] ?? [] as $i => $image)
                <div @class(['bg-white p-1.5 shadow-[0_0_6px_rgba(0,0,0,0.15)]', 'sm:col-span-2' => $i === 0])>
                    <img src="{{ media_url($image['image']) }}" alt="{{ $s['title'] }}" loading="lazy" class="h-[260px] w-full object-cover lg:h-[318px]">
                </div>
            @endforeach
        </div>
    </div>
</section>
