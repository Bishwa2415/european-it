{{-- "Early Bird gift (for the first 15)": 3 gift cards + a note + Enroll button --}}
<section class="bg-white px-5 pt-[76px] pb-[60px]">
    <div class="mx-auto max-w-[1310px] text-center">
        <h2 class="text-[30px] leading-10 font-semibold text-[#00608D] lg:text-[39px] lg:leading-[49px]">
            {{ $s['title'] }} <span class="text-[#DB1C1C]">{{ $s['title_red'] ?? '' }}</span>
        </h2>
        @if (! empty($s['subtitle']))
            <p class="mt-2.5 text-lg leading-[33px] font-bold text-[#00608D] lg:text-[22px]">{{ $s['subtitle'] }}</p>
        @endif

        <div class="mt-[54px] grid gap-[38px] md:grid-cols-3">
            @foreach ($s['items'] ?? [] as $gift)
                <div class="rounded-lg border border-[#2CCAFF] bg-[#0072AB] px-3 py-6">
                    <x-landing.icon :icon="$gift['icon'] ?: 'bi-gift'" class="text-[50px] text-white" img-class="mx-auto h-[50px] w-[50px]" />
                    <h3 class="mt-1.5 font-heading text-lg leading-5 font-bold text-white">{{ $gift['title'] }}</h3>
                    @if (! empty($gift['value']))
                        <p class="mt-2 font-heading text-lg leading-5 font-bold text-[#FFDA0A]">{{ $gift['value'] }}</p>
                    @endif
                    <p class="mt-[30px] text-base leading-6 font-bold text-white">{{ $gift['text'] }}</p>
                </div>
            @endforeach
        </div>

        @if (! empty($s['note']))
            <p class="mt-[38px] flex items-center justify-center gap-1.5 text-lg leading-[27px] font-medium text-[#1E3A8A]"><i class="bi bi-info-circle-fill"></i> {{ $s['note'] }}</p>
        @endif
        @if (! empty($s['button_label']))
            <a href="{{ $enrollUrl }}" class="lp-btn-yellow mt-2.5">{{ $s['button_label'] }} <i class="bi bi-arrow-right"></i></a>
        @endif
    </div>
</section>
