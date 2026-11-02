<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * A course sales page (Dashboard → Landing pages). Website: /{slug}, view resources/views/landing/show.blade.php
 * content (JSON) = sections, see App\Support\LandingContent::SECTIONS.
 */
class LandingPage extends Model
{
    public const TYPES = ['online' => 'Online course', 'offline' => 'Offline course'];

    protected $fillable = ['title', 'slug', 'type', 'course_id', 'content', 'is_active', 'sort_order', 'meta_title', 'meta_description'];

    protected function casts(): array
    {
        return [
            'content' => 'array',
            'is_active' => 'boolean',
        ];
    }

    // The course it sells (optional): its price and "Admission Now" link can be used on the page
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    public function isOffline(): bool
    {
        return $this->type === 'offline';
    }

    /** One section's data, or null when it is switched off: $landing->section('faq') */
    public function section(string $key): ?array
    {
        $section = $this->content[$key] ?? null;

        return is_array($section) && ($section['show'] ?? true) ? $section : null;
    }

    public function url(): string
    {
        return url($this->slug);
    }
}
