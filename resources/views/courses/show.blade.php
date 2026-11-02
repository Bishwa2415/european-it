@extends('layouts.app')

{{-- Course detail page  /course/{slug}   (App\Http\Controllers\CourseController@show)
     $course  = the course (details JSON = curriculum, projects, tools ...)
     $page    = parts shared by all courses (config/course-page.php + Dashboard → Course page)
     Each section is its own file in resources/views/courses/partials and hides itself when it has no content. --}}

@section('title', ($course->meta_title ?: $course->title).' | European IT Institute')
@section('description', $course->meta_description ?: \Illuminate\Support\Str::limit($course->overview ?? $course->detail('intro', ''), 155))

@section('content')
    @include('courses.partials.hero')

    {{-- Light blue gradient behind the content + sidebar --}}
    <div class="bg-linear-to-b from-[#E4F7FD] to-[#F4FCFF] px-5 pb-[95px]">
        <div class="mx-auto grid max-w-[1330px] gap-4 lg:grid-cols-[minmax(0,788fr)_minmax(0,526fr)]">

            {{-- Sidebar first in the HTML: on phones it shows right after the hero (like the live site) --}}
            <aside class="lg:order-last lg:px-5">
                @include('courses.partials.sidebar')
            </aside>

            {{-- Main column. id="course-content": the hero "Admission Now" button jumps here --}}
            <div id="course-content" class="min-w-0 pt-10 lg:pt-0 [&>section]:mt-[110px] [&>section:first-child]:mt-0">
                @include('courses.partials.overview')
                @include('courses.partials.curriculum')
                @include('courses.partials.careers')
                @include('courses.partials.reasons')
                @include('courses.partials.mentors')
                @include('courses.partials.projects')
                @include('courses.partials.tools')
                @include('courses.partials.why-european')
                @include('courses.partials.videos')
                @include('courses.partials.glance')
            </div>
        </div>
    </div>

    @include('courses.partials.help')
@endsection
