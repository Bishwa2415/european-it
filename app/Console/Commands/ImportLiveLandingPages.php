<?php

namespace App\Console\Commands;

use App\Models\Course;
use App\Models\LandingPage;
use App\Services\SiteContentService;
use App\Support\LandingContent;
use DOMDocument;
use DOMElement;
use DOMNode;
use DOMXPath;
use Illuminate\Console\Command;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * One-time helper while moving from WordPress: copies the 12 course landing pages
 * (Header menu → "Popular Courses" → Online / Offline) from europeanit-inst.com into the landing_pages table.
 *
 *   php artisan landing:import-live --ca="C:/Program Files/Git/mingw64/etc/ssl/certs/ca-bundle.crt"
 *   php artisan media:localize --ca="..."      ← afterwards: download their pictures (self-hosting)
 *
 * Each live section is found by words in its heading (they are the same on every landing page),
 * then read through Elementor's standard widgets (icon-box, image-box, image, video, accordion ...).
 */
class ImportLiveLandingPages extends Command
{
    protected $signature = 'landing:import-live {--ca= : CA certificate file for HTTPS, if PHP has none}';

    protected $description = 'Copy the online/offline course landing pages from the live WordPress site';

    // Words (regex) in a section's title → our section key (checked in this order)
    private const FIND = [
        'learn' => 'যা যা শিখবেন',
        'outline' => 'আউটলাইন',
        'webinar' => 'ওয়েবিনার|সেমিনার|ফর্ম পূরণ',
        'reasons' => 'কেন এই কোর্সটি',
        'labs' => 'ল্যাব',
        'projects' => 'প্রজেক্টগুলো',
        'tools' => 'টুলস',
        'stories' => 'সফলতার গল্প',
        'gifts' => 'গিফট',
        'offer' => 'Offer Ends',
        'students' => 'সফল শিক্ষার্থীরা',
        'why' => 'ইউরোপিয়ান আইটি',
        'testimonials' => 'মতামত',
        'screenshots' => 'স্ক্রিনশট|স্টুডেন্ট রিভিউ',
        'certificate' => 'সার্টিফিকেট',
        'glance' => 'এক নজরে',
        'confused' => 'কনফিউজড',
        'faq' => 'প্রশ্ন',
        'accept' => 'We Accept',
        'highlights' => 'Certified By',
        'mentor' => 'View Portfolio',
    ];

    // Font Awesome icons used on the live pages → Bootstrap Icons (icons.getbootstrap.com)
    private const ICONS = [
        'laptop' => 'bi-laptop', 'laptop-code' => 'bi-code-slash', 'user' => 'bi-person', 'building' => 'bi-building',
        'mobile-alt' => 'bi-phone', 'bolt' => 'bi-lightning-charge-fill', 'figma' => 'bi-vector-pen', 'search' => 'bi-search',
        'globe' => 'bi-globe', 'html5' => 'bi-filetype-html', 'css3' => 'bi-filetype-css', 'css3-alt' => 'bi-filetype-css',
        'js' => 'bi-filetype-js', 'js-square' => 'bi-filetype-js', 'wordpress' => 'bi-wordpress', 'github' => 'bi-github',
        'git-alt' => 'bi-git', 'php' => 'bi-filetype-php', 'database' => 'bi-database', 'server' => 'bi-hdd-network',
        'shopping-cart' => 'bi-cart', 'cart-plus' => 'bi-cart-plus', 'gift' => 'bi-gift', 'certificate' => 'bi-award',
        'award' => 'bi-award', 'video' => 'bi-camera-video', 'clock' => 'bi-clock', 'calendar-alt' => 'bi-calendar3',
        'check' => 'bi-check2', 'check-circle' => 'bi-check-circle', 'users' => 'bi-people', 'chalkboard-teacher' => 'bi-easel',
        'briefcase' => 'bi-briefcase', 'paint-brush' => 'bi-brush', 'palette' => 'bi-palette', 'pen-nib' => 'bi-pen',
        'chart-line' => 'bi-graph-up', 'bullhorn' => 'bi-megaphone', 'facebook' => 'bi-facebook', 'youtube' => 'bi-youtube',
        'file-alt' => 'bi-file-earmark-text', 'book' => 'bi-book', 'book-open' => 'bi-book', 'headset' => 'bi-headset',
        'star' => 'bi-star-fill', 'trophy' => 'bi-trophy', 'rocket' => 'bi-rocket-takeoff', 'tools' => 'bi-tools',
        'cogs' => 'bi-gear', 'cog' => 'bi-gear', 'code' => 'bi-code-slash', 'film' => 'bi-film', 'camera' => 'bi-camera',
        'map-marker-alt' => 'bi-geo-alt', 'phone' => 'bi-telephone', 'whatsapp' => 'bi-whatsapp', 'graduation-cap' => 'bi-mortarboard',
        'handshake' => 'bi-hand-thumbs-up', 'lightbulb' => 'bi-lightbulb', 'list' => 'bi-list-check', 'medal' => 'bi-award',
    ];

