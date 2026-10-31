@extends('admin.layouts.app')

@section('title', 'Add Course')

@section('content')
    <a href="{{ route('admin.courses.index') }}" class="mb-4 inline-block text-sm text-gray-500 hover:text-primary">← All courses</a>

    {{-- enctype="multipart/form-data" is required to upload files --}}
    <form method="POST" action="{{ route('admin.courses.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.courses._form')
    </form>
@endsection
