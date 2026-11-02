{{-- Groups that each have a title and their own list of rows (a repeater inside a repeater).
     Example: dropdown menus → "Media" with its links, "Success Stories" with its links.
     Use:
        <x-admin.group-repeater name="dropdowns_json" label="Dropdown menus" title-label="Menu title"
                                :fields="['label' => 'Text', 'url' => 'Link']"
                                :groups="[['title' => 'Media', 'items' => [['label' => 'Seminar', 'url' => '/seminar']]]]" />
     Saved as JSON in a hidden input: [{"title": "...", "items": [{...}, ...]}, ...]  (JavaScript in resources/js/admin.js)
     show-title=false → groups without a title (e.g. footer link columns). --}}
@props(['name', 'label' => null, 'titleLabel' => 'Title', 'fields' => [], 'groups' => [], 'hint' => null,
        'addLabel' => 'Add group', 'rowLabel' => 'Add link', 'showTitle' => true])

@php
    $groups = old($name) ? (json_decode(old($name), true) ?: []) : ($groups ?? []);
@endphp

<div data-group-repeater>
    @if ($label)
        <p class="mb-1.5 text-sm font-medium text-heading">{{ $label }}</p>
    @endif

    <input type="hidden" name="{{ $name }}" value="{{ json_encode(array_values($groups)) }}" data-group-output>

    <div class="space-y-3" data-group-rows>
        @foreach ($groups as $group)
            @include('components.admin.group-repeater-row', compact('group', 'fields', 'titleLabel', 'rowLabel', 'showTitle'))
        @endforeach
    </div>

    <template data-group-template>
        @include('components.admin.group-repeater-row', ['group' => ['title' => '', 'items' => []]] + compact('fields', 'titleLabel', 'rowLabel', 'showTitle'))
    </template>

    <button type="button" data-group-add
            class="mt-3 rounded-lg border border-dashed border-primary/50 px-3 py-1.5 text-sm text-primary hover:bg-primary/5">
        <i class="bi bi-plus-lg"></i> {{ $addLabel }}
    </button>

    @if ($hint)
        <p class="mt-1 text-xs text-gray-500">{{ $hint }}</p>
    @endif
    @error($name)
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>
