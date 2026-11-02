@extends('admin.layouts.app')

@section('title', 'Landing pages')

@section('content')
    <div class="mb-5 flex flex-wrap items-center gap-3">
        @foreach (['' => 'All'] + \App\Models\LandingPage::TYPES as $type => $label)
            <a href="{{ route('admin.landing-pages.index', array_filter(['type' => $type])) }}"
               @class(['rounded-lg px-3 py-1.5 text-sm', 'bg-primary text-white' => request('type', '') === $type, 'bg-white text-gray-600 hover:text-primary' => request('type', '') !== $type])>{{ $label }}</a>
        @endforeach
        <a href="{{ route('admin.landing-pages.create') }}" class="ml-auto rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-primary-hover">
            <i class="bi bi-plus-lg"></i> Add landing page
        </a>
    </div>

    <div class="overflow-x-auto rounded-xl bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-gray-100 bg-gray-50 text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-4 py-3">Page</th>
                    <th class="px-4 py-3">Design</th>
                    <th class="px-4 py-3">Course</th>
                    <th class="px-4 py-3 text-center">Leads</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($pages as $page)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.landing-pages.edit', $page) }}" class="font-medium text-heading hover:text-primary">{{ $page->title }}</a>
                            <span class="block text-xs text-gray-500">/{{ $page->slug }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <span @class(['rounded-full px-2 py-0.5 text-xs font-semibold', 'bg-sky-100 text-sky-700' => ! $page->isOffline(), 'bg-violet-100 text-violet-700' => $page->isOffline()])>{{ ucfirst($page->type) }}</span>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $page->course?->title ?? '—' }}</td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('admin.leads.index', ['page_id' => $page->id]) }}" class="text-primary hover:underline">{{ $page->leads_count }}</a>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span @class(['rounded-full px-2 py-0.5 text-xs font-semibold', 'bg-green-100 text-green-700' => $page->is_active, 'bg-gray-100 text-gray-500' => ! $page->is_active])>{{ $page->is_active ? 'Active' : 'Hidden' }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.landing-pages.edit', $page) }}" title="Edit" class="rounded-lg p-2 text-gray-500 hover:bg-primary/10 hover:text-primary"><i class="bi bi-pencil-square"></i></a>
                                <a href="{{ $page->url() }}" target="_blank" title="View" class="rounded-lg p-2 text-gray-500 hover:bg-primary/10 hover:text-primary"><i class="bi bi-box-arrow-up-right"></i></a>
                                <form method="POST" action="{{ route('admin.landing-pages.destroy', $page) }}" data-confirm="Delete “{{ $page->title }}”?">
                                    @csrf @method('DELETE')
                                    <button title="Delete" class="rounded-lg p-2 text-gray-500 hover:bg-red-50 hover:text-red-600"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-10 text-center text-gray-500">No landing pages yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
