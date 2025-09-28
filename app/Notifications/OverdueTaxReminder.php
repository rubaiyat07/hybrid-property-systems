<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\PropertyTax;

class OverdueTaxReminder extends Notification implements ShouldQueue
{
    use Queueable;

    protected $tax;

    public function __construct(PropertyTax $tax)
    {
        $this->tax = $tax;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Overdue Tax Payment Reminder')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('This is a reminder that you have an overdue tax payment.')
            ->line('Tax Details:')
            ->line('Amount: $' . number_format($this->tax->amount, 2))
            ->line('Due Date: ' . $this->tax->due_date->format('M d, Y'))
            ->line('Property: ' . $this->tax->property->name)
            ->action('Upload Receipt', url('/landlord/taxes'))
            ->line('Please upload the tax payment receipt as soon as possible.');
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'overdue_tax',
            'tax_id' => $this->tax->id,
            'amount' => $this->tax->amount,
            'due_date' => $this->tax->due_date,
            'property_name' => $this->tax->property->name,
            'message' => 'You have an overdue tax payment of $' . number_format($this->tax->amount, 2) . ' for ' . $this->tax->property->name,
        ];
    }
}
