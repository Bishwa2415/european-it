<?php

namespace App\Models;

use App\Models\Concerns\HasDisplayOrder;
use Illuminate\Database\Eloquent\Model;

class SuccessVideo extends Model
{
    use HasDisplayOrder;

    protected $fillable = ['title', 'youtube_url', 'is_active', 'sort_order'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    // Works with youtu.be/ID, youtube.com/watch?v=ID, youtube.com/embed/ID, youtube.com/shorts/ID
    public function youtubeId(): ?string
    {
        preg_match('~(?:youtu\.be/|v=|embed/|shorts/)([A-Za-z0-9_-]{11})~', $this->youtube_url, $match);

        return $match[1] ?? null;
    }

    // youtube-nocookie = no tracking cookies until the visitor presses play
    public function embedUrl(): ?string
    {
        return $this->youtubeId() ? 'https://www.youtube-nocookie.com/embed/'.$this->youtubeId() : null;
    }
}
