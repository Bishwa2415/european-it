<?php

namespace Tests\Feature;

use App\Models\LandingPage;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Course landing pages (/{slug}), the webinar form, and the dashboard editor.
 * Run: php artisan test --filter=LandingPageTest
 */
class LandingPageTest extends TestCase
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

    private function page(array $content = [], string $type = 'online'): LandingPage
    {
        return LandingPage::create([
            'title' => 'Web design – Online', 'slug' => 'online-web-design', 'type' => $type, 'is_active' => true,
            'content' => $content + [
                'settings' => ['countdown_hours' => 72, 'enroll_url' => '/admission-now'],
                'hero' => ['show' => true, 'title' => 'Web Design & Development', 'subtitle' => 'Online Course', 'button_label' => 'Enroll Now'],
                'webinar' => ['show' => true, 'title' => 'Free webinar', 'form_title' => 'Book a seat', 'button_label' => 'Book Now'],
                'faq' => ['show' => true, 'title' => 'FAQ', 'items' => [['question' => 'Is there a recording?', 'answer' => 'Yes, for life.']]],
                'gifts' => ['show' => false, 'title' => 'Hidden gift section'],
            ],
        ]);
    }

    public function test_a_landing_page_opens_at_its_slug_with_its_sections(): void
    {
        $this->page();

        $this->get('/online-web-design')->assertOk()
            ->assertSee('Web Design &amp; Development', false)
            ->assertSee('Is there a recording?')
            ->assertSee('data-countdown="72"', false)
            ->assertDontSee('Hidden gift section');   // switched off
    }

    public function test_a_hidden_landing_page_is_not_found(): void
    {
        $this->page()->update(['is_active' => false]);

        $this->get('/online-web-design')->assertNotFound();
    }

    public function test_the_webinar_form_saves_a_lead_and_refuses_bad_phones_and_bots(): void
    {
        $page = $this->page();

        $this->post("/landing/{$page->id}/lead", ['name' => 'Rahim', 'phone' => '01712345678'])
            ->assertRedirect(url('online-web-design').'#webinar')->assertSessionHas('lead_saved');
        $this->assertDatabaseHas('leads', ['name' => 'Rahim', 'phone' => '01712345678', 'landing_page_id' => $page->id]);

        $this->post("/landing/{$page->id}/lead", ['name' => 'X', 'phone' => 'call me'])->assertSessionHasErrors('phone');

        // Hidden "website" field filled → a bot: nothing saved
        $this->post("/landing/{$page->id}/lead", ['name' => 'Bot', 'phone' => '01700000000', 'website' => 'spam.com']);
        $this->assertSame(1, Lead::count());
    }

    public function test_admin_edits_sections_and_sees_leads(): void
    {
        $page = $this->page();
        Lead::create(['landing_page_id' => $page->id, 'name' => 'Karim', 'phone' => '01811111111']);

        $this->actingAs($this->admin)->get('/admin/landing-pages')->assertOk()->assertSee('online-web-design');
        $this->actingAs($this->admin)->get("/admin/landing-pages/{$page->id}/edit")->assertOk()->assertSee('data-tab="faq"', false);
        $this->actingAs($this->admin)->get('/admin/leads')->assertOk()->assertSee('Karim');
        $this->actingAs($this->admin)->get('/admin/leads/export')->assertOk()->assertHeader('content-type', 'text/csv; charset=UTF-8');

        $this->actingAs($this->admin)->put("/admin/landing-pages/{$page->id}", [
            'title' => 'Web design – Online', 'slug' => 'online-web-design', 'type' => 'offline', 'is_active' => '1',
            'settings' => ['countdown_hours' => 24],
            'content' => [
                'hero' => ['show' => '1', 'title' => 'New hero title', 'video_source' => 'youtube'],
                'faq' => ['show' => '1', 'title' => 'Questions'],
                'labs' => ['show' => '1', 'title' => 'Our labs'],
            ],
            'lists' => [
                'faq' => ['items' => json_encode([['question' => 'Weekend batch?', 'answer' => 'Yes']])],
                'labs' => ['images' => json_encode([['image' => 'media/lab.jpg']])],
            ],
        ])->assertSessionHasNoErrors();

        $this->get('/online-web-design')->assertOk()
            ->assertSee('New hero title')->assertSee('Weekend batch?')
            ->assertSee('Our labs')                        // offline design shows the labs gallery
            ->assertSee('data-countdown="24"', false)
            ->assertDontSee('Free webinar');               // webinar was not switched on in the form
    }

    public function test_landing_slug_cannot_take_a_reserved_url(): void
    {
        $this->actingAs($this->admin)->post('/admin/landing-pages', ['title' => 'Admin', 'slug' => 'admin', 'type' => 'online'])
            ->assertSessionHasErrors('slug');
    }
}
