{{-- "What you will learn" – OFFLINE design (like the live offline pages):
       a straight blue line in the middle with a dot on top and bottom;
       modules alternate left / right in reading order (1st half of the list on the left, 2nd half on the right),
       each one = blue title label + text, joined to the line by a white icon circle, a short line and a ringed dot.
     The "Download Full Course Outline" button (Course outline tab) closes the section. --}}
@php
    $rows = [];
    foreach ($left as $i => $item) {
        $rows[] = ['item' => $item, 'side' => 'left'];
        if (isset($right[$i])) {
            $rows[] = ['item' => $right[$i], 'side' => 'right'];
        }
    }
    $outline = $landing->section('outline');
@endphp

<section class="bg-[#F2FBFF] px-5 py-[60px]">
    <div class="mx-auto max-w-[1310px]">
        <x-landing.title :offline="true">{{ $s['title'] }}</x-landing.title>
        <hr class="mt-2.5 border-[#00ADEE]">

        <div class="relative mx-auto mt-14 max-w-[1250px]">
            {{-- the middle line with its end dots --}}
            <span class="absolute top-0 bottom-0 left-[23px] w-[3px] bg-[#1FA7E0] md:left-1/2 md:-translate-x-1/2"></span>
            <span class="absolute -top-2 left-[16px] h-[17px] w-[17px] rounded-full bg-[#1FA7E0] md:left-1/2 md:-translate-x-1/2"></span>
            <span class="absolute -bottom-2 left-[16px] h-[17px] w-[17px] rounded-full bg-[#1FA7E0] md:left-1/2 md:-translate-x-1/2"></span>

            <div class="py-6">
                @foreach ($rows as ['item' => $item, 'side' => $side])
                    @php $isLeft = $side === 'left'; @endphp
                    <div class="relative grid min-h-[110px] grid-cols-1 pl-[70px] md:grid-cols-2 md:pl-0">
                        {{-- ringed dot on the line + short line + white icon circle --}}
                        <span class="absolute top-[46px] left-[14px] z-10 h-[20px] w-[20px] rounded-full border-[3px] border-[#E58A54] bg-[#5BC8F5] md:left-1/2 md:-translate-x-1/2"></span>
                        <span @class(['absolute top-[55px] hidden h-[2px] w-[70px] bg-[#1FA7E0] md:block', 'right-1/2' => $isLeft, 'left-1/2' => ! $isLeft])></span>
                        <span @class([
                            'absolute top-[33px] z-10 hidden h-[46px] w-[46px] items-center justify-center rounded-full bg-white text-[#00ADEE] shadow-[0_2px_8px_rgba(0,0,0,0.25)] md:flex',
                            'right-[calc(50%+70px)] translate-x-1/2' => $isLeft, 'left-[calc(50%+70px)] -translate-x-1/2' => ! $isLeft,
                        ])>
                            <x-landing.icon :icon="$item['icon'] ?: 'bi-check-circle'" class="text-lg" size="h-[18px] w-[18px]" />
                        </span>

                        <div @class(['pt-6 pb-4', 'md:col-start-1 md:pr-[130px] md:text-right' => $isLeft, 'md:col-start-2 md:pl-[130px]' => ! $isLeft])>
                            <h3 class="inline-block bg-[#0A8FD4] px-3.5 py-[7px] font-['Noto_Sans_Bengali'] text-[17px] leading-6 font-semibold text-white">{{ $item['title'] }}</h3>
                            <p class="mt-2.5 font-['Noto_Sans_Bengali'] text-sm leading-5 text-[#1F2124]">{{ $item['text'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        @if ($outline && ! empty($outline['file']))
            <div class="mt-[110px] text-center">
                <a href="{{ media_url($outline['file']) }}" target="_blank" rel="noopener" class="lp-btn-yellow !text-[15px]">
                    {{ $outline['button_label'] ?: 'Download Full Course Outline' }} <i class="bi bi-arrow-down"></i>
                </a>
            </div>
        @endif
    </div>
</section>
