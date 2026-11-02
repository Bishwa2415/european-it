{{-- Students' success stories: videos (YouTube / upload / other link) with the student's name bar, 2 per row --}}
<section class="bg-[#F2FBFF] px-5 py-[60px]">
    <div class="mx-auto max-w-[1310px]">
        <x-landing.title :offline="$offline">{{ $s['title'] }}</x-landing.title>

        <div class="mt-[60px] grid gap-[38px] md:grid-cols-2">
            @foreach ($s['items'] ?? [] as $item)
                <div class="rounded bg-white p-2 shadow-[0_8px_4px_rgba(0,0,0,0.25)]">
                    @include('landing.partials.video', ['video' => video_embed($item), 'cover' => $item['cover'] ?? '', 'label' => '', 'ratio' => 'aspect-video'])
                    @if (! empty($item['name']))
                        <h3 class="bg-[#065074] px-2 pt-[15px] pb-4 text-lg leading-5 font-semibold text-white">{{ $item['name'] }}</h3>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>
