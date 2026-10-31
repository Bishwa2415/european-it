{{-- Multi-line text. Use: <x-admin.textarea name="overview" label="Overview" :value="$course->overview" rows="6" /> --}}
@props(['name', 'label' => null, 'value' => null, 'hint' => null])

@php $key = str_replace(['[', ']'], ['.', ''], $name); @endphp

<div>
    @if ($label)
        <label for="{{ $key }}" class="mb-1.5 block text-sm font-medium text-heading">{{ $label }}</label>
    @endif
    <textarea id="{{ $key }}" name="{{ $name }}"
              {{ $attributes->class([
                  'w-full rounded-lg border px-3 py-2.5 text-sm outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20',
                  'border-red-400' => $errors->has($key),
                  'border-gray-300' => ! $errors->has($key),
              ]) }}>{{ old($key, $value) }}</textarea>
    @if ($hint)
        <p class="mt-1 text-xs text-gray-500">{{ $hint }}</p>
    @endif
    @error($key)
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>
