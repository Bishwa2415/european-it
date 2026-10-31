{{-- Course card. Use: <x-course-card :course="$course" />  ($course = App\Models\Course) --}}
@props(['course'])

@php
    $range = $course->priceRange();   // [lowest, highest] or null
@endphp

<div {{ $attributes->merge(['class' => 'group flex h-full lg:h-[452px] flex-col rounded-[11px] border border-[#D8D6D6] bg-white px-[11px] pt-[11px] pb-[36px]']) }}>

    {{-- Thumbnail --}}
    <a href="{{ $course->url() }}" class="block overflow-hidden rounded-t-[11px]">
        <img src="{{ $course->thumbnailUrl() }}" alt="{{ $course->title }}" loading="lazy"
             class="h-[239px] w-full object-cover transition duration-500 group-hover:scale-105">
    </a>

    <div class="flex flex-1 flex-col px-[5px]">
        {{-- Title --}}
        <h3 class="mt-[30px] text-base leading-[17px] font-bold text-black">
            <a href="{{ $course->url() }}" class="hover:text-primary">{{ $course->title }}</a>
        </h3>

        {{-- Rating + students --}}
        <div class="mt-5 flex items-center justify-between text-sm leading-[22px] text-black">
            <span class="flex items-center gap-2">
                <span class="flex gap-[3px] text-[15px] text-[#FFAE4F]">
                    @for ($i = 0; $i < 5; $i++)
                        <i class="bi bi-star-fill"></i>
                    @endfor
                </span>
                {{ $course->review_count }}+ Review
            </span>
            <span>{{ $course->student_count }}+ Students</span>
        </div>

        {{-- Price range + button (mt-auto pushes this row to the bottom of the card) --}}
        <div class="mt-auto pt-4">
            <div class="flex items-center justify-between gap-3 border-t border-[#E5E5E5] pt-5">
                @if ($range)
                    <p class="font-heading text-[15px] font-bold text-black">
                        ৳ {{ number_format($range[0], 2) }}
                        @if ($range[0] != $range[1]) – ৳ {{ number_format($range[1], 2) }} @endif
                    </p>
                @endif

                <a href="{{ $course->url() }}"
                   class="group/btn ml-auto shrink-0 rounded-full border border-[#ED6700] px-4 py-[3px] text-sm leading-[22px] font-medium text-[#ED6700] transition hover:bg-[#ED6700] hover:text-white">
                    <span class="group-hover/btn:hidden">Learn and Earn</span>
                    <span class="hidden group-hover/btn:inline">Get Discount</span>
                </a>
            </div>
        </div>
    </div>
</div>
