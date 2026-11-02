{{-- Landing page icon: a Bootstrap icon name ("bi-laptop") or a picture path ("media/live/icons/x.svg").
     Use: <x-landing.icon :icon="$item['icon']" class="text-[39px]" img-class="h-[39px] w-[39px]" /> --}}
@props(['icon', 'imgClass' => 'h-10 w-10'])

@if (! empty($icon))
    @if (str_starts_with($icon, 'bi-'))
        <i {{ $attributes->merge(['class' => 'bi '.$icon]) }}></i>
    @else
        <img src="{{ media_url($icon) }}" alt="" class="{{ $imgClass }} object-contain">
    @endif
@endif
