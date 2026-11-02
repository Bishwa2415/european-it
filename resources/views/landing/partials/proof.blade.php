{{-- "200+ students enrolled" with student photos + 5 stars "500+ google reviews" (hero) --}}
<div @class(['mt-5 flex flex-wrap items-center gap-x-5 gap-y-2', 'justify-center' => $center])>
    @if (! empty($s['students_text']))
        <span class="flex items-center gap-2.5">
            <span class="flex">
                @foreach ($avatars as $avatar)
                    <img src="{{ media_url($avatar) }}" alt="" class="-ml-[5px] h-6 w-6 rounded-full border border-white object-cover first:ml-0">
                @endforeach
            </span>
            <span @class(['text-[#020202]', 'text-xs' => ! $offline, 'text-base' => $offline])>{{ $s['students_text'] }}</span>
        </span>
    @endif
    @if (! empty($s['reviews_text']))
        <a href="{{ $s['reviews_url'] ?: '#' }}" target="_blank" rel="noopener" class="flex items-center gap-2 font-sans text-xs text-[#020202] lg:text-[15px]">
            <span class="flex text-[#F7B500]">@for ($i = 0; $i < 5; $i++) <i class="bi bi-star-fill"></i> @endfor</span>
            {{ $s['reviews_text'] }}
        </a>
    @endif
</div>
