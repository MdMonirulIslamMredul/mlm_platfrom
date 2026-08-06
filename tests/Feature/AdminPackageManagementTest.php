<?php

namespace Tests\Feature;

use App\Models\Package;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPackageManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test admin can view packages list.
     */
    public function test_admin_can_view_packages_index()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get('/admin/packages');
        $response->assertStatus(200);
        $response->assertViewIs('admin.packages.index');
    }

    /**
     * Test admin and super_admin can view all users list.
     */
    public function test_admin_and_super_admin_can_access_all_users_page()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        $normalUser = User::factory()->create(['role' => 'user', 'name' => 'John Normal User']);

        $responseAdmin = $this->actingAs($admin)->get('/admin/users');
        $responseAdmin->assertStatus(200);
        $responseAdmin->assertSee('John Normal User');

        $responseSuperAdmin = $this->actingAs($superAdmin)->get('/admin/users');
        $responseSuperAdmin->assertStatus(200);
    }

    /**
     * Test admin can create a new package.
     */
    public function test_admin_can_create_package()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->post('/admin/packages', [
            'name' => 'Gold Package',
            'price' => 500.00,
            'cycle_days' => 60,
            'daily_return' => 25.00,
        ]);

        $response->assertRedirect('/admin/packages');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('packages', [
            'name' => 'Gold Package',
            'price' => 500.00,
            'cycle_days' => 60,
            'daily_return' => 25.00,
        ]);
    }

    /**
     * Test admin can delete a package.
     */
    public function test_admin_can_delete_package()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $package = Package::create([
            'name' => 'Silver Package',
            'price' => 250.00,
            'cycle_days' => 30,
            'daily_return' => 10.00,
        ]);

        $response = $this->actingAs($admin)->delete("/admin/packages/{$package->id}");

        $response->assertRedirect('/admin/packages');
        $this->assertDatabaseMissing('packages', [
            'id' => $package->id,
        ]);
    }
}
