<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PropertyBill;
use Illuminate\Support\Facades\Storage;

class LandlordBillController extends Controller
{
    public function index()
    {
        $bills = PropertyBill::whereHas('property', function($q) {
            $q->where('owner_id', auth()->id());
        })->with('property')->orderBy('due_date', 'asc')->paginate(15);

        return view('landlord.bills.index', compact('bills'));
    }

    public function pay(Request $request, PropertyBill $bill)
    {
        if ($bill->property->owner_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'receipt' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $file = $request->file('receipt');
        $path = $file->store('bill-receipts', 'public');

        $bill->update([
            'receipt_path' => $path,
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Bill paid and receipt uploaded successfully.');
    }

    public function download(PropertyBill $bill)
    {
        if ($bill->property->owner_id !== auth()->id()) {
            abort(403);
        }

        return Storage::disk('public')->download($bill->receipt_path);
    }
}
