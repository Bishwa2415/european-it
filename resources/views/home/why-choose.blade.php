{{-- Home page: "কেন ইউরোপিয়ান আইটিকে বেছে নেবেন?"
     Content: `features` table (dashboard module coming), first filled by database/seeders/HomeContentSeeder.php
     Effect: every card is position: sticky with a top 25px lower than the card before,
     so while scrolling the next card slides over and a strip of each earlier card stays visible. --}}
@if ($features->isNotEmpty())
@php
    // Same "stick at" offsets as the live site (px from the top of the window)
    $stickyTops = [100, 125, 150, 175, 200, 260];
@endphp
<section class="px-5 pt-[42px] pb-20 font-bangla">
    <h2 class="text-center text-[26px] md:text-[44px] leading-[45px] font-bold text-black">{{ $homeTitles['why_choose_title'] }}</h2>

    <div class="mx-auto mt-[65px] flex max-w-[1310px] flex-col items-center">
        @foreach ($features as $feature)
            <div @class([
                    'sticky w-full rounded-t-xl px-5 pt-[30px] pb-[120px] md:px-[52px] md:pt-[34px]',
                    'md:w-[90%] md:min-h-[500px]' => $loop->first,
                    'md:w-[88%] -mt-5' => ! $loop->first,
                    'md:pb-[200px]' => $loop->last,   // live site: the last card has more space at the bottom
                 ])
                 style="top: {{ $stickyTops[$loop->index] ?? 100 + 25 * $loop->index }}px; background-color: {{ $feature->bg_color }}">
                <div class="flex flex-col gap-5 md:flex-row">
                    @if ($feature->iconUrl())
                        <img src="{{ $feature->iconUrl() }}" alt="" loading="lazy" class="w-[90px] md:w-[121px] h-auto shrink-0 self-start">
                    @endif
                    <div class="min-w-0 flex-1 md:max-w-[866px]">
                        <h3 class="text-xl md:text-[22px] leading-[30px] md:leading-[22px] font-bold text-black">{{ $feature->title }}</h3>
                        <div class="mt-5 h-0.5 bg-white"></div>
                        {{-- HTML written by admins: <p>, <strong>, <ul><li> (styles in app.css → .feature-body) --}}
                        <div class="feature-body mt-5 text-sm leading-[26px] md:text-[17px] md:leading-[30px] text-black">
                            {!! $feature->body !!}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endif
