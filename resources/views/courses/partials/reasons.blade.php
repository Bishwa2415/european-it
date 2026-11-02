{{-- White box: "Why This course is best for you!" – icon cards (same card style as "Why European IT") --}}
@if ($reasons = $course->detail('reasons'))
    <section class="rounded-xl bg-white pt-[50px]">
        <x-course-title :title="$page['titles']['reasons']" />

        <div class="mt-[28px] grid gap-5 px-2.5 pt-5 pb-2.5 md:grid-cols-2 md:gap-[30px] md:px-[26px] md:pt-[26px] md:pb-[60px]">
            @foreach ($reasons as $reason)
                <div class="course-icon-card">
                    @if (! empty($reason['icon']))
                        <img src="{{ media_url($reason['icon']) }}" alt="" class="mx-auto mb-[18px] h-[50px] w-[50px] object-contain">
                    @endif
                    <h3 class="mb-5 pt-5 font-heading text-2xl leading-[30px] font-bold text-[#042E42]">{{ $reason['title'] }}</h3>
                    <p class="text-base text-[#04334D]">{{ $reason['text'] }}</p>
                </div>
            @endforeach
        </div>
    </section>
@endif
