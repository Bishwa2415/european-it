{{-- One course link inside a mega menu: arrow + label + optional badge --}}
@props(['link'])

@php
    $badgeColors = [
        'advance' => 'bg-[#EB4D4B]',
        'popular' => 'bg-[#2ECC71]',
        'new'     => 'bg-primary',
    ];
@endphp

<a href="{{ url($link['url']) }}"
   class="flex items-baseline border border-[#EDEDED] p-2.5 font-menu text-sm leading-5 font-medium text-[#707070]
          hover:border-primary hover:text-primary transition">
    <i class="bi bi-chevron-right text-[11px] text-primary [-webkit-text-stroke:1px]"></i>
    <span class="pl-[9px]">
        {{ $link['label'] }}
        @if (! empty($link['badge']))
            <span class="ml-[5px] rounded-[3px] px-1 py-0.5 text-[9px] font-semibold uppercase text-white {{ $badgeColors[$link['badge']] }}">{{ $link['badge'] }}</span>
        @endif
    </span>
</a>