    private DOMXPath $x;

    private string $css = '';

    public function handle(SiteContentService $content): int
    {
        $sort = 0;
        foreach ($content->headerMenu()['popular'] as $group => $links) {
            $type = str_contains(strtolower($group), 'offline') ? 'offline' : 'online';

            foreach ($links as $link) {
                $slug = trim(parse_url($link['url'], PHP_URL_PATH), '/');
                $html = $this->fetch("https://europeanit-inst.com/{$slug}/");
                if ($html === null) {
                    continue;
                }

                libxml_use_internal_errors(true);
                $doc = new DOMDocument;
                $doc->loadHTML('<?xml encoding="UTF-8">'.$html);
                $this->x = new DOMXPath($doc);

                // The page's own CSS file holds the section background pictures
                preg_match("~[^'\"]+/elementor/css/post-(\d+)\.css[^'\"]*~", $html, $m);
                $pageId = $this->one("//*[@data-elementor-type='wp-page']")?->getAttribute('data-elementor-id');
                $cssUrl = $pageId ? "https://europeanit-inst.com/wp-content/uploads/elementor/css/post-{$pageId}.css" : null;
                $this->css = $cssUrl ? ($this->fetch($cssUrl) ?? '') : '';

                $page = $this->parse($type);
                $title = $this->text($this->one('//h1')) ?: $link['label'];

                LandingPage::updateOrCreate(['slug' => $slug], [
                    'title' => $title.' – '.ucfirst($type),
                    'type' => $type,
                    'course_id' => $this->guessCourse($title)?->id,
                    'content' => $page,
                    'is_active' => true,
                    'sort_order' => $sort++,
                    'meta_title' => $this->text($this->one('//title')),
                ]);

                $found = collect($page)->except('settings')->filter(fn ($s) => count(array_filter($s, fn ($v) => $v !== '' && $v !== [] && $v !== null)) > 1)->count();
                $this->line(sprintf('%-8s %-62s %d sections', $type, $slug, $found));
            }
        }

        $this->info('Done. Now self-host the pictures: php artisan media:localize --ca="..."');

        return self::SUCCESS;
    }

    // ------------------------------------------------------------------

    private function parse(string $type): array
    {
        $sections = iterator_to_array($this->x->query("//*[@data-elementor-type='wp-page']/*"));
        $found = ['hero' => array_shift($sections)];

        // 1st try: the section's first headings (the title). 2nd try: all its text (mentor, offer, webinar have no clear title)
        foreach (['headings', 'text'] as $pass) {
            foreach ($sections as $i => $section) {
                $text = $pass === 'headings'
                    ? implode(' ', array_map($this->text(...), array_slice($this->all(".//*[contains(@class,'elementor-heading-title') or contains(@class,'elementor-headline')]", $section), 0, 2)))
                    : $this->text($section);

                foreach (self::FIND as $key => $words) {
                    if (! isset($found[$key]) && preg_match('/'.$words.'/u', $text)) {
                        $found[$key] = $section;
                        unset($sections[$i]);
                        break;
                    }
                }
            }
        }

        // Success stories can have any title (e.g. "Adobe Illustrator & Photoshop"): it is the section with videos
        foreach ($sections as $section) {
            if (! isset($found['stories']) && $this->one(".//*[contains(@class,'elementor-widget-video')]", $section)) {
                $found['stories'] = $section;
            }
        }

        // Offline pages have the "course outline" box inside the "what you learn" section
        if (! isset($found['outline']) && isset($found['learn']) && preg_match('/আউটলাইন/u', $this->text($found['learn']))) {
            $found['outline'] = $found['learn'];
        }

        $countdown = $this->one('//*[@data-evergreen-interval]');
        $content = ['settings' => [
            'countdown_hours' => $countdown ? (int) round($countdown->getAttribute('data-evergreen-interval') / 3600) : 72,
            'enroll_url' => '',
        ]];

        foreach (LandingContent::sectionsFor($type) as $key => $definition) {
            $section = $found[$key] ?? null;
            $content[$key] = ['show' => (bool) $section] + ($section ? $this->{'read'.Str::studly($key)}($section) : []);
        }

        return $content;
    }

