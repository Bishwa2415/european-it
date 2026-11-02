{{-- One row of <x-admin.repeater>. Inputs have no "name": admin.js reads them by data-field and writes JSON.
     Field types: text, url, number, email, textarea, select, upload (upload = path box + "Upload" button,
     'accept' => 'image' or 'video'). 'span' => 2 makes a field twice as wide as the others. --}}
@php $columns = collect($fields)->sum(fn ($field) => $field['span'] ?? 1); @endphp

<div class="flex items-start gap-2 rounded-lg border border-gray-200 bg-gray-50 p-2" data-repeater-row>
    <div class="grid flex-1 gap-2" style="grid-template-columns: repeat({{ $columns }}, minmax(0, 1fr))">
        @foreach ($fields as $key => $field)
            <div class="block" style="grid-column: span {{ $field['span'] ?? 1 }}">
                <span class="mb-0.5 block text-[11px] uppercase text-gray-500">{{ $field['label'] }}</span>
                @if ($field['type'] === 'textarea')
                    <textarea data-field="{{ $key }}" rows="{{ $field['rows'] ?? 4 }}"
                              class="w-full rounded-md border border-gray-300 bg-white px-2 py-1.5 text-sm outline-none focus:border-primary">{{ $row[$key] ?? '' }}</textarea>
                @elseif ($field['type'] === 'select')
                    <select data-field="{{ $key }}" class="w-full rounded-md border border-gray-300 bg-white px-2 py-1.5 text-sm outline-none focus:border-primary">
                        @foreach ($field['options'] as $optionValue => $optionLabel)
                            <option value="{{ $optionValue }}" @selected((string) ($row[$key] ?? '') === (string) $optionValue)>{{ $optionLabel }}</option>
                        @endforeach
                    </select>
                @elseif ($field['type'] === 'upload')
                    @php $kind = $field['accept'] ?? 'image'; @endphp
                    {{-- data-upload-box: admin.js puts the uploaded file's path into the data-field input --}}
                    <div class="flex items-center gap-1.5" data-upload-box>
                        @if ($kind === 'image')
                            <img src="{{ media_url($row[$key] ?? null, 'images/fsv.webp') }}" alt="" data-upload-preview
                                 class="h-8 w-8 shrink-0 rounded border border-gray-200 bg-white object-contain">
                        @endif
                        <input type="text" value="{{ $row[$key] ?? '' }}" data-field="{{ $key }}" placeholder="path"
                               class="min-w-0 flex-1 rounded-md border border-gray-300 bg-white px-2 py-1.5 text-xs outline-none focus:border-primary">
                        <label class="shrink-0 cursor-pointer rounded-md bg-primary/10 px-2 py-1.5 text-xs font-semibold text-primary hover:bg-primary/20" title="Upload">
                            <i class="bi bi-upload"></i>
                            <input type="file" class="hidden" data-upload="{{ $kind }}"
                                   accept="{{ $kind === 'video' ? 'video/mp4,video/webm' : 'image/png,image/jpeg,image/webp,image/gif' }}">
                        </label>
                    </div>
                @else
                    <input type="{{ $field['type'] }}" value="{{ $row[$key] ?? '' }}" data-field="{{ $key }}"
                           @if ($field['type'] === 'number') step="any" @endif
                           class="w-full rounded-md border border-gray-300 bg-white px-2 py-1.5 text-sm outline-none focus:border-primary">
                @endif
            </div>
        @endforeach
    </div>
    <div class="flex shrink-0 gap-1 pt-4">
        <button type="button" data-repeater-up title="Move up" class="rounded border border-gray-300 bg-white px-1.5 text-sm hover:bg-gray-100">↑</button>
        <button type="button" data-repeater-down title="Move down" class="rounded border border-gray-300 bg-white px-1.5 text-sm hover:bg-gray-100">↓</button>
        <button type="button" data-repeater-remove title="Remove" class="rounded border border-red-200 bg-white px-1.5 text-sm text-red-600 hover:bg-red-50"><i class="bi bi-x"></i></button>
    </div>
</div>
