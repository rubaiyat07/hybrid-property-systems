<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\UnitInquiry;

class UnitInquiryNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $inquiry;

    /**
     * Create a new notification instance.
     */
    public function __construct(UnitInquiry $inquiry)
    {
        $this->inquiry = $inquiry;
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
        $unit = $this->inquiry->unit;
        $property = $unit->property;

        return (new MailMessage)
            ->subject('New Rental Inquiry - ' . $unit->unit_number)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('You have received a new inquiry for your rental property.')
            ->line('**Property:** ' . $property->name)
            ->line('**Unit:** ' . $unit->unit_number)
            ->line('**Rent:** ' . $unit->display_price)
            ->line('**Inquirer:** ' . $this->inquiry->inquirer_name)
            ->line('**Email:** ' . $this->inquiry->inquirer_email)
            ->line('**Phone:** ' . ($this->inquiry->inquirer_phone ?? 'Not provided'))
            ->line('**Type:** ' . ucfirst(str_replace('_', ' ', $this->inquiry->inquiry_type)))
            ->when($this->inquiry->message, function ($mail) {
                return $mail->line('**Message:** ' . $this->inquiry->message);
            })
            ->when($this->inquiry->preferred_viewing_date, function ($mail) {
                return $mail->line('**Preferred Viewing Date:** ' . $this->inquiry->preferred_viewing_date->format('F j, Y'));
            })
            ->when($this->inquiry->preferred_viewing_time, function ($mail) {
                return $mail->line('**Preferred Viewing Time:** ' . $this->inquiry->preferred_viewing_time->format('g:i A'));
            })
            ->action('View Inquiry', route('landlord.inquiries.show', $this->inquiry))
            ->line('Please respond to this inquiry within 24 hours to maintain good tenant relations.')
            ->salutation('Best regards, ' . config('app.name') . ' Team');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'inquiry_id' => $this->inquiry->id,
            'unit_id' => $this->inquiry->unit_id,
            'unit_number' => $this->inquiry->unit->unit_number,
            'property_name' => $this->inquiry->unit->property->name,
            'inquirer_name' => $this->inquiry->inquirer_name,
            'inquiry_type' => $this->inquiry->inquiry_type,
            'message' => 'New ' . ucfirst(str_replace('_', ' ', $this->inquiry->inquiry_type)) . ' for ' . $this->inquiry->unit->unit_number,
        ];
    }
}
