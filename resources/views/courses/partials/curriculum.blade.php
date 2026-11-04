{{-- Curriculum: 2 columns of classes. Click a class to open its topics.
     <details>/<summary> = the browser's own open/close box, so no JavaScript is needed.
     Topics text: a line starting with "- " is a bullet, any other line is a small heading. --}}
@if ($curriculumCols)
    <section>
        <x-course-title :title="$page['titles']['curriculum']" />

        <div class="mt-[21px] grid gap-x-5 md:grid-cols-2">
            @foreach ($curriculumCols as $column)
                <div>
                    @foreach ($column as $class)
                        <details class="group mb-2.5 overflow-hidden rounded-md border border-[#A0E9FF]/70 bg-white">
                            <summary class="relative cursor-pointer list-none py-3 pr-[30px] pl-4 font-heading text-sm leading-[21px] font-bold text-black select-none
                                            group-open:mb-2.5 group-open:border-b group-open:text-[#05ACFC] [&::-webkit-details-marker]:hidden">
                                {{ $class['title'] }}
                                <span class="absolute top-2 right-4 text-xl after:content-['+'] group-open:after:content-['–']"></span>
                            </summary>

                            @if (! empty($class['details']))
                                <div class="px-5 pb-2.5">
                                    {{-- /u: work on letters, not bytes (without it Bangla text gets cut in half) --}}
                                    @foreach (preg_split('/\R/u', trim($class['details'])) as $line)
                                        @if (str_starts_with($line, '- '))
                                            <p class="mb-2 flex gap-1.5 pl-2.5 text-sm leading-5 text-black">
                                                <i class="bi bi-arrow-right-short text-base leading-5 text-primary"></i>{{ substr($line, 2) }}
                                            </p>
                                        @elseif (trim($line) !== '')
                                            <h4 class="mt-[15px] mb-2 text-sm font-semibold text-[#1E40AF]">{{ $line }}</h4>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </details>
                    @endforeach
                </div>
            @endforeach
        </div>
    </section>
@endif
