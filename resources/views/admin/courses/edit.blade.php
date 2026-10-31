@extends('admin.layouts.app')

@section('title', 'Edit Course')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <a href="{{ route('admin.courses.index') }}" class="text-sm text-gray-500 hover:text-primary">← All courses</a>

        {{-- Separate form: a form inside another form is not allowed in HTML --}}
        <form method="POST" action="{{ route('admin.courses.destroy', $course) }}"
              data-confirm="Delete “{{ $course->title }}”? This cannot be undone.">
            @csrf
            @method('DELETE')
            <button class="rounded-lg border border-red-200 px-3 py-1.5 text-sm text-red-600 hover:bg-red-50">
                <i class="bi bi-trash"></i> Delete course
            </button>
        </form>
    </div>

    <form method="POST" action="{{ route('admin.courses.update', $course) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')   {{-- HTML forms only know GET/POST; this hidden field tells Laravel it is an update --}}
        @include('admin.courses._form')
    </form>
@endsection
