{{-- Home page: "কেন ইউরোপিয়ান আইটিকে বেছে নেবেন?" — cards stack on top of each other while scrolling (position: sticky) --}}
@if ($features->isNotEmpty())
<section class="px-5 pt-[42px] pb-20 font-bangla">
    <h2 class="text-center text-[30px] lg:text-[44px] leading-[45px] font-bold text-black">কেন ইউরোপিয়ান আইটিকে বেছে নেবেন?</h2>

    <div class="mx-auto mt-[65px] max-w-[1153px]">
        @foreach ($features as $feature)
            <div @class([
                    'sticky top-24 lg:top-32 rounded-t-xl px-6 pt-8 pb-16 lg:px-[52px] lg:pt-[34px] lg:pb-[145px]',
                    '-mt-5' => ! $loop->first,
                 ])
                 style="background-color: {{ $feature->bg_color }}">
                <div class="flex flex-col gap-5 sm:flex-row">
                    @if ($feature->iconUrl())
                        <img src="{{ $feature->iconUrl() }}" alt="" loading="lazy" class="h-[90px] w-[90px] lg:h-[121px] lg:w-[121px] shrink-0">
                    @endif
                    <div class="min-w-0">
                        <h3 class="text-xl lg:text-[22px] leading-[30px] lg:leading-[22px] font-bold text-black">{{ $feature->title }}</h3>
                        <div class="feature-body mt-5 lg:mt-[42px] text-[17px] leading-[30px] text-black">
                            {!! $feature->body !!}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endif
