<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\PropertyDocument;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PropertyDocumentController extends Controller
{
    /**
     * Display documents for a property
     */
    public function index(Property $property)
    {
        // Verify ownership
        if ($property->owner_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $documents = $property->documents()->orderBy('created_at', 'desc')->get();

        // Group documents by type for better organization
        $documentsByType = $documents->groupBy('doc_type');

        return view('landlord.property.documents', compact('property', 'documents', 'documentsByType'));
    }

    /**
     * Store new property documents
     */
    public function store(Request $request, Property $property)
    {
        // Verify ownership
        if ($property->owner_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'documents.*' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // 10MB max
            'doc_type.*' => 'required|string|in:deed,mutation,registration,tax_receipt,others',
            'uploaded_at.*' => 'required|date',
        ]);

        $uploadedDocuments = [];

        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $index => $file) {
                $path = $file->store('property_documents', 'public');

                $document = PropertyDocument::create([
                    'property_id' => $property->id,
                    'doc_type' => $request->input("doc_type.{$index}"),
                    'file_path' => '/storage/' . $path,
                    'status' => 'pending', // Documents start as pending for admin review
                    'uploaded_at' => $request->input("uploaded_at.{$index}"),
                ]);

                $uploadedDocuments[] = $document;
            }
        }

        return redirect()->back()->with('success', count($uploadedDocuments) . ' document(s) uploaded successfully and pending admin review.');
    }

    /**
     * Download a document
     */
    public function download(Property $property, PropertyDocument $document)
    {
        // Verify ownership or admin access
        if ($property->owner_id !== auth()->id() && !auth()->user()->hasRole('Admin')) {
            abort(403, 'Unauthorized action.');
        }

        if ($document->property_id !== $property->id) {
            abort(403, 'Document does not belong to this property.');
        }

        $filePath = str_replace('/storage/', 'public/', $document->file_path);

        if (!Storage::exists($filePath)) {
            abort(404, 'Document not found.');
        }

        return Storage::download($filePath, $document->getOriginalFileName());
    }

    /**
     * Delete property document
     */
    public function destroy(Property $property, PropertyDocument $document)
    {
        // Verify ownership
        if ($property->owner_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($document->property_id !== $property->id) {
            abort(403, 'Document does not belong to this property.');
        }

        // Delete file from storage
        if ($document->file_path && Storage::exists(str_replace('/storage/', 'public/', $document->file_path))) {
            Storage::delete(str_replace('/storage/', 'public/', $document->file_path));
        }

        $document->delete();

        return redirect()->back()->with('success', 'Document deleted successfully.');
    }

    /**
     * Get document types for dropdown
     */
    public function getDocumentTypes()
    {
        return [
            'deed' => 'Property Deed',
            'mutation' => 'Mutation Document',
            'registration' => 'Registration Certificate',
            'tax_receipt' => 'Tax Receipt',
            'others' => 'Other Documents'
        ];
    }
}
