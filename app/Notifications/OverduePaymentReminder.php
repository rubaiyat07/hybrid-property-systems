<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Payment;

class OverduePaymentReminder extends Notification implements ShouldQueue
{
    use Queueable;

    protected $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Overdue Payment Reminder')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('This is a reminder that you have an overdue payment.')
            ->line('Payment Details:')
            ->line('Amount: $' . number_format($this->payment->amount, 2))
            ->line('Due Date: ' . $this->payment->due_date->format('M d, Y'))
            ->line('Property: ' . $this->payment->lease->unit->property->name)
            ->action('Pay Now', url('/tenant/payments/' . $this->payment->id))
            ->line('Please make the payment as soon as possible to avoid any penalties.');
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'overdue_payment',
            'payment_id' => $this->payment->id,
            'amount' => $this->payment->amount,
            'due_date' => $this->payment->due_date,
            'property_name' => $this->payment->lease->unit->property->name,
            'message' => 'You have an overdue payment of $' . number_format($this->payment->amount, 2) . ' for ' . $this->payment->lease->unit->property->name,
        ];
    }
}
