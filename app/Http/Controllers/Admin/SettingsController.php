<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ReadsRepeaterJson;
use App\Http\Controllers\Controller;
use App\Models\SiteContent;
use App\Services\SiteContentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\View\View;

/**
 * Dashboard → Site settings: phones, emails, address, opening hours, social links, floating buttons ...
 * Saved in site_contents (type "setting", slug "site"); the website reads it through SiteContentService::settings().
 */
class SettingsController extends Controller
{
    use ReadsRepeaterJson;

    // Simple one-line text settings: key => [label, rules]
    public const FIELDS = [
        'name' => ['Institute name', ['required', 'string', 'max:150']],
        'address' => ['Address', ['required', 'string', 'max:300']],
        'contact_url' => ['Contact page link', ['required', 'string', 'max:255']],
        'admission_url' => ['Admission page link', ['required', 'string', 'max:255']],
        'whatsapp' => ['WhatsApp number (digits only, with country code)', ['nullable', 'regex:/^[0-9]{8,15}$/']],
        'map_url' => ['Google map link', ['nullable', 'url', 'max:500']],
        'reviews_url' => ['Google reviews link', ['nullable', 'url', 'max:500']],
        'tin' => ['TIN number', ['nullable', 'string', 'max:50']],
        'demo_class_label' => ['Left floating button text', ['required', 'string', 'max:40']],
        'demo_class_url' => ['Left floating button link', ['required', 'string', 'max:255']],
        'discount_label' => ['Right floating button text', ['required', 'string', 'max:40']],
        'discount_url' => ['Right floating button link', ['required', 'string', 'max:255']],
    ];

    public function edit(SiteContentService $content): View
    {
        return view('admin.settings.edit', ['settings' => $content->settings()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate(array_map(fn ($field) => $field[1], self::FIELDS) + [
            'hours_days' => ['required', 'string', 'max:60'],
            'hours_time' => ['required', 'string', 'max:60'],
        ]);

        $data = Arr::only($validated, array_keys(self::FIELDS)) + [
            'hours' => [$validated['hours_days'], $validated['hours_time']],
            'phones' => $this->values($request->input('phones_json'), 'Phones (footer)', ['string', 'max:30']),
            'header_phones' => $this->values($request->input('header_phones_json'), 'Phones (top bar)', ['string', 'max:30']),
            'emails' => $this->values($request->input('emails_json'), 'Emails', ['email', 'max:150']),
            'socials' => $this->rows($request->input('socials_json'), [
                '*.label' => ['required', 'string', 'max:40'],
                '*.icon' => ['required', 'string', 'regex:/^bi-[a-z0-9-]+$/'],
                '*.url' => ['required', 'url', 'max:500'],
            ], 'Social links'),
        ];

        SiteContent::updateOrCreate(
            ['type' => 'setting', 'slug' => 'site'],
            ['title' => 'Site settings', 'is_active' => true, 'data' => $data],
        );   // saving clears the cache (SiteContent::booted)

        return back()->with('success', 'Site settings saved.');
    }
}
