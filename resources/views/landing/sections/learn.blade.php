{{-- "What you will learn" (like the live page):
       left column  = first half of the modules (text right-aligned)
       middle       = all module icons on a blue wavy line, alternating left / right of the line
       right column = second half of the modules, starting a bit lower
     Phones: one list, icon on the left of each card. --}}
@php
    $items = array_values($s['items'] ?? []);
    $half = (int) ceil(count($items) / 2);
    $left = array_slice($items, 0, $half);
    $right = array_slice($items, $half);
    // Middle icons in reading order: left 1, right 1, left 2, right 2 ...
    $icons = [];
    foreach ($left as $i => $item) {
        $icons[] = ['icon' => $item['icon'], 'side' => 'left'];
        if (isset($right[$i])) {
            $icons[] = ['icon' => $right[$i]['icon'], 'side' => 'right'];
        }
    }
@endphp

{{-- The offline design is a different timeline: see landing/partials/learn-offline.blade.php --}}
@if ($offline)
    @include('landing.partials.learn-offline')
@else
<section class="bg-[#F2FBFF] px-5 py-[70px]">
    <div class="mx-auto max-w-[1310px]">
        <x-landing.title :offline="$offline" class="!text-[#04334D]">{{ $s['title'] }}</x-landing.title>
        @if (! empty($s['subtitle']))
            <p class="mt-2.5 text-center text-lg leading-7 font-semibold text-[#00608D] lg:text-xl">{{ $s['subtitle'] }}</p>
        @endif
        <hr class="mt-5 border-[#C4C4C4]">

        {{-- Desktop: 3 columns --}}
        <div class="mt-[70px] hidden grid-cols-[1fr_130px_1fr] gap-2.5 lg:grid">
            <div class="space-y-5 pt-5">
                @foreach ($left as $item)
                    @include('landing.partials.learn-card', ['item' => $item, 'align' => 'right'])
                @endforeach
            </div>

            <div class="lp-wave flex flex-col items-center gap-[25px] pt-[54px] pb-6">
                @foreach ($icons as $icon)
                    <span @class(['flex h-[60px] w-[60px] shrink-0 items-center justify-center rounded-full bg-[#00ADEE] text-[30px] text-white',
                                  '-translate-x-6' => $icon['side'] === 'left', 'translate-x-6' => $icon['side'] === 'right'])>
                        <x-landing.icon :icon="$icon['icon'] ?: 'bi-check-circle'" size="h-[30px] w-[30px]" />
                    </span>
                @endforeach
            </div>

            <div class="space-y-5 pt-[95px]">
                @foreach ($right as $item)
                    @include('landing.partials.learn-card', ['item' => $item, 'align' => 'left'])
                @endforeach
            </div>
        </div>

        {{-- Phones / tablets: one list --}}
        <div class="mt-10 space-y-4 lg:hidden">
            @foreach ($items as $item)
                <div class="flex items-start gap-3">
                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-[#00ADEE] text-2xl text-white">
                        <x-landing.icon :icon="$item['icon'] ?: 'bi-check-circle'" size="h-6 w-6" />
                    </span>
                    <div class="flex-1">@include('landing.partials.learn-card', ['item' => $item, 'align' => 'left'])</div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
