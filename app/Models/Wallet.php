<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function moneySent()
    {
        return $this->hasMany(MoneyTransfer::class, 'from_wallet_id');
    }

    public function moneyReceived()
    {
        return $this->hasMany(MoneyTransfer::class, 'to_wallet_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function balance(): Attribute
    {
        return Attribute::get(
            fn() => $this->transactions()->deposits()->sum('amount') - $this->transactions()->withdraws()->sum('amount')
        );
    }
}
