<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    public array $currencies = [
        ['code' => 'USD', 'name' => 'United States Dollar', 'symbol' => '$'],
        ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€'],
    ];

    public function run()
    {
        foreach ($this->currencies as $currency) {
            Currency::create($currency);
        }
    }
}