    private function readHero(DOMNode $s): array
    {
        $headings = array_map($this->text(...), $this->all(".//*[contains(@class,'elementor-heading-title')][not(self::h1)]", $s));
        $video = $this->video($this->one(".//*[contains(@class,'elementor-widget-video')]", $s));

        return [
            'title' => $this->text($this->one('.//h1', $s)),
            'subtitle' => collect($headings)->first(fn ($h) => ! str_contains($h, 'শিক্ষার্থী') && ! str_contains($h, 'review')) ?? '',
            'students_text' => collect($headings)->first(fn ($h) => str_contains($h, 'শিক্ষার্থী')) ?? '',
            'reviews_text' => $this->text($this->one(".//a[contains(.,'review')]", $s)),
            'reviews_url' => $this->one(".//a[contains(.,'review')]", $s)?->getAttribute('href') ?? '',
            'intro' => $this->text($this->one(".//*[contains(@class,'elementor-icon-list-text')] | .//*[contains(@class,'elementor-widget-text-editor')]", $s)),
            'button_label' => $this->text($this->one(".//*[contains(@class,'elementor-button-text')]", $s)),
            // The strip on the video ("এক নজরে দেখুন কোর্সের ডেমো ক্লাস") is the 2nd icon list (or an html widget)
            'video_label' => $this->text($this->one("(.//*[contains(@class,'elementor-widget-icon-list')])[2] | .//*[contains(@class,'elementor-widget-html')]", $s)),
            'video_source' => 'youtube',
            'video_url' => $video['url'],
            'video_file' => '',
            'video_cover' => $video['cover'] ?: $this->src($this->one(".//*[contains(@class,'elementor-widget-image')][not(contains(@class,'elementor-absolute'))]//img[not(starts-with(@src,'data:'))][@width>=600]", $s)),
            'background' => $this->background($s),
            'avatars' => $this->images(".//*[contains(@class,'student-review-col')]//img", $s),
            'floating_icons' => $this->images(".//*[contains(@class,'elementor-absolute')]//img | .//*[contains(@class,'circle-container')]//img", $s),
        ];
    }

    private function readHighlights(DOMNode $s): array
    {
        return [
            'certified_title' => $this->text($this->one(".//*[contains(@class,'elementor-heading-title')]", $s)),
            'certified_image' => $this->images(".//*[contains(@class,'elementor-widget-image')]//img", $s)[0]['image'] ?? '',
            'items' => array_map(fn ($b) => ['icon' => $b['icon'] ?: $b['image'], 'label' => $b['title'], 'value' => $b['text']], $this->cards($s)),
        ];
    }

    private function readWebinar(DOMNode $s): array
    {
        $headings = array_map($this->text(...), $this->all(".//*[contains(@class,'elementor-heading-title')]", $s));

        return [
            'badge' => $this->text($this->one(".//*[contains(@class,'elementor-widget-html')]", $s)),
            'title' => $headings[0] ?? '',
            'note' => $this->text($this->one(".//*[contains(@class,'elementor-icon-list-text')]", $s)),
            'form_title' => $headings[1] ?? '',
            'button_label' => $this->text($this->one('.//button', $s)) ?: 'Book Now',
        ];
    }

    private function readLearn(DOMNode $s): array
    {
        // Timeline icons sit in the middle line, not inside the cards: take them in the same order
        $icons = array_values(array_unique(array_map($this->icon(...), $this->all(".//*[contains(@class,'elementor-widget-icon')]//svg", $s))));
        // The offline pages put the "course outline" box in this section: not a module
        $cards = array_values(array_filter($this->cards($s), fn ($c) => ! preg_match('/আউটলাইন|Outline/u', $c['title'])));

        return [
            'title' => $this->text($this->one(".//*[contains(@class,'elementor-heading-title')]", $s)),
            'subtitle' => $this->text($this->one("./descendant::*[contains(@class,'elementor-widget-text-editor')][1]", $s)),
            'items' => array_map(fn ($b, $i) => ['icon' => $b['icon'] ?: $b['image'] ?: ($icons[$i] ?? 'bi-check-circle'), 'title' => $b['title'], 'text' => $b['text']],
                $cards, array_keys($cards)),
        ];
    }

