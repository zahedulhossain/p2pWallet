<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoneyTransfer extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function receiverWallet()
    {
        return $this->hasOne(Wallet::class, 'to_wallet_id');
    }

    public function senderWallet()
    {
        return $this->hasOne(Wallet::class, 'from_wallet_id');
    }
}
