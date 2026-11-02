{{-- "Tools You will Learn": logo tiles, 4 per row (3 on phones) --}}
@if ($tools = $course->detail('tools'))
    <section class="!mt-20">
        <x-course-title :title="$page['titles']['tools']" />

        <div class="mt-[29px] grid grid-cols-3 gap-3.5 md:grid-cols-4 md:gap-5">
            @foreach ($tools as $tool)
                <div class="flex flex-col items-center gap-5 rounded-lg border-2 border-white bg-linear-150 from-white to-[#C3DCE5] px-2.5 py-6 shadow-[0_0_4px_rgba(0,0,0,0.15)]">
                    @if (! empty($tool['icon']))
                        <img src="{{ media_url($tool['icon']) }}" alt="{{ $tool['name'] }}" class="h-[50px] w-[50px] object-contain">
                    @endif
                    <span class="text-center font-heading text-sm font-semibold text-black">{{ $tool['name'] }}</span>
                </div>
            @endforeach
        </div>
    </section>
@endif
