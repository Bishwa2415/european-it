{{-- Blue top bar. Phones/email: Dashboard → Site settings (header_phones, emails) --}}
@php $phones = $siteData['header_phones'] ?? array_slice($siteData['phones'], 1, 2); @endphp

<div class="bg-linear-to-r from-[#00ADEE] to-[#0D9FD5] text-white text-sm font-medium">
    <div class="max-w-[1386px] mx-auto px-5 lg:px-7 py-2.5 md:py-0 md:h-[47px] flex flex-col md:flex-row items-center justify-center md:justify-between gap-1">
        <div class="flex items-center gap-3">
            @foreach ($phones as $phone)
                @if (! $loop->first)<span class="opacity-60">|</span>@endif
                <a href="tel:{{ str_replace(' ', '', $phone) }}" class="hover:underline">
                    @if ($loop->first)<i class="bi bi-telephone-fill"></i>@endif {{ $phone }}
                </a>
            @endforeach
        </div>
        <a href="mailto:{{ $siteData['emails'][0] }}" class="hover:underline flex items-center gap-1.5">
            <i class="bi bi-envelope-fill text-lg"></i> {{ $siteData['emails'][0] }}
        </a>
    </div>
</div>