    private function readOutline(DOMNode $s): array
    {
        $button = $this->one(".//a[contains(@class,'elementor-button')][contains(.,'Outline') or contains(.,'আউটলাইন')]", $s)
            ?? $this->one(".//a[contains(@class,'elementor-button')]", $s);

        return [
            'title' => $this->text($this->one(".//*[contains(@class,'elementor-heading-title')][contains(.,'আউটলাইন')]", $s)),
            'button_label' => $this->text($button),
            'file' => $button?->getAttribute('href') ?? '',
        ];
    }

    private function readReasons(DOMNode $s): array
    {
        return [
            'title' => $this->text($this->one(".//*[contains(@class,'elementor-heading-title')]", $s)),
            'items' => array_map(fn ($b) => ['icon' => $b['icon'] ?: $b['image'], 'title' => $b['title'], 'text' => $b['text']], $this->cards($s)),
            'button_label' => $this->text($this->one(".//*[contains(@class,'elementor-button-text')]", $s)),
        ];
    }

    private function readLabs(DOMNode $s): array
    {
        return [
            'title' => $this->text($this->one(".//*[contains(@class,'elementor-heading-title')]", $s)),
            'images' => $this->images(".//*[contains(@class,'elementor-widget-image')]//img", $s),
        ];
    }

    private function readMentor(DOMNode $s): array
    {
        $headings = array_map($this->text(...), $this->all(".//*[contains(@class,'elementor-heading-title')]", $s));
        $badges = array_map($this->text(...), $this->all(".//a[contains(@class,'elementor-button')][not(contains(.,'Portfolio'))]", $s));
        $box = $this->one(".//*[contains(@class,'elementor-widget-icon-box')]", $s);

        // Offline design: name + designation are an icon-box, and the headings are the floating badges
        if ($box) {
            return [
                'photo' => $this->images(".//*[contains(@class,'elementor-widget-image')]//img", $s)[0]['image'] ?? '',
                'name' => $this->text($this->one(".//*[contains(@class,'elementor-icon-box-title')]", $box)),
                'designation' => $this->text($this->one(".//*[contains(@class,'elementor-icon-box-description')]", $box)),
                'company' => '',
                'facts' => '',
                'bio' => $this->text($this->one(".//*[contains(@class,'elementor-widget-text-editor')]", $s)),
                'badges' => implode(', ', array_filter($headings)),
                'url' => $this->one(".//a[contains(.,'Portfolio')]", $s)?->getAttribute('href') ?? '',
            ];
        }

        $facts = array_map($this->text(...), $this->all(".//*[contains(@class,'elementor-icon-list-text')]", $s));

        return [
            'photo' => $this->images(".//*[contains(@class,'elementor-widget-image')]//img", $s)[0]['image'] ?? '',
            'name' => $headings[0] ?? '',
            'designation' => $headings[1] ?? '',
            'company' => $headings[2] ?? '',
            'facts' => implode("\n", array_filter($facts)),
            'bio' => $this->text($this->one(".//*[contains(@class,'elementor-widget-text-editor')]", $s)),
            'badges' => implode(', ', array_filter($badges)),
            'url' => $this->one(".//a[contains(.,'Portfolio')]", $s)?->getAttribute('href') ?? '',
        ];
    }

    private function readProjects(DOMNode $s): array
    {
        // Card: first picture = the project screenshot, the other pictures = small tool logos
        return [
            'title' => $this->text($this->one(".//*[contains(@class,'elementor-heading-title')]", $s)),
            'items' => array_map(fn ($b) => [
                'image' => $b['image'], 'title' => $b['title'], 'text' => $b['text'], 'icons' => implode(', ', array_slice($b['images'], 1)),
            ], $this->cards($s)),
        ];
    }

    private function readTools(DOMNode $s): array
    {
        return [
            'title' => $this->text($this->one(".//*[contains(@class,'elementor-heading-title')]", $s)),
            'items' => array_map(fn ($b) => ['icon' => $b['image'], 'name' => $b['title']], $this->cards($s)),
        ];
    }

