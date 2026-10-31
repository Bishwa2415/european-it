{{-- Home page: Popular Courses. $popularCourses comes from HomeController@index (database) --}}
<section class="bg-[#00ADEE] px-5 pt-[72px] pb-[90px]">
    <div class="max-w-[1330px] mx-auto">
        <x-section-title title="Popular Courses" light tight class="[&_p]:max-w-[835px]">
            Explore our most in-demand courses designed to boost your IT career. Start learning today and build
            your future with <strong>European IT™</strong> Solutions Bangladesh.
        </x-section-title>

        <div class="mt-[75px] grid gap-5 gap-y-14 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($popularCourses as $course)
                <x-course-card :course="$course" />
            @endforeach
        </div>
    </div>
</section>
