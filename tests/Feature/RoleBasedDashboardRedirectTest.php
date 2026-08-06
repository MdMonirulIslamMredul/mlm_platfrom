<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleBasedDashboardRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_when_accessing_dashboard()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect(route('login'));
    }

    public function test_regular_user_is_redirected_to_frontend_dashboard()
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertViewIs('frontend.dashboard');
    }

    public function test_admin_is_redirected_to_admin_dashboard()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get('/dashboard');
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_super_admin_is_redirected_to_admin_dashboard()
    {
        $superAdmin = User::factory()->create([
            'role' => 'super_admin',
        ]);

        $response = $this->actingAs($superAdmin)->get('/dashboard');
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_authenticated_user_accessing_login_page_is_redirected()
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $response = $this->actingAs($user)->get('/login');
        $response->assertRedirect(route('dashboard'));

        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $adminResponse = $this->actingAs($admin)->get('/login');
        $adminResponse->assertRedirect(route('admin.dashboard'));
    }
}
