{{-- "We Accept": payment methods picture in a light frame --}}
<section @class(['px-5 pb-20', 'bg-white' => ! $offline, 'bg-[#F2FBFF] pt-10' => $offline])>
    <div @class(['mx-auto max-w-[1061px] px-5 pt-10 text-center', 'border border-[#E4E4E4]/55' => ! $offline])>
        <h2 @class(['text-3xl leading-[35px] font-semibold text-black lg:text-[35px]', 'font-roboto' => ! $offline, 'font-heading' => $offline])>{{ $s['title'] }}</h2>
        @if (! empty($s['image']))
            <img src="{{ media_url($s['image']) }}" alt="{{ $s['title'] }}" loading="lazy" class="mx-auto mt-5 w-full max-w-[900px]">
        @endif
    </div>
</section>
