<?php

namespace Tests\Feature;

use App\Models\SiteContent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoryManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_can_view_success_stories_and_case_studies(): void
    {
        $this->seed();

        // Create a Success Story
        $story = SiteContent::create([
            'section' => 'stories',
            'title' => 'Electrical Engineering Trainee Success',
            'slug' => 'electrical-engineering-trainee-success',
            'category' => 'Success Story',
            'excerpt' => 'Graduated and started electrical contracting business.',
            'body' => '<p>Full inspiring story details here.</p>',
            'status' => 'published',
            'sort_order' => 1,
        ]);

        // Create a Case Study
        $case = SiteContent::create([
            'section' => 'stories',
            'title' => 'Youth Employment Impact Study 2026',
            'slug' => 'youth-employment-impact-study-2026',
            'category' => 'Case Study',
            'excerpt' => 'Detailed research on trainee job retention.',
            'body' => '<p>Comprehensive case analysis.</p>',
            'status' => 'published',
            'sort_order' => 2,
        ]);

        // Public Success Stories page
        $resStories = $this->get('/success-stories');
        $resStories->assertOk()
            ->assertSee('Electrical Engineering Trainee Success')
            ->assertSee('Graduated and started electrical contracting business.')
            ->assertSee('Success Stories')
            ->assertSee('Case Studies');

        // Public Case Studies page
        $resCases = $this->get('/case-studies');
        $resCases->assertOk()
            ->assertSee('Youth Employment Impact Study 2026')
            ->assertSee('Detailed research on trainee job retention.')
            ->assertSee('Case Studies');

        // Public Story detail page
        $resDetail = $this->get('/stories/' . $story->slug);
        $resDetail->assertOk()
            ->assertSee('Electrical Engineering Trainee Success')
            ->assertSee('Full inspiring story details here.');

        // Public Case detail page
        $resCaseDetail = $this->get('/stories/' . $case->slug);
        $resCaseDetail->assertOk()
            ->assertSee('Youth Employment Impact Study 2026')
            ->assertSee('Comprehensive case analysis.');
    }

    public function test_admin_can_manage_stories_crud(): void
    {
        $this->seed();
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.local',
            'password' => 'Passw0rd!123',
            'is_admin' => true,
        ]);

        $this->actingAs($admin);

        // Access index page
        $this->get('/admin/content/stories')
            ->assertOk()
            ->assertSee('Success Stories & Case Studies');

        // Access create page
        $this->get('/admin/content/stories/create')
            ->assertOk()
            ->assertSee('Story Type')
            ->assertSee('Success Story')
            ->assertSee('Case Study');

        // Store new story
        $postRes = $this->post('/admin/content/stories', [
            'title' => 'Plumbing Trainee Goes Global',
            'category' => 'Success Story',
            'excerpt' => 'Works now in modern piping installations.',
            'body' => '<p>Plumbing trainee full journey details.</p>',
            'status' => 'published',
            'sort_order' => 1,
        ]);

        $postRes->assertRedirect(route('admin.content.index', 'stories'));

        $this->assertDatabaseHas('site_contents', [
            'section' => 'stories',
            'title' => 'Plumbing Trainee Goes Global',
            'category' => 'Success Story',
        ]);

        $item = SiteContent::where('title', 'Plumbing Trainee Goes Global')->first();
        $this->assertNotNull($item);

        // Edit page
        $this->get('/admin/content/stories/' . $item->id . '/edit')
            ->assertOk()
            ->assertSee('Plumbing Trainee Goes Global');

        // Update
        $this->put('/admin/content/stories/' . $item->id, [
            'title' => 'Plumbing Trainee Goes Global - Updated',
            'category' => 'Case Study',
            'excerpt' => 'Updated excerpt here.',
            'body' => '<p>Updated body.</p>',
            'status' => 'published',
            'sort_order' => 2,
        ])->assertRedirect(route('admin.content.index', 'stories'));

        $this->assertDatabaseHas('site_contents', [
            'id' => $item->id,
            'title' => 'Plumbing Trainee Goes Global - Updated',
            'category' => 'Case Study',
        ]);

        // Delete
        $this->from(route('admin.content.index', 'stories'))
            ->delete('/admin/content/stories/' . $item->id)
            ->assertRedirect(route('admin.content.index', 'stories'));

        $this->assertDatabaseMissing('site_contents', [
            'id' => $item->id,
        ]);
    }

    public function test_admin_can_live_edit_story_content(): void
    {
        $this->seed();
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@test.local',
            'password' => 'Passw0rd!123',
            'is_admin' => true,
        ]);

        $story = SiteContent::create([
            'section' => 'stories',
            'title' => 'Original Story Title',
            'slug' => 'original-story-title',
            'category' => 'Success Story',
            'excerpt' => 'Original excerpt',
            'body' => '<p>Original body</p>',
            'status' => 'published',
            'sort_order' => 1,
        ]);

        $this->actingAs($admin);

        // Live edit title
        $response = $this->postJson('/admin/live-edit/save-content', [
            'id' => $story->id,
            'field' => 'title',
            'value' => 'New Live Edited Title',
        ]);

        $response->assertOk()->assertJson(['success' => true]);

        $story->refresh();
        $this->assertEquals('New Live Edited Title', $story->title);

        // Live edit category
        $responseCat = $this->postJson('/admin/live-edit/save-content', [
            'id' => $story->id,
            'field' => 'category',
            'value' => 'Case Study',
        ]);

        $responseCat->assertOk()->assertJson(['success' => true]);

        $story->refresh();
        $this->assertEquals('Case Study', $story->category);
    }
}