    private function readStories(DOMNode $s): array
    {
        $names = array_map($this->text(...), $this->all(".//*[contains(@class,'elementor-heading-title')]", $s));
        $items = [];
        foreach ($this->all(".//*[contains(@class,'elementor-widget-video')]", $s) as $i => $widget) {
            $video = $this->video($widget);
            $items[] = ['name' => $names[$i + 1] ?? '', 'source' => 'youtube', 'url' => $video['url'], 'file' => '', 'cover' => $video['cover']];
        }

        return ['title' => $names[0] ?? '', 'items' => $items];
    }

    private function readGifts(DOMNode $s): array
    {
        // Title: an animated headline (offline) or a plain heading with the red word in a <span> (online)
        $headline = $this->one(".//*[contains(@class,'elementor-headline')]", $s) ?? $this->one(".//*[contains(@class,'elementor-heading-title')]", $s);
        $red = $this->text($this->one(".//*[contains(@class,'elementor-headline-dynamic-text') or contains(@class,'elementor-headline-text-wrapper')][last()]", $s)
            ?? ($headline ? $this->one('.//span[last()]', $headline) : null));
        $title = trim(str_replace($red, '', $this->text($headline)));
        // Animated headline: every rotating word (গিফট! অফার! গিফট!) joined with a space
        $words = array_map($this->text(...), $this->all(".//*[contains(@class,'elementor-headline-dynamic-text')]", $s));
        if (count($words) > 1) {
            $red = implode(' ', $words);
            $title = $this->text($this->one(".//*[contains(@class,'elementor-headline-plain-text')]", $s));
        }
        $editors = $this->all(".//*[contains(@class,'elementor-widget-text-editor')]", $s);
        $texts = array_map($this->text(...), $editors);
        $note = $this->text($this->one(".//*[contains(@class,'elementor-icon-list-text')]", $s));
        // Subtitle that starts with a coloured <span> (যেই ১৫ জন) → that part is shown red
        $subRed = isset($editors[0]) ? $this->text($this->one(".//span[contains(@style,'color')]", $editors[0])) : '';
        $subtitle = $texts[0] ?? '';
        if ($subRed !== '' && str_starts_with($subtitle, $subRed)) {
            $subtitle = trim(substr($subtitle, strlen($subRed)));
        } else {
            $subRed = '';
        }

        return [
            'title' => $title,
            'title_red' => $red,
            'subtitle_red' => $subRed,
            'subtitle' => $subtitle,
            'items' => array_map(function ($b) {
                // Offline: "Ready Portfolio Template Gift - 1 ⇒ Value 1,500 TK" in one title → split it.
                // Online: the "Gift - 1 ⇒ Value ..." line is the first text, the description the second.
                [$title, $value] = array_pad(preg_split('/\s*(?=Gift\s*-)/u', $b['title'], 2), 2, '');
                $texts = $b['texts'];
                if ($value === '' && isset($texts[0]) && preg_match('/^Gift\s*-/u', $texts[0])) {
                    $value = array_shift($texts);
                }

                return ['icon' => $b['icon'], 'title' => trim($title), 'value' => trim($value), 'text' => $texts[0] ?? ''];
            }, $this->cards($s)),
            'note' => $note ?: ($texts[1] ?? ''),
            'button_label' => $this->text($this->one(".//*[contains(@class,'elementor-button-text')]", $s)),
        ];
    }

    private function readOffer(DOMNode $s): array
    {
        $texts = $this->all(".//*[contains(@class,'elementor-widget-text-editor')]", $s);
        $images = $this->images(".//*[contains(@class,'elementor-widget-image')]//img", $s);
        $regular = $texts[2] ?? null;
        $struck = $this->text($regular ? $this->one(".//*[contains(@style,'line-through')] | .//del | .//s", $regular) : null);
        // No marked price: the crossed-out part is the last "৩০,০০০ টাকা" of the line
        if ($struck === '' && preg_match('/(\S+\s+টাকা)\s*$/u', $this->text($regular), $m)) {
            $struck = $m[1];
        }

        // 1st picture = the logo in the box, 2nd = the round guarantee badge sitting on the top edge
        return [
            'badge_image' => $images[0]['image'] ?? '',
            'top_image' => $images[1]['image'] ?? '',
            'guarantee_text' => $this->text($texts[0] ?? null),
            'guarantee_note' => $this->text($texts[1] ?? null),
            'countdown_title' => $this->text($this->one(".//*[contains(@class,'elementor-heading-title')][contains(.,'Offer')]", $s)),
            'regular_text' => trim(str_replace($struck, '', $this->text($regular))),
            'regular_price' => $struck,
            'offer_text' => $this->text($this->one(".//*[contains(@class,'elementor-heading-title')][not(contains(.,'Offer Ends'))]", $s)),
            'button_label' => $this->text($this->one(".//*[contains(@class,'elementor-button-text')]", $s)),
        ];
    }

