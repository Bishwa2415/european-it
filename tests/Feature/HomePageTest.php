<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\TeamMember;
use Database\Seeders\CourseSeeder;
use Database\Seeders\HomeContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * The home page shows database content, and dashboard changes reach it.
 */
class HomePageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([CourseSeeder::class, HomeContentSeeder::class]);
    }

    public function test_every_home_section_is_shown(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Popular Courses')
            ->assertSee('4th Industrial Courses')
            ->assertSee('কেন ইউরোপিয়ান আইটিকে বেছে নেবেন?')
            ->assertSee('Live Project-Based Classes at European IT™')
            ->assertSee('Success Stories')
            ->assertSee("Student's Feedback")
            ->assertSee('Our Support Team')
            ->assertSee('Our Partner and Collaboration');
    }

    public function test_mega_menu_and_footer_links_come_from_the_database(): void
    {
        $course = Course::where('slug', 'mtcna')->first();
        $course->update(['title' => 'MTCNA Pro Training', 'in_footer' => true, 'short_title' => 'MTCNA Pro']);

        // Saving the course cleared the menu cache, so the new names show at once
        $this->get('/')
            ->assertSee('MTCNA Pro Training')               // mega menu
            ->assertSee('MTCNA Pro</a>', false);            // footer short title
    }

    public function test_hidden_team_members_are_not_shown(): void
    {
        TeamMember::where('name', 'Imam Hasan')->update(['is_active' => false]);

        $this->get('/')->assertDontSee('Accounts Executive');
    }
}
