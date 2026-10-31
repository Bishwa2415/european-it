<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\View\Composers\NavigationComposer;
use Illuminate\Support\Facades\Storage;

class Course extends Model
{
    protected $fillable = [
        'course_category_id', 'title', 'short_title', 'slug', 'custom_url', 'thumbnail', 'badge', 'overview',
        'review_count', 'student_count', 'is_popular', 'is_fourth_ir', 'in_footer', 'is_active',
        'sort_order', 'home_order', 'meta_title', 'meta_description',
    ];

    protected function casts(): array
    {
        return [
            'is_popular'   => 'boolean',
            'is_fourth_ir' => 'boolean',
            'in_footer'    => 'boolean',
            'is_active'    => 'boolean',
        ];
    }

    // Model events: after any save/delete, the cached header/footer course links are rebuilt
    protected static function booted(): void
    {
        static::saved(fn () => NavigationComposer::clearCache());
        static::deleted(fn () => NavigationComposer::clearCache());
    }

    // Short name for small lists (footer); falls back to the full title
    public function shortTitle(): string
    {
        return $this->short_title ?: $this->title;
    }

    // ---------- Relationships ----------

    // $course->category
    public function category(): BelongsTo
    {
        return $this->belongsTo(CourseCategory::class, 'course_category_id');
    }

    // $course->plans  (Online / Offline)
    public function plans(): HasMany
    {
        return $this->hasMany(CoursePlan::class);
    }

    // ---------- Query scopes: Course::active()->popular()->get() ----------

    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    public function scopePopular(Builder $query): void
    {
        $query->where('is_popular', true);
    }

    public function scopeFourthIr(Builder $query): void
    {
        $query->where('is_fourth_ir', true);
    }

    // ---------- Helpers used in Blade ----------

    // Lowest and highest current price of the active plans, e.g. [8000, 11000]
    public function priceRange(): ?array
    {
        $prices = $this->plans->where('is_active', true)->map->currentPrice();

        return $prices->isEmpty() ? null : [$prices->min(), $prices->max()];
    }

    // Link to the course detail page (the page itself is built in a later step)
    public function url(): string
    {
        return url($this->custom_url ?? '/course/'.$this->slug);
    }

    // Thumbnail URL for <img src="...">
    // Seeded images live in public/images/..., images uploaded from the dashboard live in storage/app/public/...
    public function thumbnailUrl(): string
    {
        if (! $this->thumbnail) {
            return asset('images/fsv.webp');
        }

        return str_starts_with($this->thumbnail, 'images/')
            ? asset($this->thumbnail)
            : Storage::disk('public')->url($this->thumbnail);
    }
}
