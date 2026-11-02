<?php

namespace Tests\Feature\Admin;

use App\Models\SiteContent;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * The CMS: pages, menus, popups, home page editor, home collections, media library.
 * Run: php artisan test
 */
class ContentManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create();
        $this->admin->role = 'admin';
        $this->admin->save();
    }

    public function test_a_published_page_opens_at_its_slug_and_a_draft_does_not(): void
    {
        $this->actingAs($this->admin)->post('/admin/content/page', [
            'title' => 'Refund Policy', 'slug' => '', 'body' => '<p>Money back in 7 days.</p>', 'is_active' => '1',
        ])->assertSessionHasNoErrors();

        $this->get('/refund-policy')->assertOk()->assertSee('Money back in 7 days.', false);

        SiteContent::where('slug', 'refund-policy')->update(['is_active' => false]);
        $this->get('/refund-policy')->assertNotFound();
    }

    public function test_a_page_cannot_use_a_reserved_slug(): void
    {
        $this->actingAs($this->admin)
            ->post('/admin/content/page', ['title' => 'Admin', 'slug' => 'admin', 'is_active' => '1'])
            ->assertSessionHasErrors('slug');
    }

    public function test_header_menu_changes_show_on_the_website_right_away(): void
    {
        $this->get('/')->assertSee('Freelancing');   // default from config/menu.php (also fills the cache)

        // The "header" menu row already exists (created by a migration), so edit it like an admin would
        $header = SiteContent::where('type', 'menu')->where('slug', 'header')->firstOrFail();
        $links = [['label' => 'Job Board', 'url' => '/jobs', 'placement' => 'before']];
        $this->actingAs($this->admin)->put("/admin/content/menu/{$header->id}", [
            'title' => 'Header menu', 'slug' => 'header', 'is_active' => '1',
            'data_json' => json_encode(['links' => $links]),
        ])->assertSessionHasNoErrors();

        // Saving cleared the cache (SiteContent::booted), so the new link is there
        $this->get('/')->assertSee('Job Board')->assertDontSee('>Freelancing<', false);
    }

    public function test_an_active_popup_is_on_every_page(): void
    {
        SiteContent::create(['type' => 'popup', 'title' => 'Eid Offer 30% off', 'is_active' => true]);

        $this->get('/')->assertSee('Eid Offer 30% off')->assertSee('data-popup-key', false);
    }

    public function test_home_page_editor_saves_hero_and_hides_unticked_sections(): void
    {
        $this->actingAs($this->admin)->put('/admin/home-page', [
            'layout'          => ['builtin:hero'],   // only the hero is ticked
            'eyebrow'         => 'Grow your career',
            'title_before'    => 'Learn',
            'title_highlight' => 'Skills',
            'title_after'     => 'Get hired',
            'description'     => 'Hands-on IT training.',
            'buttons_json'    => json_encode([['label' => 'Join now', 'url' => '/admission-now']]),
            'slides_json'     => json_encode([['image' => 'images/home/hero-slide-1.webp', 'alt' => 'Class']]),
            'stats_json'      => json_encode([['icon' => 'bi-star', 'count' => '50', 'suffix' => '+', 'label' => 'Courses']]),
            'titles'          => ['team_title' => 'Our Team'],
        ])->assertSessionHasNoErrors();

        $this->get('/')
            ->assertSee('Grow your career')
            ->assertSee('Join now')
            ->assertDontSee('Our Partner and Collaboration');   // partners section not ticked
    }

    public function test_team_photo_can_be_uploaded_and_the_old_upload_is_removed(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin)->post('/admin/home-content/team-members', [
            'name' => 'Rina Akter', 'designation' => 'Mentor', 'is_active' => '1', 'show_on_home' => '1',
            'photo_file' => UploadedFile::fake()->image('rina.jpg'),
        ])->assertSessionHasNoErrors();

        $member = TeamMember::where('name', 'Rina Akter')->first();
        $this->assertSame('rina-akter', $member->slug);              // made from the name
        Storage::disk('public')->assertExists($member->photo);

        $oldPhoto = $member->photo;
        $this->actingAs($this->admin)->put("/admin/home-content/team-members/{$member->id}", [
            'name' => 'Rina Akter', 'slug' => 'rina-akter', 'designation' => 'Senior Mentor', 'photo' => $oldPhoto,
            'photo_file' => UploadedFile::fake()->image('new.jpg'),
        ])->assertSessionHasNoErrors();

        Storage::disk('public')->assertMissing($oldPhoto);
        Storage::disk('public')->assertExists($member->fresh()->photo);
    }

    public function test_media_library_uploads_and_blocks_unsafe_deletes(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin)->post('/admin/media', [
            'files' => [UploadedFile::fake()->image('banner.png')],
        ])->assertSessionHasNoErrors();

        $this->assertCount(1, Storage::disk('public')->allFiles('media'));

        // Trying to delete something outside media/ is refused
        $this->actingAs($this->admin)->delete('/admin/media', ['path' => '../.env'])->assertNotFound();
    }

    public function test_every_dashboard_page_opens(): void
    {
        $this->seed([\Database\Seeders\CourseSeeder::class, \Database\Seeders\HomeContentSeeder::class]);
        $member = TeamMember::first();

        $urls = [
            '/admin', '/admin/home-page', '/admin/media', '/admin/courses', '/admin/courses/create',
            "/admin/home-content/team-members/{$member->id}/edit",
        ];
        foreach (['features', 'content-blocks', 'success-videos', 'testimonials', 'team-members', 'partners'] as $collection) {
            $urls[] = "/admin/home-content/{$collection}";
            $urls[] = "/admin/home-content/{$collection}/create";
        }
        foreach (['page', 'post', 'section', 'popup', 'menu', 'setting'] as $type) {
            $urls[] = "/admin/content/{$type}";
            $urls[] = "/admin/content/{$type}/create";
        }

        foreach ($urls as $url) {
            $this->actingAs($this->admin)->get($url)->assertOk();
        }
    }

    public function test_students_cannot_reach_the_cms(): void
    {
        $student = User::factory()->create();

        $this->actingAs($student)->get('/admin/content/page')->assertForbidden();
        $this->actingAs($student)->get('/admin/media')->assertForbidden();
    }
}
