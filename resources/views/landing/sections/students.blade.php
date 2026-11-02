{{-- "Our successful students": small dark name tags with a round photo sticking out on the left, 5 per row --}}
<section class="bg-white px-5 py-[60px]">
    <div class="mx-auto max-w-[1310px]">
        <x-landing.title :offline="$offline">{{ $s['title'] }}</x-landing.title>

        <div class="mt-[71px] grid grid-cols-1 gap-x-[25px] gap-y-[52px] sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5">
            @foreach ($s['items'] ?? [] as $student)
                <div class="relative rounded-[3px] border border-[#2CCAFF] bg-[#04334D] pt-[7px] pr-1 pb-2.5 pl-16">
                    @if (! empty($student['photo']))
                        <img src="{{ media_url($student['photo']) }}" alt="{{ $student['name'] }}" loading="lazy"
                             class="absolute -top-[27px] left-px h-[57px] w-[57px] rounded-full object-cover">
                    @endif
                    <h3 class="font-heading text-base leading-6 font-medium text-white">{{ $student['name'] }}</h3>
                    <p class="font-heading text-xs leading-4 text-white">{{ $student['role'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
