<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

/**
 * Shared by the small "content" models (team, testimonials, partners ...).
 * A trait = a piece of code many classes can "use" instead of copy-pasting it.
 *
 *   TeamMember::active()->ordered()->get()
 *   $member->mediaUrl($member->photo)
 */
trait HasDisplayOrder
{
    // Only rows switched on in the dashboard
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    // Order set in the dashboard (smaller first), then oldest first
    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('sort_order')->orderBy('id');
    }

    // Seeded files live in public/images/..., dashboard uploads in storage/app/public/...
    public function mediaUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        return str_starts_with($path, 'images/') ? asset($path) : Storage::disk('public')->url($path);
    }
}
