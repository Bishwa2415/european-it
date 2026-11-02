{{-- Home page: 4th Industrial Courses (courses with "4th Industrial course" switched on in the dashboard) --}}
@if ($fourthIrCourses->isNotEmpty())
<section class="px-5 pt-[72px] pb-20 relative overflow-hidden">
    <div class="bg-blue-grid-pattern z-[-1] absolute inset-0 pointer-events-none"></div>
    <div class="max-w-[1330px] mx-auto">
        <x-section-title :title="$homeTitles['fourth_ir_title']" :subtitle="$homeTitles['fourth_ir_description']" />

        <div class="mt-[75px] grid gap-5 gap-y-14 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($fourthIrCourses as $course)
                <x-course-card :course="$course" />
            @endforeach
        </div>

        <div class="mt-10 text-center">
            <a href="{{ url($allCoursesUrl) }}" class="hero-btn min-w-[158px]">{{ $homeTitles['fourth_ir_button'] }}</a>
        </div>
    </div>
</section>
@endif
