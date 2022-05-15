<?php

namespace App\Queries;

use App\Models\MoneyTransfer;
use App\Models\User;
use App\Models\Wallet;

class MostConversionByUserQuery
{
    private function getConversionSum()
    {
        return MoneyTransfer::query()
            ->groupBy('from_wallet_id')
            ->selectRaw('from_wallet_id, SUM(converted_amount) as totalAmount')
            ->orderByDesc('totalAmount')
            ->take(1)
            ->get();
    }

    public function get()
    {
        $walletId = $this->getConversionSum()->pluck('from_wallet_id')->implode('from_wallet_id');
        return User::query()
            ->whereHas('wallet', function ($query) use ($walletId) {
                $query->where('id', $walletId);
            })->first();
    }
}
