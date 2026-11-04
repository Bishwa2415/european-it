{{-- "Still confused?": title, text, WhatsApp button + number.
     Online: full-width blue band. Offline: a rounded box with the blue wave picture (content.confused.background)
     and white text, like live. --}}
@php $number = preg_replace('/\D/', '', $s['phone'] ?? ''); @endphp

<section @class(['text-center', 'bg-[#0072AB] px-5 py-[60px]' => ! $offline, 'bg-[#F2FBFF] px-2.5 pt-[70px] lg:px-5' => $offline])>
    <div @class(['rounded-lg bg-[#0090D4] bg-cover bg-center px-5 py-[30px]' => $offline])
         @if ($offline && ! empty($s['background'])) style="background-image: url('{{ media_url($s['background']) }}')" @endif>
        <x-landing.title :offline="$offline" :light="true">{{ $s['title'] }}</x-landing.title>
        <p @class(['text-white', 'mt-3 text-lg leading-7 font-semibold lg:text-xl' => ! $offline, 'lp-noto mt-2 text-lg leading-7' => $offline])>{{ $s['text'] }}</p>

        @if ($number !== '')
            <div @class(['flex justify-center gap-2.5', 'mt-12' => ! $offline, 'mt-6' => $offline])>
                <a href="https://wa.me/88{{ ltrim($number, '88') }}" target="_blank" rel="noopener" aria-label="WhatsApp"
                   class="flex h-[60px] w-[60px] items-center justify-center rounded bg-white text-[40px] text-[#25D366] shadow">
                    <i class="bi bi-whatsapp"></i>
                </a>
                <a href="tel:{{ $number }}" class="rounded bg-white px-3 py-[15px] font-heading text-xl leading-7 font-semibold text-[#268E2E] shadow">{{ $s['phone'] }}</a>
            </div>
        @endif
    </div>
</section>
