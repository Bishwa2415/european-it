{{-- "We Accept": payment methods picture in a light frame --}}
<section class="bg-white px-5 pb-20">
    <div class="mx-auto max-w-[1061px] border border-[#E4E4E4]/55 px-5 pt-10 text-center">
        <h2 class="font-roboto text-3xl leading-[35px] font-semibold text-black lg:text-[35px]">{{ $s['title'] }}</h2>
        @if (! empty($s['image']))
            <img src="{{ media_url($s['image']) }}" alt="{{ $s['title'] }}" loading="lazy" class="mx-auto mt-5 w-full max-w-[900px]">
        @endif
    </div>
</section>
