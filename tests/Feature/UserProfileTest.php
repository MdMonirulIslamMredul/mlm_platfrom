<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_profile_page()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('user.profile'));

        $response->assertStatus(200);
        $response->assertSee('Edit Profile Details');
    }

    public function test_user_can_update_profile_details()
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
        ]);

        $response = $this->actingAs($user)->post(route('user.profile.update'), [
            'name' => 'New Updated Name',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Profile details updated successfully!');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'New Updated Name',
        ]);
    }

    public function test_user_can_change_password()
    {
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword123'),
        ]);

        $response = $this->actingAs($user)->post(route('user.profile.password'), [
            'current_password' => 'oldpassword123',
            'password' => 'newsecretpassword123',
            'password_confirmation' => 'newsecretpassword123',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Password changed successfully!');
        $this->assertTrue(Hash::check('newsecretpassword123', $user->fresh()->password));
    }
}
