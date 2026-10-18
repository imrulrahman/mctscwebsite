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
}
