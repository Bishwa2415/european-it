{{-- "At a glance": small blue tiles (icon + short Bangla text) --}}
@if ($glance = $course->detail('glance'))
    <section class="!mt-20">
        <x-course-title :title="$page['titles']['glance']" />

        <div class="mt-[33px] grid grid-cols-3 gap-1.5 md:grid-cols-4">
            @foreach ($glance as $item)
                <div class="flex flex-col items-center gap-2.5 rounded-lg bg-[#0090D4] px-2.5 py-5">
                    @if (! empty($item['icon']))
                        <img src="{{ media_url($item['icon']) }}" alt="" class="h-[21px] w-[21px] object-contain">
                    @endif
                    <span class="text-center font-heading text-xs leading-[19px] font-semibold text-white">{{ $item['text'] }}</span>
                </div>
            @endforeach
        </div>
    </section>
@endif
