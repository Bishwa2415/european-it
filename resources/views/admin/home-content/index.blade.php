@extends('admin.layouts.app')

@section('title', $label)

@section('content')
    <div class="mb-5 flex items-center justify-between gap-4">
        <p class="text-sm text-gray-500">Changes show on the home page right after saving.</p>
        <a href="{{ route('admin.home-content.create', $collection) }}" class="rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-primary-hover">
            <i class="bi bi-plus-lg"></i> Add item
        </a>
    </div>

    <div class="overflow-x-auto rounded-xl bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-gray-100 bg-gray-50 text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-4 py-3">Item</th>
                    <th class="px-4 py-3">Order</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($items as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                @if ($imageField)
                                    <img src="{{ media_url($item->{$imageField}, 'images/fsv.webp') }}" alt="" class="h-10 w-10 shrink-0 rounded object-cover">
                                @endif
                                <span class="font-medium">{{ $item->{$titleField} ?: $item->key }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-500">{{ $item->sort_order ?? '—' }}</td>
                        <td class="px-4 py-3">
                            @if (array_key_exists('is_active', $item->getAttributes()))
                                <span @class([
                                    'rounded-full px-2.5 py-0.5 text-xs font-medium',
                                    'bg-green-100 text-green-700' => $item->is_active,
                                    'bg-gray-100 text-gray-500' => ! $item->is_active,
                                ])>{{ $item->is_active ? 'Active' : 'Hidden' }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.home-content.edit', [$collection, $item->id]) }}" title="Edit"
                                   class="rounded-lg p-2 text-gray-500 hover:bg-primary/10 hover:text-primary"><i class="bi bi-pencil-square"></i></a>
                                <form method="POST" action="{{ route('admin.home-content.destroy', [$collection, $item->id]) }}" data-confirm="Delete this item?">
                                    @csrf
                                    @method('DELETE')
                                    <button title="Delete" class="rounded-lg p-2 text-gray-500 hover:bg-red-50 hover:text-red-600"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-10 text-center text-gray-400">No items yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-5">{{ $items->links() }}</div>
@endsection
