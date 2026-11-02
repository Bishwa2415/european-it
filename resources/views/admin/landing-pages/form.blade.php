@extends('admin.layouts.app')

@section('title', $page->exists ? 'Edit · '.$page->title : 'Add landing page')

{{-- Form for App\Http\Controllers\Admin\LandingPageController.
     Every section tab is built from App\Support\LandingContent::SECTIONS:
       content[section][field]  → normal inputs
       lists[section][field]    → repeater JSON (rows) --}}
@section('content')
    @php
        $sections = \App\Support\LandingContent::SECTIONS;
        $content = $page->content ?? [];
        $settings = $content['settings'] ?? [];
    @endphp

    <div class="mb-4 flex flex-wrap items-center gap-4 text-sm">
        <a href="{{ route('admin.landing-pages.index') }}" class="text-gray-500 hover:text-primary">← All landing pages</a>
        @if ($page->exists)
            <a href="{{ $page->url() }}" target="_blank" class="font-semibold text-primary hover:underline">View page <i class="bi bi-box-arrow-up-right"></i></a>
            <a href="{{ route('admin.leads.index', ['page_id' => $page->id]) }}" class="text-gray-500 hover:text-primary"><i class="bi bi-person-lines-fill"></i> Leads from this page</a>
        @endif
    </div>

    @if ($errors->any())
        <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <i class="bi bi-exclamation-triangle"></i> {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ $page->exists ? route('admin.landing-pages.update', $page) : route('admin.landing-pages.store') }}" class="space-y-6">
        @csrf
        @if ($page->exists) @method('PUT') @endif

        <div class="grid gap-6 xl:grid-cols-[1fr_340px]">
            <x-admin.card title="Page">
                <div class="grid gap-4 md:grid-cols-2">
                    <x-admin.input name="title" label="Title (dashboard only)" :value="$page->title" required data-slug-source />
                    <x-admin.input name="slug" label="URL" prefix="/" :value="$page->slug" data-slug-target
                                   hint="Must match the link in Header menu → Popular Courses." />
                    <x-admin.select name="type" label="Design" :options="\App\Models\LandingPage::TYPES" :value="$page->type" />
                    <x-admin.select name="course_id" label="Course it sells" placeholder="—" :options="$courses" :value="$page->course_id" />
                    <x-admin.input name="meta_title" label="Meta title (Google)" :value="$page->meta_title" />
                    <x-admin.input name="meta_description" label="Meta description" :value="$page->meta_description" />
                </div>
            </x-admin.card>

            <x-admin.card title="Publish">
                <x-admin.toggle name="is_active" label="Active" :checked="$page->is_active" />
                <x-admin.input type="number" name="settings[countdown_hours]" min="0" label="Countdown hours" :value="$settings['countdown_hours'] ?? 72"
                               hint="Each visitor gets this many hours from their first visit. 0 = no countdown." />
                <x-admin.input name="settings[enroll_url]" label="Enroll buttons link" :value="$settings['enroll_url'] ?? ''"
                               hint="Empty = the admission form for the chosen course." />
                <x-admin.input type="number" name="sort_order" min="0" label="Order" :value="$page->sort_order ?? 0" />
                <button class="w-full rounded-lg bg-primary py-2.5 text-sm font-semibold text-white hover:bg-primary-hover">
                    <i class="bi bi-check2-circle"></i> {{ $page->exists ? 'Save' : 'Create' }}
                </button>
            </x-admin.card>
        </div>

        <x-admin.card title="Sections" description="One tab per part of the page, top to bottom. Switch a section off to hide it. Icons: Bootstrap icon name (bi-laptop) or a picture path.">
            <div class="-mx-5 -mt-5 flex flex-wrap gap-1 border-b border-gray-100 px-3 pt-3" data-tabs>
                @foreach ($sections as $key => $section)
                    <button type="button" data-tab="{{ $key }}" @class(['admin-tab', 'opacity-50' => ! ($content[$key]['show'] ?? true)])>
                        {{ $section['label'] }}
                        @isset($section['only']) <span class="text-[10px] uppercase text-gray-400">{{ $section['only'] }}</span> @endisset
                    </button>
                @endforeach
            </div>

            @foreach ($sections as $key => $section)
                @php $data = $content[$key] ?? []; @endphp
                <div data-tab-panel="{{ $key }}" hidden class="space-y-4">
                    <x-admin.toggle name="content[{{ $key }}][show]" label="Show this section" :checked="$data['show'] ?? (bool) $data" />

                    <div class="grid gap-4 md:grid-cols-2">
                        @foreach ($section['fields'] as $name => $definition)
                            @php $field = \App\Support\LandingContent::field($definition); $input = "content[{$key}][{$name}]"; @endphp
                            @switch($field['type'])
                                @case('list')
                                    <div class="md:col-span-2">
                                        <x-admin.repeater name="lists[{{ $key }}][{{ $name }}]" :label="$field['label']" :fields="$field['fields']" :items="$data[$name] ?? []" add-label="Add" />
                                    </div>
                                    @break
                                @case('textarea')
                                    <div class="md:col-span-2"><x-admin.textarea :name="$input" :label="$field['label']" :value="$data[$name] ?? ''" :rows="$field['rows'] ?? 3" /></div>
                                    @break
                                @case('upload')
                                    <x-admin.upload-field :name="$input" :label="$field['label']" :value="$data[$name] ?? ''" :accept="$field['accept'] ?? 'image'" />
                                    @break
                                @case('select')
                                    <x-admin.select :name="$input" :label="$field['label']" :options="$field['options']" :value="$data[$name] ?? ''" />
                                    @break
                                @default
                                    <x-admin.input :name="$input" :label="$field['label']" :value="$data[$name] ?? ''" />
                            @endswitch
                        @endforeach
                    </div>
                </div>
            @endforeach
        </x-admin.card>

        <div class="sticky bottom-0 flex justify-end bg-gray-100/90 py-3">
            <button class="rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white hover:bg-primary-hover">
                <i class="bi bi-check2-circle"></i> {{ $page->exists ? 'Save landing page' : 'Create landing page' }}
            </button>
        </div>
    </form>
@endsection
