@extends('admin.layouts.app')

@section('title', $item->exists ? 'Edit '.$label : 'Add '.$label)

@section('content')
    <a href="{{ route('admin.home-content.index', $collection) }}" class="mb-4 inline-block text-sm text-gray-500 hover:text-primary">← All {{ strtolower($label) }}</a>
    <form method="POST" action="{{ $item->exists ? route('admin.home-content.update', [$collection, $item->id]) : route('admin.home-content.store', $collection) }}" class="max-w-4xl space-y-5">
        @csrf
        @if ($item->exists) @method('PUT') @endif
        <x-admin.card title="{{ $label }} content">
            <div class="grid gap-4 md:grid-cols-2">
                @foreach ($fields as $field)
                    @if ($field['type'] === 'toggle')
                        <x-admin.toggle :name="$field['name']" :label="$field['label']" :checked="(bool) old($field['name'], $field['value'] ?? ($item->exists ? false : true))" />
                    @elseif ($field['type'] === 'textarea' || $field['type'] === 'json')
                        <div class="md:col-span-2"><x-admin.textarea :name="$field['name']" :label="$field['label']" :value="old($field['name'], $field['value'])" rows="{{ $field['type'] === 'json' ? 7 : 8 }}" :hint="$field['hint'] ?? null" :required="$field['required'] ?? false" /></div>
                    @else
                        <x-admin.input :name="$field['name']" :label="$field['label']" :type="$field['type']" :value="old($field['name'], $field['value'] ?? ($field['name'] === 'sort_order' ? 0 : null))" :hint="$field['hint'] ?? null" :required="$field['required'] ?? false" />
                    @endif
                @endforeach
            </div>
        </x-admin.card>
        <div class="flex justify-end"><button class="rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white hover:bg-primary-hover">Save item</button></div>
    </form>
@endsection
