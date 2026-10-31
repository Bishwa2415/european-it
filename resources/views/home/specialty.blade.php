{{-- Home page: "Our Specialty" + "15+ Years of Trusted Service" (content_blocks: home.specialty / home.trusted) --}}
<section class="py-[70px]">
    @if ($specialty)
        <x-split-block :block="$specialty" image-side="left" bg="bg-[rgba(5,143,195,0.07)]" icon="bi-mortarboard-fill" />
    @endif
    @if ($trusted)
        <x-split-block :block="$trusted" image-side="right" bg="bg-[rgba(1,172,235,0.17)]" icon="bi-award" />
    @endif
</section>
