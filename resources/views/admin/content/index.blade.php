@extends('admin.layouts.app')

@section('title', $label)

@section('content')
    <div class="mb-5 flex items-center justify-between gap-4">
        <p class="text-sm text-gray-500">Create, edit, publish, or remove {{ strtolower($label) }}.</p>
        <a href="{{ route('admin.content.create', $type) }}" class="rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white hover:bg-primary-hover">Add {{ rtrim($label, 's') }}</a>
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-xs uppercase text-gray-500"><tr><th class="px-4 py-3">Title</th><th class="px-4 py-3">URL / key</th><th class="px-4 py-3">Status</th><th class="px-4 py-3">Order</th><th class="px-4 py-3 text-right">Actions</th></tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($items as $item)
                    <tr>
                        <td class="px-4 py-3 font-semibold">{{ $item->title }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $item->slug ?: '—' }}</td>
                        <td class="px-4 py-3"><span @class(['rounded-full px-2 py-1 text-xs font-medium', 'bg-green-50 text-green-700' => $item->is_active, 'bg-gray-100 text-gray-500' => ! $item->is_active])>{{ $item->is_active ? 'Published / active' : 'Draft / inactive' }}</span></td>
                        <td class="px-4 py-3">{{ $item->sort_order }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.content.edit', [$type, $item]) }}" class="text-primary hover:underline">Edit</a>
                            <form method="POST" action="{{ route('admin.content.destroy', [$type, $item]) }}" class="ml-3 inline" onsubmit="return confirm('Delete this item?')">@csrf @method('DELETE')<button class="text-red-600 hover:underline">Delete</button></form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-10 text-center text-gray-500">No {{ strtolower($label) }} yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $items->links() }}</div>
@endsection
