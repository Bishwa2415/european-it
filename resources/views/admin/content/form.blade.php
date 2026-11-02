@extends('admin.layouts.app')

@section('title', $item->exists ? 'Edit '.$label : 'Add '.$label)

@section('content')
    @php
        $slugHint = match ($type) {
            'section' => 'Optional anchor name for this section.',
            'popup' => 'Optional internal key for this popup.',
            default => 'This becomes the public URL slug. Pages use /{slug}; posts use /blog/{slug}.',
        };
        $dataHint = match ($type) {
            'popup' => 'Optional JSON settings: button_label and button_url.',
            default => 'Optional JSON fields include image, meta_title, meta_description, button_label, and button_url.',
        };
        $extraData = $item->data ?? [];
        unset($extraData['sections']);
    @endphp
    <a href="{{ route('admin.content.index', $type) }}" class="mb-4 inline-block text-sm text-gray-500 hover:text-primary">← All {{ strtolower($label) }}</a>
    <form method="POST" action="{{ $item->exists ? route('admin.content.update', [$type, $item]) : route('admin.content.store', $type) }}" class="max-w-4xl space-y-5">
        @csrf
        @if ($item->exists) @method('PUT') @endif
        <x-admin.card title="Content">
            <div class="grid gap-4 md:grid-cols-2">
                <x-admin.input name="title" label="Title" :value="$item->title" required />
                <x-admin.input name="slug" label="URL or key" :value="$item->slug" :hint="$slugHint" />
            </div>
            <x-admin.textarea name="body" label="Body / content (HTML supported)" :value="$item->body" rows="12" />
            @if (in_array($type, ['page', 'post'], true))
                <div class="rounded-lg border border-gray-200 p-4">
                    <h3 class="text-sm font-semibold">Reusable sections</h3>
                    <p class="mb-3 mt-1 text-xs text-gray-500">Select saved sections to place after this page's content. Edit a reusable section once and it updates everywhere it is used.</p>
                    @forelse ($commonSections as $section)
                        <label class="flex items-center gap-2 border-t border-gray-100 py-2 text-sm">
                            <input type="checkbox" name="attached_sections[]" value="{{ $section->id }}" @checked(in_array($section->id, old('attached_sections', $selectedSectionIds))) class="rounded border-gray-300 text-primary focus:ring-primary">
                            {{ $section->title }}
                        </label>
                    @empty
                        <p class="text-sm text-gray-500">No reusable sections yet. Create one from Page sections in the sidebar.</p>
                    @endforelse
                </div>
            @endif
            <x-admin.textarea name="data_json" label="Additional settings (JSON)" :value="json_encode($extraData ?: new stdClass(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)" rows="8" :hint="$dataHint" />
            <div class="grid gap-4 md:grid-cols-2">
                <x-admin.input name="sort_order" label="Display order" type="number" :value="$item->sort_order ?? 0" />
                <x-admin.toggle name="is_active" label="Published / active" :checked="(bool) old('is_active', $item->is_active)" />
            </div>
        </x-admin.card>
        <div class="flex justify-end gap-3"><a href="{{ route('admin.content.index', $type) }}" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm">Cancel</a><button class="rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white hover:bg-primary-hover">Save {{ strtolower(rtrim($label, 's')) }}</button></div>
    </form>
@endsection
