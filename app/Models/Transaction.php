<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    use HasUuid;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function scopeDeposits($query)
    {
        return $query->where('action', 'deposit');
    }

    public function scopeWithdraws($query)
    {
        return $query->where('action', 'withdraw');
    }
}
