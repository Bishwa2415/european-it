{{-- One module card of the "What you will learn" timeline. $align = left / right (text alignment) --}}
<div @class(['rounded-lg border border-[#EDFAFF] bg-white p-5 shadow-[0_0_2px_rgba(0,0,0,0.25)]', 'text-right' => $align === 'right'])>
    <h3 class="text-xl leading-7 font-semibold text-[#020202] lg:text-2xl">{{ $item['title'] }}</h3>
    <p class="mt-2.5 text-base leading-6 text-[#04334D]">{{ $item['text'] }}</p>
</div>
