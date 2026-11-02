{{-- "Success Stories": videos of students from this course.
     Each row: source = youtube / upload / url (Vimeo, Facebook, .mp4 ...). <x-video-embed> picks iframe or <video>. --}}
@if ($videos = array_filter($course->detail('videos', []), fn ($video) => video_embed($video)))
    <section class="!mt-[100px]">
        <x-course-title :title="$page['titles']['videos']" />

        <div class="mt-[39px] grid gap-[25px] md:grid-cols-2">
            @foreach ($videos as $video)
                <div class="rounded-[10px] p-2 shadow-[0_0_23px_rgba(0,173,238,0.2)]">
                    <x-video-embed :video="$video" :title="($video['title'] ?? '') ?: $course->title.' – success story'"
                                   class="aspect-[366/230] w-full rounded-lg" />
                </div>
            @endforeach
        </div>
    </section>
@endif
