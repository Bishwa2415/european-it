@extends('layouts.app')

@section('content')
    {{-- Each home page section lives in its own file in resources/views/home/.
         Their data comes from HomeController@index (database). --}}
    @include('home.hero')
    @include('home.popular-courses')
    @include('home.fourth-ir')
    @include('home.why-choose')
    @include('home.specialty')
    @include('home.success-stories')
    @include('home.testimonials')
    @include('home.team')
    @include('home.partners')
@endsection
