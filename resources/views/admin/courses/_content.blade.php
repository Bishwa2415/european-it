{{-- Course page content: one tab per list (App\Support\CourseContent::LISTS). Included by _form.blade.php.
     Every list is an <x-admin.repeater>; empty lists are simply hidden on the website. --}}
@php $lists = \App\Support\CourseContent::LISTS; @endphp

<x-admin.card title="Course page content" description="Everything below the hero on /course/{slug}. Pictures: click the upload icon in a row.">
    {{-- Tab buttons (admin.js → 9. Tabs) --}}
    <div class="-mx-5 -mt-5 flex flex-wrap gap-1 border-b border-gray-100 px-3 pt-3" data-tabs>
        <button type="button" data-tab="hero" class="admin-tab">Hero</button>
        @foreach ($lists as $key => [$label])
            <button type="button" data-tab="{{ $key }}" class="admin-tab">
                {{ $label }} <span class="text-xs text-gray-400">{{ count($course->detail($key, [])) ?: '' }}</span>
            </button>
        @endforeach
    </div>

    <div data-tab-panel="hero" class="space-y-4">
        <div class="grid gap-4 md:grid-cols-2">
            <x-admin.input name="tagline" label="Small blue text above the title" :value="$course->detail('tagline')" />
            <x-admin.input name="intro" label="Short text under the title" :value="$course->detail('intro')" />
        </div>
        <x-admin.image-field name="banner" label="Banner picture (640 × 339)" :value="$course->detail('banner')" />
        <x-admin.repeater name="avatars_json" label="Student photos (next to the student count)" add-label="Add photo"
                          :fields="\App\Support\CourseContent::AVATAR_FIELDS"
                          :items="collect($course->detail('avatars', []))->map(fn ($photo) => ['photo' => $photo])->all()" />
    </div>

    @foreach ($lists as $key => [$label, $hint, $fields])
        <div data-tab-panel="{{ $key }}" hidden>
            <x-admin.repeater :name="$key.'_json'" :fields="$fields" :items="$course->detail($key, [])" :hint="$hint" add-label="Add" />
        </div>
    @endforeach
</x-admin.card>
