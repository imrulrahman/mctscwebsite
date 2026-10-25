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
        // Homepage now features Hero Slider, About, Vision/Mission/Objectives, and Partners
        $this->get('/')->assertOk()
            ->assertSee('Vision')
            ->assertSee('Mission')
            ->assertSee('Objectives')
            ->assertSee('Development Partners');

        // Dedicated sub-pages host Courses and Projects
        $this->get('/courses')->assertOk()->assertSee('Our Training Courses')->assertSee('Computer Operation');
        $this->get('/projects')->assertOk()->assertSee('NHRDF');
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
        $response->assertSee('Vision');
        $response->assertSee('Mission');
        $response->assertSee('Objectives');
        $response->assertSee('Development Partners');
        $response->assertSee('EN</a>', false);
    }

    public function test_homepage_renders_in_bengali_when_switched(): void
    {
        $this->seed();
        $response = $this->get('/?lang=bn');
        $response->assertOk();
        $response->assertSee('রূপকল্প');
        $response->assertSee('লক্ষ্য');
        $response->assertSee('উদ্দেশ্যসমূহ');
        $response->assertSee('উন্নয়ন অংশীদারগণ');
        $response->assertSee('বাং</a>', false);
    }

    public function test_language_switch_route_persists_in_session(): void
    {
        $response = $this->get('/lang/bn');
        $response->assertRedirect();
        $response->assertSessionHas('locale', 'bn');

        $homeResponse = $this->withSession(['locale' => 'bn'])->get('/');
        $homeResponse->assertOk();
        $homeResponse->assertSee('রূপকল্প');
        $homeResponse->assertSee('লক্ষ্য');
        $homeResponse->assertSee('উদ্দেশ্যসমূহ');
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

        // 3. Verify Admin can view Application Show with A4 form and billing voucher
        $showResponse = $this->actingAs($admin)->get(route('admin.applications.show', $app));
        $showResponse->assertOk();
        $showResponse->assertSee('ভর্তি আবেদন ফরম (TRAINEE ADMISSION APPLICATION FORM)');
        $showResponse->assertSee('OFFICIAL MONEY RECEIPT & ADMISSION BILLING VOUCHER', false);
        $showResponse->assertSee('Paid Batch');
        $showResponse->assertSee('ECHO');
        $showResponse->assertSee('NHRDF');
        $showResponse->assertSee('ASSET');

        // 4. Verify 2-Part Official Payment Voucher print
        $voucherResponse = $this->actingAs($admin)->get(route('admin.applications.voucher', $app));
        $voucherResponse->assertOk();
        $voucherResponse->assertSee('STUDENT COPY / শিক্ষার্থী কপি');
        $voucherResponse->assertSee('OFFICE COPY / অফিস কপি');
        $voucherResponse->assertSee('OFFICIAL PAYMENT RECEIPT');
        $voucherResponse->assertSee($admin->name);

        // 5. Verify Duplicate Voucher print
        $dupResponse = $this->actingAs($admin)->get(route('admin.applications.voucher', [$app, 'duplicate' => 1]));
        $dupResponse->assertOk();
        $dupResponse->assertSee('DUPLICATE');

        // 6. Verify Search by Mobile Number
        $searchResponse = $this->actingAs($admin)->get(route('admin.applications', ['q' => $app->contact_no]));
        $searchResponse->assertOk();
        $searchResponse->assertSee($app->tracking_no);

        // 7. Verify Undo Payment
        $undoResponse = $this->actingAs($admin)->post(route('admin.applications.undo-payment', $app));
        $undoResponse->assertRedirect();
        $app->refresh();
        $this->assertEquals(0, (float)$app->paid_amount);
        $this->assertEquals('unpaid', $app->payment_status);
        $this->assertEquals('reviewing', $app->status);
        $this->assertNull($app->approved_at);
    }

    public function test_dedicated_site_pages_render_successfully(): void
    {
        $about = $this->get(route('about'))->assertOk()->assertSee('About');
        $about->assertSee('INTRODUCTION');
        $about->assertSee('Institute Core Values');
        $about->assertSee('Integrity and Accountability');
        $about->assertSee('Legal Status');
        $about->assertSee('70102');
        $about->assertSee('STP-CHA-000135');
        $about->assertDontSee('Founding Vision');
        $about->assertDontSee('Vision for the Future');
        $about->assertDontSee('Building Skills. Unlocking Potential. Shaping Futures.');
        $about->assertDontSee('Institutional Registration and Identification');
        $about->assertDontSee('Skills for a Better Tomorrow');
        $about->assertSee('Apply for Admission');
        $about->assertSee('Industry Partnerships for Employment and Career Development');
        $about->assertSee('Alert Fashion Ltd.');
        $about->assertSee('Hotel New View');
        $about->assertSee('1224');

        $infr = $this->get(route('infrastructure'))->assertOk();
        $infr->assertSee('Infrastructure');
        $infr->assertSee('Training Facilities');
        $infr->assertSee('Theory Classrooms');
        $infr->assertSee('Training Capacity at a Glance');
        $infr->assertSee('17,000 sq. ft.');

        $this->get(route('courses'))->assertOk()->assertSee('Courses');
        $this->get(route('projects'))->assertOk()->assertSee('Projects');
        $this->get(route('notices'))->assertOk()->assertSee('Notice');
        $this->get(route('gallery'))->assertOk()->assertSee('Gallery');
        $this->get(route('contact'))->assertOk()->assertSee('Contact');
        $this->get(route('admissions'))->assertRedirect(route('apply'));
    }

    public function test_notice_transparency_hero_banner_and_about_layout_settings(): void
    {
        $admin = User::first() ?? User::create([
            'name' => 'Settings Admin',
            'email' => 'settings_admin@test.local',
            'password' => 'Passw0rd!123',
            'is_admin' => true,
            'is_system_admin' => true,
            'is_active' => true,
        ]);

        // 1. Save Notice Bar transparency and color in admin settings
        $response = $this->actingAs($admin)->put(route('admin.settings.update'), [
            'institution_name' => 'Mahmudunnabi Chowdhury Technical School and College',
            'notice_bar_bg_color' => '#0f5132',
            'notice_bar_opacity' => '25',
            'notice_bar_text_color' => '#163d32',
        ]);
        $response->assertRedirect();

        // 2. Verify setting is persisted in DB
        $this->assertEquals('25', \App\Models\SiteSetting::where('key', 'notice_bar_opacity')->value('value'));

        // 3. Verify Homepage renders hero image slider, Institute Background section, and no homepage notice bar
        $homeResponse = $this->get(route('home'));
        $homeResponse->assertOk();
        $homeResponse->assertSee('hero-slider-wrap');
        $homeResponse->assertSee('aspect-ratio: 1920 / 720;');
        $homeResponse->assertSee('object-fit: cover !important;');
        $homeResponse->assertSee('Institute Background');
        $homeResponse->assertSee('About us');
        $homeResponse->assertDontSee('class="section-kicker">ABOUT US');
        $homeResponse->assertSee('Mahmudunnabi Chowdhury Technical School');
        $homeResponse->assertSee('Technical and Vocational Education and Training (TVET) institution');
        $homeResponse->assertSee('grid-template-columns: 1.25fr 0.95fr !important;');
        $homeResponse->assertDontSee('id="notices"');

        // 4. Verify Admin Settings page displays recommended resolution badges
        $settingsResponse = $this->actingAs($admin)->get(route('admin.settings'));
        $settingsResponse->assertOk();
        $settingsResponse->assertSee('📐 Recommended Resolution: 1920 × 720 px');
        $settingsResponse->assertSee('📐 Recommended Resolution: 1000 × 650 px');
        $settingsResponse->assertSee('📐 Recommended: 800 × 500 px');
    }

    public function test_admin_can_upload_custom_about_image_and_it_reflects_on_site(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        $admin = User::first() ?? User::create([
            'name' => 'Settings Admin',
            'email' => 'settings_admin@test.local',
            'password' => 'Passw0rd!123',
            'is_admin' => true,
            'is_system_admin' => true,
            'is_active' => true,
        ]);

        $fakeImage = \Illuminate\Http\UploadedFile::fake()->image('custom_campus_about.jpg', 1200, 800);

        $response = $this->actingAs($admin)->put(route('admin.settings.update'), [
            'about_image' => $fakeImage,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $savedPath = \App\Models\SiteSetting::where('key', 'about_image')->value('value');
        $this->assertNotEmpty($savedPath);
        $this->assertStringStartsWith('settings/', $savedPath);

        // Check About page
        $aboutResponse = $this->get(route('about'));
        $aboutResponse->assertOk();
        $aboutResponse->assertSee($savedPath);

        // Check Home page
        $homeResponse = $this->get(route('home'));
        $homeResponse->assertOk();
        $homeResponse->assertSee($savedPath);
    }

    public function test_about_us_nav_link_is_single_line_with_nowrap(): void
    {
        $homeResponse = $this->get(route('home'));
        $homeResponse->assertOk();
        $homeResponse->assertSee('style="white-space: nowrap;"', false);
        $homeResponse->assertSee('white-space: nowrap !important;', false);

        $aboutResponse = $this->get(route('about'));
        $aboutResponse->assertOk();
        $aboutResponse->assertSee('style="white-space: nowrap;"', false);
        $aboutResponse->assertSee('white-space: nowrap !important;', false);
    }

    public function test_admin_can_manage_development_partners_full_crud(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        $admin = User::first() ?? User::create([
            'name' => 'Partners Admin',
            'email' => 'partners_admin@test.local',
            'password' => 'Passw0rd!123',
            'is_admin' => true,
            'is_system_admin' => true,
            'is_active' => true,
        ]);

        // 1. View partners index
        $indexResponse = $this->actingAs($admin)->get(route('admin.content.index', 'partners'));
        $indexResponse->assertOk();
        $indexResponse->assertSee('Development Partners');
        $indexResponse->assertSee('＋ Add Partner');

        // 2. Create a new partner
        $fakeLogo = \Illuminate\Http\UploadedFile::fake()->image('global_aid_logo.png', 240, 80);
        $createResponse = $this->actingAs($admin)->post(route('admin.content.store', 'partners'), [
            'title' => 'Global Aid Alliance',
            'category' => 'Youth Vocational Skills',
            'excerpt' => 'https://globalaid.org',
            'image' => $fakeLogo,
            'sort_order' => 12,
            'status' => 'published',
        ]);
        $createResponse->assertRedirect(route('admin.content.index', 'partners'));
        $createResponse->assertSessionHas('success');

        $this->assertDatabaseHas('site_contents', [
            'section' => 'partners',
            'title' => 'Global Aid Alliance',
            'category' => 'Youth Vocational Skills',
        ]);

        $createdPartner = \App\Models\SiteContent::where('section', 'partners')->where('title', 'Global Aid Alliance')->first();
        $this->assertNotNull($createdPartner);

        // 3. Verify it shows on homepage marquee
        $homeResponse = $this->get(route('home'));
        $homeResponse->assertOk();
        $homeResponse->assertSee('Global Aid Alliance');
        $homeResponse->assertSee('https://globalaid.org');

        // 4. Edit the partner
        $editResponse = $this->actingAs($admin)->put(route('admin.content.update', ['section' => 'partners', 'content' => $createdPartner]), [
            'title' => 'Global Aid Alliance International',
            'category' => 'TVET Empowerment Project',
            'excerpt' => 'https://globalaid.org/tvet',
            'sort_order' => 15,
            'status' => 'published',
        ]);
        $editResponse->assertRedirect(route('admin.content.index', 'partners'));
        $this->assertDatabaseHas('site_contents', [
            'section' => 'partners',
            'title' => 'Global Aid Alliance International',
            'category' => 'TVET Empowerment Project',
        ]);

        // 5. Delete the partner
        $deleteResponse = $this->actingAs($admin)->delete(route('admin.content.destroy', ['section' => 'partners', 'content' => $createdPartner]));
        $deleteResponse->assertRedirect();
        $this->assertDatabaseMissing('site_contents', [
            'section' => 'partners',
            'title' => 'Global Aid Alliance International',
        ]);
    }

    public function test_admin_can_manage_hero_slides_full_crud_and_fixed_mode(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        $admin = User::first() ?? User::create([
            'name' => 'Slider Admin',
            'email' => 'slider_admin@test.local',
            'password' => 'Passw0rd!123',
            'is_admin' => true,
            'is_system_admin' => true,
            'is_active' => true,
        ]);

        // 1. View hero slides index
        $indexResponse = $this->actingAs($admin)->get(route('admin.content.index', 'hero_slides'));
        $indexResponse->assertOk();
        $indexResponse->assertSee('Hero Slider Banners');
        $indexResponse->assertSee('＋ Add Slide');

        // 2. Add a new 4th slide
        $fakeBanner = \Illuminate\Http\UploadedFile::fake()->image('custom_smart_lab.jpg', 1920, 720);
        $createResponse = $this->actingAs($admin)->post(route('admin.content.store', 'hero_slides'), [
            'title' => 'Smart Industry 4.0 Lab',
            'category' => 'High Tech',
            'image' => $fakeBanner,
            'sort_order' => 4,
            'status' => 'published',
        ]);
        $createResponse->assertRedirect(route('admin.content.index', 'hero_slides'));
        $createResponse->assertSessionHas('success');

        $this->assertDatabaseHas('site_contents', [
            'section' => 'hero_slides',
            'title' => 'Smart Industry 4.0 Lab',
        ]);

        $createdSlide = \App\Models\SiteContent::where('section', 'hero_slides')->where('title', 'Smart Industry 4.0 Lab')->first();
        $this->assertNotNull($createdSlide);

        // 3. Verify it shows on homepage
        $homeResponse = $this->get(route('home'));
        $homeResponse->assertOk();
        $homeResponse->assertSee('Smart Industry 4.0 Lab');
        $homeResponse->assertSee('class="hero-slider-btn hero-slider-prev"', false);

        // 4. Edit slide
        $editResponse = $this->actingAs($admin)->put(route('admin.content.update', ['section' => 'hero_slides', 'content' => $createdSlide]), [
            'title' => 'Advanced Robotics Innovation Suite',
            'category' => 'Specialized Lab',
            'sort_order' => 1,
            'status' => 'published',
        ]);
        $editResponse->assertRedirect(route('admin.content.index', 'hero_slides'));
        $this->assertDatabaseHas('site_contents', [
            'section' => 'hero_slides',
            'title' => 'Advanced Robotics Innovation Suite',
        ]);

        // 5. Test Fixed Mode: Admin sets hero_slider_mode = 'fixed'
        $settingsResponse = $this->actingAs($admin)->put(route('admin.settings.update'), [
            'hero_slider_mode' => 'fixed',
            'hero_slider_fixed_id' => (string)$createdSlide->id,
        ]);
        $settingsResponse->assertRedirect();
        $this->assertEquals('fixed', \App\Models\SiteSetting::where('key', 'hero_slider_mode')->value('value'));

        // Verify Homepage now renders only the fixed slide and NO slider controls
        $fixedHomeResponse = $this->get(route('home'));
        $fixedHomeResponse->assertOk();
        $fixedHomeResponse->assertSee('Advanced Robotics Innovation Suite');
        $fixedHomeResponse->assertDontSee('class="hero-slider-btn hero-slider-prev"', false);
        $fixedHomeResponse->assertDontSee('class="hero-slider-dots"', false);

        // 6. Delete slide and restore slide mode
        $deleteResponse = $this->actingAs($admin)->delete(route('admin.content.destroy', ['section' => 'hero_slides', 'content' => $createdSlide]));
        $deleteResponse->assertRedirect();
        $this->assertDatabaseMissing('site_contents', [
            'section' => 'hero_slides',
            'title' => 'Advanced Robotics Innovation Suite',
        ]);

        // Restore to slide mode
        \App\Models\SiteSetting::updateOrCreate(['key' => 'hero_slider_mode'], ['value' => 'slide']);
    }
}
