<?php

namespace App\Http\Controllers;

use App\Models\LandingPage;
use App\Models\Lead;
use App\Services\SiteContentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Website: course landing pages (/{slug}) and their free-webinar form.
 * The /{slug} route is shared with dashboard pages: SiteContentController@page asks this controller first.
 */
class LandingPageController extends Controller
{
    public function show(LandingPage $landing, SiteContentService $content): View
    {
        $course = $landing->course;
        $settings = $landing->content['settings'] ?? [];

        // Every "Enroll Now" button: the link from the dashboard, else the admission form for this course + plan
        $enrollUrl = ($settings['enroll_url'] ?? '') ?: url($content->settings()['admission_url']).'?'.http_build_query(array_filter([
            'course' => $course?->slug,
            'plan' => $landing->type,
        ]));

        return view('landing.show', [
            'landing' => $landing,
            'offline' => $landing->isOffline(),
            'enrollUrl' => $enrollUrl,
        ]);
    }

    // POST /landing/{landing}/lead  (the "Book Now" form) → saved in leads, shown in Dashboard → Leads
    public function lead(Request $request, LandingPage $landing): RedirectResponse
    {
        abort_unless($landing->is_active, 404);

        // A hidden "website" field: people leave it empty, spam bots fill it → pretend it worked, save nothing
        if ($request->filled('website')) {
            return redirect(url($landing->slug).'#webinar')->with('lead_saved', true);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'regex:/^\+?[0-9\s-]{10,17}$/'],
        ], [
            'phone.regex' => 'সঠিক মোবাইল নম্বর দিন (যেমন 01XXXXXXXXX)।',
        ]);

        Lead::create($data + ['landing_page_id' => $landing->id, 'source' => 'webinar']);

        return redirect(url($landing->slug).'#webinar')->with('lead_saved', true);
    }
}
