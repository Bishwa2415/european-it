{{-- "Certificate": big white title + text on the left, the certificate picture in a blue frame on the right --}}
<section @class(['px-5 py-[60px]', 'bg-[#0072AB]' => ! $offline, 'bg-[#0090D4]' => $offline])>
    <div class="mx-auto flex max-w-[1115px] flex-col items-center gap-10 md:flex-row md:justify-between">
        <div class="text-center md:text-left">
            <h2 class="text-[40px] leading-[60px] font-bold text-white lg:text-[57px]">{{ $s['title'] }}</h2>
            <p class="mt-2 text-lg leading-7 font-semibold text-white lg:text-xl">{{ $s['text'] }}</p>
        </div>
        @if (! empty($s['image']))
            <div class="w-full max-w-[574px] shrink-0 rounded-lg border-8 border-[#00ADEE] bg-[#D9D9D9]">
                <img src="{{ media_url($s['image']) }}" alt="{{ $s['title'] }}" loading="lazy" class="w-full">
            </div>
        @endif
    </div>
</section>
