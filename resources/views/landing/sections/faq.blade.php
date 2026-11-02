{{-- FAQ: big light-blue number + question, click to open the answer (<details>, no JavaScript). First one open. --}}
<section class="bg-[#F1FBFF] px-5 py-[60px]">
    <div class="mx-auto max-w-[722px]">
        <x-landing.title :offline="$offline">{{ $s['title'] }}</x-landing.title>

        <div class="mt-9 space-y-6">
            @foreach ($s['items'] ?? [] as $i => $item)
                <details class="group" @if ($i === 0) open @endif>
                    <summary class="flex cursor-pointer list-none items-start gap-6 lg:gap-10 [&::-webkit-details-marker]:hidden">
                        <span class="w-9 shrink-0 font-heading text-5xl leading-[60px] font-bold text-[#6EC1E4] lg:text-6xl">{{ \Illuminate\Support\Str::of($i + 1)->replaceMatches('/\d/', fn ($d) => mb_substr('০১২৩৪৫৬৭৮৯', (int) $d[0], 1)) }}</span>
                        <span class="flex-1 pt-3 text-lg leading-7 font-semibold text-[#1F2124] lg:text-xl">{{ $item['question'] }}</span>
                        <i class="bi bi-plus-lg pt-3 text-xl group-open:hidden"></i>
                        <i class="bi bi-dash-lg hidden pt-3 text-xl group-open:inline"></i>
                    </summary>
                    <p class="mt-2 pl-[60px] text-base leading-6 text-[#595959] lg:pl-[76px]">{{ $item['answer'] }}</p>
                </details>
            @endforeach
        </div>
    </div>
</section>
