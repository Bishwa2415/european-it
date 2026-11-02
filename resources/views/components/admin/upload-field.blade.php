{{-- One picture/video path with an "Upload" button (same upload as in repeater rows, admin.js → 8).
     Use: <x-admin.upload-field name="content[hero][background]" label="Background" :value="..." accept="image" /> --}}
@props(['name', 'label', 'value' => null, 'accept' => 'image'])

@php $key = str_replace(['[', ']'], ['.', ''], $name); @endphp

<div>
    <span class="mb-1.5 block text-sm font-medium text-heading">{{ $label }}</span>
    <div class="flex items-center gap-2" data-upload-box>
        @if ($accept === 'image')
            <img src="{{ media_url(old($key, $value), 'images/fsv.webp') }}" alt="" data-upload-preview
                 class="h-10 w-10 shrink-0 rounded border border-gray-200 bg-gray-50 object-contain">
        @endif
        <input type="text" name="{{ $name }}" value="{{ old($key, $value) }}" data-field="path" placeholder="path or link"
               class="min-w-0 flex-1 rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none focus:border-primary">
        <label class="shrink-0 cursor-pointer rounded-lg bg-primary/10 px-3 py-2.5 text-sm font-semibold text-primary hover:bg-primary/20">
            <i class="bi bi-upload"></i> Upload
            <input type="file" class="hidden" data-upload="{{ $accept }}"
                   accept="{{ $accept === 'video' ? 'video/mp4,video/webm' : 'image/png,image/jpeg,image/webp,image/gif' }}">
        </label>
    </div>
</div>