    private function readStudents(DOMNode $s): array
    {
        return [
            'title' => $this->text($this->one(".//*[contains(@class,'elementor-heading-title')]", $s)),
            'items' => array_map(fn ($b) => ['photo' => $b['image'], 'name' => $b['title'], 'role' => $b['text']], $this->cards($s)),
        ];
    }

    private function readWhy(DOMNode $s): array
    {
        return [
            'title' => $this->text($this->one(".//*[contains(@class,'elementor-heading-title')]", $s)),
            'items' => array_map(fn ($b) => ['icon' => $b['icon'] ?: $b['image'], 'title' => $b['title'], 'text' => $b['text']], $this->cards($s)),
        ];
    }

    private function readTestimonials(DOMNode $s): array
    {
        // Slides of a carousel: image-box (photo, name, course) + text-editor (feedback); carousels repeat slides, so de-duplicate
        $items = [];
        foreach ($this->all(".//*[contains(@class,'elementor-widget-image-box')]", $s) as $box) {
            $text = $this->text($this->one("following::*[contains(@class,'elementor-widget-text-editor')][1]", $box));
            $name = $this->text($this->one(".//*[contains(@class,'elementor-image-box-title')]", $box));
            $items[$name.$text] = [
                'photo' => $this->src($this->one(".//img[not(starts-with(@src,'data:'))]", $box)),
                'name' => $name,
                'course' => $this->text($this->one(".//*[contains(@class,'elementor-image-box-description')]", $box)),
                'text' => $text,
            ];
        }

        return ['title' => $this->text($this->one(".//*[contains(@class,'elementor-heading-title')]", $s)), 'items' => array_values($items)];
    }

    private function readScreenshots(DOMNode $s): array
    {
        return [
            'title' => $this->text($this->one(".//*[contains(@class,'elementor-heading-title')]", $s)),
            'images' => $this->images(".//*[contains(@class,'elementor-widget-image')]//img", $s),
        ];
    }

    private function readCertificate(DOMNode $s): array
    {
        $images = $this->images(".//*[contains(@class,'elementor-widget-image')]//img", $s);
        usort($images, fn ($a, $b) => ($b['width'] ?? 0) <=> ($a['width'] ?? 0));

        return [
            'title' => $this->text($this->one(".//*[contains(@class,'elementor-heading-title')]", $s)),
            'text' => $this->text($this->one(".//*[contains(@class,'elementor-widget-text-editor')]", $s)),
            'image' => $images[0]['image'] ?? '',
            'decoration' => $images[1]['image'] ?? '',   // confetti / paper planes around the frame
        ];
    }

    private function readGlance(DOMNode $s): array
    {
        return [
            'title' => $this->text($this->one(".//*[contains(@class,'elementor-heading-title')]", $s)),
            'items' => array_map(fn ($b) => ['icon' => $b['icon'] ?: $b['image'], 'text' => $b['title']], $this->cards($s)),
            'button_label' => $this->text($this->one(".//*[contains(@class,'elementor-button-text')]", $s)),
        ];
    }

    private function readConfused(DOMNode $s): array
    {
        // Offline: the box has a blue wave picture as background (set in the page CSS)
        $background = $this->background($s);
        foreach ($this->all(".//*[@data-element_type='container']", $s) as $box) {
            $background = $background ?: $this->background($box);
        }

        return [
            'background' => $background,
            'title' => $this->text($this->one(".//*[contains(@class,'elementor-heading-title')]", $s)),
            'text' => $this->text($this->one(".//*[contains(@class,'elementor-widget-text-editor')]", $s)),
            'phone' => preg_replace('/\D/', '', $this->text($this->one(".//*[contains(@class,'elementor-button-text')]", $s))),
        ];
    }

