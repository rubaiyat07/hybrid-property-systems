<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\TenantLead;

class NewTenantLeadNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $tenantLead;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(TenantLead $tenantLead)
    {
        $this->tenantLead = $tenantLead;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Tenant Lead: ' . $this->tenantLead->name)
            ->greeting('New Tenant Lead Received!')
            ->line('A new tenant lead has been received for your property system.')
            ->line('**Lead Details:**')
            ->line('Name: ' . $this->tenantLead->name)
            ->line('Email: ' . $this->tenantLead->email)
            ->line('Phone: ' . ($this->tenantLead->phone ?? 'Not provided'))
            ->when($this->tenantLead->property, function($mail) {
                return $mail->line('Property: ' . $this->tenantLead->property->name);
            })
            ->when($this->tenantLead->unit, function($mail) {
                return $mail->line('Unit: ' . $this->tenantLead->unit->unit_number);
            })
            ->when($this->tenantLead->preferred_move_in_date, function($mail) {
                return $mail->line('Preferred Move-in Date: ' . $this->tenantLead->preferred_move_in_date->format('M d, Y'));
            })
            ->when($this->tenantLead->budget_range, function($mail) {
                return $mail->line('Budget Range: ' . $this->tenantLead->budget_range);
            })
            ->when($this->tenantLead->message, function($mail) {
                return $mail->line('Message: ' . $this->tenantLead->message);
            })
            ->line('Priority: ' . ucfirst($this->tenantLead->priority))
            ->line('Source: ' . ucfirst(str_replace('_', ' ', $this->tenantLead->source)))
            ->action('View Lead', route('admin.tenant-leads.show', $this->tenantLead))
            ->line('Please follow up with this lead as soon as possible.')
            ->salutation('Best regards, ' . config('app.name') . ' Team');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'tenant_lead_id' => $this->tenantLead->id,
            'message' => 'New tenant lead received from ' . $this->tenantLead->name,
            'name' => $this->tenantLead->name,
            'email' => $this->tenantLead->email,
            'priority' => $this->tenantLead->priority,
            'source' => $this->tenantLead->source,
            'created_at' => $this->tenantLead->created_at
        ];
    }
}
