<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteContent extends Model
{
    protected $fillable = ['type', 'title', 'slug', 'body', 'data', 'is_active', 'sort_order'];

    protected function casts(): array
    {
        return ['data' => 'array', 'is_active' => 'boolean'];
    }

    public function scopeType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
