{{-- Course hero: tagline, title, students + rating, short intro, 2 buttons, banner picture --}}
@php
    $banner = $course->detail('banner') ?? $course->thumbnail;
    $avatars = array_slice($course->detail('avatars', []), 0, 4);
@endphp

<section class="bg-[#E4F7FD] px-5 lg:px-[30px]">
    <div class="mx-auto flex max-w-[1330px] flex-col-reverse gap-10 py-10 lg:min-h-[606px] lg:flex-row lg:items-center lg:justify-between lg:py-[55px]">

        <div class="lg:max-w-[590px]">
            @if ($course->detail('tagline'))
                <p class="font-heading text-lg leading-[22px] font-bold text-[#00ADEE] lg:max-w-[520px] lg:text-[22px]">{{ $course->detail('tagline') }}</p>
            @endif

            <h1 class="mt-5 font-heading text-[28px] leading-9 font-bold text-black lg:text-5xl lg:leading-[60px]">{{ $course->title }}</h1>

            {{-- Student photos + count, 5 stars + review count --}}
            @if ($course->student_count || $course->review_count)
                <div class="mt-5 flex items-center gap-5 font-heading text-xs font-semibold text-[#020202]">
                    @if ($course->student_count)
                        <span class="flex items-center gap-2">
                            @if ($avatars)
                                <span class="flex">
                                    @foreach ($avatars as $avatar)
                                        <img src="{{ media_url($avatar) }}" alt="" class="-ml-[5px] h-6 w-6 rounded-full border border-white object-cover first:ml-0">
                                    @endforeach
                                </span>
                            @endif
                            {{ number_format($course->student_count) }}+
                        </span>
                    @endif
                    @if ($course->review_count)
                        <span class="flex items-center gap-2">
                            <span class="flex text-sm text-[#F7B500]" aria-label="Rated 5 out of 5">
                                @for ($i = 0; $i < 5; $i++) <i class="bi bi-star-fill"></i> @endfor
                            </span>
                            {{ number_format($course->review_count) }}+
                        </span>
                    @endif
                </div>
            @endif

            @if ($course->detail('intro'))
                <p class="mt-5 font-heading text-base leading-7 font-semibold text-black lg:text-lg">{{ $course->detail('intro') }}</p>
            @endif

            <div class="mt-[34px] flex flex-wrap gap-5">
                <a href="#course-content" class="course-hero-btn">{{ $page['admission_label'] }} <i class="bi bi-arrow-right"></i></a>
                <a href="{{ url($page['seminar_url']) }}" class="course-hero-btn">{{ $page['seminar_label'] }} <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>

        @if ($banner)
            <div class="relative lg:w-[640px] lg:shrink-0">
                {{-- Small dot pattern behind the top-right corner --}}
                @if ($page['dots_image'])
                    <img src="{{ media_url($page['dots_image']) }}" alt="" class="pointer-events-none absolute -top-6 -right-4 w-24 lg:-top-10 lg:-right-8 lg:w-32">
                @endif
                <img src="{{ media_url($banner) }}" alt="{{ $course->title }}"
                     class="relative aspect-[640/339] w-full rounded-xl border-[3px] border-[#00ADEE] object-cover shadow-[0_0_20px_rgba(5,195,248,0.4)]">
            </div>
        @endif
    </div>
</section>
