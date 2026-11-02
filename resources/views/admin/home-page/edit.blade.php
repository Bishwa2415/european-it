@extends('admin.layouts.app')

@section('title', 'Home Page')

@section('content')
    <div class="mb-5 flex flex-wrap items-center gap-4 text-sm">
        <p class="text-gray-600">Choose which sections appear on the home page, put them in order, and edit the hero and headings.</p>
        <a href="{{ route('admin.content.index', 'section') }}" class="font-semibold text-primary hover:underline">Reusable sections →</a>
        <a href="{{ route('home') }}" target="_blank" class="text-gray-500 hover:text-primary">View home page <i class="bi bi-box-arrow-up-right"></i></a>
    </div>

    @if ($errors->any())
        <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <i class="bi bi-exclamation-triangle"></i> {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.home-page.update') }}">
        @csrf
        @method('PUT')

        <div class="grid gap-6 xl:grid-cols-[1fr_380px]">
            <div class="min-w-0 space-y-6">
                {{-- Hero --}}
                <x-admin.card title="Hero banner" description="The top part of the home page.">
                    <div class="grid gap-4 md:grid-cols-2">
                        <x-admin.input name="eyebrow" label="Small heading" :value="$hero['eyebrow']" required />
                        <x-admin.input name="title_before" label="Headline (black part)" :value="$hero['title_before']" required />
                        <x-admin.input name="title_highlight" label="Headline (blue part)" :value="$hero['title_highlight']" required />
                        <x-admin.input name="title_after" label="Headline second line" :value="$hero['title_after']" required />
                    </div>
                    <x-admin.textarea name="description" label="Description" :value="$hero['description']" rows="3" required />

                    <x-admin.repeater name="buttons_json" label="Buttons" add-label="Add button"
                                      :fields="['label' => 'Text', 'url' => 'Link (e.g. /discount)']" :items="$hero['buttons']" />

                    <x-admin.repeater name="slides_json" label="Slider images" add-label="Add slide"
                                      :fields="['image' => 'Image path', 'alt' => 'Alt text (for Google)']" :items="$hero['slides']"
                                      hint="Upload a picture in Media library, click “Copy path” and paste it here." />

                    <x-admin.repeater name="stats_json" label="Number boxes" add-label="Add number box"
                                      :fields="['icon' => 'Icon (bi-...)', 'count' => ['label' => 'Number', 'type' => 'number'], 'suffix' => 'After number (+, K+, %)', 'label' => 'Text']"
                                      :items="$hero['stats']"
                                      hint="Icon names: icons.getbootstrap.com (e.g. bi-mortarboard-fill)." />
                </x-admin.card>

                {{-- Headings of the other sections --}}
                <x-admin.card title="Section headings & buttons">
                    <div class="grid gap-4 md:grid-cols-2">
                        @foreach ($homeTitles as $key => $value)
                            <x-admin.input name="titles[{{ $key }}]" :label="str($key)->replace('_', ' ')->ucfirst()" :value="$value" />
                        @endforeach
                    </div>
                </x-admin.card>
            </div>

            {{-- Section order --}}
            <div class="space-y-6">
                <x-admin.card title="Sections & order" description="Tick to show, use ↑ ↓ to move.">
                    <ol class="-mx-5 -my-5 divide-y divide-gray-100">
                        @foreach ($items as $item)
                            <li class="flex items-center gap-3 px-5 py-2.5" data-section-row>
                                <input type="checkbox" name="layout[]" value="{{ $item['key'] }}" @checked(in_array($item['key'], $layout, true))
                                       class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary">
                                <span class="min-w-0 flex-1">
                                    <span class="block text-sm font-medium">{{ $item['label'] }}</span>
                                    <span class="text-xs text-gray-500">{{ $item['kind'] }}</span>
                                </span>
                                <button type="button" data-move="up" aria-label="Move up" class="rounded border border-gray-300 px-2 text-sm hover:bg-gray-50">↑</button>
                                <button type="button" data-move="down" aria-label="Move down" class="rounded border border-gray-300 px-2 text-sm hover:bg-gray-50">↓</button>
                            </li>
                        @endforeach
                    </ol>
                </x-admin.card>

                <button class="w-full rounded-lg bg-primary py-2.5 text-sm font-semibold text-white hover:bg-primary-hover">
                    <i class="bi bi-check2-circle"></i> Save home page
                </button>
            </div>
        </div>
    </form>
@endsection
