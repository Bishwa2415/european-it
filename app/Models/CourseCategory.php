<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseCategory extends Model
{
    protected $fillable = ['name', 'slug', 'menu_column', 'sort_order'];

    // One category has many courses: $category->courses
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class)->orderBy('sort_order');
    }
}
