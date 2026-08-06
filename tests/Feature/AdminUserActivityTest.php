<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserActivityTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_user_activity_page()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $referrer = User::factory()->create(['name' => 'Leader Parent']);
        $targetUser = User::factory()->create([
            'name' => 'Target Active User',
            'referred_by' => $referrer->id,
            'balance' => 150.00,
            'total_refer_bonus' => 25.00,
        ]);

        // Create 2 team members for target user
        $teamMember1 = User::factory()->create([
            'name' => 'Child Member One',
            'referred_by' => $targetUser->id,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.users.activity', $targetUser));

        $response->assertStatus(200);
        $response->assertSee('User Activity Report');
        $response->assertSee('Target Active User');
        $response->assertSee('Child Member One');
        $response->assertSee(route('admin.users.activity', $teamMember1));
    }
}
