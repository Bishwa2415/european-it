<?php

namespace App\Models;

use App\Models\Concerns\HasDisplayOrder;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasDisplayOrder;

    protected $fillable = ['title', 'body', 'icon', 'bg_color', 'is_active', 'sort_order'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function iconUrl(): ?string
    {
        return $this->mediaUrl($this->icon);
    }
}
