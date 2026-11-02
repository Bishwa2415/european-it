@extends('admin.layouts.app')

@section('title', 'Home Page Editor')

@section('content')
    <div class="mb-5 max-w-3xl">
        <p class="text-sm text-gray-600">Choose which sections appear on the homepage, then arrange them in order. Reusable sections can also be added to other pages.</p>
        <a href="{{ route('admin.content.index', 'section') }}" class="mt-2 inline-block text-sm font-semibold text-primary hover:underline">Manage reusable sections →</a>
        <a href="{{ route('home') }}" target="_blank" class="ml-4 text-sm text-gray-500 hover:text-primary">Preview homepage ↗</a>
    </div>

    <form method="POST" action="{{ route('admin.home-page.update') }}" class="max-w-4xl">
        @csrf
        @method('PUT')
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
            <div class="flex items-center justify-between border-b border-gray-200 px-5 py-4">
                <div><h2 class="font-semibold">Homepage sections</h2><p class="text-xs text-gray-500">Use the arrows to change their order.</p></div>
                <a href="{{ route('admin.content.create', 'section') }}" class="rounded-lg border border-gray-300 px-3 py-2 text-sm hover:bg-gray-50">Create reusable section</a>
            </div>
            <ol id="home-section-list" class="divide-y divide-gray-100">
                @foreach ($items as $item)
                    @php($isSelected = in_array($item['key'], $layout, true))
                    <li class="flex items-center gap-4 px-5 py-3" data-section-row>
                        <input type="checkbox" name="layout[]" value="{{ $item['key'] }}" @checked($isSelected) class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary">
                        <span class="min-w-0 flex-1"><span class="block text-sm font-medium">{{ $item['label'] }}</span><span class="text-xs text-gray-500">{{ $item['kind'] }}</span></span>
                        <div class="flex gap-1">
                            <button type="button" data-move="up" aria-label="Move {{ $item['label'] }} up" class="rounded border border-gray-300 px-2 py-1 text-sm hover:bg-gray-50">↑</button>
                            <button type="button" data-move="down" aria-label="Move {{ $item['label'] }} down" class="rounded border border-gray-300 px-2 py-1 text-sm hover:bg-gray-50">↓</button>
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>
        <div class="mt-5 rounded-xl border border-gray-200 bg-white p-5">
            <h2 class="font-semibold">Homepage hero content</h2>
            <p class="mb-4 mt-1 text-xs text-gray-500">Edit the main headline, message, buttons, slides, and statistics shown at the top of the homepage.</p>
            <div class="grid gap-4 md:grid-cols-2">
                <x-admin.input name="eyebrow" label="Small heading" :value="old('eyebrow', $hero['eyebrow'])" required />
                <x-admin.input name="title_before" label="Headline before highlight" :value="old('title_before', $hero['title_before'])" required />
                <x-admin.input name="title_highlight" label="Highlighted headline" :value="old('title_highlight', $hero['title_highlight'])" required />
                <x-admin.input name="title_after" label="Second headline line" :value="old('title_after', $hero['title_after'])" required />
            </div>
            <x-admin.textarea name="description" label="Description" :value="old('description', $hero['description'])" rows="3" required />
            <x-admin.textarea name="buttons_json" label="Hero buttons (JSON)" :value="old('buttons_json', json_encode($hero['buttons'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES))" rows="5" hint='Each button has a label and url, for example: [{"label":"Our Courses","url":"/courses"}]' required />
            <x-admin.textarea name="slides_json" label="Hero slides (JSON)" :value="old('slides_json', json_encode($hero['slides'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES))" rows="7" hint='Each slide has an image path and optional alt text.' required />
            <x-admin.textarea name="stats_json" label="Hero statistics (JSON)" :value="old('stats_json', json_encode($hero['stats'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES))" rows="7" hint='Each statistic has icon, count, suffix, and label.' required />
            <x-admin.textarea name="section_titles_json" label="Other homepage section headings (JSON)" :value="old('section_titles_json', json_encode($homeTitles, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES))" rows="12" hint="Edit the titles, intro text, and button labels used by the remaining built-in homepage sections." required />
        </div>
        @error('layout.*')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
        <div class="mt-5 flex justify-end"><button class="rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white hover:bg-primary-hover">Save homepage</button></div>
    </form>

    <script>
        document.querySelectorAll('[data-move]').forEach((button) => button.addEventListener('click', () => {
            const row = button.closest('[data-section-row]');
            const list = row.parentElement;
            if (button.dataset.move === 'up' && row.previousElementSibling) list.insertBefore(row, row.previousElementSibling);
            if (button.dataset.move === 'down' && row.nextElementSibling) list.insertBefore(row.nextElementSibling, row);
        }));
    </script>
@endsection
