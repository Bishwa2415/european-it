<?php

/*
|--------------------------------------------------------------------------
| Small global helper functions (loaded by composer.json → autoload.files)
|--------------------------------------------------------------------------
*/

use Illuminate\Support\Facades\Storage;

if (! function_exists('media_url')) {
    /**
     * Turn a saved image path into a URL the browser can load:
     *   "https://..."              → unchanged (external image)
     *   "images/logo.webp"         → public/images/logo.webp   (files shipped with the site)
     *   "media/2026/11/photo.webp" → storage/app/public/media/... (uploaded from the dashboard)
     */
    function media_url(?string $path, ?string $fallback = null): ?string
    {
        if (! $path) {
            return $fallback ? asset($fallback) : null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return str_starts_with($path, 'images/') || str_starts_with($path, '/images/')
            ? asset(ltrim($path, '/'))
            : Storage::disk('public')->url($path);
    }
}

if (! function_exists('video_embed')) {
    /**
     * A video saved in the dashboard → what the page should show.
     *   ['source' => 'youtube', 'url' => 'https://youtu.be/abc...']  → ['iframe', 'https://www.youtube.com/embed/abc...']
     *   ['source' => 'upload',  'file' => 'media/2026/11/a.mp4']     → ['video',  'http://.../storage/media/2026/11/a.mp4']
     *   ['source' => 'url',     'url' => 'https://vimeo.com/123']    → ['iframe', 'https://player.vimeo.com/video/123']
     *   ['source' => 'url',     'url' => 'https://cdn.x.com/a.mp4']  → ['video',  'https://cdn.x.com/a.mp4']
     * Old rows that only have 'youtube' => 'id or link' still work. Returns null when nothing usable is saved.
     */
    function video_embed(array $video): ?array
    {
        $source = $video['source'] ?? 'youtube';
        $url = trim((string) ($video['url'] ?? $video['youtube'] ?? ''));

        if ($source === 'upload') {
            return empty($video['file']) ? null : ['video', media_url($video['file'])];
        }

        // YouTube: full link (watch?v=, youtu.be/, shorts/, embed/) or just the 11 character id
        if (preg_match('~(?:youtube\.com/(?:watch\?v=|embed/|shorts/)|youtu\.be/)([\w-]{11})~', $url, $m)
            || preg_match('~^([\w-]{11})$~', $url, $m)) {
            return ['iframe', 'https://www.youtube.com/embed/'.$m[1]];
        }

        if (preg_match('~vimeo\.com/(?:video/)?(\d+)~', $url, $m)) {
            return ['iframe', 'https://player.vimeo.com/video/'.$m[1]];
        }

        if (str_contains($url, 'facebook.com/')) {
            return ['iframe', 'https://www.facebook.com/plugins/video.php?href='.urlencode($url)];
        }

        if (preg_match('~^https?://~', $url)) {
            // A direct video file plays in <video>, anything else (an embed link) goes in an <iframe>
            return [preg_match('~\.(mp4|webm|ogg)(\?.*)?$~i', $url) ? 'video' : 'iframe', $url];
        }

        return null;
    }
}
