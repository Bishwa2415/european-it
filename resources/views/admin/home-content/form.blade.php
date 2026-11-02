@extends('admin.layouts.app')

@section('title', ($item->exists ? 'Edit · ' : 'Add · ').$label)

{{-- One form for every home collection. $fields comes from HomeContentController::COLLECTIONS --}}
@section('content')
    <a href="{{ route('admin.home-content.index', $collection) }}" class="mb-4 inline-block text-sm text-gray-500 hover:text-primary">← {{ $label }}</a>

    <form method="POST" enctype="multipart/form-data" class="max-w-4xl space-y-5"
          action="{{ $item->exists ? route('admin.home-content.update', [$collection, $item->id]) : route('admin.home-content.store', $collection) }}">
        @csrf
        @if ($item->exists) @method('PUT') @endif

        <x-admin.card :title="$label">
            <div class="grid gap-4 md:grid-cols-2">
                @foreach ($fields as $name => $field)
                    @php $value = $item->{$name}; @endphp

                    @switch($field['type'])
                        @case('toggle')
                            <x-admin.toggle :name="$name" :label="$field['label']" :checked="(bool) $value" />
                            @break

                        @case('textarea')
                            <div class="md:col-span-2">
                                <x-admin.textarea :name="$name" :label="$field['label']" :value="$value" rows="8" :hint="$field['hint'] ?? null" />
                            </div>
                            @break

                        @case('repeater')
                            <div class="md:col-span-2">
                                <x-admin.repeater :name="$name" :label="$field['label']" :fields="$field['fields']" :items="$value ?? []" />
                            </div>
                            @break

                        @case('image')
                            <div class="md:col-span-2 grid gap-4 rounded-lg border border-gray-200 p-4 sm:grid-cols-[160px_1fr]">
                                <img src="{{ media_url($value, 'images/fsv.webp') }}" alt="" data-image-preview="{{ $name }}_file"
                                     class="h-28 w-40 rounded-lg border border-gray-200 bg-gray-50 object-contain">
                                <div class="space-y-3">
                                    <p class="text-sm font-medium text-heading">{{ $field['label'] }}</p>
                                    <input type="file" name="{{ $name }}_file" id="{{ $name }}_file" accept="image/png,image/jpeg,image/webp,image/gif"
                                           class="block w-full text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-primary/10 file:px-3 file:py-2 file:text-primary">
                                    @error($name.'_file') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                                    <x-admin.input :name="$name" label="…or image path" :value="$value"
                                                   hint="Upload a file above, or paste a path from the Media library." />
                                </div>
                            </div>
                            @break

                        @default
                            <x-admin.input :name="$name" :label="$field['label']" :type="$field['type']" :value="$value" :hint="$field['hint'] ?? null" />
                    @endswitch
                @endforeach
            </div>
        </x-admin.card>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.home-content.index', $collection) }}" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm">Cancel</a>
            <button class="rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white hover:bg-primary-hover">
                <i class="bi bi-check2-circle"></i> Save
            </button>
        </div>
    </form>
@endsection
