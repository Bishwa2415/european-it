{{-- Home page: 4th Industrial Courses (courses with "4th Industrial course" switched on in the dashboard) --}}
@if ($fourthIrCourses->isNotEmpty())
<section class="px-5 pt-[72px] pb-20">
    <div class="max-w-[1330px] mx-auto">
        <x-section-title title="4th Industrial Courses"
                         subtitle="Future-ready skills in AI, IoT, cloud, blockchain, and more—designed for the 4th industrial revolution." />

        <div class="mt-[43px] grid gap-5 gap-y-8 lg:gap-y-9 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($fourthIrCourses as $course)
                <x-course-card :course="$course" />
            @endforeach
        </div>

        <div class="mt-10 text-center">
            <a href="{{ url('/european-it-courses') }}" class="hero-btn min-w-[158px]">All Course</a>
        </div>
    </div>
</section>
@endif
