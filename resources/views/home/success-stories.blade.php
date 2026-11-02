{{-- Home page: Success Stories (YouTube videos from the success_videos table) --}}
@if ($successVideos->isNotEmpty())
<section class="relative px-5 pt-[70px] pb-[71px]">
    <div class="bg-grid-pattern absolute inset-0 pointer-events-none"></div>

    <div class="relative max-w-[1330px] mx-auto">
        <x-section-title :title="$homeTitles['success_stories_title']" />

        <div class="mt-[62px] grid gap-[31px] sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($successVideos as $video)
                {{-- Same as live: 16px round corners + 2px black border --}}
                <div class="aspect-video overflow-hidden rounded-2xl border-2 border-black bg-black">
                    {{-- loading="lazy": the video loads only when the visitor scrolls near it --}}
                    <iframe src="{{ $video->embedUrl() }}" title="{{ $video->title ?? 'Student success story' }}" loading="lazy"
                            class="h-full w-full" allowfullscreen
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
                </div>
            @endforeach
        </div>

        <div class="mt-[43px] text-center">
            <a href="https://www.youtube.com/@EuropeanIT" target="_blank" rel="noopener" class="hero-btn min-w-[158px]">{{ $homeTitles['success_stories_button'] }}</a>
        </div>
    </div>
</section>
@endif
