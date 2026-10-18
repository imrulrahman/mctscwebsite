<?php

namespace Tests\Feature;

use App\Models\AdmissionApplication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebsiteCmsTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_homepage_shows_seeded_site_content(): void
    {
        $this->seed();
        $this->get('/')->assertOk()->assertSee('Our Training Courses')->assertSee('Computer Operation')->assertSee('NHRDF');
    }

    public function test_admin_dashboard_requires_administrator_login(): void
    {
        $this->get('/admin')->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_sign_in_and_view_dashboard(): void
    {
        User::create(['name' => 'Site Admin', 'email' => 'admin@test.local', 'password' => 'Passw0rd!123', 'is_admin' => true]);
        $this->post('/admin/login', ['email' => 'admin@test.local', 'password' => 'Passw0rd!123'])->assertRedirect(route('admin.dashboard'));
        $this->get('/admin')->assertOk()->assertSee('Manage your college website content');
    }

    public function test_public_admission_form_persists_submission(): void
    {
        $this->post('/apply', ['name' => 'Test Applicant', 'phone' => '01700000000', 'course' => 'Computer Operation'])->assertRedirect();
        $this->assertDatabaseHas('admission_applications', ['name' => 'Test Applicant', 'status' => 'new']);
    }

    public function test_admin_can_save_colors_via_apply_now_button(): void
    {
        $admin = User::create(['name' => 'Admin User', 'email' => 'admin_test@test.local', 'password' => 'Passw0rd!123', 'is_admin' => true]);

        $response = $this->actingAs($admin)->put(route('admin.settings.update'), [
            'action' => 'apply_color',
            'primary_color' => '#881337',
            'primary_hover' => '#4c0519',
            'secondary_color' => '#d97706',
            'secondary_hover' => '#b45309',
        ]);

        $response->assertRedirect(route('admin.settings') . '#theme-section');
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('site_settings', ['key' => 'primary_color', 'value' => '#881337']);
    }

    public function test_admin_can_save_all_settings_via_save_all_button(): void
    {
        $admin = User::create(['name' => 'Admin User 2', 'email' => 'admin_test2@test.local', 'password' => 'Passw0rd!123', 'is_admin' => true]);

        $response = $this->actingAs($admin)->put(route('admin.settings.update'), [
            'institution_name' => 'MCTSC Updated Name',
            'primary_color' => '#1e3a8a',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('site_settings', ['key' => 'institution_name', 'value' => 'MCTSC Updated Name']);
    }

    public function test_homepage_renders_in_english_by_default(): void
    {
        $this->seed();
        $response = $this->get('/');
        $response->assertOk();
        $response->assertSee('Our Training Courses');
        $response->assertSee('Online Trainee Admission');
        $response->assertDontSee('অনলাইন ভর্তি আবেদন (Trainee Admission)');
        $response->assertSee('EN</a>', false);
    }

    public function test_homepage_renders_in_bengali_when_switched(): void
    {
        $this->seed();
        $response = $this->get('/?lang=bn');
        $response->assertOk();
        $response->assertSee('আমাদের ট্রেইনিং কোর্সসমূহ');
        $response->assertSee('অনলাইন ট্রেইনী এডমিশন আবেদন');
        $response->assertSee('একাডেমিক রেজাল্ট');
        $response->assertSee('বাং</a>', false);
    }

    public function test_language_switch_route_persists_in_session(): void
    {
        $response = $this->get('/lang/bn');
        $response->assertRedirect();
        $response->assertSessionHas('locale', 'bn');

        $homeResponse = $this->withSession(['locale' => 'bn'])->get('/');
        $homeResponse->assertOk();
        $homeResponse->assertSee('আমাদের ট্রেইনিং কোর্সসমূহ');
        $homeResponse->assertSee('অনলাইন ট্রেইনী এডমিশন আবেদন');
    }
}
