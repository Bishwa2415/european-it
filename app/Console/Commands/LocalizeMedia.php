<?php

namespace App\Console\Commands;

use App\Models\Course;
use App\Models\LandingPage;
use App\Models\SiteContent;
use App\Services\SiteContentService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

/**
 * Self-hosting: every picture/video that still points to the old WordPress site
 * (https://europeanit-inst.com/wp-content/uploads/...) is downloaded into storage/app/public/media/live/...
 * and the saved link is replaced by the local path, e.g. "media/live/2025/09/Certified-By-Logo.png".
 *
 *   php artisan media:localize
 *   php artisan media:localize --ca="C:/Program Files/Git/mingw64/etc/ssl/certs/ca-bundle.crt"   (WAMP without CA file)
 *
 * Looks in: courses (thumbnail + details JSON), site_contents (settings, menus, home, course page ...), landing_pages.
 * Safe to run again: files already downloaded are not downloaded twice.
 */
class LocalizeMedia extends Command
{
    protected $signature = 'media:localize {--ca= : CA certificate file for HTTPS, if PHP has none}';

    protected $description = 'Download images linked from the old WordPress site and use the local copies';

    // Full links, and links starting with /wp-content/uploads/ (both point to the old site); pictures, videos, PDFs
    private const PATTERN = '~(?:https?://(?:www\.)?europeanit-inst\.com)?/wp-content/uploads/([^\s"\',)]+\.(?:png|jpe?g|webp|gif|svg|mp4|webm|pdf))~i';

    private int $downloaded = 0;

    private array $failed = [];

    public function handle(SiteContentService $content): int
    {
        // The course page defaults live in config/course-page.php; save them once so their links can be replaced too
        if (! SiteContent::type('setting')->where('slug', 'course-page')->exists()) {
            SiteContent::create(['type' => 'setting', 'slug' => 'course-page', 'title' => 'Course page', 'is_active' => true, 'data' => $content->coursePage()]);
        }

        foreach (Course::all() as $course) {
            $course->thumbnail = $this->localize($course->thumbnail);
            $course->details = $this->localize($course->details);
            $course->isDirty() && $course->save();
        }

        foreach (SiteContent::all() as $item) {
            $item->data = $this->localize($item->data);
            $item->body = $this->localize($item->body);
            $item->isDirty() && $item->save();   // saving clears the site cache
        }

        foreach (LandingPage::all() as $page) {
            $page->content = $this->localize($page->content);
            $page->isDirty() && $page->save();
        }

        $this->info("Downloaded {$this->downloaded} file(s).");
        foreach ($this->failed as $url) {
            $this->warn("Could not download: {$url}");
        }

        return self::SUCCESS;
    }

    /** Strings, arrays (any depth) → the same value with live links replaced by local paths */
    private function localize(mixed $value): mixed
    {
        if (is_array($value)) {
            return array_map($this->localize(...), $value);
        }

        if (! is_string($value) || ! str_contains($value, '/wp-content/uploads/')) {
            return $value;
        }

        return preg_replace_callback(self::PATTERN, fn ($m) => $this->download(str_starts_with($m[0], '/') ? 'https://europeanit-inst.com'.$m[0] : $m[0], $m[1]) ?? $m[0], $value);
    }

    /** https://europeanit-inst.com/wp-content/uploads/2025/09/a.png → "media/live/2025/09/a.png" (null if it failed) */
    private function download(string $url, string $relative): ?string
    {
        $path = 'media/live/'.rawurldecode($relative);
        $disk = Storage::disk('public');

        if ($disk->exists($path)) {
            return $path;
        }

        $response = Http::withUserAgent('Mozilla/5.0')->timeout(60)
            ->withOptions(['verify' => $this->option('ca') ?: true])
            ->get($url);

        if (! $response->ok()) {
            $this->failed[] = $url;

            return null;
        }

        $disk->put($path, $response->body());
        $this->downloaded++;
        $this->line("  ↓ {$path}");

        return $path;
    }
}
