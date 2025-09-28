<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\PropertyTransfer;

class PropertyTransferCompleted extends Notification implements ShouldQueue
{
    use Queueable;

    protected $transfer;
    protected $userType;

    public function __construct(PropertyTransfer $transfer, $userType = 'seller')
    {
        $this->transfer = $transfer;
        $this->userType = $userType;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $isSeller = $this->userType === 'seller';

        return (new MailMessage)
            ->subject('Property Transfer Completed - ' . $this->transfer->property->name)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line($isSeller ? 'Congratulations! Your property has been successfully transferred.' : 'Congratulations! You have successfully acquired a new property.')
            ->line('**Property:** ' . $this->transfer->property->name)
            ->line('**Address:** ' . $this->transfer->property->address)
            ->line('**Transfer Type:** ' . $this->transfer->transfer_type_label)
            ->line('**Final Price:** $' . number_format($this->transfer->proposed_price, 2))
            ->line('**Transfer Date:** ' . $this->transfer->transfer_date->format('M d, Y'))
            ->line($isSeller ? '**Transferred to:** ' . $this->transfer->proposedBuyer->name : '**Previous Owner:** ' . $this->transfer->currentOwner->name)
            ->line('The property ownership has been officially transferred and all records have been updated.')
            ->line($isSeller ? 'Please ensure you have handed over all property documents and keys to the new owner.' : 'Please collect all property documents and keys from the previous owner.')
            ->action('View Property Details', route('landlord.property.show', $this->transfer->property))
            ->line('Thank you for using ' . config('app.name') . '!')
            ->salutation('Best regards, ' . config('app.name') . ' Team');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'transfer_id' => $this->transfer->id,
            'property_id' => $this->transfer->property->id,
            'property_name' => $this->transfer->property->name,
            'user_type' => $this->userType,
            'message' => 'Property transfer completed for ' . $this->transfer->property->name,
            'action_url' => route('landlord.property.show', $this->transfer->property)
        ];
    }
}
