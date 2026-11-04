{{-- Landing page icon. Three kinds:
       "bi-laptop"                      → Bootstrap icon (font)
       "media/live/icons/fa-html5.svg"  → one-colour icon file: drawn with a CSS mask, so it takes the text colour (text-white ...)
       any other picture path           → shown as a normal picture (many colours)
     Use: <x-landing.icon :icon="$item['icon']" class="text-white" size="h-[39px] w-[39px]" /> --}}
@props(['icon', 'size' => 'h-10 w-10'])

@if (! empty($icon))
    @if (str_starts_with($icon, 'bi-'))
        <i {{ $attributes->merge(['class' => 'bi '.$icon]) }}></i>
    @elseif (str_contains($icon, '/icons/fa-'))
        <span {{ $attributes->merge(['class' => 'lp-mask inline-block bg-current '.$size]) }} style="--icon: url('{{ media_url($icon) }}')"></span>
    @else
        <img src="{{ media_url($icon) }}" alt="" class="{{ $size }} inline-block object-contain">
    @endif
@endif
