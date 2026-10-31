{{-- Shared by create.blade.php and edit.blade.php. Needs: $course, $categories --}}
@php
    $plans = $course->plans->keyBy('type');   // ['online' => CoursePlan, 'offline' => CoursePlan]
@endphp

{{-- Error summary at the top when the save failed --}}
@if ($errors->any())
    <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
        <i class="bi bi-exclamation-triangle"></i> Please fix the {{ $errors->count() }} highlighted field(s) below.
    </div>
@endif

<div class="grid gap-6 xl:grid-cols-[1fr_340px]">

    {{-- ================= Left column ================= --}}
    <div class="min-w-0 space-y-6">

        <x-admin.card title="Basic information">
            <x-admin.input name="title" label="Course title" :value="$course->title" required data-slug-source />
            <x-admin.input name="short_title" label="Short title" :value="$course->short_title"
                           hint="Optional short name for small lists like the footer, e.g. “Web Development”." />
            <x-admin.input name="slug" label="URL slug" :value="$course->slug" prefix="/course/" data-slug-target
                           hint="Leave empty to create it from the title. Changing it changes the page URL." />

            <div class="grid gap-5 sm:grid-cols-2">
                <x-admin.select name="course_category_id" label="Category" required placeholder="Choose a category"
                                :options="$categories->pluck('name', 'id')" :value="$course->course_category_id" />
                <x-admin.select name="badge" label="Badge (mega menu)" placeholder="No badge"
                                :options="['advance' => 'ADVANCE', 'popular' => 'POPULAR', 'new' => 'NEW']" :value="$course->badge" />
            </div>

            <x-admin.textarea name="overview" label="Course overview" :value="$course->overview" rows="5"
                              hint="Short description shown on the course page." />
        </x-admin.card>

        {{-- Online / Offline plans --}}
        <x-admin.card title="Pricing & schedule" description="Turn on the plans you sell. The card price range is made from these.">
            @foreach (['online' => 'Online', 'offline' => 'Offline'] as $type => $label)
                @php $plan = $plans->get($type); @endphp

                <div class="rounded-lg border border-gray-200 p-4" data-plan>
                    <x-admin.toggle name="plans[{{ $type }}][enabled]" label="{{ $label }} course"
                                    :checked="(bool) $plan" data-plan-toggle />

                    <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3" data-plan-fields>
                        <x-admin.input type="number" step="0.01" min="0" name="plans[{{ $type }}][regular_price]" label="Regular price"
                                       prefix="৳" :value="$plan?->regular_price" />
                        <x-admin.input type="number" step="0.01" min="0" name="plans[{{ $type }}][sale_price]" label="Sale price"
                                       prefix="৳" :value="$plan?->sale_price" hint="Optional discount price" />
                        <x-admin.input name="plans[{{ $type }}][duration]" label="Duration" placeholder="e.g. 4 Months" :value="$plan?->duration" />
                        <x-admin.input type="number" min="1" name="plans[{{ $type }}][total_classes]" label="Total classes" :value="$plan?->total_classes" />
                        <x-admin.input type="number" min="1" max="7" name="plans[{{ $type }}][classes_per_week]" label="Classes per week" :value="$plan?->classes_per_week" />
                    </div>
                </div>
            @endforeach
        </x-admin.card>

        <x-admin.card title="SEO" description="What Google shows. Leave empty to use the course title.">
            <x-admin.input name="meta_title" label="Meta title" :value="$course->meta_title" maxlength="255" />
            <x-admin.textarea name="meta_description" label="Meta description" :value="$course->meta_description" rows="3" maxlength="255" />
        </x-admin.card>
    </div>

    {{-- ================= Right column ================= --}}
    <div class="space-y-6">

        <x-admin.card title="Publish">
            <x-admin.toggle name="is_active" label="Active" hint="Hidden courses are not shown on the website." :checked="$course->is_active" />
            <x-admin.toggle name="is_popular" label="Popular course" hint="Show in home page “Popular Courses”." :checked="$course->is_popular" />
            <x-admin.toggle name="is_fourth_ir" label="4th Industrial course" hint="Show in home page “4th Industrial Courses”." :checked="$course->is_fourth_ir" />
            <x-admin.toggle name="in_footer" label="Show in footer" hint="Footer “Popular Courses” list (uses the short title)." :checked="$course->in_footer" />
            <div class="grid grid-cols-2 gap-3">
                <x-admin.input type="number" min="0" name="sort_order" label="Menu order" :value="$course->sort_order ?? 0" hint="Inside its mega menu group." />
                <x-admin.input type="number" min="0" name="home_order" label="Home order" :value="$course->home_order ?? 0" hint="On home page sections." />
            </div>

            <button class="w-full rounded-lg bg-primary py-2.5 text-sm font-semibold text-white hover:bg-primary-hover transition">
                <i class="bi bi-check2-circle"></i> {{ $course->exists ? 'Save changes' : 'Add course' }}
            </button>
            @if ($course->exists)
                <a href="{{ $course->url() }}" target="_blank" class="block text-center text-sm text-primary hover:underline">
                    View on website <i class="bi bi-box-arrow-up-right"></i>
                </a>
            @endif
        </x-admin.card>

        <x-admin.card title="Thumbnail">
            <img src="{{ $course->thumbnailUrl() }}" alt="" data-image-preview="thumbnail"
                 class="aspect-[2/1] w-full rounded-lg border border-gray-200 object-cover">
            <div>
                <input type="file" name="thumbnail" id="thumbnail" accept="image/png,image/jpeg,image/webp"
                       class="block w-full text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-primary/10 file:px-3 file:py-2 file:text-primary">
                <p class="mt-1 text-xs text-gray-500">JPG, PNG or WEBP, max 2 MB. Best size 800×400.</p>
                @error('thumbnail') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </x-admin.card>

        <x-admin.card title="Card numbers">
            <x-admin.input type="number" min="0" name="review_count" label="Reviews" :value="$course->review_count ?? 0" />
            <x-admin.input type="number" min="0" name="student_count" label="Students" :value="$course->student_count ?? 0" />
        </x-admin.card>

        <x-admin.card title="Advanced">
            <x-admin.input name="custom_url" label="Custom link" :value="$course->custom_url"
                           hint="Only if this course opens another page, e.g. https://kidsict.com" />
        </x-admin.card>
    </div>
</div>
