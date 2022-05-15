<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MoneyReceived extends Notification implements ShouldQueue
{
    use Queueable;

    private $amount;
    private $currency;
    private $sender;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($amount, $currency, $sender)
    {
        $this->amount = $amount;
        $this->currency = $currency;
        $this->sender = $sender;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->greeting("You received {$this->amount} {$this->currency->code}!")
            ->line("{$this->sender->name} has sent you {$this->amount} {$this->currency->code}.")
            ->action('Check balance', route('dashboard'))
            ->line('Thank you for using our application!');
    }
}
