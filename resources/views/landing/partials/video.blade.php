{{-- Video box: cover picture + play button. Needs $video (video_embed() result or null), $cover, $label, $ratio.
     Optional: $rounded (corner class, default rounded-xl), $play = 'solid' (white round button, hero) or 'ring' (thin ring, stories).
     A click loads the real player (countdown.js). Without a video link the play button is only decoration, like the live hero. --}}
@php
    $rounded ??= 'rounded-xl';
    $play ??= 'solid';
    $playIcon = $play === 'ring'
        ? '<span class="lp-play-ring h-[95px] w-[95px] text-4xl text-white"><i class="bi bi-play-fill ml-1"></i></span>'
        : '<span class="lp-play-ring h-[70px] w-[70px] border-[#7FD6F7] bg-white text-4xl text-[#00ADEE] shadow-lg"><i class="bi bi-play-fill ml-1"></i></span>';
@endphp

<div class="relative {{ $ratio }} w-full overflow-hidden {{ $rounded }} bg-black">
    @if ($cover)
        @if ($video)
            <button type="button" class="group absolute inset-0 h-full w-full" aria-label="Play video"
                    data-video-play="{{ $video[1] }}" data-video-kind="{{ $video[0] }}">
                <img src="{{ media_url($cover) }}" alt="" class="h-full w-full object-cover">
                <span class="absolute top-1/2 left-1/2 -translate-1/2 transition group-hover:scale-110">{!! $playIcon !!}</span>
            </button>
        @else
            <img src="{{ media_url($cover) }}" alt="" class="h-full w-full object-cover">
            <span class="pointer-events-none absolute top-1/2 left-1/2 -translate-1/2">{!! $playIcon !!}</span>
        @endif
    @elseif ($video)
        <x-video-embed :video="['source' => 'url', 'url' => $video[1]]" class="absolute inset-0 h-full w-full" />
    @endif

    @if ($label)
        {{-- Blue strip with a slanted right edge, top-left corner (like the live hero) --}}
        <span class="pointer-events-none absolute top-0 left-0 flex items-center gap-2 bg-[#00ADEE] py-2.5 pr-12 pl-[30px] text-base leading-6 text-white [clip-path:polygon(0_0,100%_0,calc(100%-28px)_100%,0_100%)]">
            <i class="bi bi-play-circle-fill text-lg"></i> {{ $label }}
        </span>
    @endif
</div>
