{{-- Dropdown. Use: <x-admin.select name="badge" label="Badge" :options="['new' => 'New']" :value="$course->badge" placeholder="None" /> --}}
@props(['name', 'label' => null, 'options' => [], 'value' => null, 'placeholder' => null])

@php
    $key = str_replace(['[', ']'], ['.', ''], $name);
    $selected = (string) old($key, $value);
@endphp

<div>
    @if ($label)
        <label for="{{ $key }}" class="mb-1.5 block text-sm font-medium text-heading">
            {{ $label }} @if ($attributes->has('required')) <span class="text-red-500">*</span> @endif
        </label>
    @endif
    <select id="{{ $key }}" name="{{ $name }}"
            {{ $attributes->class([
                'w-full rounded-lg border bg-white px-3 py-2.5 text-sm outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20',
                'border-red-400' => $errors->has($key),
                'border-gray-300' => ! $errors->has($key),
            ]) }}>
        @if ($placeholder !== null)
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach ($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected($selected === (string) $optionValue)>{{ $optionLabel }}</option>
        @endforeach
    </select>
    @error($key)
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>
