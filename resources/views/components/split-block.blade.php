{{-- Half image / half text row (home "Our Specialty", "15+ Years").
     Use: <x-split-block :block="$specialty" image-side="left" bg="..." icon="bi-mortarboard-fill" />
     $block = App\Models\ContentBlock --}}
@props(['block', 'imageSide' => 'left', 'bg' => '', 'icon' => 'bi-mortarboard-fill'])

<div class="grid lg:grid-cols-2 {{ $bg }}">
    {{-- Image half --}}
    <div @class(['min-h-[300px] lg:min-h-[714px]', 'lg:order-2' => $imageSide === 'right'])>
        <img src="{{ $block->imageUrl() }}" alt="{{ $block->title }}" loading="lazy" class="h-full w-full object-cover">
    </div>

    {{-- Text half --}}
    <div @class([
        'flex items-center px-5 py-12 lg:py-0',
        'lg:pl-[60px] lg:pr-5' => $imageSide === 'left',
        'lg:order-1 lg:justify-end lg:pr-[60px] lg:pl-5' => $imageSide === 'right',
    ])>
        <div class="max-w-[586px]">
            @if ($block->eyebrow)
                <p class="flex items-center gap-4">
                    <i class="bi {{ $icon }} text-xl text-[#00ADEE]"></i>
                    <span class="rounded-full bg-white px-5 py-2.5 font-heading text-xl leading-5 font-semibold text-[#00ADEE]">{{ $block->eyebrow }}</span>
                </p>
            @endif

            <h2 class="mt-5 font-heading text-[32px] leading-10 lg:text-[44px] lg:leading-[50px] font-bold text-black">{{ $block->title }}</h2>

            {{-- {!! !!} prints HTML without escaping: only for trusted text written by admins --}}
            <div class="mt-5 space-y-[25px] text-[17px] leading-[22px] text-black [&_strong]:font-bold">
                {!! $block->body !!}
            </div>

            @if ($block->buttons)
                <div class="mt-[46px] flex flex-wrap gap-7">
                    @foreach ($block->buttons as $button)
                        <a href="{{ url($button['url']) }}" class="hero-btn min-w-[158px] text-center">{{ $button['label'] }}</a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
