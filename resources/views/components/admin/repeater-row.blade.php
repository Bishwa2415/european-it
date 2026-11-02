{{-- One row of <x-admin.repeater>. Inputs have no "name": admin.js reads them by data-field and writes JSON. --}}
<div class="flex items-start gap-2 rounded-lg border border-gray-200 bg-gray-50 p-2" data-repeater-row>
    <div class="grid flex-1 gap-2" style="grid-template-columns: repeat({{ count($fields) }}, minmax(0, 1fr))">
        @foreach ($fields as $key => $field)
            <label class="block">
                <span class="mb-0.5 block text-[11px] uppercase text-gray-500">{{ $field['label'] }}</span>
                <input type="{{ $field['type'] }}" value="{{ $row[$key] ?? '' }}" data-field="{{ $key }}"
                       @if ($field['type'] === 'number') step="any" @endif
                       class="w-full rounded-md border border-gray-300 bg-white px-2 py-1.5 text-sm outline-none focus:border-primary">
            </label>
        @endforeach
    </div>
    <div class="flex shrink-0 gap-1 pt-4">
        <button type="button" data-repeater-up title="Move up" class="rounded border border-gray-300 bg-white px-1.5 text-sm hover:bg-gray-100">↑</button>
        <button type="button" data-repeater-down title="Move down" class="rounded border border-gray-300 bg-white px-1.5 text-sm hover:bg-gray-100">↓</button>
        <button type="button" data-repeater-remove title="Remove" class="rounded border border-red-200 bg-white px-1.5 text-sm text-red-600 hover:bg-red-50"><i class="bi bi-x"></i></button>
    </div>
</div>
