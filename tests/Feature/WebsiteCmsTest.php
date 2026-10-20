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

    public function test_admission_approval_workflow_assigns_project_and_calculates_fees(): void
    {
        $admin = User::create([
            'name' => 'Approver Admin',
            'email' => 'approver@test.local',
            'password' => 'Passw0rd!123',
            'is_admin' => true,
        ]);

        // 1. Public student submits application (No project or fee selected)
        $this->post('/apply', [
            'name' => 'Karim Rahman',
            'trainee_name_en' => 'Karim Rahman',
            'contact_no' => '01811223344',
            'phone' => '01811223344',
            'course' => 'Computer Operation',
        ])->assertRedirect();

        $app = \App\Models\AdmissionApplication::where('contact_no', '01811223344')->firstOrFail();
        $this->assertEquals('new', $app->status);
        $this->assertNull($app->project_name);
        $this->assertEquals(0, (float)$app->payable_amount);

        // 2. Admin approves and assigns project (e.g. ECHO at ৳500)
        $response = $this->actingAs($admin)->post(route('admin.applications.approve', $app), [
            'project_name' => 'ECHO',
            'course_fee' => 500,
            'discount' => 100,
            'paid_amount' => 400,
            'payment_method' => 'bKash',
            'payment_trx_id' => 'BK123456',
        ]);

        $response->assertRedirect();
        $app->refresh();

        $this->assertEquals('accepted', $app->status);
        $this->assertEquals('ECHO', $app->project_name);
        $this->assertEquals(500, (float)$app->course_fee);
        $this->assertEquals(100, (float)$app->discount);
        $this->assertEquals(400, (float)$app->payable_amount);
        $this->assertEquals(400, (float)$app->paid_amount);
        $this->assertEquals('paid', $app->payment_status);
        $this->assertEquals($admin->id, $app->approved_by);
    }
}
