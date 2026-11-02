<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ReadsRepeaterJson;
use App\Http\Controllers\Controller;
use App\Models\SiteContent;
use App\Services\SiteContentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Dashboard → Header menu  (/admin/menus/header)  and  Footer  (/admin/menus/footer).
 * Saved in site_contents (type "menu", slug "header" / "footer");
 * the website reads them through SiteContentService::headerMenu() and ::footer().
 *
 * The "All Course" mega menu and the footer "Popular Courses" list come from the courses table
 * (Dashboard → Courses), not from here.
 */
class MenuController extends Controller
{
    use ReadsRepeaterJson;

    public const BADGES = ['' => 'No badge', 'advance' => 'ADVANCE', 'popular' => 'POPULAR', 'new' => 'NEW'];

    private const IMAGE_RULES = ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:2048'];

    // Text fields: key => [label, rules]
    public const HEADER_FIELDS = [
        'logo_alt' => ['Logo alt text', ['required', 'string', 'max:120']],
        'admission_label' => ['Admission button text', ['required', 'string', 'max:40']],
        'admission_url' => ['Admission button link', ['required', 'string', 'max:255']],
        'all_courses_label' => ['"All Course" menu text', ['required', 'string', 'max:40']],
        'all_courses_url' => ['"All Course" page link', ['required', 'string', 'max:255']],
        'popular_courses_label' => ['"Popular Courses" menu text', ['required', 'string', 'max:40']],
    ];

    public const FOOTER_FIELDS = [
        'logo_alt' => ['Logo alt text', ['required', 'string', 'max:120']],
        'contact_label' => ['Contact box title', ['required', 'string', 'max:60']],
        'follow_label' => ['Social links title', ['required', 'string', 'max:60']],
        'quick_links_title' => ['Quick links title', ['required', 'string', 'max:60']],
        'popular_courses_title' => ['Popular courses title', ['required', 'string', 'max:60']],
        'map_title' => ['Map box title', ['required', 'string', 'max:60']],
        'map_button_label' => ['Map button text', ['required', 'string', 'max:40']],
        'payment_title' => ['Payment box title', ['required', 'string', 'max:80']],
    ];

    public function edit(string $menu, SiteContentService $content): View
    {
        if ($menu === 'header') {
            $header = $content->headerMenu();

            return view('admin.menus.header', [
                'menu' => $header,
                'popular' => $this->toGroups($header['popular'] ?? []),
                'dropdowns' => $this->toGroups($header['dropdowns'] ?? []),
            ]);
        }

        $footer = $content->footer();

        return view('admin.menus.footer', [
            'menu' => $footer,
            'quickLinks' => array_map(fn ($links) => ['title' => '', 'items' => $links], $footer['quick_links'] ?? []),
        ]);
    }

    public function update(Request $request, string $menu, SiteContentService $content): RedirectResponse
    {
        $data = $menu === 'header' ? $this->headerData($request, $content) : $this->footerData($request, $content);

        SiteContent::updateOrCreate(
            ['type' => 'menu', 'slug' => $menu],
            ['title' => $menu === 'header' ? 'Header menu' : 'Footer', 'is_active' => true, 'data' => $data],
        );   // saving clears the cache (SiteContent::booted)

        return back()->with('success', ($menu === 'header' ? 'Header menu' : 'Footer').' saved.');
    }

    // ------------------------------------------------------------------

    private function headerData(Request $request, SiteContentService $content): array
    {
        $validated = $request->validate(array_map(fn ($field) => $field[1], self::HEADER_FIELDS) + [
            'logo' => ['required_without:logo_file', 'nullable', 'string', 'max:255'],
            'logo_file' => self::IMAGE_RULES,
        ]);

        $link = ['*.label' => ['required', 'string', 'max:80'], '*.url' => ['required', 'string', 'max:255']];
        $courseLink = $link + ['*.badge' => ['nullable', Rule::in(array_keys(self::BADGES))]];

        $links = $this->rows($request->input('links_json'), $link + [
            '*.mobile_label' => ['nullable', 'string', 'max:80'],
            '*.placement' => ['required', Rule::in(['before', 'after'])],
        ], 'Header links', choiceKeys: ['placement']);

        return Arr::only($validated, array_keys(self::HEADER_FIELDS)) + [
            'logo' => $this->storeImage($request->file('logo_file'), $validated['logo'] ?? null, $content->headerMenu()['logo'] ?? null, 'uploads/menu'),
            // mobile_label is optional: drop it when empty so the menu falls back to label
            'links' => array_map(fn ($row) => array_filter($row, fn ($v) => $v !== null && $v !== ''), $links),
            'popular' => $this->toMap($this->groups($request->input('popular_json'), $courseLink, 'Popular courses'), withBadge: true),
            'dropdowns' => $this->toMap($this->groups($request->input('dropdowns_json'), $link, 'Dropdown menus')),
        ];
    }

    private function footerData(Request $request, SiteContentService $content): array
    {
        $validated = $request->validate(array_map(fn ($field) => $field[1], self::FOOTER_FIELDS) + [
            'logo' => ['required_without:logo_file', 'nullable', 'string', 'max:255'],
            'logo_file' => self::IMAGE_RULES,
        ]);

        $link = ['*.label' => ['required', 'string', 'max:80'], '*.url' => ['required', 'string', 'max:255']];

        return Arr::only($validated, array_keys(self::FOOTER_FIELDS)) + [
            'logo' => $this->storeImage($request->file('logo_file'), $validated['logo'] ?? null, $content->footer()['logo'] ?? null, 'uploads/menu'),
            'quick_links' => array_column($this->groups($request->input('quick_links_json'), $link, 'Quick links', needsTitle: false), 'items'),
            'bottom_links' => $this->rows($request->input('bottom_links_json'), $link, 'Bottom links'),
            'payments' => array_map(fn ($row) => ['width' => (int) ($row['width'] ?? 0) ?: null] + $row,
                $this->rows($request->input('payments_json'), [
                    '*.image' => ['required', 'string', 'max:255'],
                    '*.alt' => ['required', 'string', 'max:80'],
                    '*.text' => ['nullable', 'string', 'max:80'],
                    '*.width' => ['nullable', 'integer', 'min:20', 'max:400'],
                ], 'Payment merchants')),
        ];
    }

    /** ['Media' => [links]]  →  [['title' => 'Media', 'items' => [links]]]  (what the group repeater shows) */
    private function toGroups(array $map): array
    {
        return collect($map)->map(fn ($items, $title) => ['title' => $title, 'items' => $items])->values()->all();
    }

    /** The other way round, like config/menu.php. An empty badge becomes null (= no badge). */
    private function toMap(array $groups, bool $withBadge = false): array
    {
        return collect($groups)->mapWithKeys(fn ($group) => [$group['title'] => array_map(
            fn ($link) => $withBadge ? ['badge' => ($link['badge'] ?? '') ?: null] + $link : Arr::only($link, ['label', 'url']),
            $group['items'],
        )])->all();
    }
}
