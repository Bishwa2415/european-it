<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * Validation for the course add/edit form in the dashboard.
 * Used by Admin\CourseController@store and @update.
 * If any rule fails, Laravel goes back to the form with the errors automatically.
 */
class CourseRequest extends FormRequest
{
    /**
     * Only admins reach this request (routes are behind the 'admin' middleware).
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * Clean the input BEFORE the rules run.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            // Empty slug → make one from the title: "Laravel Framework" → "laravel-framework"
            'slug' => Str::slug($this->input('slug') ?: $this->input('title')),
            // Unchecked checkboxes send nothing, so turn them into real true/false
            'is_popular'   => $this->boolean('is_popular'),
            'is_fourth_ir' => $this->boolean('is_fourth_ir'),
            'in_footer'    => $this->boolean('in_footer'),
            'is_active'    => $this->boolean('is_active'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // On edit, the course's own slug is allowed (ignore it in the "unique" check)
        $courseId = $this->route('course')?->id;

        return [
            'course_category_id' => ['required', 'exists:course_categories,id'],
            'title'              => ['required', 'string', 'max:255'],
            'short_title'        => ['nullable', 'string', 'max:100'],
            'slug'             => ['required', 'alpha_dash', 'max:255', Rule::unique('courses', 'slug')->ignore($courseId)],
            'badge'              => ['nullable', Rule::in(['advance', 'popular', 'new'])],
            'thumbnail'          => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'], // max 2 MB
            'overview'           => ['nullable', 'string', 'max:5000'],
            'review_count'       => ['nullable', 'integer', 'min:0'],
            'student_count'      => ['nullable', 'integer', 'min:0'],
            'sort_order'         => ['nullable', 'integer', 'min:0'],
            'home_order'         => ['nullable', 'integer', 'min:0'],
            'custom_url'         => ['nullable', 'string', 'max:255'],
            'meta_title'         => ['nullable', 'string', 'max:255'],
            'meta_description'   => ['nullable', 'string', 'max:255'],
            'is_popular'         => ['boolean'],
            'is_fourth_ir'       => ['boolean'],
            'in_footer'          => ['boolean'],
            'is_active'          => ['boolean'],

            // Online / Offline plans: plans[online][regular_price], plans[offline][...]
            'plans'                      => ['array'],
            'plans.*.enabled'            => ['nullable', 'boolean'],
            'plans.*.duration'           => ['nullable', 'string', 'max:50'],
            'plans.*.total_classes'      => ['nullable', 'integer', 'min:1'],
            'plans.*.classes_per_week'   => ['nullable', 'integer', 'min:1', 'max:7'],
            'plans.*.regular_price'      => ['nullable', 'required_if_accepted:plans.*.enabled', 'numeric', 'min:0'],
            'plans.*.sale_price'         => ['nullable', 'numeric', 'min:0', 'lt:plans.*.regular_price'],
        ];
    }

    /**
     * Friendly names in error messages ("The regular price field..." instead of "plans.online.regular_price")
     */
    public function attributes(): array
    {
        return [
            'course_category_id'            => 'category',
            'plans.online.regular_price'    => 'online regular price',
            'plans.online.sale_price'       => 'online sale price',
            'plans.offline.regular_price'   => 'offline regular price',
            'plans.offline.sale_price'      => 'offline sale price',
        ];
    }
}
