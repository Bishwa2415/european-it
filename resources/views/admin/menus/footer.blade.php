@extends('admin.layouts.app')

@section('title', 'Footer')

{{-- Form for App\Http\Controllers\Admin\MenuController (footer). Shown in partials/footer. --}}
@section('content')
    <p class="mb-5 text-sm text-gray-600">
        Phones, emails, address and social links are in
        <a href="{{ route('admin.settings.edit') }}" class="font-semibold text-primary hover:underline">Site settings</a>.
        The <b>Popular Courses</b> list comes from
        <a href="{{ route('admin.courses.index') }}" class="font-semibold text-primary hover:underline">Courses</a> (“Show in footer”).
    </p>

    @if ($errors->any())
        <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <i class="bi bi-exclamation-triangle"></i> {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.menus.update', 'footer') }}" enctype="multipart/form-data" class="max-w-5xl space-y-6">
        @csrf
        @method('PUT')

        <x-admin.card title="Logo & titles">
            <x-admin.image-field name="logo" label="Footer logo" :value="$menu['logo']" />
            <div class="grid gap-4 md:grid-cols-2">
                @foreach (\App\Http\Controllers\Admin\MenuController::FOOTER_FIELDS as $key => $field)
                    <x-admin.input :name="$key" :label="$field[0]" :value="$menu[$key] ?? ''" required />
                @endforeach
            </div>
        </x-admin.card>

        <x-admin.card title="Quick links" description="Each column is one list of links.">
            <x-admin.group-repeater name="quick_links_json" :show-title="false" add-label="Add column" row-label="Add link"
                                    :groups="$quickLinks" :fields="['label' => 'Text', 'url' => 'Link']" />
        </x-admin.card>

        <x-admin.card title="Payment merchants">
            <x-admin.repeater name="payments_json" add-label="Add merchant" :items="$menu['payments']"
                              :fields="[
                                  'image' => 'Logo path',
                                  'alt' => 'Name',
                                  'text' => 'Text under logo',
                                  'width' => ['label' => 'Logo width (px)', 'type' => 'number'],
                              ]"
                              hint="Upload a logo in Media library, click “Copy path” and paste it here." />
        </x-admin.card>

        <x-admin.card title="Bottom bar links">
            <x-admin.repeater name="bottom_links_json" add-label="Add link" :items="$menu['bottom_links']"
                              :fields="['label' => 'Text', 'url' => 'Link']" />
        </x-admin.card>

        <div class="flex justify-end">
            <button class="rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white hover:bg-primary-hover">
                <i class="bi bi-check2-circle"></i> Save footer
            </button>
        </div>
    </form>
@endsection
