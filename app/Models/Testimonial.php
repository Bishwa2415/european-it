<?php

namespace App\Models;

use App\Models\Concerns\HasDisplayOrder;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasDisplayOrder;

    protected $fillable = ['name', 'course_name', 'batch', 'message', 'photo', 'is_active', 'sort_order'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function photoUrl(): string
    {
        return $this->mediaUrl($this->photo) ?? asset('images/testimonials/tawfiq-islam.webp');
    }
}
