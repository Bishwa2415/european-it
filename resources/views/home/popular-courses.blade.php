{{-- Home page: Popular Courses. $popularCourses comes from HomeController@index (database) --}}
<section class="bg-[#00ADEE] px-5 pt-[72px] pb-[90px]">
    <div class="max-w-[1330px] mx-auto">
        <h2 class="text-center font-heading text-[32px] lg:text-[44px] leading-[45px] font-bold text-white">Popular Courses</h2>
        <p class="mx-auto mt-2.5 max-w-[835px] text-center text-lg lg:text-xl leading-[30px] text-white">
            Explore our most in-demand courses designed to boost your IT career. Start learning today and build
            your future with <strong>European IT™</strong> Solutions Bangladesh.
        </p>

        <div class="mt-20 grid gap-5 gap-y-10 lg:gap-y-[73px] sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($popularCourses as $course)
                <x-course-card :course="$course" />
            @endforeach
        </div>
    </div>
</section>
