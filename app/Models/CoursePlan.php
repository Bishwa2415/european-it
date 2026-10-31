<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoursePlan extends Model
{
    protected $fillable = [
        'course_id', 'type', 'duration', 'total_classes', 'classes_per_week',
        'regular_price', 'sale_price', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'regular_price' => 'decimal:2',
            'sale_price'    => 'decimal:2',
            'is_active'     => 'boolean',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    // The price the student pays now: sale price if there is one, otherwise the regular price
    public function currentPrice(): float
    {
        return (float) ($this->sale_price ?? $this->regular_price);
    }
}
