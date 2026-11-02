<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\CoursePageController;
use App\Models\Course;
use App\Models\User;
use Database\Seeders\CourseDetailSeeder;
use Database\Seeders\CourseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Course detail page /course/{slug} + its dashboard editors.
 * Run: php artisan test --filter=CoursePageTest
 */
class CoursePageTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([CourseSeeder::class, CourseDetailSeeder::class]);

        $this->admin = User::factory()->create();
        $this->admin->role = 'admin';
        $this->admin->save();
    }

    public function test_course_page_shows_its_content_and_prices(): void
    {
        $this->get('/course/web-design-development-with-wordpress')
            ->assertOk()
            ->assertSee('Web Design &amp; Development with WordPress', false)
            ->assertSee('Class 01: Introduction to Web Design &amp; Development', false)
            ->assertSee('Faruk Ahmed')
            ->assertSee('Industry-Relevant Curriculum')   // shared "Why European IT" card
            ->assertSee('15,000.00');   // offline sale price
    }

    public function test_a_course_without_details_still_opens_and_unknown_or_hidden_courses_do_not(): void
    {
        Course::where('slug', 'networking')->update(['details' => null]);   // no content yet → sections are hidden
        $this->get('/course/networking')->assertOk()->assertDontSee('Course Curriculum');
        $this->get('/course/no-such-course')->assertNotFound();

        Course::where('slug', 'networking')->update(['is_active' => false]);
        $this->get('/course/networking')->assertNotFound();
    }

    public function test_admin_edits_the_course_page_content_tabs_and_the_website_changes(): void
    {
        $course = Course::where('slug', 'networking')->first();

        $this->actingAs($this->admin)->get("/admin/courses/{$course->id}/edit")
            ->assertOk()->assertSee('Course page content')->assertSee('data-tab="curriculum"', false);

        // The course form sends the basic fields + one JSON string per content tab
        $form = $course->only(['course_category_id', 'title', 'slug']) + ['is_active' => '1'];

        $this->actingAs($this->admin)->put("/admin/courses/{$course->id}", $form + [
            'tagline' => 'Become a network engineer',
            'curriculum_json' => json_encode([['title' => 'Class 01: OSI model', 'details' => "Layers\n- Physical layer"]]),
            'tools_json' => json_encode([['icon' => 'media/cisco.png', 'name' => 'Cisco Packet Tracer'], ['icon' => '', 'name' => '']]),   // empty row ignored
            'projects_json' => json_encode([['image' => '', 'title' => 'Office network', 'text' => 'Build it', 'icons' => 'Cisco Packet Tracer']]),
            'videos_json' => json_encode([
                ['title' => '', 'source' => 'youtube', 'url' => 'https://youtu.be/iCZ4Szzc6Rg', 'file' => ''],
                ['title' => '', 'source' => 'upload', 'url' => '', 'file' => 'media/2026/11/story.mp4'],
                ['title' => '', 'source' => 'youtube', 'url' => '', 'file' => ''],   // only a dropdown value → empty row, ignored
            ]),
        ])->assertSessionHasNoErrors();

        $this->get('/course/networking')
            ->assertSee('Become a network engineer')
            ->assertSee('Class 01: OSI model')
            ->assertSee('Physical layer')
            ->assertSee('Cisco Packet Tracer')
            ->assertSee('storage/media/cisco.png', false)                     // project shows the tool's logo by name
            ->assertSee('https://www.youtube.com/embed/iCZ4Szzc6Rg', false)
            ->assertSee('<video src="'.url('storage/media/2026/11/story.mp4').'"', false);

        $this->assertCount(1, $course->fresh()->detail('tools'));
        $this->assertCount(2, $course->fresh()->detail('videos'));

        // A class without a title is refused
        $this->actingAs($this->admin)->put("/admin/courses/{$course->id}", $form + [
            'curriculum_json' => json_encode([['title' => '', 'details' => 'x']]),
        ])->assertSessionHasErrors();
    }

    public function test_upload_button_stores_a_picture_and_refuses_other_files(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin)
            ->post('/admin/media/upload', ['file' => UploadedFile::fake()->image('logo.png'), 'kind' => 'image'])
            ->assertOk()->assertJsonStructure(['path', 'url']);

        $this->actingAs($this->admin)
            ->postJson('/admin/media/upload', ['file' => UploadedFile::fake()->create('evil.php', 1), 'kind' => 'image'])
            ->assertUnprocessable();
    }

    public function test_admin_edits_the_shared_course_page_parts(): void
    {
        $this->actingAs($this->admin)->get('/admin/course-page')->assertOk();

        $data = collect(config('course-page'))
            ->only(array_keys(CoursePageController::FIELDS))->all();

        $this->actingAs($this->admin)->put('/admin/course-page', [
            'help_title' => 'Need help choosing?',
            'titles' => config('course-page.titles'),
            'why_european_json' => json_encode([['icon' => '', 'title' => 'Job placement cell', 'text' => 'We help you find a job.']]),
        ] + $data)->assertSessionHasNoErrors();   // + keeps the left value, so the changes win

        $this->get('/course/web-design-development-with-wordpress')
            ->assertSee('Need help choosing?')
            ->assertSee('Job placement cell')
            ->assertDontSee('Proven Track Record');
    }
}
