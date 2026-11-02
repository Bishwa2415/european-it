{{-- Mentor card(s): photo, name, designation, badge pills, portfolio button.
     badges are typed in the dashboard as one line: "NSDA Certified, 80+ Freelance Project" --}}
@if ($mentors = $course->detail('mentors'))
    <section class="space-y-6">
        @foreach ($mentors as $mentor)
            <div class="flex flex-wrap items-center gap-3.5 rounded-xl border border-[#00ADEE] bg-white px-6 py-8 md:flex-nowrap md:gap-[30px] md:px-16 md:py-10">
                @if (! empty($mentor['photo']))
                    <img src="{{ media_url($mentor['photo']) }}" alt="{{ $mentor['name'] }}" class="mx-auto w-[222px] shrink-0 md:mx-0 md:mb-2.5">
                @endif

                <div>
                    <h3 class="font-heading text-[28px] leading-10 font-bold text-[#04334D]">{{ $mentor['name'] }}</h3>
                    <p class="mb-6 mt-3 font-heading text-lg leading-5 font-bold text-[#00ADEE]">{{ $mentor['designation'] }}</p>

                    @if (! empty($mentor['badges']))
                        <div class="mb-10 flex flex-wrap gap-[15px]">
                            @foreach (array_filter(array_map('trim', explode(',', $mentor['badges']))) as $badge)
                                <span class="rounded-[51px] border border-[#00ADEE] bg-[#00ADEE] px-3 py-1 font-heading text-xs leading-4 font-semibold text-white">{{ $badge }}</span>
                            @endforeach
                        </div>
                    @endif

                    @if (! empty($mentor['url']))
                        <a href="{{ url($mentor['url']) }}" class="inline-block rounded-[33px] border border-[#00ADEE] bg-[#00ADEE] px-[18px] py-[9px] font-heading text-sm font-bold text-white transition hover:bg-transparent hover:text-[#00ADEE]">
                            View Portfolio
                        </a>
                    @endif
                </div>
            </div>
        @endforeach
    </section>
@endif
