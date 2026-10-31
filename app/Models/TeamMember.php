<?php

namespace App\Models;

use App\Models\Concerns\HasDisplayOrder;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasDisplayOrder;

    protected $fillable = ['name', 'slug', 'designation', 'bio', 'photo', 'show_on_home', 'is_active', 'sort_order'];

    protected function casts(): array
    {
        return ['show_on_home' => 'boolean', 'is_active' => 'boolean'];
    }

    public function photoUrl(): string
    {
        return $this->mediaUrl($this->photo) ?? asset('images/fsv.webp');
    }
}
