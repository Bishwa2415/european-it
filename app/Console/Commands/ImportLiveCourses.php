<?php

namespace App\Console\Commands;

use App\Models\Course;
use DOMDocument;
use DOMNode;
use DOMXPath;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

/**
 * One-time helper while moving from WordPress: reads the live course pages on europeanit-inst.com
 * and writes their content to database/data/course-details/{slug}.json (then CourseDetailSeeder loads it).
 *
 *   php artisan course:import-live                 → every course without a custom_url
 *   php artisan course:import-live python-development
 *
 * Two page designs exist on the live site:
 *   new  – Course Curriculum / Why This course ... (classes like faq-item, course-reason-item)
 *   old  – Overview / Modules / Highlight / Software Taught / Career Opportunity (plain lists)
 * Both are turned into the same JSON shape, so every course uses the one new design here.
 */
class ImportLiveCourses extends Command
{
    protected $signature = 'course:import-live {slug? : Only this course}
                            {--ca= : Path to a CA certificate file, if PHP has none (WAMP), e.g. C:/Program Files/Git/mingw64/etc/ssl/certs/ca-bundle.crt}';

    protected $description = 'Copy course page content from the live WordPress site into database/data/course-details';

    private DOMXPath $x;

    public function handle(): int
    {
        $slugs = $this->argument('slug')
            ? [$this->argument('slug')]
            : Course::whereNull('custom_url')->pluck('slug')->all();

        File::ensureDirectoryExists(database_path('data/course-details'));

        foreach ($slugs as $slug) {
            $response = Http::withUserAgent('Mozilla/5.0')->timeout(60)
                ->withOptions(['verify' => $this->option('ca') ?: true])   // never false: keep HTTPS checked
                ->get("https://europeanit-inst.com/course/{$slug}/");
            if (! $response->ok()) {
                $this->warn("{$slug}: HTTP {$response->status()}");

                continue;
            }

            libxml_use_internal_errors(true);
            $doc = new DOMDocument;
            $doc->loadHTML('<?xml encoding="UTF-8">'.$response->body());
            $this->x = new DOMXPath($doc);

            $isNew = (bool) $this->one("//*[contains(@class,'faq-item') or contains(@class,'course-reason-item')]");
            $data = $isNew ? $this->newDesign() : $this->oldDesign();
            $data['plans'] = $this->plans();

            File::put(database_path("data/course-details/{$slug}.json"),
                json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

            $this->line(sprintf('%-50s %s  classes=%d tools=%d careers=%d', $slug, $isNew ? 'new design' : 'old design',
                count($data['curriculum']), count($data['tools']), count($data['careers'])));
        }

        $this->info('Done. Load it with: php artisan db:seed --class=CourseDetailSeeder');

        return self::SUCCESS;
    }

    // ------------------------------------------------------------------ new design

    private function newDesign(): array
    {
        $h1 = $this->one('//h1');
        $hero = $h1;
        while ($hero && ! ($hero instanceof \DOMElement && str_contains($hero->getAttribute('class'), 'e-parent'))) {
            $hero = $hero->parentNode;
        }

        $headings = $this->all(".//*[contains(@class,'elementor-heading-title')]", $hero);
        $tagline = '';
        foreach ($headings as $h) {
            if ($h->nodeName === 'h1') {
                break;
            }
            $tagline = $this->text($h);
        }
        $counts = array_values(array_filter(array_map($this->text(...), $headings), fn ($t) => preg_match('/^[\d,]+\+$/', $t)));
        $overviewTitle = $this->one("//*[contains(@class,'elementor-heading-title')][normalize-space()='Course Overview']");

        return [
            'tagline' => $tagline,
            'intro' => $this->text($this->one(".//*[contains(@class,'elementor-widget-text-editor')]", $hero)),
            'banner' => $this->src($this->one(".//img[contains(@style,'object-fit')]", $hero)),
            'avatars' => array_values(array_unique(array_map($this->src(...), $this->all(".//img[@width='72']", $hero)))),
            'student_count' => (int) str_replace(',', '', $counts[0] ?? '0'),
            'review_count' => (int) str_replace(',', '', $counts[1] ?? '0'),
            'overview' => $overviewTitle ? $this->text($this->one("following::*[contains(@class,'elementor-widget-text-editor')][1]", $overviewTitle)) : '',

            'curriculum' => array_map(function ($item) {
                $lines = [];
                foreach ($this->all(".//*[contains(@class,'faq-details')]//*[self::h5 or self::li or self::p]", $item) as $n) {
                    if (($t = $this->text($n)) !== '') {
                        $lines[] = ($n->nodeName === 'li' ? '- ' : '').$t;
                    }
                }

                return ['title' => $this->text($this->one(".//*[contains(@class,'faq-title')]", $item)), 'details' => implode("\n", $lines)];
            }, $this->all("//*[contains(@class,'faq-item')]")),

            'careers' => array_map(fn ($i) => [
                'title' => $this->text($this->one(".//*[contains(@class,'title')]", $i)),
                'text' => $this->text($this->one(".//*[contains(@class,'details')]", $i)),
            ], $this->all("//*[contains(@class,'career-item')]")),

            'reasons' => array_map(fn ($i) => [
                'icon' => $this->src($this->one('.//img', $i)), 'title' => $this->text($this->one('.//h3', $i)), 'text' => $this->text($this->one('.//p', $i)),
            ], $this->all("//*[contains(@class,'course-reason-item')]")),

            'mentors' => array_map(fn ($i) => [
                'photo' => $this->src($this->one('.//img', $i)),
                'name' => $this->text($this->one('.//h3', $i)),
                'designation' => $this->text($this->one(".//*[contains(@class,'mentor-info-text')]/p", $i)),
                'badges' => implode(', ', array_map($this->text(...), $this->all(".//*[contains(@class,'mentor-info-text')]/div/span", $i))),
                'url' => $this->one(".//a[contains(@class,'mentor-portfolio-btn')]", $i)?->getAttribute('href') ?? '',
            ], $this->all("//*[contains(@class,'mentor-item')]")),

            'projects' => array_map(fn ($i) => [
                'image' => $this->src($this->one(".//*[contains(@class,'main-img')]//img", $i)),
                'title' => $this->text($this->one('.//h3', $i)),
                'text' => $this->text($this->one('.//p', $i)),
                'icons' => implode(', ', array_map($this->src(...), $this->all(".//*[contains(@class,'extra-images')]//img", $i))),
            ], $this->all("//*[contains(@class,'project-item')]")),

            'tools' => array_map(fn ($i) => [
                'icon' => $this->src($this->one('.//img', $i)), 'name' => $this->text($this->one('.//span', $i)),
            ], $this->all("//*[contains(@class,'tool-item')]")),

            'videos' => array_values(array_filter(array_map(fn ($f) => preg_match('~embed/([\w-]{11})~', $f->getAttribute('src'), $m)
                ? ['title' => '', 'source' => 'youtube', 'url' => 'https://www.youtube.com/watch?v='.$m[1], 'file' => ''] : null,
                $this->all("//*[contains(@class,'success-stories')]//iframe")))),

            'glance' => array_map(fn ($i) => [
                'icon' => $this->src($this->one('.//img', $i)), 'text' => $this->text($this->one('.//span', $i)),
            ], $this->all("//*[contains(@class,'at-a-glance-item')]")),
        ];
    }

    // ------------------------------------------------------------------ old design

    private function oldDesign(): array
    {
        // The list that follows a heading, e.g. "Modules" → ["Python Basic", ...]
        $listAfter = function (string $heading): array {
            $h = $this->one("//h2[starts-with(normalize-space(),'{$heading}')]");

            return $h ? array_values(array_filter(array_map($this->text(...), $this->all('following::ul[1]/li', $h)))) : [];
        };

        // Overview: every paragraph between "Overview:" and "Modules"
        $overview = [];
        if ($start = $this->one("//h2[starts-with(normalize-space(),'Overview')]")) {
            foreach ($this->all('following::*[self::p or self::h2 or self::h3]', $start) as $n) {
                if ($n->nodeName === 'h2') {
                    break;
                }
                if (($t = $this->text($n)) !== '') {
                    $overview[] = $n->nodeName === 'h3' ? "\n{$t}" : $t;
                }
            }
        }

        return [
            'tagline' => '', 'intro' => '', 'banner' => $this->src($this->one("//*[contains(@class,'woocommerce-product-gallery')]//img")),
            'avatars' => [], 'student_count' => 0, 'review_count' => 0,
            'overview' => trim(implode("\n", $overview)),
            'curriculum' => array_map(fn ($t) => ['title' => $t, 'details' => ''], $listAfter('Modules')),
            'careers' => array_map(fn ($t) => ['title' => $t, 'text' => ''], $listAfter('Career Opportunity')),
            'reasons' => array_map(fn ($t) => ['icon' => '', 'title' => $t, 'text' => ''], $listAfter('Highlight')),
            'mentors' => [], 'projects' => [],
            'tools' => array_map(fn ($t) => ['icon' => '', 'name' => $t], $listAfter('Software Taught')),
            'videos' => [], 'glance' => [],
        ];
    }

    // ------------------------------------------------------------------ prices (both designs: WooCommerce variations)

    private function plans(): array
    {
        $plans = [];
        $form = $this->one("//form[contains(@class,'variations_form')]");
        $variations = $form ? json_decode(html_entity_decode($form->getAttribute('data-product_variations')), true) : [];

        foreach ($variations ?: [] as $v) {
            $type = strtolower($v['attributes']['attribute_course-type'] ?? $v['attributes']['attribute_pa_course-type'] ?? '');
            if (in_array($type, ['online', 'offline'], true)) {
                $regular = (float) ($v['display_regular_price'] ?? 0);
                $price = (float) ($v['display_price'] ?? 0);
                $plans[$type] = ['info' => [], 'prices' => $price < $regular ? [$regular, $price] : [$regular]];
            }
        }

        // "Duration: 4 Months", "Class Per Week: 2 Day", "Total Class: 32" (old design sidebar)
        $page = $this->text($this->one('//body'));
        foreach (['Duration' => 'Duration', 'Class Per Week' => 'Class Per Week', 'Total' => 'Total Class'] as $key => $label) {
            if (preg_match('/'.preg_quote($label, '/').':\s*([\w ]+?)(?=\s+[A-Z][a-z]+(?: [A-Z][a-z]+)*:|$)/u', $page, $m)) {
                foreach ($plans as &$plan) {
                    $plan['info'][$key] ??= trim($m[1]);
                }
                unset($plan);
            }
        }

        return $plans;
    }

    // ------------------------------------------------------------------ small DOM helpers

    private function all(string $path, ?DOMNode $ctx = null): array
    {
        return iterator_to_array($this->x->query($path, $ctx));
    }

    private function one(string $path, ?DOMNode $ctx = null): ?DOMNode
    {
        return $this->x->query($path, $ctx)->item(0);
    }

    private function text(?DOMNode $node): string
    {
        return $node ? trim(preg_replace('/\s+/u', ' ', $node->textContent)) : '';
    }

    private function src(?DOMNode $img): string
    {
        if (! $img instanceof \DOMElement) {
            return '';
        }
        $src = $img->getAttribute('data-src') ?: $img->getAttribute('src');

        return str_starts_with($src, 'data:') ? $img->getAttribute('data-lazy-src') : $src;
    }
}
