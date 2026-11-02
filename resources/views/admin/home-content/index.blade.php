@extends('admin.layouts.app')

@section('title', $label)

@section('content')
    <div class="mb-5 flex items-center justify-between gap-4">
        <p class="text-sm text-gray-500">Edit the content shown in this homepage section.</p>
        <a href="{{ route('admin.home-content.create', $collection) }}" class="rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-primary-hover">Add item</a>
    </div>
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-xs uppercase text-gray-500"><tr><th class="px-4 py-3">Title</th><th class="px-4 py-3">Order</th><th class="px-4 py-3">Status</th><th class="px-4 py-3 text-right">Actions</th></tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($items as $item)
                    <tr>
                        <td class="px-4 py-3 font-semibold">{{ $item->{$collection === 'testimonials' || $collection === 'team-members' || $collection === 'partners' ? 'name' : 'title'} ?: $item->key }}</td>
                        <td class="px-4 py-3">{{ $item->sort_order ?? '—' }}</td>
                        <td class="px-4 py-3">{{ ! isset($item->is_active) || $item->is_active ? 'Active' : 'Hidden' }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.home-content.edit', [$collection, $item->id]) }}" class="text-primary hover:underline">Edit</a>
                            <form method="POST" action="{{ route('admin.home-content.destroy', [$collection, $item->id]) }}" class="ml-3 inline" onsubmit="return confirm('Delete this item?')">@csrf @method('DELETE')<button class="text-red-600 hover:underline">Delete</button></form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-10 text-center text-gray-500">No items yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $items->links() }}</div>
@endsection
