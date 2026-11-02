{{-- "Project You Will Do": picture, title, text, small tool logos.
     "Tools used" in the dashboard = names from the Tools tab ("HTML5, CSS3"), or image paths, separated by commas.
     A name is turned into that tool's logo with $toolLogos (lower-case name → logo path). --}}
@php
    $toolLogos = collect($course->detail('tools', []))->filter(fn ($tool) => ! empty($tool['icon']))
        ->mapWithKeys(fn ($tool) => [mb_strtolower(trim($tool['name'])) => $tool['icon']]);
@endphp
@if ($projects = $course->detail('projects'))
    <section class="!mt-20">
        <x-course-title :title="$page['titles']['projects']" />

        <div class="mt-[19px] grid gap-6 md:grid-cols-2 md:gap-[33px]">
            @foreach ($projects as $project)
                <div class="rounded-xl bg-linear-120 from-[#E3F9FF] from-73% to-[#FAEDFF] pb-[30px] shadow-[0_0_4px_rgba(0,0,0,0.25)]">
                    @if (! empty($project['image']))
                        <img src="{{ media_url($project['image']) }}" alt="{{ $project['title'] }}" class="mb-[47px] aspect-[300/165] w-full rounded-lg object-cover">
                    @endif
                    <h3 class="mb-[15px] px-5 font-menu text-[19.58px] leading-[27.4px] font-semibold text-black">{{ $project['title'] }}</h3>
                    <p class="px-5 font-heading text-[15.08px] leading-[21.54px] text-[#3D3D3D]">{{ $project['text'] }}</p>

                    @if (! empty($project['icons']))
                        <div class="mt-[22px] flex flex-wrap gap-2.5 px-5">
                            @foreach (array_filter(array_map('trim', explode(',', $project['icons']))) as $icon)
                                @php $logo = $toolLogos[mb_strtolower($icon)] ?? (preg_match('~[/.]~', $icon) ? $icon : null); @endphp
                                @if ($logo)
                                    <img src="{{ media_url($logo) }}" alt="{{ $icon }}" title="{{ $icon }}" class="h-10 w-10 object-contain">
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </section>
@endif
