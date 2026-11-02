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
