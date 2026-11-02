<?php

namespace Tests\Feature\Admin;

use App\Http\Controllers\Admin\SettingsController;
use App\Models\SiteContent;
use App\Models\TeamMember;
use App\Models\User;
use Database\Seeders\CourseSeeder;
use Database\Seeders\HomeContentSeeder;
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

    public function test_header_menu_form_changes_show_on_the_website_right_away(): void
    {
        $this->get('/')->assertSee('Freelancing')->assertSee('Office Gallery');   // defaults (also fills the cache)

        $menu = config('menu');
        $this->actingAs($this->admin)->put('/admin/menus/header', [
            'logo' => $menu['logo'],
            'logo_alt' => $menu['logo_alt'],
            'admission_label' => 'Apply Now',
            'admission_url' => '/admission-now',
            'all_courses_label' => $menu['all_courses_label'],
            'all_courses_url' => $menu['all_courses_url'],
            'popular_courses_label' => $menu['popular_courses_label'],
            // What the repeaters send: JSON rows (the empty row is ignored)
            'links_json' => json_encode([
                ['label' => 'Job Board', 'mobile_label' => '', 'url' => '/jobs', 'placement' => 'before'],
                ['label' => '', 'mobile_label' => '', 'url' => '', 'placement' => 'after'],
            ]),
            'popular_json' => json_encode([['title' => 'Online Course', 'items' => [['label' => 'Python', 'url' => '/python', 'badge' => 'new']]]]),
            'dropdowns_json' => json_encode([['title' => 'Media', 'items' => [['label' => 'Seminar', 'url' => '/seminar']]]]),
        ])->assertSessionHasNoErrors();

        // Saving cleared the cache (SiteContent::booted), so the changes are there right away,
        // and the removed dropdown links (Office Gallery) do not come back from config/menu.php
        $this->get('/')->assertSee('Job Board')->assertSee('Apply Now')->assertSee('Python')
            ->assertDontSee('>Freelancing<', false)->assertDontSee('Office Gallery');
    }

    public function test_header_menu_rejects_an_unknown_badge(): void
    {
        $this->actingAs($this->admin)->put('/admin/menus/header', config('menu') + [
            'popular_json' => json_encode([['title' => 'Online', 'items' => [['label' => 'X', 'url' => '/x', 'badge' => 'hot']]]]),
        ])->assertSessionHasErrors();
    }

    public function test_footer_and_site_settings_forms_save(): void
    {
        $footer = config('site.footer');
        $this->actingAs($this->admin)->put('/admin/menus/footer', array_diff_key($footer, array_flip(['quick_links', 'payments', 'bottom_links'])) + [
            'quick_links_json' => json_encode([['title' => '', 'items' => [['label' => 'Alumni', 'url' => '/alumni']]]]),
            'payments_json' => json_encode([['image' => 'images/footer/bkash.webp', 'alt' => 'bKash', 'text' => '017', 'width' => '120']]),
            'bottom_links_json' => json_encode([['label' => 'Privacy', 'url' => '/privacy']]),
        ])->assertSessionHasNoErrors();

        $settings = collect(config('site'))->only(array_keys(SettingsController::FIELDS))->all();
        $this->actingAs($this->admin)->put('/admin/settings', $settings + [
            'hours_days' => 'Saturday - Thursday',
            'hours_time' => '9AM - 8PM',
            'phones_json' => json_encode([['value' => '+880 111']]),
            'header_phones_json' => json_encode([['value' => '+880 222']]),
            'emails_json' => json_encode([['value' => 'info@example.com']]),
            'socials_json' => json_encode([['label' => 'Facebook', 'icon' => 'bi-facebook', 'url' => 'https://facebook.com/x']]),
        ])->assertSessionHasNoErrors();

        $this->get('/')
            ->assertSee('Alumni')->assertSee('Privacy')->assertSee('width: 120px', false)
            ->assertSee('Saturday - Thursday')->assertSee('+880 222')->assertSee('info@example.com');

        // A wrong email is refused
        $this->actingAs($this->admin)->put('/admin/settings', $settings + [
            'hours_days' => 'x', 'hours_time' => 'y', 'emails_json' => json_encode([['value' => 'not-an-email']]),
        ])->assertSessionHasErrors();
    }

    public function test_an_active_popup_is_on_every_page(): void
    {
        SiteContent::create(['type' => 'popup', 'title' => 'Eid Offer 30% off', 'is_active' => true]);

        $this->get('/')->assertSee('Eid Offer 30% off')->assertSee('data-popup-key', false);
    }

    public function test_home_page_editor_saves_hero_and_hides_unticked_sections(): void
    {
        $this->actingAs($this->admin)->put('/admin/home-page', [
            'layout' => ['builtin:hero'],   // only the hero is ticked
            'eyebrow' => 'Grow your career',
            'title_before' => 'Learn',
            'title_highlight' => 'Skills',
            'title_after' => 'Get hired',
            'description' => 'Hands-on IT training.',
            'buttons_json' => json_encode([['label' => 'Join now', 'url' => '/admission-now']]),
            'slides_json' => json_encode([['image' => 'images/home/hero-slide-1.webp', 'alt' => 'Class']]),
            'stats_json' => json_encode([['icon' => 'bi-star', 'count' => '50', 'suffix' => '+', 'label' => 'Courses']]),
            'titles' => ['team_title' => 'Our Team'],
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
        $this->seed([CourseSeeder::class, HomeContentSeeder::class]);
        $member = TeamMember::first();

        $urls = [
            '/admin', '/admin/home-page', '/admin/media', '/admin/settings', '/admin/menus/header', '/admin/menus/footer', '/admin/courses', '/admin/courses/create',
            "/admin/home-content/team-members/{$member->id}/edit",
        ];
        foreach (['features', 'content-blocks', 'success-videos', 'testimonials', 'team-members', 'partners'] as $collection) {
            $urls[] = "/admin/home-content/{$collection}";
            $urls[] = "/admin/home-content/{$collection}/create";
        }
        foreach (['page', 'post', 'section', 'popup'] as $type) {
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
