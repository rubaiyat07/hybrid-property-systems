<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\PropertyTransfer;

class PropertyTransferAccepted extends Notification implements ShouldQueue
{
    use Queueable;

    protected $transfer;

    public function __construct(PropertyTransfer $transfer)
    {
        $this->transfer = $transfer;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Property Transfer Accepted - ' . $this->transfer->property->name)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Great news! Your property transfer request has been accepted.')
            ->line('**Property:** ' . $this->transfer->property->name)
            ->line('**Buyer:** ' . $this->transfer->proposedBuyer->name)
            ->line('**Transfer Type:** ' . $this->transfer->transfer_type_label)
            ->line('**Accepted Price:** $' . number_format($this->transfer->proposed_price, 2))
            ->line('**Transfer Date:** ' . $this->transfer->transfer_date->format('M d, Y'))
            ->line('The transfer is now pending admin approval for completion.')
            ->action('View Transfer Details', route('property.transfer.show', [$this->transfer->property, $this->transfer]))
            ->line('Please ensure all necessary documents are prepared for the transfer.')
            ->salutation('Best regards, ' . config('app.name') . ' Team');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'transfer_id' => $this->transfer->id,
            'property_id' => $this->transfer->property->id,
            'property_name' => $this->transfer->property->name,
            'buyer_name' => $this->transfer->proposedBuyer->name,
            'message' => 'Your property transfer request for ' . $this->transfer->property->name . ' has been accepted',
            'action_url' => route('property.transfer.show', [$this->transfer->property, $this->transfer])
        ];
    }
}
