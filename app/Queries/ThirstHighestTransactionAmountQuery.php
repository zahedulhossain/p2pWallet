<?php

namespace App\Queries;

use App\Models\MoneyTransfer;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;

class ThirstHighestTransactionAmountQuery
{
    public function get()
    {
        return Transaction::query()
            ->selectRaw('MAX(amount) as amount')
            ->where('amount', '<', function ($subQuery) {
                $subQuery->selectRaw('MAX(amount)')->from('transactions')
                    ->where('amount', '<', function ($subQuery) {
                        $subQuery->selectRaw('MAX(amount)')->from('transactions');
                    });
            })
            ->get()
            ->pluck('amount')
            ->implode('amount');
    }
}
