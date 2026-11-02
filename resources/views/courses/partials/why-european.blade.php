{{-- "Why European IT? !" – same 6 cards on every course page (Dashboard → Course page) --}}
@if ($page['why_european'])
    <section class="rounded-xl bg-white pt-[50px]">
        <x-course-title :title="$page['titles']['why_european']" />

        <div class="mt-5 grid gap-5 px-2.5 pt-[15px] pb-10 md:grid-cols-2 md:gap-7 md:px-[27px] md:pb-16">
            @foreach ($page['why_european'] as $card)
                <div class="course-icon-card">
                    @if (! empty($card['icon']))
                        <img src="{{ media_url($card['icon']) }}" alt="" class="mx-auto mb-[15px] h-[70px] w-[70px]">
                    @endif
                    <h3 class="font-menu text-2xl leading-[30px] font-bold text-[#042E42]">{{ $card['title'] }}</h3>
                    <p class="mt-10 text-base leading-6 text-[#04334D]">{{ $card['text'] }}</p>
                </div>
            @endforeach
        </div>
    </section>
@endif
