<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Property;

class AdminController extends Controller
{
    // Admin dashboard
    public function adminDashboard()
    {
        // Example data to show on dashboard
        $totalUsers = User::count();
        $totalProperties = Property::count();

        return view('admin.dashboard', compact('totalUsers', 'totalProperties'));
    }

    // Tenant Screening Management
    public function screenings()
    {
        $screenings = \App\Models\TenantScreening::with(['tenant.user'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.screenings.index', compact('screenings'));
    }

    public function showScreening($screeningId)
    {
        $screening = \App\Models\TenantScreening::with(['tenant.user', 'reviewer'])
            ->findOrFail($screeningId);

        return view('admin.screenings.show', compact('screening'));
    }

    public function approveScreening(Request $request, $screeningId)
    {
        $screening = \App\Models\TenantScreening::findOrFail($screeningId);

        $screening->update([
            'status' => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'notes' => $request->input('notes'),
        ]);

        // Update user's screening_verified status
        $screening->tenant->user->update(['screening_verified' => true]);

        return redirect()->route('admin.screenings.index')->with('success', 'Screening approved successfully.');
    }

    public function rejectScreening(Request $request, $screeningId)
    {
        $screening = \App\Models\TenantScreening::findOrFail($screeningId);

        $screening->update([
            'status' => 'rejected',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'notes' => $request->input('notes'),
        ]);

        return redirect()->route('admin.screenings.index')->with('success', 'Screening rejected.');
    }

    // Tax Management
    public function taxes()
    {
        $taxes = \App\Models\PropertyTax::with('property.owner')
            ->orderBy('due_date', 'asc')
            ->paginate(15);

        return view('admin.taxes.index', compact('taxes'));
    }

    public function verifyTax(Request $request, $taxId)
    {
        $tax = \App\Models\PropertyTax::findOrFail($taxId);

        $tax->update([
            'status' => 'verified',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
            'notes' => $request->input('notes'),
        ]);

        return redirect()->back()->with('success', 'Tax verified successfully.');
    }

    // Utility Bills Management
    public function bills()
    {
        $bills = \App\Models\PropertyBill::with('property.owner')
            ->orderBy('due_date', 'asc')
            ->paginate(15);

        return view('admin.bills.index', compact('bills'));
    }

    public function verifyBill(Request $request, $billId)
    {
        $bill = \App\Models\PropertyBill::findOrFail($billId);

        $bill->update([
            'status' => 'verified',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
            'notes' => $request->input('notes'),
        ]);

        return redirect()->back()->with('success', 'Bill verified successfully.');
    }

    // Property Documents Management
    public function documents()
    {
        $documents = \App\Models\PropertyDocument::with('property.owner')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.documents.index', compact('documents'));
    }

    public function approveDocument(Request $request, $documentId)
    {
        $document = \App\Models\PropertyDocument::findOrFail($documentId);

        $document->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'notes' => $request->input('notes'),
        ]);

        return redirect()->back()->with('success', 'Document approved successfully.');
    }

    public function rejectDocument(Request $request, $documentId)
    {
        $document = \App\Models\PropertyDocument::findOrFail($documentId);

        $document->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'notes' => $request->input('notes'),
        ]);

        return redirect()->back()->with('success', 'Document rejected.');
    }

    // Property Transfers Management
    public function transfers()
    {
        $transfers = \App\Models\PropertyTransfer::with('property', 'fromUser', 'toUser')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.transfers.index', compact('transfers'));
    }

    public function approveTransfer(Request $request, $transferId)
    {
        $transfer = \App\Models\PropertyTransfer::findOrFail($transferId);

        $transfer->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'notes' => $request->input('notes'),
        ]);

        // Update property owner
        $transfer->property->update(['owner_id' => $transfer->to_user_id]);

        // Send notification
        $transfer->fromUser->notify(new \App\Notifications\PropertyTransferCompleted($transfer));
        $transfer->toUser->notify(new \App\Notifications\PropertyTransferCompleted($transfer));

        return redirect()->back()->with('success', 'Transfer approved successfully.');
    }

    public function rejectTransfer(Request $request, $transferId)
    {
        $transfer = \App\Models\PropertyTransfer::findOrFail($transferId);

        $transfer->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'notes' => $request->input('notes'),
        ]);

        // Send notification
        $transfer->fromUser->notify(new \App\Notifications\PropertyTransferRejected($transfer));
        $transfer->toUser->notify(new \App\Notifications\PropertyTransferRejected($transfer));

        return redirect()->back()->with('success', 'Transfer rejected.');
    }

    // Reports
    public function incomeReport()
    {
        $payments = \App\Models\Payment::with('lease.unit.property')
            ->where('status', 'paid')
            ->get();

        $totalIncome = $payments->sum('amount');
        $monthlyIncome = $payments->groupBy(function($payment) {
            return $payment->created_at->format('Y-m');
        })->map(function($group) {
            return $group->sum('amount');
        });

        return view('admin.reports.income', compact('totalIncome', 'monthlyIncome'));
    }

    public function occupancyReport()
    {
        $properties = \App\Models\Property::with('units')->get();

        $occupancyData = $properties->map(function($property) {
            $totalUnits = $property->units->count();
            $occupiedUnits = $property->units->where('status', 'occupied')->count();
            $occupancyRate = $totalUnits > 0 ? ($occupiedUnits / $totalUnits) * 100 : 0;

            return [
                'property' => $property->name,
                'total_units' => $totalUnits,
                'occupied_units' => $occupiedUnits,
                'occupancy_rate' => $occupancyRate,
            ];
        });

        return view('admin.reports.occupancy', compact('occupancyData'));
    }
}
