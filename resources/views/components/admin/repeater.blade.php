{{-- A list of rows the admin can add / remove / move, instead of typing JSON by hand.
     Use:
        <x-admin.repeater name="buttons_json" label="Buttons"
                          :fields="['label' => 'Label', 'url' => 'Link']"
                          :items="$hero['buttons']" />
     A field can also be an array:
        'count'     => ['label' => 'Number', 'type' => 'number']
        'placement' => ['label' => 'Where', 'type' => 'select', 'options' => ['before' => 'Before', 'after' => 'After']]
     The rows are saved as JSON into a hidden input called {name} (JavaScript in resources/js/admin.js),
     so the controller still receives one JSON string and validates it.
     nested = used inside <x-admin.group-repeater>: no form name, the group repeater collects it. --}}
@props(['name' => null, 'label' => null, 'fields' => [], 'items' => [], 'hint' => null, 'addLabel' => 'Add row', 'nested' => false])

@php
    // Normalise: 'url' => 'Link'  →  'url' => ['label' => 'Link', 'type' => 'text']
    $fields = collect($fields)->map(fn ($f) => is_array($f) ? $f + ['type' => 'text'] : ['label' => $f, 'type' => 'text'])->all();
    $rows = (! $nested && old($name)) ? (json_decode(old($name), true) ?: []) : ($items ?? []);
@endphp

<div data-repeater>
    @if ($label)
        <p class="mb-1.5 text-sm font-medium text-heading">{{ $label }}</p>
    @endif

    <input type="hidden" @unless ($nested) name="{{ $name }}" @endunless value="{{ json_encode(array_values($rows)) }}" data-repeater-output>

    <div class="space-y-2" data-repeater-rows>
        @foreach ($rows as $row)
            @include('components.admin.repeater-row', ['fields' => $fields, 'row' => $row])
        @endforeach
    </div>

    {{-- Empty row used as a template when "Add" is clicked --}}
    <template data-repeater-template>
        @include('components.admin.repeater-row', ['fields' => $fields, 'row' => []])
    </template>

    <button type="button" data-repeater-add
            class="mt-2 rounded-lg border border-dashed border-gray-300 px-3 py-1.5 text-sm text-gray-600 hover:border-primary hover:text-primary">
        <i class="bi bi-plus-lg"></i> {{ $addLabel }}
    </button>

    @if ($hint)
        <p class="mt-1 text-xs text-gray-500">{{ $hint }}</p>
    @endif
    @if ($name)
        @error($name)
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    @endif
</div>
