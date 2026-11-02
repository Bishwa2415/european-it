{{-- Light blue box: job titles the student can get after the course --}}
@if ($careers = $course->detail('careers'))
    <section class="rounded-xl bg-[#DEF3FF] pt-9">
        <x-course-title :title="$page['titles']['careers']" />

        <div class="mt-[29px] flex flex-wrap justify-center gap-4 px-2.5 pt-5 pb-[50px] lg:px-[30px]">
            @foreach ($careers as $career)
                <div class="w-full rounded-xl border border-[#00ADEE] bg-linear-to-b from-white to-[#DEF4FF] p-5 shadow-[inset_2px_2px_8px_rgba(0,0,0,0.35)] md:w-[48%]">
                    <h3 class="font-heading text-lg leading-none font-semibold text-[#00ADEE]">{{ $career['title'] }}</h3>
                    <p class="mt-5 font-heading text-sm leading-6 text-black">{{ $career['text'] }}</p>
                </div>
            @endforeach
        </div>
    </section>
@endif
