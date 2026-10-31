<?php

namespace App\Models;

use App\Models\Concerns\HasDisplayOrder;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasDisplayOrder;

    protected $fillable = ['name', 'logo', 'website', 'is_active', 'sort_order'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function logoUrl(): ?string
    {
        return $this->mediaUrl($this->logo);
    }
}
