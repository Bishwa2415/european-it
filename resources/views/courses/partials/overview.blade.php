{{-- White box: "Course Overview" + line + text --}}
@if ($course->overview)
    <section class="overflow-hidden rounded-xl bg-white pt-[30px]">
        <x-course-title :title="$page['titles']['overview']" class="!font-bold !leading-7" />
        <hr class="mt-5 border-black">
        <div class="px-5 pt-5 pb-[30px] text-justify font-heading text-base leading-7 font-medium text-black lg:px-10">
            {!! nl2br(e($course->overview)) !!}
        </div>
    </section>
@endif
