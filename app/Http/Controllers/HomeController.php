<?php

namespace App\Http\Controllers;

use App\Models\ContentBlock;
use App\Models\Course;
use App\Models\Feature;
use App\Models\Partner;
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
        return view('pages.home', [
            'popularCourses'   => $this->courses('popular'),
            'fourthIrCourses'  => $this->courses('fourthIr'),
            'features'         => Feature::active()->ordered()->get(),
            'specialty'        => ContentBlock::for('home.specialty'),
            'trusted'          => ContentBlock::for('home.trusted'),
            'successVideos'    => SuccessVideo::active()->ordered()->get(),
            'testimonials'     => Testimonial::active()->ordered()->get(),
            'teamMembers'      => TeamMember::active()->where('show_on_home', true)->ordered()->get(),
            'partners'         => Partner::active()->ordered()->get(),
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
