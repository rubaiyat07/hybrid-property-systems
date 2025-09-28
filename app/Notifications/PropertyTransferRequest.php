<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\PropertyTransfer;

class PropertyTransferRequest extends Notification implements ShouldQueue
{
    use Queueable;

    protected $transfer;

    /**
     * Create a new notification instance.
     */
    public function __construct(PropertyTransfer $transfer)
    {
        $this->transfer = $transfer;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Property Transfer Request - ' . $this->transfer->property->name)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('You have received a property transfer request for:')
            ->line('**Property:** ' . $this->transfer->property->name)
            ->line('**Address:** ' . $this->transfer->property->address)
            ->line('**Transfer Type:** ' . $this->transfer->transfer_type_label)
            ->line('**Proposed Price:** $' . number_format($this->transfer->proposed_price, 2))
            ->line('**Transfer Date:** ' . $this->transfer->transfer_date->format('M d, Y'))
            ->line('**From:** ' . $this->transfer->currentOwner->name)
            ->line('Please review the transfer terms and conditions:')
            ->line($this->transfer->terms_conditions)
            ->action('View Transfer Request', route('property.transfer.show', [$this->transfer->property, $this->transfer]))
            ->line('You can accept or reject this request from your dashboard.')
            ->salutation('Best regards, ' . config('app.name') . ' Team');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'transfer_id' => $this->transfer->id,
            'property_id' => $this->transfer->property->id,
            'property_name' => $this->transfer->property->name,
            'transfer_type' => $this->transfer->transfer_type,
            'proposed_price' => $this->transfer->proposed_price,
            'from_user' => $this->transfer->currentOwner->name,
            'message' => 'You have received a property transfer request for ' . $this->transfer->property->name,
            'action_url' => route('property.transfer.show', [$this->transfer->property, $this->transfer])
        ];
    }
}
