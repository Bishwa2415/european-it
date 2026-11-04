{{-- Countdown boxes (days, hours, minutes, seconds). resources/js/countdown.js fills the numbers.
     "Evergreen" like the live site: every visitor gets countdown_hours from their first visit (saved in localStorage).
     size = small (header) / large (offer section). Font: Inter (set with style, see .lp-countdown in app.css). --}}
@php $large = ($size ?? 'small') === 'large'; @endphp

<div class="lp-countdown flex gap-2 lg:gap-2.5" data-countdown="{{ $landing->content['settings']['countdown_hours'] ?? 72 }}" data-countdown-key="{{ $landing->slug }}">
    @foreach (['d' => 'days', 'h' => 'hours', 'm' => 'minutes', 's' => 'seconds'] as $label => $part)
        <div @class([
            'flex items-center justify-center gap-1 rounded-[5px] bg-white text-[#F01414]',
            'h-[43px] min-w-[52px] px-2.5 lg:min-w-[63px]' => ! $large,
            'h-[51px] min-w-[60px] px-2 lg:min-w-[67px]' => $large,
        ])>
            <span data-countdown-{{ $part }} @class(['font-semibold', 'text-lg' => ! $large, 'text-2xl' => $large])>00</span>
            <span @class(['font-medium', 'text-lg lg:text-2xl' => ! $large, 'text-2xl font-semibold' => $large])>{{ $label }}</span>
        </div>
    @endforeach
</div>
