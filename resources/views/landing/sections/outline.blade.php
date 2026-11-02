{{-- "Want the full course outline?" box with a download button (PDF / Drive link) --}}
@if (! empty($s['file']) || ! empty($s['title']))
<section class="bg-[#F2FBFF] px-5 pb-[60px]">
    <div class="mx-auto max-w-[838px] rounded-[15px] border border-[#00ADEE] bg-[#0072AB] p-6 text-center">
        <h2 class="text-2xl leading-10 font-semibold text-white lg:text-[28px]">{{ $s['title'] }}</h2>
        @if (! empty($s['file']))
            <a href="{{ media_url($s['file']) }}" target="_blank" rel="noopener"
               class="mt-3 inline-flex items-center gap-2.5 rounded-[44px] border border-[#DEF3FF] bg-[#FFDA0A] px-6 py-3 font-heading text-base leading-6 font-medium text-[#020202] transition hover:bg-white">
                {{ $s['button_label'] ?: 'Download' }} <i class="bi bi-download"></i>
            </a>
        @endif
    </div>
</section>
@endif
