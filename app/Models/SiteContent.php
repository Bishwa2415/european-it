<?php

namespace App\Models;

use App\Services\SiteContentService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * One flexible table for editable content of different types:
 * page, post, section (reusable block), popup, menu, setting, home.
 *   - title / slug / body: normal content
 *   - data (JSON): extra settings, e.g. menu links, meta title, button label
 */
class SiteContent extends Model
{
    protected $fillable = ['type', 'title', 'slug', 'body', 'data', 'is_active', 'sort_order'];

    protected function casts(): array
    {
        return ['data' => 'array', 'is_active' => 'boolean'];
    }

    // Any change in the dashboard → forget the cached settings / menus / home / popup
    protected static function booted(): void
    {
        static::saved(fn () => app(SiteContentService::class)->flush());
        static::deleted(fn () => app(SiteContentService::class)->flush());
    }

    // SiteContent::type('page')->...
    public function scopeType(Builder $query, string $type): void
    {
        $query->where('type', $type);
    }

    // Only rows switched on ("Published / active") in the dashboard
    public function scopePublished(Builder $query): void
    {
        $query->where('is_active', true);
    }
}
