{{-- Big centered section heading (+ optional sub text). Use: <x-section-title title="Popular Courses" /> --}}
@props(['title', 'subtitle' => null, 'light' => false, 'tight' => false])

<div {{ $attributes->merge(['class' => 'text-center']) }}>
    <h2 @class([
        'font-heading text-[32px] lg:text-[44px] leading-[45px] font-bold',
        'text-white' => $light,
        'text-black' => ! $light,
    ])>{{ $title }}</h2>

    @if ($subtitle || $slot->isNotEmpty())
        <p @class([
            'mx-auto max-w-[1000px] text-lg lg:text-xl leading-[30px]',
            'mt-2.5' => $tight,
            'mt-5' => ! $tight,
            'text-white' => $light,
            'text-black' => ! $light,
        ])>{{ $subtitle ?? $slot }}</p>
    @endif
</div>
