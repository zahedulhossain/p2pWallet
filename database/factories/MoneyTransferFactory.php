<?php

namespace Database\Factories;

use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MoneyTransfer>
 */
class MoneyTransferFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'from_wallet_id' => Wallet::factory(),
            'to_wallet_id' => Wallet::factory(),
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'conversion_rate' => $this->faker->randomFloat(2, 1, 100),
            'converted_amount' => $this->faker->randomFloat(2, 10, 1000),
        ];
    }
}
