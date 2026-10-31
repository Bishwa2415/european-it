@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    {{-- Stat cards: $stats comes from Admin\DashboardController --}}
    <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($stats as $stat)
            <div class="flex items-center gap-4 rounded-xl bg-white p-5 shadow-sm">
                <span class="flex h-12 w-12 items-center justify-center rounded-lg bg-primary/10 text-xl text-primary">
                    <i class="bi {{ $stat['icon'] }}"></i>
                </span>
                <div>
                    <p class="text-sm text-gray-500">{{ $stat['label'] }}</p>
                    <p class="font-heading text-2xl font-bold">{{ $stat['value'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Recently updated courses --}}
    <div class="mt-6 rounded-xl bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4">
            <h2 class="font-heading font-bold">Recently updated courses</h2>
            <a href="{{ route('admin.courses.index') }}" class="text-sm text-primary hover:underline">All courses →</a>
        </div>
        <ul class="divide-y divide-gray-100 text-sm">
            @foreach ($latestCourses as $course)
                <li class="flex items-center justify-between px-5 py-3">
                    <span>{{ $course->title }}</span>
                    <span class="text-gray-400">{{ $course->category->name }} · {{ $course->updated_at->diffForHumans() }}</span>
                </li>
            @endforeach
        </ul>
    </div>
@endsection