    private function readFaq(DOMNode $s): array
    {
        return [
            'title' => $this->text($this->one(".//*[contains(@class,'elementor-heading-title')]", $s)),
            'items' => array_map(fn ($d) => [
                'question' => $this->text($this->one(".//*[contains(@class,'e-n-accordion-item-title-text')]", $d)),
                'answer' => $this->text($this->one(".//*[contains(@class,'elementor-widget-text-editor')]", $d)),
            ], $this->all('.//details', $s)),
        ];
    }

    private function readAccept(DOMNode $s): array
    {
        return [
            'title' => $this->text($this->one(".//*[contains(@class,'elementor-heading-title')]", $s)),
            'image' => $this->images('.//img', $s)[0]['image'] ?? '',
        ];
    }

    // ------------------------------------------------------------------ Elementor widgets

    /**
     * The repeated cards of a section, whatever widgets they are made of (the live pages mix
     * "icon-box"/"image-box" widgets with separate heading + icon + text widgets).
     * A card = the biggest box around one title that holds no other title.
     * → [['title', 'text', 'texts' => [...], 'icon' => 'bi-...', 'image' => first picture, 'images' => [...]], ...]
     */
    private function cards(DOMNode $s): array
    {
        $titles = $this->all(".//*[contains(@class,'elementor-icon-box-title') or contains(@class,'elementor-image-box-title') or contains(@class,'elementor-heading-title')]", $s);
        // The first plain heading is the section title, not a card
        $first = $this->one(".//*[contains(@class,'elementor-heading-title')]", $s);
        $titles = array_values(array_filter($titles, fn ($t) => $t !== $first));

        $contains = function (DOMNode $box, DOMNode $node): bool {
            for ($n = $node; $n; $n = $n->parentNode) {
                if ($n === $box) {
                    return true;
                }
            }

            return false;
        };

        $cards = [];
        foreach ($titles as $title) {
            $card = $title;
            for ($p = $title->parentNode; $p && $p !== $s; $p = $p->parentNode) {
                if (count(array_filter($titles, fn ($t) => $contains($p, $t))) > 1) {
                    break;
                }
                $card = $p;
            }

            $texts = array_values(array_filter(array_map($this->text(...), $this->all(
                ".//*[contains(@class,'elementor-widget-text-editor') or contains(@class,'elementor-icon-box-description') or contains(@class,'elementor-image-box-description')]", $card))));
            $images = array_column($this->images('.//img', $card), 'image');
            $item = [
                'title' => $this->text($title),
                'text' => $texts[0] ?? '',
                'texts' => $texts,
                'icon' => $this->icon($this->one('.//svg', $card)) ?: $this->iconFont($this->one(".//i[contains(@class,'icon-')]", $card)),
                'image' => $images[0] ?? '',
                'images' => $images,
            ];
            $cards[$item['title'] ?: $item['text']] ??= $item;   // pages repeat some widgets for phones: keep the first
        }

        return array_values($cards);
    }

    /** [['image' => url, 'width' => n], ...] without lazy-load placeholders and duplicates */
    private function images(string $path, DOMNode $s): array
    {
        $images = [];
        foreach ($this->all($path, $s) as $img) {
            $src = $this->src($img);
            if ($src && ! isset($images[$src])) {
                $images[$src] = ['image' => $src, 'width' => (int) $img->getAttribute('width')];
            }
        }

        return array_values($images);
    }

    /** video widget → youtube link + cover picture (from data-settings) */
    private function video(?DOMNode $widget): array
    {
        $settings = $widget instanceof DOMElement ? json_decode(html_entity_decode($widget->getAttribute('data-settings')), true) : [];

        return ['url' => $settings['youtube_url'] ?? '', 'cover' => $settings['image_overlay']['url'] ?? ''];
    }

    /** Background picture of an element, from the page CSS file */
    private function background(DOMNode $el): string
    {
        $id = $el instanceof DOMElement ? $el->getAttribute('data-id') : '';

        return $id && preg_match('~\.elementor-element-'.preg_quote($id, '~').'[^{]*\{[^}]*background-image:url\("?([^")]+)~', $this->css, $m) ? $m[1] : '';
    }

