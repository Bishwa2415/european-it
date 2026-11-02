{{-- "At a glance: what you get in this course": dark blue tiles, 4 per row + Enroll button --}}
<section class="bg-white px-5 py-[60px]">
    <div class="mx-auto max-w-[1150px]">
        <x-landing.title :offline="$offline">{{ $s['title'] }}</x-landing.title>

        <div class="mt-[59px] grid grid-cols-2 gap-2.5 md:grid-cols-4">
            @foreach ($s['items'] ?? [] as $item)
                <div class="flex flex-col items-center gap-2.5 rounded-lg bg-[#00608D] px-4 py-8 text-center lg:px-12">
                    <x-landing.icon :icon="$item['icon'] ?: 'bi-check-circle'" class="text-[32px] text-white" img-class="h-8 w-8 brightness-0 invert" />
                    <span class="text-base leading-7 font-bold text-white lg:text-lg">{{ $item['text'] }}</span>
                </div>
            @endforeach
        </div>

        @if (! empty($s['button_label']))
            <div class="mt-[60px] text-center"><a href="{{ $enrollUrl }}" class="lp-btn-yellow">{{ $s['button_label'] }} <i class="bi bi-arrow-right"></i></a></div>
        @endif
    </div>
</section>
