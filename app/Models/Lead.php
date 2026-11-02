<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** Someone who booked the free webinar / seminar on a landing page (Dashboard → Leads) */
class Lead extends Model
{
    protected $fillable = ['landing_page_id', 'name', 'phone', 'source', 'is_contacted'];

    protected function casts(): array
    {
        return ['is_contacted' => 'boolean'];
    }

    public function landingPage(): BelongsTo
    {
        return $this->belongsTo(LandingPage::class);
    }
}
