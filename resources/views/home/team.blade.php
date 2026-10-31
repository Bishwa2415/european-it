{{-- Home page: Our Support Team. Left box shows the active person; right is a photo slider.
     The two are linked in resources/js/team.js --}}
@if ($teamMembers->isNotEmpty())
@php $first = $teamMembers->first(); @endphp
<section class="overflow-hidden bg-[rgba(110,193,228,0.22)] px-5 pt-[70px] pb-[90px]" data-team>
    <x-section-title title="Our Support Team" />

    <div class="relative mx-auto mt-[50px] grid max-w-[1310px] items-center gap-10 lg:grid-cols-[586px_1fr] lg:gap-5 lg:pl-[59px]">
        {{-- decorative dashed line with a dot, like the original --}}
        <div class="absolute left-[26px] top-0 bottom-0 hidden border-l border-dashed border-primary/60 lg:block">
            <span class="absolute -left-[5px] top-1/2 h-2.5 w-2.5 rounded-full border-2 border-primary bg-white"></span>
        </div>

        {{-- Active member --}}
        <div class="flex flex-col justify-center rounded-[30px] bg-[rgba(29,188,255,0.84)] p-8 lg:h-[672px] lg:p-16">
            <h3 class="text-[26px] lg:text-[31px] leading-[31px] font-semibold text-black" data-team-name>{{ $first->name }}</h3>
            <p class="mt-2 text-sm font-medium text-white" data-team-designation>{{ $first->designation }}</p>
            <p class="mt-5 whitespace-pre-line text-justify font-opensans text-base leading-6 text-black" data-team-bio>{{ $first->bio }}</p>
        </div>

        {{-- Photo slider: on big screens it runs to the right edge of the window --}}
        <div class="min-w-0 lg:-mr-[calc(50vw-655px)]" data-team-slider
             data-slider='{"slidesPerView": "auto", "spaceBetween": 30}'>
            <div class="swiper !py-16">
                <div class="swiper-wrapper items-center">
                    @foreach ($teamMembers as $member)
                        <div class="swiper-slide !w-[248px]"
                             data-name="{{ $member->name }}" data-designation="{{ $member->designation }}" data-bio="{{ $member->bio }}">
                            <div class="team-card relative h-[320px] overflow-hidden rounded-lg shadow-md">
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
            <div class="flex gap-3">
                <button data-slider-prev aria-label="Previous" class="flex h-10 w-10 items-center justify-center rounded-full bg-primary/20 text-primary transition hover:bg-primary hover:text-white"><i class="bi bi-chevron-left"></i></button>
                <button data-slider-next aria-label="Next" class="flex h-10 w-10 items-center justify-center rounded-full bg-primary/20 text-primary transition hover:bg-primary hover:text-white"><i class="bi bi-chevron-right"></i></button>
            </div>
        </div>
    </div>
</section>
@endif
