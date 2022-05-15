<?php

namespace Tests\Feature;

use App\Events\MoneyTransferred;
use App\Models\Currency;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MoneyTransferTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_users_get_exception_when_sending_money_without_sufficient_balance()
    {
        $user = User::factory()->create();
        $currency = Currency::factory()->create();
        $wallet = Wallet::create([
            'currency_id' => $currency->id,
            'user_id' => $user->id,
        ]);

        $user2 = User::factory()->create();
        $wallet2 = Wallet::factory()->create([
            'user_id' => $user2->id,
        ]);

        $response = $this->actingAs($user)->post('/money-transfer', [
            'user_id' => $user2->id,
            'amount' => $this->faker->randomFloat(2, 10, 100)
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas(['flash.banner' => 'Your account balance is insufficient.']);
    }

    public function test_users_get_exception_when_sending_money_to_their_own_account()
    {
        $user = User::factory()->create();
        $currency = Currency::factory()->create();
        $wallet = Wallet::create([
            'currency_id' => $currency->id,
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->post('/money-transfer', [
            'user_id' => $user->id,
            'amount' => $this->faker->randomFloat(2, 10, 100)
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas(['flash.banner' => 'Oops! You selected yourself as the receiver.']);
    }

    public function test_users_can_send_money_to_other_wallets_in_same_currency()
    {
        $user = User::factory()->create();
        $currency = Currency::factory()->create();
        $wallet = Wallet::create([
            'currency_id' => $currency->id,
            'user_id' => $user->id,
        ]);
        Transaction::factory(20)->create([
            'wallet_id' => $wallet->id,
            'action' => 'deposit'
        ]);

        $user2 = User::factory()->create();
        $wallet2 = Wallet::factory()->create([
            'currency_id' => $currency->id,
            'user_id' => $user2->id,
        ]);

        $response = $this->actingAs($user)->post('/money-transfer', [
            'user_id' => $user2->id,
            'amount' => $amount = $this->faker->randomFloat(2, 10, 100)
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas(['flash.banner' => 'Money Sent.']);

        $this->assertDatabaseHas('money_transfers', [
            'conversion_rate' => null,
            'amount' => $amount,
            'converted_amount' => null,
            'from_wallet_id' => $wallet->id,
            'to_wallet_id' => $wallet2->id,
        ]);
    }

    public function test_a_user_can_send_money_to_another_user_having_different_currency_based_wallet()
    {
        $user = User::factory()->create();
        $currency = Currency::factory()->create([
            'code' => 'USD'
        ]);
        $wallet = Wallet::create([
            'currency_id' => $currency->id,
            'user_id' => $user->id,
        ]);
        Transaction::factory(20)->create([
            'wallet_id' => $wallet->id,
            'action' => 'deposit'
        ]);

        $user2 = User::factory()->create();
        $currency2 = Currency::factory()->create([
            'code' => 'EUR'
        ]);
        $wallet2 = Wallet::factory()->create([
            'currency_id' => $currency2->id,
            'user_id' => $user2->id,
        ]);

        Http::fake([
            'openexchangerates.org/*' => Http::response([
                'disclaimer' => "https://openexchangerates.org/terms/",
                'license' => "https://openexchangerates.org/license/",
                'timestamp' => 1449877801,
                'base' => 'USD',
                'rates' => ['EUR' => $rate = '0.96'],
            ], 200, []),
        ]);
        Event::fake();

        $response = $this->actingAs($user)->post('/money-transfer', [
            'user_id' => $user2->id,
            'amount' => $amount = $this->faker->randomFloat(2, 10, 100)
        ]);
        Event::assertDispatched(MoneyTransferred::class);

        $response->assertStatus(302);
        $response->assertSessionHas(['flash.banner' => 'Money Sent.']);

        $this->assertDatabaseHas('money_transfers', [
            'conversion_rate' => $rate,
            'amount' => $amount,
            'converted_amount' => $amount * $rate,
            'from_wallet_id' => $wallet->id,
            'to_wallet_id' => $wallet2->id,
        ]);
    }
}
