{{-- One group of <x-admin.group-repeater>: title + its own repeater of rows --}}
<div class="rounded-xl border border-gray-200 p-3" data-group-row>
    <div class="mb-3 flex items-end gap-2">
        @if ($showTitle)
            <label class="block flex-1">
                <span class="mb-0.5 block text-[11px] uppercase text-gray-500">{{ $titleLabel }}</span>
                <input type="text" value="{{ $group['title'] ?? '' }}" data-group-title
                       class="w-full rounded-md border border-gray-300 px-2 py-1.5 text-sm font-semibold outline-none focus:border-primary">
            </label>
        @else
            <span class="flex-1 text-xs font-semibold uppercase text-gray-500">Column</span>
            <input type="hidden" value="" data-group-title>
        @endif
        <button type="button" data-group-up title="Move group up" class="rounded border border-gray-300 px-2 py-1 text-sm hover:bg-gray-50">↑</button>
        <button type="button" data-group-down title="Move group down" class="rounded border border-gray-300 px-2 py-1 text-sm hover:bg-gray-50">↓</button>
        <button type="button" data-group-remove title="Remove group" class="rounded border border-red-200 px-2 py-1 text-sm text-red-600 hover:bg-red-50"><i class="bi bi-trash"></i></button>
    </div>

    <x-admin.repeater nested :fields="$fields" :items="$group['items'] ?? []" :add-label="$rowLabel" />
</div>
