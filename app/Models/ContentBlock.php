<?php

namespace App\Models;

use App\Models\Concerns\HasDisplayOrder;
use Illuminate\Database\Eloquent\Model;

/**
 * A text + image block found by its key.
 * In Blade: $block = ContentBlock::for('home.specialty');
 */
class ContentBlock extends Model
{
    use HasDisplayOrder;

    protected $fillable = ['key', 'eyebrow', 'title', 'body', 'image', 'buttons'];

    protected function casts(): array
    {
        return ['buttons' => 'array'];   // JSON in the database ⇄ PHP array in code
    }

    public static function for(string $key): ?self
    {
        return static::where('key', $key)->first();
    }

    public function imageUrl(): ?string
    {
        return $this->mediaUrl($this->image);
    }
}
