{{-- Course page section heading: black text + blue highlight, e.g. "Why This course is <best for you!>"
     Use: <x-course-title :title="$page['titles']['reasons']" />   (before / highlight / after from config/course-page.php) --}}
@props(['title'])

<h2 {{ $attributes->merge(['class' => 'text-center font-menu text-[26px] lg:text-[32px] leading-10 font-semibold text-black']) }}>
    {{ $title['before'] ?? '' }}
    @if (! empty($title['highlight']))
        <span class="text-primary">{{ $title['highlight'] }}</span>
    @endif
    {{ $title['after'] ?? '' }}
</h2>
