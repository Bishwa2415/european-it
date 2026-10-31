<?php

namespace App\Models;

use App\View\Composers\NavigationComposer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseCategory extends Model
{
    protected $fillable = ['name', 'slug', 'menu_column', 'sort_order'];

    // After any save/delete, the cached mega menu is rebuilt
    protected static function booted(): void
    {
        static::saved(fn () => NavigationComposer::clearCache());
        static::deleted(fn () => NavigationComposer::clearCache());
    }

    // One category has many courses: $category->courses
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class)->orderBy('sort_order');
    }
}
