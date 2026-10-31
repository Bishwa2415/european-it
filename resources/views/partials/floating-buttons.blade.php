{{-- Buttons that stay on screen while scrolling (position: fixed) --}}

{{-- Desktop: vertical "Demo Class" tab on the left, "Get Discount" on the right --}}
<a href="#" class="hidden lg:block fixed left-0 top-1/2 -translate-y-1/2 z-40
                   rounded-r-[20px] bg-primary-hover px-2.5 py-[30px] font-heading font-bold uppercase tracking-[1.3px] text-white
                   [writing-mode:vertical-rl] rotate-180 hover:bg-primary transition">
    Demo Class
</a>
<a href="#" class="hidden lg:block fixed right-0 top-1/2 -translate-y-1/2 z-40
                   rounded-l-[20px] bg-primary-hover px-2.5 py-[30px] font-heading font-bold uppercase tracking-[1.3px] text-white
                   [writing-mode:vertical-rl] hover:bg-primary transition">
    Get Discount
</a>

{{-- Mobile: Demo Class + Get Discount as a bar at the bottom --}}
<div class="lg:hidden fixed inset-x-0 bottom-0 z-40 grid grid-cols-2 gap-2 font-bold uppercase text-white">
    <a href="#" class="rounded-tr-[40px] bg-[#00ADEE] pt-5 pb-[13px] text-center">Demo Class</a>
    <a href="#" class="rounded-tl-[40px] bg-[#00ADEE] pt-5 pb-[13px] text-center">Get Discount</a>
</div>

{{-- Google reviews badge (bottom-left) --}}
<a href="{{ config('site.reviews_url') }}" target="_blank" rel="noopener"
   class="hidden lg:flex fixed left-4 bottom-5 z-40 items-center gap-3 rounded-[10px] bg-white px-4 py-2.5 shadow-[0_0_5px_1px_#00AEEF]">
    <span class="leading-tight">
        <span class="block text-lg tracking-wider text-[#FBBC05]">★★★★★</span>
        <span class="block text-sm text-heading">Read our reviews</span>
    </span>
    <i class="bi bi-google text-3xl text-[#4285F4]"></i>
</a>

{{-- WhatsApp chat (bottom-right) --}}
<a href="https://api.whatsapp.com/send?phone={{ config('site.whatsapp') }}&text={{ urlencode('Need Help? Chat with us') }}"
   target="_blank" rel="noopener" aria-label="Chat on WhatsApp"
   class="fixed right-5 bottom-20 lg:bottom-5 z-40 flex h-[54px] w-[54px] items-center justify-center rounded-full bg-[#25D366] text-3xl text-white shadow-lg hover:scale-110 transition">
    <i class="bi bi-whatsapp"></i>
</a>
