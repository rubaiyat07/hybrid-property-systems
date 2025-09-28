<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\PropertyTransfer;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use App\Notifications\PropertyTransferRequest;

class PropertyTransferController extends Controller
{
    /**
     * Display transfer requests for a property
     */
    public function index(Property $property)
    {
        // Verify ownership
        if ($property->owner_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $transfers = $property->transfers()->orderBy('created_at', 'desc')->get();

        return view('landlord.property.transfers', compact('property', 'transfers'));
    }

    /**
     * Show the form for creating a new transfer request
     */
    public function create(Property $property)
    {
        // Verify ownership
        if ($property->owner_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Get potential buyers (users with buyer role)
        $buyers = User::whereHas('roles', function($query) {
            $query->where('name', 'Buyer');
        })->where('id', '!=', auth()->id())->get();

        return view('landlord.property.transfer', compact('property', 'buyers'));
    }

    /**
     * Store a new transfer request
     */
    public function store(Request $request, Property $property)
    {
        // Verify ownership
        if ($property->owner_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'buyer_id' => 'required|exists:users,id',
            'transfer_type' => 'required|string|in:sale,lease_transfer,ownership_transfer',
            'proposed_price' => 'nullable|numeric|min:0',
            'transfer_date' => 'required|date|after:today',
            'terms_conditions' => 'required|string|max:2000',
            'documents.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120', // 5MB max
        ]);

        // Check if buyer is not the current owner
        if ($request->buyer_id == auth()->id()) {
            return back()->withErrors(['buyer_id' => 'You cannot transfer property to yourself.']);
        }

        // Check if there's already a pending transfer for this property
        $existingTransfer = PropertyTransfer::where('property_id', $property->id)
            ->where('status', 'pending')
            ->first();

        if ($existingTransfer) {
            return back()->withErrors(['general' => 'There is already a pending transfer request for this property.']);
        }

        $transfer = PropertyTransfer::create([
            'property_id' => $property->id,
            'current_owner_id' => auth()->id(),
            'proposed_buyer_id' => $request->buyer_id,
            'transfer_type' => $request->transfer_type,
            'proposed_price' => $request->proposed_price,
            'transfer_date' => $request->transfer_date,
            'terms_conditions' => $request->terms_conditions,
            'status' => 'pending',
            'initiated_at' => now(),
        ]);

        // Handle document uploads
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $path = $file->store('transfer_documents', 'private');

                $transfer->documents()->create([
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                ]);
            }
        }

        // Notify the proposed buyer
        $buyer = User::find($request->buyer_id);
        $buyer->notify(new PropertyTransferRequest($transfer));

