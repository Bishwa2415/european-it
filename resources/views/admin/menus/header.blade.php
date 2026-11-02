@extends('admin.layouts.app')

@section('title', 'Header menu')

{{-- Form for App\Http\Controllers\Admin\MenuController (header). Shown in partials/navbar + partials/mobile-menu. --}}
@section('content')
    <p class="mb-5 text-sm text-gray-600">
        The <b>All Course</b> mega menu is filled from
        <a href="{{ route('admin.courses.index') }}" class="font-semibold text-primary hover:underline">Courses</a> automatically.
        Everything else in the header is edited here.
    </p>

    @if ($errors->any())
        <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <i class="bi bi-exclamation-triangle"></i> {{ $errors->first() }}
        </div>
    @endif

    @php $fields = \App\Http\Controllers\Admin\MenuController::HEADER_FIELDS; @endphp

    <form method="POST" action="{{ route('admin.menus.update', 'header') }}" enctype="multipart/form-data" class="max-w-5xl space-y-6">
        @csrf
        @method('PUT')

        <x-admin.card title="Logo & buttons">
            <x-admin.image-field name="logo" label="Logo" :value="$menu['logo']" />
            <div class="grid gap-4 md:grid-cols-2">
                @foreach ($fields as $key => $field)
                    <x-admin.input :name="$key" :label="$field[0]" :value="$menu[$key] ?? ''" required />
                @endforeach
            </div>
        </x-admin.card>

        <x-admin.card title="Simple links" description="Before = left of the Success Stories / Media dropdowns, After = right of them.">
            <x-admin.repeater name="links_json" add-label="Add link" :items="$menu['links']"
                              :fields="[
                                  'label' => 'Text',
                                  'mobile_label' => 'Mobile text (optional)',
                                  'url' => 'Link',
                                  'placement' => ['label' => 'Where', 'type' => 'select', 'options' => ['before' => 'Before dropdowns', 'after' => 'After dropdowns']],
                              ]" />
        </x-admin.card>

        <x-admin.card title="Popular Courses mega menu" description="Each group is one column (e.g. Online Course, Offline Course).">
            <x-admin.group-repeater name="popular_json" title-label="Column title" add-label="Add column" row-label="Add course link"
                                    :groups="$popular"
                                    :fields="[
                                        'label' => 'Course name',
                                        'url' => 'Link',
                                        'badge' => ['label' => 'Badge', 'type' => 'select', 'options' => \App\Http\Controllers\Admin\MenuController::BADGES],
                                    ]" />
        </x-admin.card>

        <x-admin.card title="Dropdown menus" description="e.g. Success Stories, Media. The title is the menu text, the links open below it.">
            <x-admin.group-repeater name="dropdowns_json" title-label="Menu text" add-label="Add dropdown" row-label="Add link"
                                    :groups="$dropdowns" :fields="['label' => 'Text', 'url' => 'Link']" />
        </x-admin.card>

        <div class="flex justify-end">
            <button class="rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white hover:bg-primary-hover">
                <i class="bi bi-check2-circle"></i> Save header menu
            </button>
        </div>
    </form>
@endsection
