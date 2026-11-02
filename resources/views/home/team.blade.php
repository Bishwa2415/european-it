{{-- Home page: Our Support Team
     Content: `team_members` table, first filled by database/seeders/HomeContentSeeder.php
     Left: dot bar (1 dot per person) + blue box with the active person's text (slides in on change)
     Right: photo slider; the active photo is taller. Linked together in resources/js/team.js --}}
@if ($teamMembers->isNotEmpty())
@php $first = $teamMembers->first(); @endphp
<section class="overflow-hidden bg-[rgba(110,193,228,0.22)] px-5 pt-[70px] pb-[90px]" data-team>
    <x-section-title title="Our Support Team" />

    <div class="relative mx-auto mt-[50px] grid max-w-[1310px] items-center gap-10 lg:grid-cols-[586px_1fr] lg:gap-5 lg:pl-[59px]">

        {{-- Dot bar: white line with one dot per person; the active dot grows and turns blue --}}
        <div class="absolute left-2.5 top-0 hidden h-full w-[29px] flex-col items-center justify-between lg:flex">
            <span class="absolute left-1/2 top-2.5 bottom-2.5 w-px -translate-x-1/2 bg-white"></span>
            @foreach ($teamMembers as $member)
                <button type="button" data-team-dot aria-label="Show {{ $member->name }}"
                        @class(['team-dot relative', 'is-active' => $loop->first])></button>
            @endforeach
        </div>

        {{-- Active person. Each text sits in an overflow-hidden box so the slide-in animation is clipped --}}
        <div class="flex flex-col justify-center rounded-[30px] bg-[rgba(29,188,255,0.84)] p-8 lg:h-[672px] lg:p-16">
            <div class="overflow-hidden">
                <h3 class="team-text text-[26px] lg:text-[31px] leading-[31px] font-semibold text-black" data-team-name>{{ $first->name }}</h3>
            </div>
            <div class="mt-2 overflow-hidden">
                <p class="team-text text-sm font-medium text-white" data-team-designation>{{ $first->designation }}</p>
            </div>
            <div class="mt-5 overflow-hidden">
                <p class="team-text whitespace-pre-line text-justify font-opensans text-base leading-6 text-black" data-team-bio>{{ $first->bio }}</p>
            </div>
        </div>

        {{-- Photo slider: on big screens it runs to the right edge of the window --}}
        <div class="min-w-0 lg:-mr-[calc(50vw-655px)]" data-team-slider
             data-slider='{"slidesPerView": "auto", "spaceBetween": 30, "speed": 500}'>
            <div class="swiper">
                {{-- fixed 410px height = the active photo's height, so nothing jumps --}}
                <div class="swiper-wrapper h-[410px] items-center">
                    @foreach ($teamMembers as $member)
                        <div class="swiper-slide !w-[248px]"
                             data-name="{{ $member->name }}" data-designation="{{ $member->designation }}" data-bio="{{ $member->bio }}">
                            <div class="team-card relative overflow-hidden rounded-lg">
                                <img src="{{ $member->photoUrl() }}" alt="{{ $member->name }}" loading="lazy" class="h-full w-full object-cover object-top">
                                <div class="absolute inset-x-0 bottom-0 bg-linear-to-t from-black/80 to-transparent px-3 pb-3 pt-10">
                                    <p class="text-sm font-semibold text-white">{{ $member->name }}</p>
                                    <p class="text-xs text-[#6EC1E4]">{{ $member->designation }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="mt-6 flex gap-3">
                <button data-slider-prev aria-label="Previous" class="flex h-10 w-10 items-center justify-center rounded-full bg-primary/20 text-primary transition hover:bg-primary hover:text-white"><i class="bi bi-chevron-left"></i></button>
                <button data-slider-next aria-label="Next" class="flex h-10 w-10 items-center justify-center rounded-full bg-primary/20 text-primary transition hover:bg-primary hover:text-white"><i class="bi bi-chevron-right"></i></button>
            </div>
        </div>
    </div>
</section>
@endif