        return redirect()->route('property.transfer.show', [$property, $transfer])
            ->with('success', 'Transfer request submitted successfully. The buyer has been notified.');
    }

    /**
     * Display the specified transfer request
     */
    public function show(Property $property, PropertyTransfer $transfer)
    {
        // Verify access (owner or proposed buyer or admin)
        if ($transfer->property_id !== $property->id) {
            abort(403, 'Transfer does not belong to this property.');
        }

        if ($transfer->current_owner_id !== auth()->id() &&
            $transfer->proposed_buyer_id !== auth()->id() &&
            !auth()->user()->hasRole('Admin')) {
            abort(403, 'Unauthorized action.');
        }

        return view('landlord.property.transfer-show', compact('property', 'transfer'));
    }

    /**
     * Show the form for editing a transfer request
     */
    public function edit(Property $property, PropertyTransfer $transfer)
    {
        // Only allow editing by current owner if status is pending
        if ($transfer->current_owner_id !== auth()->id() ||
            $transfer->property_id !== $property->id ||
            $transfer->status !== 'pending') {
            abort(403, 'Unauthorized action.');
        }

        $potentialBuyers = User::whereHas('roles', function($query) {
            $query->where('name', 'Buyer');
        })->where('id', '!=', auth()->id())->get();

        return view('landlord.property.transfer-edit', compact('property', 'transfer', 'potentialBuyers'));
    }

    /**
     * Update the transfer request
     */
    public function update(Request $request, Property $property, PropertyTransfer $transfer)
    {
        // Only allow updating by current owner if status is pending
        if ($transfer->current_owner_id !== auth()->id() ||
            $transfer->property_id !== $property->id ||
            $transfer->status !== 'pending') {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'proposed_price' => 'nullable|numeric|min:0',
            'transfer_date' => 'required|date|after:today',
            'terms_conditions' => 'required|string|max:2000',
            'documents.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        $transfer->update($request->only(['proposed_price', 'transfer_date', 'terms_conditions']));

        // Handle new document uploads
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $path = $file->store('transfer_documents', 'private');

                $transfer->documents()->create([
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                ]);
            }
        }

        return redirect()->route('property.transfer.show', [$property, $transfer])
            ->with('success', 'Transfer request updated successfully.');
    }

    /**
     * Accept transfer request (by buyer)
     */
    public function accept(Request $request, Property $property, PropertyTransfer $transfer)
    {
        // Only allow acceptance by proposed buyer
        if ($transfer->proposed_buyer_id !== auth()->id() ||
            $transfer->property_id !== $property->id ||
            $transfer->status !== 'pending') {
            abort(403, 'Unauthorized action.');
        }

        $transfer->update([
            'status' => 'accepted',
            'buyer_response_at' => now(),
            'buyer_response_notes' => $request->input('response_notes')
        ]);

        // Notify the current owner
        $owner = User::find($transfer->current_owner_id);
        Notification::send($owner, new \App\Notifications\PropertyTransferAccepted($transfer));

        return redirect()->back()->with('success', 'Transfer request accepted successfully.');
    }

    /**
     * Reject transfer request (by buyer)
     */
    public function reject(Request $request, Property $property, PropertyTransfer $transfer)
    {
        // Only allow rejection by proposed buyer
        if ($transfer->proposed_buyer_id !== auth()->id() ||
            $transfer->property_id !== $property->id ||
            $transfer->status !== 'pending') {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:1000'
        ]);

        $transfer->update([
            'status' => 'rejected',
            'buyer_response_at' => now(),
            'buyer_response_notes' => $request->rejection_reason
        ]);

        // Notify the current owner
        $owner = User::find($transfer->current_owner_id);
        Notification::send($owner, new \App\Notifications\PropertyTransferRejected($transfer));

        return redirect()->back()->with('success', 'Transfer request rejected.');
    }

    /**
     * Cancel transfer request (by owner)
     */
    public function cancel(Property $property, PropertyTransfer $transfer)
    {
        // Only allow cancellation by current owner
        if ($transfer->current_owner_id !== auth()->id() ||
            $transfer->property_id !== $property->id ||
            !in_array($transfer->status, ['pending', 'accepted'])) {
            abort(403, 'Unauthorized action.');
        }

        $transfer->update([
            'status' => 'cancelled',
            'cancelled_at' => now()
        ]);

        return redirect()->back()->with('success', 'Transfer request cancelled.');
    }

    /**
     * Complete transfer (admin only)
     */
    public function complete(Request $request, Property $property, PropertyTransfer $transfer)
    {
        // Only admin can complete transfers
        if (!auth()->user()->hasRole('Admin')) {
            abort(403, 'Unauthorized action.');
        }

        if ($transfer->property_id !== $property->id ||
            $transfer->status !== 'accepted') {
            abort(403, 'Invalid transfer status.');
        }

        $request->validate([
            'completion_notes' => 'nullable|string|max:1000'
        ]);

        // Update transfer
        $transfer->update([
            'status' => 'completed',
            'completed_at' => now(),
            'completion_notes' => $request->completion_notes
        ]);

        // Update property ownership
        $property->update([
            'owner_id' => $transfer->proposed_buyer_id
        ]);

        // Notify both parties
        $owner = User::find($transfer->current_owner_id);
        $buyer = User::find($transfer->proposed_buyer_id);

        Notification::send($owner, new \App\Notifications\PropertyTransferCompleted($transfer, 'seller'));
        Notification::send($buyer, new \App\Notifications\PropertyTransferCompleted($transfer, 'buyer'));

        return redirect()->back()->with('success', 'Property transfer completed successfully.');
    }

    /**
     * Download transfer document
     */
    public function downloadDocument(Property $property, PropertyTransfer $transfer, $documentId)
    {
        // Verify access
        if ($transfer->property_id !== $property->id) {
            abort(403, 'Document does not belong to this transfer.');
        }

        if ($transfer->current_owner_id !== auth()->id() &&
            $transfer->proposed_buyer_id !== auth()->id() &&
            !auth()->user()->hasRole('Admin')) {
            abort(403, 'Unauthorized action.');
        }

        $document = $transfer->documents()->findOrFail($documentId);

        if (!Storage::exists($document->file_path)) {
            abort(404, 'Document not found.');
        }

        return Storage::download($document->file_path, $document->file_name);
    }
}
