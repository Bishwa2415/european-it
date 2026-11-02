{{-- "Student review screenshots": pictures in dark frames, two columns that fill like a wall (CSS columns) --}}
<section class="bg-white px-5 py-[60px]">
    <div class="mx-auto max-w-[1310px]">
        <x-landing.title :offline="$offline">{{ $s['title'] }}</x-landing.title>

        <div class="mt-8 columns-1 gap-5 md:columns-2">
            @foreach ($s['images'] ?? [] as $image)
                <div class="mb-5 break-inside-avoid rounded-md bg-black p-2.5">
                    <img src="{{ media_url($image['image']) }}" alt="Student review" loading="lazy" class="w-full rounded-[10px]">
                </div>
            @endforeach
        </div>
    </div>
</section>
