@extends('layouts.app')

@section('content')
    @foreach ($homeLayout as $layoutItem)
        @if (str_starts_with($layoutItem, 'builtin:'))
            @includeIf('home.'.substr($layoutItem, strlen('builtin:')))
        @elseif (str_starts_with($layoutItem, 'common:'))
            @php($section = $commonSections->get((int) substr($layoutItem, strlen('common:'))))
            @if ($section)
                @include('home.common-section', ['section' => $section])
            @endif
        @endif
    @endforeach
@endsection