    /** <svg class="e-font-icon-svg e-fas-laptop"> → "bi-laptop" */
    private function icon(?DOMNode $svg): string
    {
        if (! $svg instanceof DOMElement) {
            return '';
        }

        // Keep the live icon exactly: save the inline <svg> as our own file.
        //   Font Awesome icons (one colour) → "media/live/icons/fa-html5.svg": the page colours them with a CSS mask
        //   custom drawings (many colours)  → "media/live/icons/{hash}.svg": shown as a normal picture
        $markup = $svg->ownerDocument->saveXML($svg);
        $markup = preg_replace('/\s(xmlns(:xlink)?="[^"]*"|aria-hidden="[^"]*"|class="[^"]*")/', '', $markup);
        // The HTML reader writes attribute names in lower case; SVG needs some of them in camelCase
        $markup = str_replace(
            ['viewbox=', 'patterncontentunits=', 'patternunits=', 'preserveaspectratio=', 'gradientunits=', 'gradienttransform=', 'clippathunits=', 'maskunits=', 'maskcontentunits=', 'stddeviation='],
            ['viewBox=', 'patternContentUnits=', 'patternUnits=', 'preserveAspectRatio=', 'gradientUnits=', 'gradientTransform=', 'clipPathUnits=', 'maskUnits=', 'maskContentUnits=', 'stdDeviation='],
            $markup
        );
        $markup = preg_replace('/^<svg/', '<svg xmlns="http://www.w3.org/2000/svg"'.(str_contains($markup, 'xlink:') ? ' xmlns:xlink="http://www.w3.org/1999/xlink"' : ''), $markup);

        // fa-s-user (solid) and fa-r-user (outline) are different icons
        $name = preg_match('/e-fa([srb])-([\w-]+)/', $svg->getAttribute('class'), $m) ? "fa-{$m[1]}-{$m[2]}" : md5($markup);
        $path = 'media/live/icons/'.$name.'.svg';
        Storage::disk('public')->put($path, $markup);

        return $path;
    }

    /** ElementsKit icon font (<i class="icon icon-github">) → the closest Bootstrap icon */
    private function iconFont(?DOMNode $i): string
    {
        if (! $i instanceof DOMElement || ! preg_match('/\bicon-([\w-]+)/', $i->getAttribute('class'), $m)) {
            return '';
        }

        return [
            'github' => 'bi-github', 'speed' => 'bi-speedometer2', 'customer-support-2' => 'bi-headset', 'file' => 'bi-file-earmark',
            'shopping-cart' => 'bi-cart3', 'shopping-cart-3' => 'bi-cart3', 'right-arrow' => 'bi-arrow-right',
        ][$m[1]] ?? 'bi-check-circle';
    }

    private function guessCourse(string $title): ?Course
    {
        $words = collect(preg_split('/\W+/', strtolower($title)))->filter(fn ($w) => strlen($w) > 3);

        return Course::all()->sortByDesc(fn ($c) => $words->filter(fn ($w) => str_contains(strtolower($c->title), $w))->count())
            ->first(fn ($c) => $words->filter(fn ($w) => str_contains(strtolower($c->title), $w))->count() >= 2);
    }

    // ------------------------------------------------------------------ DOM helpers

    private function fetch(string $url): ?string
    {
        try {
            // The live server is sometimes slow: try 3 times before giving up on this page
            $response = Http::withUserAgent('Mozilla/5.0')->connectTimeout(30)->timeout(90)->retry(3, 2000, throw: false)
                ->withOptions(['verify' => $this->option('ca') ?: true])   // never false: keep HTTPS checked
                ->get($url);
        } catch (ConnectionException $e) {
            $this->warn("{$url}: {$e->getMessage()}");

            return null;
        }

        if (! $response->ok()) {
            $this->warn("{$url}: HTTP {$response->status()}");

            return null;
        }

        return $response->body();
    }

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
        if (! $img instanceof DOMElement) {
            return '';
        }
        $src = $img->getAttribute('data-lazy-src') ?: $img->getAttribute('data-src') ?: $img->getAttribute('src');
        if (str_starts_with($src, 'data:')) {
            return '';
        }

        // WordPress makes small copies ("photo-300x165.jpg"); the original ("photo.jpg") is sharp → use it
        return preg_replace('/-\d{2,4}x\d{2,4}(?=\.(?:jpe?g|png|webp|gif)$)/i', '', $src);
    }
}
