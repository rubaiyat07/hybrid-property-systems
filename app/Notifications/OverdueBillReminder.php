<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\PropertyBill;

class OverdueBillReminder extends Notification implements ShouldQueue
{
    use Queueable;

    protected $bill;

    public function __construct(PropertyBill $bill)
    {
        $this->bill = $bill;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Overdue Bill Payment Reminder')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('This is a reminder that you have an overdue bill payment.')
            ->line('Bill Details:')
            ->line('Type: ' . ucfirst($this->bill->type))
            ->line('Amount: $' . number_format($this->bill->amount, 2))
            ->line('Due Date: ' . $this->bill->due_date->format('M d, Y'))
            ->line('Property: ' . $this->bill->property->name)
            ->action('Upload Receipt', url('/landlord/bills'))
            ->line('Please upload the bill payment receipt as soon as possible.');
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'overdue_bill',
            'bill_id' => $this->bill->id,
            'type' => $this->bill->type,
            'amount' => $this->bill->amount,
            'due_date' => $this->bill->due_date,
            'property_name' => $this->bill->property->name,
            'message' => 'You have an overdue ' . $this->bill->type . ' bill of $' . number_format($this->bill->amount, 2) . ' for ' . $this->bill->property->name,
        ];
    }
}
