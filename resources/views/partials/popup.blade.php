@if ($activePopup)
    <div class="fixed inset-0 z-[80] flex items-center justify-center bg-black/60 p-5" data-popup-overlay>
        <section role="dialog" aria-modal="true" aria-label="{{ $activePopup->title }}" class="relative w-full max-w-xl rounded-2xl bg-white p-7 shadow-2xl">
            <button type="button" data-popup-close aria-label="Close popup" class="absolute right-4 top-3 text-2xl text-gray-500 hover:text-black">&times;</button>
            <h2 class="mb-4 pr-8 text-2xl font-bold">{{ $activePopup->title }}</h2>
            @if ($activePopup->body)<div class="prose max-w-none">{!! $activePopup->body !!}</div>@endif
            @if ($activePopup->data['button_label'] ?? false)
                <a href="{{ $activePopup->data['button_url'] ?? '#' }}" class="mt-5 inline-block rounded-lg bg-primary px-5 py-3 font-semibold text-white">{{ $activePopup->data['button_label'] }}</a>
            @endif
        </section>
    </div>
    <script>document.querySelectorAll('[data-popup-close], [data-popup-overlay]').forEach((element) => element.addEventListener('click', (event) => { if (event.target === element || event.target.closest('[data-popup-close]')) element.closest('[data-popup-overlay]')?.remove(); }));</script>
@endif
