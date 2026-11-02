{{-- Big section heading of a landing page. The two designs use different fonts:
     online = Hind Siliguri 36px, offline = Noto Sans Bengali 32px. light = white text. --}}
@props(['offline' => false, 'light' => false])

<h2 {{ $attributes->class([
    'text-center font-semibold',
    'font-bangla text-[28px] leading-9 lg:text-4xl lg:leading-10' => ! $offline,
    'font-["Noto_Sans_Bengali"] text-[26px] leading-8 lg:text-[32px]' => $offline,
    'text-white' => $light,
    'text-black' => ! $light,
]) }}>{{ $slot }}</h2>
