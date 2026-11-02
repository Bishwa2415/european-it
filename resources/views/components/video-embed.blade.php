{{-- Show one dashboard video (YouTube, Vimeo, Facebook, uploaded mp4 or any video link).
     Use: <x-video-embed :video="$row" title="Success story" class="aspect-video w-full rounded-lg" />
     video_embed() in app/helpers.php decides between <iframe> and <video>. --}}
@props(['video', 'title' => 'Video'])

@if ($embed = video_embed((array) $video))
    @if ($embed[0] === 'video')
        <video src="{{ $embed[1] }}" controls preload="metadata" {{ $attributes->merge(['class' => 'bg-black']) }}></video>
    @else
        <iframe src="{{ $embed[1] }}" title="{{ $title }}" loading="lazy" allowfullscreen
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                {{ $attributes }}></iframe>
    @endif
@endif
