{{-- On/off switch (a styled checkbox). Use: <x-admin.toggle name="is_active" label="Active" :checked="$course->is_active" /> --}}
@props(['name', 'label', 'checked' => false, 'hint' => null])

@php $key = str_replace(['[', ']'], ['.', ''], $name); @endphp

<label class="flex cursor-pointer items-start justify-between gap-4">
    <span>
        <span class="block text-sm font-medium text-heading">{{ $label }}</span>
        @if ($hint)
            <span class="block text-xs text-gray-500">{{ $hint }}</span>
        @endif
    </span>

    {{-- hidden 0 first: if the box is unchecked the browser sends nothing, so we still get "0" --}}
    <input type="hidden" name="{{ $name }}" value="0">
    <input type="checkbox" name="{{ $name }}" value="1" class="peer sr-only" @checked(old($key, $checked)) {{ $attributes }}>
    <span class="relative mt-0.5 h-6 w-11 shrink-0 rounded-full bg-gray-300 transition peer-checked:bg-primary
                 after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow after:transition
                 peer-checked:after:translate-x-5"></span>
</label>
