<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->setupAWallet();
        $this->setupAWallet('user2@example.com', 'EUR');
    }

    private function setupAWallet($email = 'user@example.com', $currencyCode = 'USD')
    {
        $user = User::factory()->create([
            'email' => $email
        ]);

        $wallet = Wallet::create([
            'currency_id' => Currency::where('code', $currencyCode)->first()->id,
            'user_id' => $user->id,
        ]);

        Transaction::factory(100)->create([
            'wallet_id' => $wallet->id,
            'action' => 'deposit'
        ]);

        Transaction::factory(20)->create([
            'wallet_id' => $wallet->id,
            'action' => 'withdraw'
        ]);
    }
}
