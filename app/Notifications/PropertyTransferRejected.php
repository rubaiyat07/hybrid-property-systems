<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\PropertyTransfer;

class PropertyTransferRejected extends Notification implements ShouldQueue
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
            ->subject('Property Transfer Rejected - ' . $this->transfer->property->name)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your property transfer request has been rejected.')
            ->line('**Property:** ' . $this->transfer->property->name)
            ->line('**Buyer:** ' . $this->transfer->proposedBuyer->name)
            ->line('**Transfer Type:** ' . $this->transfer->transfer_type_label)
            ->line('**Rejection Reason:** ' . $this->transfer->buyer_response_notes)
            ->line('You can create a new transfer request if needed.')
            ->action('View Transfer Details', route('property.transfer.show', [$this->transfer->property, $this->transfer]))
            ->salutation('Best regards, ' . config('app.name') . ' Team');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'transfer_id' => $this->transfer->id,
            'property_id' => $this->transfer->property->id,
            'property_name' => $this->transfer->property->name,
            'buyer_name' => $this->transfer->proposedBuyer->name,
            'rejection_reason' => $this->transfer->buyer_response_notes,
            'message' => 'Your property transfer request for ' . $this->transfer->property->name . ' has been rejected',
            'action_url' => route('property.transfer.show', [$this->transfer->property, $this->transfer])
        ];
    }
}
