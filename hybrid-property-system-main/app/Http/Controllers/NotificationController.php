<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Models\Payment;
use App\Models\PropertyTax;
use App\Models\PropertyBill;
use App\Models\User;
use App\Notifications\OverduePaymentReminder;
use App\Notifications\OverdueTaxReminder;
use App\Notifications\OverdueBillReminder;

class NotificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $notifications = $user->notifications()->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $user = auth()->user();
        $notification = $user->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        $user = auth()->user();
        $user->unreadNotifications->markAsRead();

        return response()->json(['success' => true]);
    }

    // Send overdue payment reminders
    public function sendOverdueReminders()
    {
        // Overdue payments
        $overduePayments = Payment::where('status', 'pending')
            ->where('due_date', '<', now()->subDays(7)) // 7 days overdue
            ->with('tenant.user')
            ->get();

        foreach ($overduePayments as $payment) {
            $payment->tenant->user->notify(new OverduePaymentReminder($payment));
        }

        // Overdue taxes
        $overdueTaxes = PropertyTax::where('status', 'pending')
            ->where('due_date', '<', now()->subDays(7))
            ->with('property.owner')
            ->get();

        foreach ($overdueTaxes as $tax) {
            $tax->property->owner->notify(new OverdueTaxReminder($tax));
        }

        // Overdue bills
        $overdueBills = PropertyBill::where('status', 'pending')
            ->where('due_date', '<', now()->subDays(7))
            ->with('property.owner')
            ->get();

        foreach ($overdueBills as $bill) {
            $bill->property->owner->notify(new OverdueBillReminder($bill));
        }

        return response()->json(['message' => 'Overdue reminders sent successfully']);
    }
}
