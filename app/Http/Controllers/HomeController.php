<?php

namespace App\Http\Controllers;

use App\Models\ContentBlock;
use App\Models\Course;
use App\Models\Feature;
use App\Models\Partner;
use App\Models\SiteContent;
use App\Models\SuccessVideo;
use App\Models\TeamMember;
use App\Models\Testimonial;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Home page: get every section's data from the database, then hand it to the view.
     */
    public function index(): View
    {
        $homeLayout = SiteContent::type('home')->first()?->data['layout']
            ?? array_map(fn ($key) => 'builtin:'.$key, ['hero', 'popular-courses', 'fourth-ir', 'why-choose', 'specialty', 'success-stories', 'testimonials', 'team', 'partners']);
        $commonSectionIds = collect($homeLayout)
            ->filter(fn ($key) => is_string($key) && str_starts_with($key, 'common:'))
            ->map(fn ($key) => (int) substr($key, strlen('common:')))
            ->all();
        $homeData = SiteContent::type('home')->first()?->data ?? [];
        $hero = array_replace(config('home.hero'), $homeData['hero'] ?? []);
        $homeTitles = array_replace(config('home.titles'), $homeData['titles'] ?? []);
        $headerMenu = SiteContent::type('menu')->where('slug', 'header')->where('is_active', true)->value('data') ?? [];

        return view('pages.home', [
            'hero' => $hero,
            'homeTitles' => $homeTitles,
            'allCoursesUrl' => $headerMenu['all_courses_url'] ?? config('menu.all_courses_url'),
            'popularCourses' => $this->courses('popular'),
            'fourthIrCourses' => $this->courses('fourthIr'),
            'features' => Feature::active()->ordered()->get(),
            'specialty' => ContentBlock::for('home.specialty'),
            'trusted' => ContentBlock::for('home.trusted'),
            'successVideos' => SuccessVideo::active()->ordered()->get(),
            'testimonials' => Testimonial::active()->ordered()->get(),
            'teamMembers' => TeamMember::active()->where('show_on_home', true)->ordered()->get(),
            'partners' => Partner::active()->ordered()->get(),
            'homeLayout' => $homeLayout,
            'commonSections' => SiteContent::type('section')->where('is_active', true)->whereIn('id', $commonSectionIds)->get()->keyBy('id'),
        ]);
    }

    /**
     * Active courses for a home section. $scope = 'popular' or 'fourthIr' (scopes in the Course model)
     */
    private function courses(string $scope)
    {
        return Course::active()
            ->{$scope}()
            ->with('plans')          // load all prices in 1 extra query (not 1 per course)
            ->orderBy('home_order')
            ->get();
    }
}
