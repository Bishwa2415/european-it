<?php

namespace Tests\Feature\Admin;

use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Checks the dashboard course add / edit / delete.
 * Run: php artisan test
 * Uses a fresh in-memory database (phpunit.xml), so your MySQL data is never touched.
 */
class CourseManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private CourseCategory $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create();
        $this->admin->role = 'admin';
        $this->admin->save();

        $this->category = CourseCategory::create(['name' => 'Development', 'slug' => 'development']);
    }

    public function test_guests_are_sent_to_login(): void
    {
        $this->get('/admin/courses')->assertRedirect('/admin/login');
    }

    public function test_students_cannot_open_the_dashboard(): void
    {
        $student = User::factory()->create();   // role defaults to "student"

        $this->actingAs($student)->get('/admin/courses')->assertForbidden();
    }

    public function test_admin_can_add_a_course_with_image_and_plans(): void
    {
        Storage::fake('public');   // pretend disk: no real file is written

        $response = $this->actingAs($this->admin)->post('/admin/courses', [
            'course_category_id' => $this->category->id,
            'title'              => 'Laravel Mastery',
            'slug'               => '',
            'thumbnail'          => UploadedFile::fake()->image('cover.jpg', 800, 400),
            'is_active'          => '1',
            'is_popular'         => '1',
            'plans' => [
                'online'  => ['enabled' => '1', 'regular_price' => 10000, 'sale_price' => 6000, 'duration' => '3 Months'],
                'offline' => ['enabled' => '0'],
            ],
        ]);

        $course = Course::where('slug', 'laravel-mastery')->first();   // slug made from the title

        $response->assertRedirect(route('admin.courses.edit', $course));
        $this->assertTrue($course->is_popular);
        $this->assertCount(1, $course->plans);
        $this->assertEquals([6000, 6000], $course->priceRange());
        Storage::disk('public')->assertExists($course->thumbnail);

        // The new course shows on the home page "Popular Courses"
        $this->get('/')->assertSee('Laravel Mastery');
    }

    public function test_validation_errors_are_shown(): void
    {
        $this->actingAs($this->admin)
            ->post('/admin/courses', [
                'title' => '',
                'plans' => ['online' => ['enabled' => '1', 'regular_price' => 5000, 'sale_price' => 9000]],
            ])
            ->assertSessionHasErrors(['title', 'course_category_id', 'plans.online.sale_price']);
    }

    public function test_admin_can_edit_a_course(): void
    {
        $course = Course::create(['course_category_id' => $this->category->id, 'title' => 'Old title', 'slug' => 'old-title']);

        $this->actingAs($this->admin)->put("/admin/courses/{$course->id}", [
            'course_category_id' => $this->category->id,
            'title'              => 'New title',
            'slug'               => 'old-title',
            'is_active'          => '0',
            'plans'              => ['offline' => ['enabled' => '1', 'regular_price' => 15000]],
        ])->assertSessionHasNoErrors();

        $course->refresh();
        $this->assertSame('New title', $course->title);
        $this->assertFalse($course->is_active);
        $this->assertSame('offline', $course->plans()->first()->type);
    }

    public function test_admin_can_delete_a_course(): void
    {
        $course = Course::create(['course_category_id' => $this->category->id, 'title' => 'Temp', 'slug' => 'temp']);
        $course->plans()->create(['type' => 'online', 'regular_price' => 1000]);

        $this->actingAs($this->admin)->delete("/admin/courses/{$course->id}")
            ->assertRedirect(route('admin.courses.index'));

        $this->assertDatabaseMissing('courses', ['id' => $course->id]);
        $this->assertDatabaseMissing('course_plans', ['course_id' => $course->id]);   // plans removed too
    }
}
