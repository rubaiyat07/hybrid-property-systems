<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PropertyTax;
use Illuminate\Support\Facades\Storage;

class LandlordTaxController extends Controller
{
    public function index()
    {
        $taxes = PropertyTax::whereHas('property', function($q) {
            $q->where('owner_id', auth()->id());
        })->with('property')->orderBy('due_date', 'asc')->paginate(15);

        return view('landlord.taxes.index', compact('taxes'));
    }

    public function uploadReceipt(Request $request, PropertyTax $tax)
    {
        if ($tax->property->owner_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'receipt' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $file = $request->file('receipt');
        $path = $file->store('tax-receipts', 'public');

        $tax->update([
            'receipt_path' => $path,
            'status' => 'submitted',
        ]);

        return redirect()->back()->with('success', 'Tax receipt uploaded successfully.');
    }

    public function download(PropertyTax $tax)
    {
        if ($tax->property->owner_id !== auth()->id()) {
            abort(403);
        }

        return Storage::disk('public')->download($tax->receipt_path);
    }
}
