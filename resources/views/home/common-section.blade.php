<section class="px-5 py-14" @if ($section->slug) id="{{ $section->slug }}" @endif>
    <div class="mx-auto max-w-[1330px]">
        @if ($section->data['image'] ?? false)
            <img src="{{ media_url($section->data['image']) }}" alt="" class="mb-8 max-h-[480px] w-full rounded-2xl object-cover">
        @endif
        <h2 class="mb-5 text-3xl font-bold">{{ $section->title }}</h2>
        @if ($section->body)<div class="prose max-w-none">{!! $section->body !!}</div>@endif
        @if ($section->data['button_label'] ?? false)
            <a href="{{ url($section->data['button_url'] ?? '#') }}" class="hero-btn mt-6 inline-block">{{ $section->data['button_label'] }}</a>
        @endif
    </div>
</section>
