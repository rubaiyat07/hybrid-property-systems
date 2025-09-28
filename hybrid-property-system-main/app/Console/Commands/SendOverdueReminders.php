<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payment;
use App\Models\PropertyTax;
use App\Models\PropertyBill;
use App\Notifications\OverduePaymentReminder;
use App\Notifications\OverdueTaxReminder;
use App\Notifications\OverdueBillReminder;

class SendOverdueReminders extends Command
{
    protected $signature = 'reminders:send-overdue';

    protected $description = 'Send overdue payment, tax, and bill reminders';

    public function handle()
    {
        $this->info('Sending overdue reminders...');

        // Overdue payments
        $overduePayments = Payment::where('status', 'pending')
            ->where('due_date', '<', now()->subDays(7))
            ->with('tenant.user')
            ->get();

        $this->info('Found ' . $overduePayments->count() . ' overdue payments');

        foreach ($overduePayments as $payment) {
            $payment->tenant->user->notify(new OverduePaymentReminder($payment));
        }

        // Overdue taxes
        $overdueTaxes = PropertyTax::where('status', 'pending')
            ->where('due_date', '<', now()->subDays(7))
            ->with('property.owner')
            ->get();

        $this->info('Found ' . $overdueTaxes->count() . ' overdue taxes');

        foreach ($overdueTaxes as $tax) {
            $tax->property->owner->notify(new OverdueTaxReminder($tax));
        }

        // Overdue bills
        $overdueBills = PropertyBill::where('status', 'pending')
            ->where('due_date', '<', now()->subDays(7))
            ->with('property.owner')
            ->get();

        $this->info('Found ' . $overdueBills->count() . ' overdue bills');

        foreach ($overdueBills as $bill) {
            $bill->property->owner->notify(new OverdueBillReminder($bill));
        }

        $this->info('Overdue reminders sent successfully!');
    }
}
