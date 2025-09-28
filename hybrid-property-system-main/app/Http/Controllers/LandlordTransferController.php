<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\PropertyTransfer;
use App\Models\PropertyTransferDocument;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class LandlordTransferController extends Controller
{
    public function index(Property $property)
    {
        $this->authorize('view', $property);

        $transfers = $property->transfers()->with('fromUser', 'toUser')->orderBy('created_at', 'desc')->get();

        return view('landlord.properties.transfers', compact('property', 'transfers'));
    }

    public function create(Property $property)
    {
        $this->authorize('update', $property);

        return view('landlord.properties.transfers.create', compact('property'));
    }

    public function store(Request $request, Property $property)
    {
        $this->authorize('update', $property);

        $request->validate([
            'to_user_email' => 'required|email|exists:users,email',
            'transfer_date' => 'required|date|after:today',
            'documents.*' => 'file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $toUser = User::where('email', $request->to_user_email)->first();

        $transfer = PropertyTransfer::create([
            'property_id' => $property->id,
            'from_user_id' => auth()->id(),
            'to_user_id' => $toUser->id,
            'transfer_date' => $request->transfer_date,
            'status' => 'pending',
        ]);

        // Upload documents
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $path = $file->store('transfer-documents', 'public');

                PropertyTransferDocument::create([
                    'property_transfer_id' => $transfer->id,
                    'file_path' => $path,
                ]);
            }
        }

        // Notify admin
        \App\Models\User::whereHas('roles', function($q) {
            $q->where('name', 'Admin');
        })->first()->notify(new \App\Notifications\PropertyTransferRequest($transfer));

        return redirect()->route('landlord.properties.transfers', $property)->with('success', 'Transfer request submitted.');
    }
}
