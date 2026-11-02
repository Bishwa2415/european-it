<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\LandingPage;
use App\Support\LandingContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Dashboard → Landing pages: the online / offline course sales pages (/{slug}).
 * The section fields come from App\Support\LandingContent::SECTIONS.
 */
class LandingPageController extends Controller
{
    // URLs the site already uses: a landing page there would never open
    private const RESERVED_SLUGS = ['admin', 'blog', 'course', 'courses', 'landing', 'storage', 'build', 'images', 'up', 'login', 'logout', 'api'];

    public function index(Request $request): View
    {
        $pages = LandingPage::with('course')->withCount('leads')
            ->when($request->type, fn ($q, $type) => $q->where('type', $type))
            ->orderBy('type')->orderBy('sort_order')->get();

        return view('admin.landing-pages.index', ['pages' => $pages]);
    }

    public function create(): View
    {
        return $this->form(new LandingPage(['type' => 'online', 'is_active' => true, 'content' => ['settings' => ['countdown_hours' => 72]]]));
    }

    public function store(Request $request): RedirectResponse
    {
        $page = LandingPage::create($this->validated($request));

        return redirect()->route('admin.landing-pages.edit', $page)->with('success', "“{$page->title}” created.");
    }

    public function edit(LandingPage $landingPage): View
    {
        return $this->form($landingPage);
    }

    public function update(Request $request, LandingPage $landingPage): RedirectResponse
    {
        $landingPage->update($this->validated($request, $landingPage));

        return back()->with('success', 'Landing page saved.');
    }

    public function destroy(LandingPage $landingPage): RedirectResponse
    {
        $landingPage->delete();   // its leads stay (landing_page_id becomes empty)

        return redirect()->route('admin.landing-pages.index')->with('success', 'Landing page deleted.');
    }

    // ------------------------------------------------------------------

    private function form(LandingPage $page): View
    {
        return view('admin.landing-pages.form', [
            'page' => $page,
            'courses' => Course::orderBy('title')->pluck('title', 'id'),
        ]);
    }

    private function validated(Request $request, ?LandingPage $page = null): array
    {
        $request->merge(['slug' => Str::slug($request->input('slug') ?: $request->input('title'))]);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'alpha_dash', 'max:255', Rule::notIn(self::RESERVED_SLUGS), Rule::unique('landing_pages', 'slug')->ignore($page)],
            'type' => ['required', Rule::in(array_keys(LandingPage::TYPES))],
            'course_id' => ['nullable', 'exists:courses,id'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'settings.countdown_hours' => ['nullable', 'integer', 'min:0', 'max:8760'],
            'settings.enroll_url' => ['nullable', 'string', 'max:255'],
        ]);

        return collect($data)->except('settings')->all() + [
            'is_active' => $request->boolean('is_active'),
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'content' => LandingContent::fromRequest($request, $data['type']),
        ];
    }
}
