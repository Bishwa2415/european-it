@extends('layouts.app')

@section('content')
    {{-- Each home page section lives in its own file in resources/views/home/ --}}
    @include('home.hero')
    @include('home.popular-courses')
@endsection
