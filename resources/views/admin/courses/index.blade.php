@extends('admin.layouts.app')

@section('title', 'Courses')

@section('content')
    {{-- Search + category filter. method="GET" puts the values in the URL: /admin/courses?search=seo --}}
    <form method="GET" class="mb-5 flex flex-wrap items-center gap-3">
        <input type="search" name="search" value="{{ request('search') }}" placeholder="Search course title..."
               class="w-64 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm outline-none focus:border-primary">

        <select name="category" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm outline-none focus:border-primary">
            <option value="">All categories</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>

        <button class="rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-primary-hover">Filter</button>
        @if (request()->hasAny(['search', 'category']))
            <a href="{{ route('admin.courses.index') }}" class="text-sm text-gray-500 hover:underline">Reset</a>
        @endif

        <span class="ml-auto text-sm text-gray-500">{{ $courses->total() }} courses</span>
        <a href="{{ route('admin.courses.create') }}"
           class="rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-primary-hover">
            <i class="bi bi-plus-lg"></i> Add course
        </a>
    </form>

    <div class="overflow-x-auto rounded-xl bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-gray-100 bg-gray-50 text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-4 py-3">Course</th>
                    <th class="px-4 py-3">Category</th>
                    <th class="px-4 py-3">Price (online – offline)</th>
                    <th class="px-4 py-3">Badge</th>
                    <th class="px-4 py-3 text-center">Popular</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($courses as $course)
                    @php $range = $course->priceRange(); @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <img src="{{ $course->thumbnailUrl() }}" alt="" class="h-10 w-16 shrink-0 rounded object-cover">
                                <div>
                                    <p class="font-medium">{{ $course->title }}</p>
                                    <p class="text-xs text-gray-400">/course/{{ $course->slug }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $course->category->name }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            @if ($range)
                                ৳{{ number_format($range[0]) }} – ৳{{ number_format($range[1]) }}
                            @else
                                <span class="text-gray-400">No plan yet</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if ($course->badge)
                                <span class="rounded px-2 py-0.5 text-xs font-semibold uppercase text-white
                                    {{ ['advance' => 'bg-[#EB4D4B]', 'popular' => 'bg-[#2ECC71]', 'new' => 'bg-primary'][$course->badge] }}">
                                    {{ $course->badge }}
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if ($course->is_popular) <i class="bi bi-star-fill text-[#FFAE4F]"></i> @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span @class([
                                'rounded-full px-2.5 py-0.5 text-xs font-medium',
                                'bg-green-100 text-green-700' => $course->is_active,
                                'bg-gray-100 text-gray-500' => ! $course->is_active,
                            ])>{{ $course->is_active ? 'Active' : 'Hidden' }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.courses.edit', $course) }}" title="Edit"
                                   class="rounded-lg p-2 text-gray-500 hover:bg-primary/10 hover:text-primary">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="{{ route('admin.courses.edit', $course) }}#tab-curriculum" title="Course page content (curriculum, projects ...)"
                                   class="rounded-lg p-2 text-gray-500 hover:bg-primary/10 hover:text-primary">
                                    <i class="bi bi-layout-text-window"></i>
                                </a>
                                <a href="{{ $course->url() }}" target="_blank" title="View on website"
                                   class="rounded-lg p-2 text-gray-500 hover:bg-primary/10 hover:text-primary">
                                    <i class="bi bi-box-arrow-up-right"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.courses.destroy', $course) }}"
                                      data-confirm="Delete “{{ $course->title }}”? This cannot be undone.">
                                    @csrf
                                    @method('DELETE')
                                    <button title="Delete" class="rounded-lg p-2 text-gray-500 hover:bg-red-50 hover:text-red-600">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-10 text-center text-gray-400">No course found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Page 1, 2, 3 links (Laravel builds them; Tailwind styles them) --}}
    <div class="mt-5">{{ $courses->links() }}</div>
@endsection
