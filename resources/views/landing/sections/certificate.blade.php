{{-- "Certificate": big white title + text on the left; the certificate picture in a blue frame on the right,
     with the confetti / paper plane decoration around it --}}
<section @class(['overflow-hidden px-5 py-[60px]', 'bg-[#0072AB]' => ! $offline, 'bg-[#0090D4]' => $offline])>
    <div class="mx-auto flex max-w-[1115px] flex-col items-center gap-10 md:flex-row md:justify-between">
        <div class="text-center md:text-left">
            <h2 class="text-[40px] leading-[60px] font-bold text-white lg:text-[57px]">{{ $s['title'] }}</h2>
            <p class="mt-2 text-lg leading-7 font-semibold text-white lg:text-xl">{{ $s['text'] }}</p>
        </div>
        @if (! empty($s['image']))
            <div class="relative w-full max-w-[574px] shrink-0">
                <div class="rounded-lg border-8 border-[#00ADEE] bg-[#D9D9D9]">
                    <img src="{{ media_url($s['image']) }}" alt="{{ $s['title'] }}" loading="lazy" class="w-full">
                </div>
                @if (! empty($s['decoration']))
                    <img src="{{ media_url($s['decoration']) }}" alt="" loading="lazy"
                         class="pointer-events-none absolute -bottom-20 -left-24 w-[300px] lg:w-[416px]">
                    <img src="{{ media_url($s['decoration']) }}" alt="" loading="lazy"
                         class="pointer-events-none absolute -right-24 -bottom-24 w-[300px] -scale-x-100 lg:w-[416px]">
                @endif
            </div>
        @endif
    </div>
</section>
