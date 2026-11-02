<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/**
 * Dashboard → Media library: upload pictures once, then paste their path in any form
 * (hero slides, menu logo, page image, popup ...). Files live in storage/app/public/media/YYYY/MM/.
 * On the website a path like "media/2026/11/x.webp" is turned into a URL by media_url() (app/helpers.php).
 */
class MediaController extends Controller
{
    private const FOLDER = 'media';

    public function index(): View
    {
        $disk = Storage::disk('public');

        // Newest first
        $files = collect($disk->allFiles(self::FOLDER))
            ->map(fn ($path) => ['path' => $path, 'url' => media_url($path), 'size' => $disk->size($path), 'time' => $disk->lastModified($path)])
            ->sortByDesc('time')
            ->values();

        return view('admin.media.index', ['files' => $files]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'files'   => ['required', 'array', 'max:20'],
            'files.*' => ['image', 'mimes:jpg,jpeg,png,webp,gif', 'max:4096'],   // 4 MB each, no SVG (it can contain scripts)
        ]);

        foreach ($request->file('files') as $file) {
            $file->store(self::FOLDER.'/'.now()->format('Y/m'), 'public');
        }

        return back()->with('success', count($request->file('files')).' file(s) uploaded.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $path = (string) $request->input('path');

        // Only files inside media/ may be deleted (no "../" tricks)
        abort_unless(str_starts_with($path, self::FOLDER.'/') && ! str_contains($path, '..'), 404);

        Storage::disk('public')->delete($path);

        return back()->with('success', 'File deleted. Pages that still use this path will show no picture.');
    }
}
