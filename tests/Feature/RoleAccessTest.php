<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);
    }

    public function test_user_cannot_access_admin_panel(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        $this->actingAs($user)->get('/admin')->assertForbidden();
    }

    public function test_partner_cannot_access_user_document_area(): void
    {
        $partner = User::factory()->create();
        $partner->assignRole('partner');

        $this->actingAs($partner)->get('/app')->assertForbidden();
    }

    public function test_admin_can_access_pending_verifications_panel(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin)->get('/admin')->assertOk();
    }

    public function test_login_redirects_each_profile_to_its_area(): void
    {
        $user = User::factory()->create(['email' => 'user@example.com']);
        $partner = User::factory()->create(['email' => 'partner@example.com']);
        $admin = User::factory()->create(['email' => 'admin@example.com']);

        $user->assignRole('user');
        $partner->assignRole('partner');
        $admin->assignRole('admin');

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect(route('app.dashboard', absolute: false));
        $this->post('/logout');

        $this->post('/login', ['email' => $partner->email, 'password' => 'password'])
            ->assertRedirect(route('partner.dashboard', absolute: false));
        $this->post('/logout');

        $this->post('/login', ['email' => $admin->email, 'password' => 'password'])
            ->assertRedirect(route('admin.dashboard', absolute: false));
    }
}
