{{-- Picture field: preview + upload a file ({name}_file) or type a path ({name}).
     Use: <x-admin.image-field name="logo" label="Logo" :value="$menu['logo']" />
     The controller decides which one wins (ReadsRepeaterJson::storeImage). --}}
@props(['name', 'label', 'value' => null])

<div class="grid gap-4 rounded-lg border border-gray-200 p-4 sm:grid-cols-[160px_1fr]">
    <img src="{{ media_url($value, 'images/fsv.webp') }}" alt="" data-image-preview="{{ $name }}_file"
         class="h-28 w-40 rounded-lg border border-gray-200 bg-gray-50 object-contain">
    <div class="space-y-3">
        <p class="text-sm font-medium text-heading">{{ $label }}</p>
        <input type="file" name="{{ $name }}_file" id="{{ $name }}_file" accept="image/png,image/jpeg,image/webp,image/gif,image/svg+xml"
               class="block w-full text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-primary/10 file:px-3 file:py-2 file:text-primary">
        @error($name.'_file') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
        <x-admin.input :name="$name" label="…or image path" :value="$value"
                       hint="Upload a file above, or paste a path from the Media library." />
    </div>
</div>
