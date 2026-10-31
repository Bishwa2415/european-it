{{-- Text / number / url input with label + error.
     Use: <x-admin.input name="title" label="Course title" :value="$course->title" required />
     Works with array names too: name="plans[online][duration]" --}}
@props(['name', 'label' => null, 'type' => 'text', 'value' => null, 'hint' => null, 'prefix' => null])

@php
    // "plans[online][duration]" → "plans.online.duration" (the format old() and $errors use)
    $key = str_replace(['[', ']'], ['.', ''], $name);
    $id = str_replace('.', '_', $key);
@endphp

<div>
    @if ($label)
        <label for="{{ $id }}" class="mb-1.5 block text-sm font-medium text-heading">
            {{ $label }} @if ($attributes->has('required')) <span class="text-red-500">*</span> @endif
        </label>
    @endif

    <div class="flex">
        @if ($prefix)
            <span class="flex items-center rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 px-3 text-sm text-gray-500">{{ $prefix }}</span>
        @endif
        <input id="{{ $id }}" name="{{ $name }}" type="{{ $type }}"
               value="{{ old($key, $value) }}"   {{-- after a failed save, show what the admin typed --}}
               {{ $attributes->class([
                   'w-full border px-3 py-2.5 text-sm outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20',
                   'rounded-lg' => ! $prefix,
                   'rounded-r-lg' => $prefix,
                   'border-red-400' => $errors->has($key),
                   'border-gray-300' => ! $errors->has($key),
               ]) }}>
    </div>

    @if ($hint)
        <p class="mt-1 text-xs text-gray-500">{{ $hint }}</p>
    @endif
    @error($key)
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>
