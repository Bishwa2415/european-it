@extends('admin.layouts.app')

@section('title', 'Site settings')

{{-- Form for App\Http\Controllers\Admin\SettingsController. Used in topbar, mobile menu, footer and floating buttons. --}}
@section('content')
    @if ($errors->any())
        <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <i class="bi bi-exclamation-triangle"></i> {{ $errors->first() }}
        </div>
    @endif

    @php $fields = \App\Http\Controllers\Admin\SettingsController::FIELDS; @endphp

    <form method="POST" action="{{ route('admin.settings.update') }}" class="max-w-5xl space-y-6">
        @csrf
        @method('PUT')

        <x-admin.card title="Institute & contact">
            <div class="grid gap-4 md:grid-cols-2">
                @foreach (['name', 'address', 'contact_url', 'admission_url', 'whatsapp', 'tin'] as $key)
                    <x-admin.input :name="$key" :label="$fields[$key][0]" :value="$settings[$key] ?? ''" />
                @endforeach
                <x-admin.input name="hours_days" label="Opening days" :value="$settings['hours'][0] ?? ''" required />
                <x-admin.input name="hours_time" label="Opening time" :value="$settings['hours'][1] ?? ''" required />
            </div>
        </x-admin.card>

        <x-admin.card title="Phones & emails">
            <div class="grid gap-6 md:grid-cols-3">
                <x-admin.repeater name="phones_json" label="Phones (footer)" add-label="Add phone"
                                  :fields="['value' => 'Phone']" :items="collect($settings['phones'])->map(fn ($v) => ['value' => $v])->all()" />
                <x-admin.repeater name="header_phones_json" label="Phones (top bar & mobile menu)" add-label="Add phone"
                                  :fields="['value' => 'Phone']" :items="collect($settings['header_phones'])->map(fn ($v) => ['value' => $v])->all()" />
                <x-admin.repeater name="emails_json" label="Emails" add-label="Add email"
                                  :fields="['value' => ['label' => 'Email', 'type' => 'email']]" :items="collect($settings['emails'])->map(fn ($v) => ['value' => $v])->all()" />
            </div>
        </x-admin.card>

        <x-admin.card title="Links">
            <div class="grid gap-4 md:grid-cols-2">
                @foreach (['map_url', 'reviews_url'] as $key)
                    <x-admin.input :name="$key" type="url" :label="$fields[$key][0]" :value="$settings[$key] ?? ''" />
                @endforeach
            </div>
            <x-admin.repeater name="socials_json" label="Social links (footer)" add-label="Add social link"
                              :fields="['label' => 'Name', 'icon' => 'Icon (bi-facebook)', 'url' => ['label' => 'Link', 'type' => 'url']]"
                              :items="$settings['socials']" hint="Icon names: icons.getbootstrap.com (e.g. bi-youtube)." />
        </x-admin.card>

        <x-admin.card title="Floating side buttons" description="The two vertical buttons on the left and right edge of every page.">
            <div class="grid gap-4 md:grid-cols-2">
                @foreach (['demo_class_label', 'demo_class_url', 'discount_label', 'discount_url'] as $key)
                    <x-admin.input :name="$key" :label="$fields[$key][0]" :value="$settings[$key] ?? ''" />
                @endforeach
            </div>
        </x-admin.card>

        <div class="flex justify-end">
            <button class="rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white hover:bg-primary-hover">
                <i class="bi bi-check2-circle"></i> Save settings
            </button>
        </div>
    </form>
@endsection
