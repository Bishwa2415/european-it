{{-- FAQ: big number + question; click to open (<details>, no JavaScript). First one open.
     Online: each question is a light blue → pink card. Offline: no cards, a divider under the title and
     bigger, bolder +/− signs, like live. --}}
<section @class(['px-5', 'bg-[#F1FBFF] py-[60px]' => ! $offline, 'bg-[#F2FBFF] py-[100px]' => $offline])>
    <div @class(['mx-auto', 'max-w-[794px]' => ! $offline, 'max-w-[1310px]' => $offline])>
        <x-landing.title :offline="$offline">{{ $s['title'] }}</x-landing.title>
        @if ($offline)
            <hr class="mt-2.5 border-[#00ADEE]">
        @endif

        <div @class(['mx-auto max-w-[794px]', 'mt-6 space-y-1.5' => ! $offline, 'mt-10 space-y-5' => $offline])>
            @foreach ($s['items'] ?? [] as $i => $item)
                <details @class(['group rounded-lg px-6 py-4 lg:px-[42px]', 'bg-linear-to-r from-[#E1F7FF] to-[#FCEEFF]' => ! $offline]) @if ($i === 0) open @endif>
                    <summary class="flex cursor-pointer list-none items-center gap-6 lg:gap-10 [&::-webkit-details-marker]:hidden">
                        <span class="w-10 shrink-0 text-center font-heading text-5xl leading-[60px] font-bold text-[#6EC1E4] lg:text-6xl">{{ \Illuminate\Support\Str::of($i + 1)->replaceMatches('/\d/', fn ($d) => mb_substr('০১২৩৪৫৬৭৮৯', (int) $d[0], 1)) }}</span>
                        <span class="flex-1 text-lg leading-7 font-semibold text-[#1F2124] lg:text-xl">{{ $item['question'] }}</span>
                        <i @class(['bi bi-plus-lg font-bold text-[#4D4D4D] group-open:hidden', 'text-2xl' => ! $offline, 'text-[28px] [-webkit-text-stroke:1.5px]' => $offline])></i>
                        <i @class(['bi bi-dash-lg hidden font-bold text-[#1F2124] group-open:inline', 'text-2xl' => ! $offline, 'text-[28px] [-webkit-text-stroke:1.5px]' => $offline])></i>
                    </summary>
                    <p class="-mt-4 pl-16 text-base leading-6 text-[#595959] lg:pl-20">{{ $item['answer'] }}</p>
                </details>
            @endforeach
        </div>
    </div>
</section>
