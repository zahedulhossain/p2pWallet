<?php

namespace App\Events;

use App\Models\MoneyTransfer;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MoneyTransferred
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public MoneyTransfer $moneyTransfer;
    public User $sender;
    public User $receiver;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(MoneyTransfer $moneyTransfer, User $sender, User $receiver)
    {
        $this->moneyTransfer = $moneyTransfer;
        $this->sender = $sender;
        $this->receiver = $receiver;
    }
}
