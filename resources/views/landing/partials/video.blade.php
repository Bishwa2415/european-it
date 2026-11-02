{{-- Video with a cover picture + round play button. Needs $video (video_embed() result or null), $cover, $label, $ratio.
     Without a cover the player is shown right away. --}}
<div class="relative {{ $ratio }} w-full overflow-hidden rounded-xl bg-black">
    @if ($cover && $video)
        <button type="button" class="group absolute inset-0 h-full w-full" aria-label="Play video"
                data-video-play="{{ $video[1] }}" data-video-kind="{{ $video[0] }}">
            <img src="{{ media_url($cover) }}" alt="" class="h-full w-full object-cover">
            <span class="absolute top-1/2 left-1/2 flex h-[70px] w-[70px] -translate-1/2 items-center justify-center rounded-full bg-white/90 text-4xl text-[#00ADEE] shadow-lg transition group-hover:scale-110">
                <i class="bi bi-play-fill"></i>
            </span>
        </button>
    @elseif ($video)
        <x-video-embed :video="['source' => 'url', 'url' => $video[1]]" class="absolute inset-0 h-full w-full" />
    @elseif ($cover)
        <img src="{{ media_url($cover) }}" alt="" class="h-full w-full object-cover">
    @endif

    @if ($label)
        <span class="pointer-events-none absolute top-0 left-0 flex items-center gap-1.5 rounded-br-[40px] bg-[#065074] py-2.5 pr-8 pl-5 text-base text-white">
            <i class="bi bi-play-circle"></i> {{ $label }}
        </span>
    @endif
</div>
