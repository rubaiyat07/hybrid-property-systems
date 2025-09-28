<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\PropertyDocument;
use Illuminate\Support\Facades\Storage;

class LandlordDocumentController extends Controller
{
    public function index(Property $property)
    {
        $this->authorize('view', $property); // Ensure landlord owns property

        $documents = $property->documents()->orderBy('created_at', 'desc')->get();

        return view('landlord.properties.documents', compact('property', 'documents'));
    }

    public function store(Request $request, Property $property)
    {
        $this->authorize('update', $property);

        $request->validate([
            'type' => 'required|in:deed,certificate,mutation,tax_receipt',
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $file = $request->file('document');
        $path = $file->store('property-documents', 'public');

        PropertyDocument::create([
            'property_id' => $property->id,
            'type' => $request->type,
            'file_path' => $path,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Document uploaded successfully.');
    }

    public function download(Property $property, PropertyDocument $document)
    {
        $this->authorize('view', $property);

        if ($document->property_id !== $property->id) {
            abort(403);
        }

        return Storage::disk('public')->download($document->file_path);
    }
}
