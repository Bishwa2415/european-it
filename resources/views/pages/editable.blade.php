@extends('layouts.app')

@section('title', $content->data['meta_title'] ?? $content->title)
@section('description', $content->data['meta_description'] ?? '')

@section('content')
    <article class="mx-auto max-w-[1100px] px-5 py-14">
        <h1 class="mb-8 text-4xl font-bold">{{ $content->title }}</h1>
        @if ($content->data['image'] ?? false)<img src="{{ media_url($content->data['image']) }}" alt="" class="mb-8 max-h-[520px] w-full rounded-2xl object-cover">@endif
        @if ($content->body)<div class="prose max-w-none">{!! $content->body !!}</div>@endif
    </article>
    @foreach (($content->data['sections'] ?? []) as $sectionId)
        @if ($commonSections->has($sectionId))
            @include('home.common-section', ['section' => $commonSections->get($sectionId)])
        @endif
    @endforeach
@endsection
