<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserHistoryPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_access_plans_page()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('user.plans'));

        $response->assertStatus(200);
        $response->assertSee('Total Investment Plans');
    }

    public function test_user_can_access_team_withdrawals_page()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('user.team-withdrawals'));

        $response->assertStatus(200);
        $response->assertSee('Team Total Withdrawals');
    }

    public function test_user_can_access_referral_bonus_page()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('user.referral-bonus'));

        $response->assertStatus(200);
        $response->assertSee('Total Refer Bonus');
    }

    public function test_user_can_access_team_invest_page()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('user.team-invest'));

        $response->assertStatus(200);
        $response->assertSee('Total Team Invest');
    }

    public function test_user_can_access_team_page()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('user.team'));

        $response->assertStatus(200);
        $response->assertSee('Total Refer Count');
    }

    public function test_user_can_access_packages_page()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('user.packages'));

        $response->assertStatus(200);
        $response->assertSee('Packages &amp; Products', false);
    }

    public function test_user_can_access_history_page()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('user.history'));

        $response->assertStatus(200);
        $response->assertSee('Account History');
    }
}
