{{-- Site popup / modal. Content: Dashboard → Popups / modals (first active one).
     Extra settings in its JSON: button_label, button_url, delay (seconds, default 2), image.
     Hidden at first; resources/js/popup.js shows it once per visitor, and again after the admin edits it. --}}
@if ($activePopup)
    @php $popupData = $activePopup->data ?? []; @endphp
    <div class="fixed inset-0 z-[80] hidden items-center justify-center bg-black/60 p-5"
         data-popup
         data-popup-key="popup-{{ $activePopup->id }}-{{ $activePopup->updated_at?->timestamp }}"
         data-popup-delay="{{ (int) ($popupData['delay'] ?? 2) }}">
        <section role="dialog" aria-modal="true" aria-label="{{ $activePopup->title }}"
                 class="relative max-h-[90vh] w-full max-w-xl overflow-y-auto rounded-2xl bg-white p-7 shadow-2xl">
            <button type="button" data-popup-close aria-label="Close popup" class="absolute right-4 top-3 text-2xl text-gray-500 hover:text-black">&times;</button>

            @if (! empty($popupData['image']))
                <img src="{{ media_url($popupData['image']) }}" alt="" class="mb-5 w-full rounded-xl">
            @endif

            <h2 class="mb-4 pr-8 font-heading text-2xl font-bold text-heading">{{ $activePopup->title }}</h2>
            @if ($activePopup->body)
                <div class="prose max-w-none">{!! $activePopup->body !!}</div>
            @endif
            @if (! empty($popupData['button_label']))
                <a href="{{ url($popupData['button_url'] ?? '#') }}" data-popup-close class="hero-btn mt-5">{{ $popupData['button_label'] }}</a>
            @endif
        </section>
    </div>
@endif
