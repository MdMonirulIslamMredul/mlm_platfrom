<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_their_profile_and_password(): void
    {
        $admin = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'admin@example.com',
            'phone' => '1234567890',
            'role' => 'admin',
            'password' => bcrypt('old-password'),
        ]);

        $response = $this->actingAs($admin)
            ->put(route('admin.profile.update'), [
                'name' => 'New Name',
                'email' => 'new-admin@example.com',
                'phone' => '9876543210',
                'current_password' => 'old-password',
                'new_password' => 'new-password123',
                'new_password_confirmation' => 'new-password123',
            ]);

        $response->assertRedirect(route('admin.profile.edit'));
        $response->assertSessionHas('success', 'Profile updated successfully!');

        $admin->refresh();
        $this->assertEquals('New Name', $admin->name);
        $this->assertEquals('new-admin@example.com', $admin->email);
        $this->assertEquals('9876543210', $admin->phone);
        $this->assertTrue(password_verify('new-password123', $admin->password));
    }
}
