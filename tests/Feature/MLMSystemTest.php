<?php

namespace Tests\Feature;

use App\Models\Investment;
use App\Models\Package;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MLMSystemTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test registration page loads successfully.
     */
    public function test_registration_page_loads_successfully()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertViewIs('auth.register');
    }

    /**
     * Test registration allows optional invite code and auto-generates referral code.
     */
    public function test_user_registration_allows_optional_invite_code_and_sets_up_referral()
    {
        // 1. Register first user without invite_code (should succeed)
        $responseFirstUser = $this->postJson('/register', [
            'name' => 'First User',
            'email' => 'first@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $responseFirstUser->assertStatus(200);

        $firstUser = User::where('email', 'first@example.com')->first();
        $this->assertNotNull($firstUser);
        $this->assertNull($firstUser->referred_by);
        $this->assertNotNull($firstUser->referral_code);

        // 2. Register second user using first user's referral_code as invite_code
        $responseSecondUser = $this->postJson('/register', [
            'name' => 'Second User',
            'email' => 'second@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'invite_code' => $firstUser->referral_code,
        ]);

        $responseSecondUser->assertStatus(200);

        $secondUser = User::where('email', 'second@example.com')->first();
        $this->assertNotNull($secondUser);
        $this->assertEquals($firstUser->id, $secondUser->referred_by);
        $this->assertNotNull($secondUser->referral_code);
        $this->assertEquals(strtoupper($secondUser->referral_code), $secondUser->referral_code);
    }

    /**
     * Test registration rejects duplicate email or phone number.
     */
    public function test_registration_rejects_duplicate_email_or_phone()
    {
        User::factory()->create([
            'email' => 'existing@example.com',
            'phone' => '01575708136',
        ]);

        // 1. Attempt registering with same email
        $responseEmail = $this->postJson('/register', [
            'name' => 'User Duplicate Email',
            'email' => 'existing@example.com',
            'phone' => '01999999999',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $responseEmail->assertStatus(422);
        $responseEmail->assertJsonValidationErrors(['email']);

        // 2. Attempt registering with same phone
        $responsePhone = $this->postJson('/register', [
            'name' => 'User Duplicate Phone',
            'email' => 'unique_new@example.com',
            'phone' => '01575708136',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $responsePhone->assertStatus(422);
        $responsePhone->assertJsonValidationErrors(['phone']);
    }

    /**
     * Test registration rejects invalid Bangladesh phone number format.
     */
    public function test_registration_rejects_invalid_bangladesh_phone_number_format()
    {
        // 1. Invalid phone digits count (less than 11 digits)
        $responseShort = $this->postJson('/register', [
            'name' => 'Short Phone',
            'email' => 'valid1@example.com',
            'phone' => '01712345',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $responseShort->assertStatus(422);
        $responseShort->assertJsonValidationErrors(['phone']);

        // 2. Phone not starting with 01
        $responsePrefix = $this->postJson('/register', [
            'name' => 'Wrong Prefix',
            'email' => 'valid2@example.com',
            'phone' => '02712345678',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $responsePrefix->assertStatus(422);
        $responsePrefix->assertJsonValidationErrors(['phone']);
    }

    /**
     * Test package purchase fails when balance is insufficient.
     */
    public function test_package_purchase_fails_due_to_insufficient_balance()
    {
        $user = User::factory()->create([
            'balance' => 50,
            'referral_code' => 'USER1111',
        ]);

        $package = Package::create([
            'name' => 'Starter Pack',
            'price' => 100,
            'cycle_days' => 30,
            'daily_return' => 5,
        ]);

        $response = $this->actingAs($user)->postJson('/packages/buy', [
            'package_id' => $package->id,
        ]);

        $response->assertStatus(400);
        $this->assertEquals(50, $user->fresh()->balance);
    }

    /**
     * Test package purchase succeeds, deducts balance, creates investment & transaction, and distributes 10% referral bonus to referrer.
     */
    public function test_package_purchase_distributes_10_percent_referral_bonus()
    {
        $referrer = User::factory()->create([
            'referral_code' => 'PARENT01',
            'balance' => 0,
            'total_refer_bonus' => 0,
        ]);

        $buyer = User::factory()->create([
            'referral_code' => 'BUYER001',
            'referred_by' => $referrer->id,
            'balance' => 500,
            'total_refer_bonus' => 0,
        ]);

        $package = Package::create([
            'name' => 'Pro Pack',
            'price' => 200,
            'cycle_days' => 30,
            'daily_return' => 10,
        ]);

        $response = $this->actingAs($buyer)->postJson('/packages/buy', [
            'package_id' => $package->id,
        ]);

        $response->assertStatus(200);

        // Buyer balance should be 500 - 200 = 300
        $this->assertEquals(300, $buyer->fresh()->balance);

        // Investment record created
        $this->assertDatabaseHas('investments', [
            'user_id' => $buyer->id,
            'package_id' => $package->id,
            'invested_amount' => 200,
            'daily_return' => 10,
            'status' => 'active',
        ]);

        // Buyer transaction record created
        $this->assertDatabaseHas('transactions', [
            'user_id' => $buyer->id,
            'type' => 'package_buy',
            'amount' => 200,
            'status' => 'completed',
        ]);

        // Referrer should get 10% of 200 = 20
        $referrerFresh = $referrer->fresh();
        $this->assertEquals(20, $referrerFresh->balance);
        $this->assertEquals(20, $referrerFresh->total_refer_bonus);

        // Referrer transaction record created
        $this->assertDatabaseHas('transactions', [
            'user_id' => $referrer->id,
            'type' => 'referral_bonus',
            'amount' => 20,
            'status' => 'completed',
        ]);
    }

    /**
     * Test user dashboard statistics.
     */
    public function test_dashboard_returns_correct_metrics()
    {
        $referrer = User::factory()->create([
            'referral_code' => 'LEADER01',
            'balance' => 150,
            'total_refer_bonus' => 50,
        ]);

        // Create 2 team members for referrer
        User::factory()->count(2)->create([
            'referred_by' => $referrer->id,
        ]);

        $package = Package::create([
            'name' => 'VIP Pack',
            'price' => 100,
            'cycle_days' => 60,
            'daily_return' => 5,
        ]);

        // Create an active investment for referrer
        Investment::create([
            'user_id' => $referrer->id,
            'package_id' => $package->id,
            'invested_amount' => 100,
            'daily_return' => 5,
            'status' => 'active',
            'expires_at' => now()->addDays(60),
        ]);

        $response = $this->actingAs($referrer)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertViewIs('frontend.dashboard');
        $response->assertViewHas('totalTeam', 2);
        $response->assertViewHas('balance', 150);
        $response->assertViewHas('totalReferBonus', 50);
        $response->assertViewHas('activePlans', 1);
    }
}
