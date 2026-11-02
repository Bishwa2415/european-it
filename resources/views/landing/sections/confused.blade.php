{{-- "Still confused?": WhatsApp button + number --}}
<section @class(['px-5 py-[60px] text-center', 'bg-[#0072AB]' => ! $offline, 'bg-[#F2FBFF]' => $offline])>
    <x-landing.title :offline="$offline" :light="! $offline">{{ $s['title'] }}</x-landing.title>
    <p @class(['mt-3 text-lg leading-7 font-semibold lg:text-xl', 'text-white' => ! $offline, 'text-[#04334D]' => $offline])>{{ $s['text'] }}</p>

    @if (! empty($s['phone']))
        @php $number = preg_replace('/\D/', '', $s['phone']); @endphp
        <div class="mt-12 flex justify-center gap-2.5">
            <a href="https://wa.me/88{{ ltrim($number, '88') }}" target="_blank" rel="noopener" aria-label="WhatsApp"
               class="flex h-[60px] w-[60px] items-center justify-center rounded bg-white text-[40px] text-[#25D366] shadow">
                <i class="bi bi-whatsapp"></i>
            </a>
            <a href="tel:{{ $number }}" class="rounded bg-white px-3 py-[15px] font-heading text-xl leading-7 font-semibold text-[#268E2E] shadow">{{ $s['phone'] }}</a>
        </div>
    @endif
</section>
