<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_login_page()
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_authenticated_users_can_visit_the_dashboard()
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->actingAs($user = User::factory()->create());
        $user->assignRole('user');

        $this->get('/dashboard')->assertRedirect(route('app.dashboard', absolute: false));
    }
}
