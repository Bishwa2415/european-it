{{-- Right column: "Certified By" logos + Offline / Online price cards.
     The cards stick under the header while scrolling (lg:sticky top-[98px] = header height + 2px) --}}
<div class="text-center">
    <h2 class="font-menu text-[23px] leading-[23px] font-bold text-[#3C3636]">{{ $page['certified_title'] }}</h2>
    @if ($page['certified_image'])
        <img src="{{ media_url($page['certified_image']) }}" alt="{{ $page['certified_title'] }}" class="mx-auto mt-5 w-full max-w-[428px]">
    @endif
</div>

@if ($plans->isNotEmpty())
    <div class="mt-5 space-y-[30px] lg:sticky lg:top-[98px]">
        @foreach ($plans as $plan)
            <div class="rounded-[14px] bg-[#0A6FD6] bg-cover bg-center py-[30px] shadow-[0_0_15px_rgba(44,202,255,0.75)]"
                 @if ($page['price_card_image']) style="background-image: url('{{ media_url($page['price_card_image']) }}')" @endif>
                <h3 class="px-7 font-menu text-2xl leading-[26px] font-bold text-white lg:text-[29px]">{{ ucfirst($plan->type) }} Course</h3>
                <hr class="mt-5 border-white">

                <ul class="mt-6 px-7 font-heading text-base leading-8 text-white lg:text-lg">
                    @if ($plan->duration)
                        <li><i class="bi bi-clock mr-2"></i><strong>Duration:</strong> {{ $plan->duration }}</li>
                    @endif
                    @if ($plan->total_classes)
                        <li><i class="bi bi-check2-square mr-2"></i><strong>Total:</strong> {{ $plan->total_classes }} Classes</li>
                    @endif
                    @if ($plan->classes_per_week)
                        <li><i class="bi bi-grid-fill mr-2"></i><strong>Class Per Week:</strong> {{ $plan->classes_per_week }} Day</li>
                    @endif
                </ul>

                <div class="mt-4 px-7 pb-[15px]">
                    {{-- Course fee: old price crossed out + current price (yellow) --}}
                    <p class="inline-flex flex-wrap items-center gap-x-1.5 rounded-[10px] border border-white px-3.5 py-[7px] text-base text-[#F7FC08]">
                        Course Fee:
                        @if ($plan->sale_price && $plan->sale_price < $plan->regular_price)
                            <del class="text-lg font-bold lg:text-xl">৳&nbsp;{{ number_format($plan->regular_price, 2) }}</del>
                        @endif
                        <span class="text-lg font-bold lg:text-xl">৳&nbsp;{{ number_format($plan->currentPrice(), 2) }}</span>
                    </p>

                    {{-- Checkout comes in a later step; for now the admission form gets the course + plan --}}
                    <a href="{{ url($admissionUrl).'?'.http_build_query(['course' => $course->slug, 'plan' => $plan->type]) }}"
                       class="mt-5 block rounded-[11px] bg-white p-[15px] text-center text-xl leading-[22px] font-bold text-[#058FC3] transition hover:bg-[#058FC3] hover:text-white lg:text-[26px]">
                        {{ $page['admission_label'] }}
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@endif
