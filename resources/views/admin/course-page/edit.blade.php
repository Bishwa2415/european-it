@extends('admin.layouts.app')

@section('title', 'Course page')

{{-- Form for App\Http\Controllers\Admin\CoursePageController: parts shared by every /course/{slug} page --}}
@section('content')
    <p class="mb-5 text-sm text-gray-600">
        These parts are the same on every course page. Curriculum, projects, tools … are edited per course:
        <a href="{{ route('admin.courses.index') }}" class="font-semibold text-primary hover:underline">Courses</a> → Edit → Detail page.
    </p>

    @if ($errors->any())
        <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <i class="bi bi-exclamation-triangle"></i> {{ $errors->first() }}
        </div>
    @endif

    @php $fields = \App\Http\Controllers\Admin\CoursePageController::FIELDS; @endphp

    <form method="POST" action="{{ route('admin.course-page.update') }}" class="max-w-5xl space-y-6">
        @csrf
        @method('PUT')

        <x-admin.card title="Buttons & pictures">
            <div class="grid gap-4 md:grid-cols-2">
                @foreach (['admission_label', 'seminar_label', 'seminar_url', 'certified_title', 'certified_image', 'price_card_image', 'dots_image'] as $key)
                    <x-admin.input :name="$key" :label="$fields[$key][0]" :value="$page[$key] ?? ''" />
                @endforeach
            </div>
        </x-admin.card>

        <x-admin.card title="Section titles" description="Black text + blue highlighted text (+ black text after it).">
            <div class="space-y-3">
                @foreach ($page['titles'] as $key => $title)
                    <div class="grid items-end gap-3 md:grid-cols-[140px_1fr_1fr_1fr]">
                        <p class="text-sm font-medium text-heading">{{ str($key)->replace('_', ' ')->ucfirst() }}</p>
                        <x-admin.input name="titles[{{ $key }}][before]" label="Before" :value="$title['before'] ?? ''" />
                        <x-admin.input name="titles[{{ $key }}][highlight]" label="Blue part" :value="$title['highlight'] ?? ''" />
                        <x-admin.input name="titles[{{ $key }}][after]" label="After" :value="$title['after'] ?? ''" />
                    </div>
                @endforeach
            </div>
        </x-admin.card>

        <x-admin.card title="“Why European IT? !” cards">
            <x-admin.repeater name="why_european_json" add-label="Add card" :items="$page['why_european']"
                              :fields="['icon' => 'Icon path', 'title' => 'Title', 'text' => ['label' => 'Text', 'type' => 'textarea', 'rows' => 2]]" />
        </x-admin.card>

        <x-admin.card title="Blue band above the footer">
            <div class="grid gap-4 md:grid-cols-2">
                @foreach (['help_title', 'help_text', 'help_phone', 'community_title', 'community_text', 'community_label', 'community_url'] as $key)
                    <x-admin.input :name="$key" :label="$fields[$key][0]" :value="$page[$key] ?? ''" />
                @endforeach
            </div>
        </x-admin.card>

        <div class="flex justify-end">
            <button class="rounded-lg bg-primary px-5 py-2.5 text-sm font-semibold text-white hover:bg-primary-hover">
                <i class="bi bi-check2-circle"></i> Save course page
            </button>
        </div>
    </form>
@endsection
