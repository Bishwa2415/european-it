@extends('layouts.landing')

{{-- Course landing page /{slug}   (App\Http\Controllers\LandingPageController@show)
     $landing  = the LandingPage (type online / offline = the two designs of the live site)
     $offline  = true for the offline design
     $enrollUrl = where every "Enroll Now" button goes
     Each section is resources/views/landing/sections/{key}.blade.php and gets its data as $s (null = switched off). --}}

@section('title', $landing->meta_title ?: $landing->title)
@section('description', $landing->meta_description ?: ($landing->section('hero')['intro'] ?? ''))

@section('content')
    @include('landing.header')

    @foreach (\App\Support\LandingContent::sectionsFor($landing->type) as $key => $definition)
        @if ($s = $landing->section($key))
            @include('landing.sections.'.$key, ['s' => $s])
        @endif
    @endforeach
@endsection
