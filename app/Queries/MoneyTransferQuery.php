<?php

namespace App\Queries;

use App\Models\MoneyTransfer;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;

class MoneyTransferQuery
{
    public function transfer($amount, $senderWallet, $receiverWallet, $note = null, $conversionArr = null)
    {
        DB::beginTransaction();
        try {
            $moneyTransfer = MoneyTransfer::query()->create([
                'conversion_rate' => $conversionArr['conversion_rate'] ?? null,
                'amount' => $amount,
                'converted_amount' => $conversionArr['converted_amount'] ?? null,
                'from_wallet_id' => $senderWallet->id,
                'to_wallet_id' => $receiverWallet->id,
                'note' => $note
            ]);

            Transaction::query()->create([
                'wallet_id' => $senderWallet->id,
                'amount' => $amount,
                'action' => 'withdraw',
            ]);

            Transaction::query()->create([
                'wallet_id' => $receiverWallet->id,
                'amount' => $conversionArr['converted_amount'] ?? $amount,
                'action' => 'deposit',
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        return $moneyTransfer;
    }
}
