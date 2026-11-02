{{-- Countdown boxes (days, hours, minutes, seconds). resources/js/countdown.js fills the numbers.
     "Evergreen" like the live site: every visitor gets countdown_hours from their first visit (saved in localStorage).
     size = small (header) / large (offer section) --}}
@php $large = ($size ?? 'small') === 'large'; @endphp

<div class="flex gap-2 lg:gap-2.5" data-countdown="{{ $landing->content['settings']['countdown_hours'] ?? 72 }}" data-countdown-key="{{ $landing->slug }}">
    @foreach (['d' => 'days', 'h' => 'hours', 'm' => 'minutes', 's' => 'seconds'] as $label => $part)
        <div @class([
            'flex items-baseline justify-center gap-1 rounded-[5px] bg-white font-["Inter"] text-[#F01414]',
            'min-w-[58px] px-2.5 py-1.5 lg:min-w-[63px]' => ! $large,
            'min-w-[67px] px-2 py-2.5' => $large,
        ])>
            <span data-countdown-{{ $part }} @class(['font-semibold', 'text-lg' => ! $large, 'text-2xl' => $large])>00</span>
            <span @class(['font-medium text-2xl' => ! $large, 'font-semibold text-2xl' => $large])>{{ $label }}</span>
        </div>
    @endforeach
</div>
