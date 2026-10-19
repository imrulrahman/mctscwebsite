<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use App\Services\PermissionManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserAndRoleManagementTest extends TestCase
{
    use RefreshDatabase;

    private function createSystemAdmin(): User
    {
        $role = Role::create([
            'name' => 'System Admin',
            'slug' => 'system-admin',
            'is_system' => true,
            'permissions' => PermissionManager::defaultRolePermissions('system-admin'),
        ]);

        return User::create([
            'name' => 'Root System Admin',
            'email' => 'sysadmin@test.local',
            'password' => Hash::make('Secret123!'),
            'is_admin' => true,
            'role_id' => $role->id,
            'status' => 'active',
        ]);
    }

    public function test_system_admin_can_view_and_create_roles(): void
    {
        $admin = $this->createSystemAdmin();

        $this->actingAs($admin)->get(route('admin.roles.index'))
            ->assertOk()
            ->assertSee('System Admin');

        $response = $this->actingAs($admin)->post(route('admin.roles.store'), [
            'name' => 'Custom Support Officer',
            'slug' => 'support-officer',
            'description' => 'Handles support queries and notices',
            'permissions' => ['notices.manage', 'messages.view'],
        ]);

        $response->assertRedirect(route('admin.roles.index'));
        $this->assertDatabaseHas('roles', [
            'name' => 'Custom Support Officer',
            'slug' => 'support-officer',
        ]);
    }

    public function test_system_admin_can_create_user_and_assign_role(): void
    {
        $admin = $this->createSystemAdmin();

        $editorRole = Role::create([
            'name' => 'Editor Role',
            'slug' => 'editor-role',
            'is_system' => false,
            'permissions' => ['notices.manage', 'events.manage'],
        ]);

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'John Editor',
            'email' => 'john@test.local',
            'phone' => '+8801700000000',
            'role_id' => $editorRole->id,
            'status' => 'active',
            'password' => 'Editor123!',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'name' => 'John Editor',
            'email' => 'john@test.local',
            'role_id' => $editorRole->id,
        ]);
    }

    public function test_user_without_permission_is_forbidden_from_users_and_roles(): void
    {
        $restrictedRole = Role::create([
            'name' => 'Simple Staff',
            'slug' => 'simple-staff',
            'is_system' => false,
            'permissions' => ['messages.view'],
        ]);

        $staff = User::create([
            'name' => 'Staff Member',
            'email' => 'staff@test.local',
            'password' => Hash::make('Staff123!'),
            'role_id' => $restrictedRole->id,
            'is_admin' => false,
            'status' => 'active',
        ]);

        $this->actingAs($staff)->get(route('admin.users.index'))->assertForbidden();
        $this->actingAs($staff)->get(route('admin.roles.index'))->assertForbidden();
    }

    public function test_individual_user_permission_override_grants_and_revokes_access(): void
    {
        $this->createSystemAdmin();

        $admissionRole = Role::create([
            'name' => 'Admission Staff',
            'slug' => 'admission-staff',
            'is_system' => false,
            'permissions' => ['admissions.view', 'admissions.create'],
        ]);

        $user = User::create([
            'name' => 'Officer Rahim',
            'email' => 'rahim@test.local',
            'password' => Hash::make('Rahim123!'),
            'role_id' => $admissionRole->id,
            'status' => 'active',
            'is_admin' => false,
        ]);

        // By default, cannot manage site settings
        $this->actingAs($user)->get(route('admin.settings'))->assertForbidden();

        // System Admin grants individual custom permission: settings.manage
        $admin = User::where('email', 'sysadmin@test.local')->first();
        $this->actingAs($admin)->put(route('admin.users.permissions.update', $user), [
            'permissions' => ['admissions.view', 'admissions.create', 'settings.manage'],
        ])->assertRedirect();

        $user->refresh();
        $this->assertTrue($user->hasPermission('settings.manage'));

        // Now Officer Rahim CAN access settings!
        $this->actingAs($user)->get(route('admin.settings'))->assertOk();

        // Now Admin revokes admissions.create individually
        $this->actingAs($admin)->put(route('admin.users.permissions.update', $user), [
            'permissions' => ['admissions.view', 'settings.manage'], // admissions.create omitted -> revoked
        ])->assertRedirect();

        $user->refresh();
        $this->assertFalse($user->hasPermission('admissions.create'));

        // Reset to role defaults
        $this->actingAs($admin)->post(route('admin.users.permissions.reset', $user))->assertRedirect();
        $user->refresh();
        $this->assertNull($user->custom_permissions);
        $this->assertTrue($user->hasPermission('admissions.create')); // restored from role
        $this->assertFalse($user->hasPermission('settings.manage')); // revoked back to role default
    }

    public function test_logged_in_user_can_edit_profile_and_change_password(): void
    {
        $admin = $this->createSystemAdmin();

        // View Profile
        $this->actingAs($admin)->get(route('admin.profile.edit'))
            ->assertOk()
            ->assertSee('My Profile & Security');

        // Update Profile
        $this->actingAs($admin)->put(route('admin.profile.update'), [
            'name' => 'Updated Admin Name',
            'email' => 'newemail@test.local',
            'phone' => '+8801999999999',
        ])->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
            'name' => 'Updated Admin Name',
            'email' => 'newemail@test.local',
        ]);

        // Change Password with wrong current password should fail
        $this->actingAs($admin)->put(route('admin.profile.password'), [
            'current_password' => 'WrongPassword!',
            'password' => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!',
        ])->assertSessionHasErrors('current_password');

        // Change Password with correct current password
        $this->actingAs($admin)->put(route('admin.profile.password'), [
            'current_password' => 'Secret123!',
            'password' => 'NewPassword123!',
            'password_confirmation' => 'NewPassword123!',
        ])->assertRedirect()->assertSessionHas('password_success');

        $admin->refresh();
        $this->assertTrue(Hash::check('NewPassword123!', $admin->password));
    }

    public function test_cannot_delete_self_or_last_system_admin(): void
    {
        $admin = $this->createSystemAdmin();

        // Attempt deleting self
        $this->actingAs($admin)->delete(route('admin.users.destroy', $admin))
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_can_upload_and_remove_avatar(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        $admin = $this->createSystemAdmin();

        $file = \Illuminate\Http\UploadedFile::fake()->image('test_avatar.jpg', 120, 120);

        // Upload avatar via profile update
        $this->actingAs($admin)->put(route('admin.profile.update'), [
            'name' => 'Root System Admin',
            'email' => 'sysadmin@test.local',
            'avatar' => $file,
        ])->assertRedirect();

        $admin->refresh();
        $this->assertNotNull($admin->avatar);
        $this->assertNotNull($admin->avatarUrl());
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($admin->avatar);

        // Remove avatar
        $this->actingAs($admin)->put(route('admin.profile.update'), [
            'name' => 'Root System Admin',
            'email' => 'sysadmin@test.local',
            'remove_avatar' => '1',
        ])->assertRedirect();

        $admin->refresh();
        $this->assertNull($admin->avatar);
        $this->assertNull($admin->avatarUrl());
    }
}
