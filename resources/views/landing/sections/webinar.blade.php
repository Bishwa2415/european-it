{{-- Free webinar / seminar: info on the left, booking form on the right. The form saves a Lead (LeadController@store).
     id="webinar": after sending, the page comes back here and shows the "thank you" message. --}}
<section id="webinar" @class(['scroll-mt-24 px-5 py-[60px]', 'bg-[#00ADEE]' => ! $offline, 'bg-[#B6EAFF]' => $offline])>
    <div @class(['mx-auto grid max-w-[1192px] items-center gap-6 rounded-2xl p-5 lg:grid-cols-[1fr_434px]', 'bg-[#04334D]' => ! $offline, 'bg-[#0090D4]' => $offline])>
        <div>
            @if (! empty($s['badge']))
                <span class="inline-flex items-center gap-2 rounded-lg bg-white py-3 pr-3 pl-5 font-heading text-lg leading-7 font-bold text-[#DD0000] lg:text-xl">
                    <span class="h-3 w-3 animate-pulse rounded-full bg-[#FF0000]"></span> {{ $s['badge'] }}
                </span>
            @endif
            <h2 class="mt-5 text-xl leading-8 font-semibold text-white lg:text-2xl">{{ $s['title'] }}</h2>
            @if (! empty($s['note']))
                <p @class(['mt-5 inline-block rounded-lg px-3 py-2 text-base leading-5 text-white lg:text-lg', 'bg-[#00ADEE]' => ! $offline, 'bg-[#04334D]' => $offline])>{{ $s['note'] }}</p>
            @endif
        </div>

        <form method="POST" action="{{ route('landing.lead', $landing) }}" @class(['rounded-xl px-6 py-4', 'bg-[#0090D4]' => ! $offline, 'bg-[#04334D]' => $offline])>
            @csrf
            <h3 class="text-center text-xl leading-[27px] font-semibold text-white lg:text-[22px]">{{ $s['form_title'] }}</h3>

            @if (session('lead_saved'))
                <p class="mt-4 rounded-xl bg-white px-4 py-3 text-center font-semibold text-[#268E2E]">
                    <i class="bi bi-check-circle-fill"></i> ধন্যবাদ! আমরা শীঘ্রই আপনার সাথে যোগাযোগ করবো।
                </p>
            @else
                <div class="mt-4 grid grid-cols-2 gap-2.5">
                    <input name="name" value="{{ old('name') }}" required maxlength="100" placeholder="Name" autocomplete="name"
                           class="h-11 rounded-xl bg-white px-[15px] text-sm text-black outline-none">
                    <input name="phone" value="{{ old('phone') }}" required maxlength="20" placeholder="Phone Number" autocomplete="tel" inputmode="tel"
                           class="h-11 rounded-xl bg-white px-[15px] text-sm text-black outline-none">
                </div>
                {{-- Bots fill every field; people never see this one, so a filled "website" means spam --}}
                <input name="website" tabindex="-1" autocomplete="off" class="hidden" aria-hidden="true">
                @if ($errors->any())
                    <p class="mt-2 text-sm text-[#FFDA0A]">{{ $errors->first() }}</p>
                @endif
                <button class="mt-2.5 h-11 w-full rounded-xl bg-[#FFDA0A] font-heading text-xl leading-7 font-semibold text-black transition hover:bg-white">
                    {{ $s['button_label'] ?: 'Book Now' }}
                </button>
            @endif
        </form>
    </div>
</section>
