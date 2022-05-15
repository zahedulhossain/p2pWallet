<?php

namespace App\Listeners;

use App\Events\MoneyTransferred;
use App\Notifications\MoneyReceived;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendMoneyReceivedNotification
{
    /**
     * Handle the event.
     *
     * @param  MoneyTransferred  $event
     * @return void
     */
    public function handle(MoneyTransferred $event)
    {
        $amount = $event->moneyTransfer->amount;
        $currency = $event->receiver->wallet->currency;
        $sender = $event->sender;

        Notification::send($event->receiver, new MoneyReceived($amount, $currency, $sender));
    }
}
