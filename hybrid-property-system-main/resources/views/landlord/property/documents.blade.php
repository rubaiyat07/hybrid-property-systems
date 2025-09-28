{{-- File: resources/views/landlord/property/documents.blade.php --}}
@extends('layouts.landlord')

@section('title', 'Property Documents - ' . $property->name)

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Property Documents</h1>
            <p class="text-gray-600 mt-1">{{ $property->name }} - {{ $property->address }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('landlord.property.show', $property) }}"
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md font-medium">
                <i class="fas fa-arrow-left mr-1"></i> Back to Property
            </a>
        </div>
    </div>

    <!-- Upload Section -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Upload Documents</h2>
        <form action="{{ route('property.documents.store', $property) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div id="document-upload-container">
                <div class="document-upload-row flex items-end space-x-4 mb-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Document File</label>
                        <input type="file" name="documents[]" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="w-48">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Document Type</label>
                        <select name="doc_type[]" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">Select Type</option>
                            <option value="deed">Property Deed</option>
                            <option value="mutation">Mutation Document</option>
                            <option value="registration">Registration Certificate</option>
                            <option value="tax_receipt">Tax Receipt</option>
                            <option value="others">Other Documents</option>
                        </select>
                    </div>
                    <div class="w-48">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Upload Date</label>
                        <input type="date" name="uploaded_at[]" max="{{ date('Y-m-d') }}"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <button type="button" onclick="removeDocumentRow(this)"
                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-md hidden">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>

            <div class="flex items-center space-x-4">
                <button type="button" onclick="addDocumentRow()"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
                    <i class="fas fa-plus mr-1"></i> Add Another Document
                </button>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md">
                    <i class="fas fa-upload mr-1"></i> Upload Documents
                </button>
            </div>
            <p class="text-sm text-gray-500 mt-2">Maximum 10MB per file. Supported formats: PDF, DOC, DOCX, JPG, JPEG, PNG</p>
            @error('documents.*')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </form>
    </div>

    <!-- Documents Section -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-medium text-gray-900">Property Documents</h2>
            <span class="text-sm text-gray-500">{{ $documents->count() }} document(s)</span>
        </div>

        @if($documents->count() > 0)
            @foreach($documentsByType as $type => $typeDocuments)
                <div class="mb-6">
                    <h3 class="text-md font-medium text-gray-800 mb-3 capitalize">
                        {{ str_replace('_', ' ', $type) }} ({{ $typeDocuments->count() }})
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($typeDocuments as $document)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center mb-2">
                                            <i class="fas fa-file-{{ $document->getFileIcon() }} text-2xl text-gray-400 mr-3"></i>
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-900">{{ $document->getDisplayName() }}</h4>
                                                <p class="text-xs text-gray-500">Uploaded: {{ $document->uploaded_at->format('M d, Y') }}</p>
                                            </div>
                                        </div>

                                        <div class="flex items-center space-x-2">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                @if($document->status === 'approved') bg-green-100 text-green-800
                                                @elseif($document->status === 'rejected') bg-red-100 text-red-800
                                                @else bg-yellow-100 text-yellow-800 @endif">
                                                {{ ucfirst($document->status) }}
                                            </span>
                                        </div>

                                        @if($document->status === 'rejected' && $document->rejection_reason)
                                            <p class="text-xs text-red-600 mt-1">Reason: {{ $document->rejection_reason }}</p>
                                        @endif
                                    </div>

                                    <div class="flex space-x-1">
                                        @if($document->status === 'approved')
                                            <a href="{{ route('property.documents.download', [$property, $document]) }}"
                                               class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded"
                                               title="Download Document">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        @endif

                                        <form action="{{ route('property.documents.destroy', [$property, $document]) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Are you sure you want to delete this document?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="bg-red-600 hover:bg-red-700 text-white p-2 rounded"
                                                    title="Delete Document">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @else
            <div class="text-center py-12">
                <i class="fas fa-file-alt text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg mb-2">No documents uploaded yet</p>
                <p class="text-gray-400 text-sm">Upload important property documents for better management</p>
            </div>
        @endif
    </div>

    <!-- Document Management Tips -->
    @if($documents->count() > 0)
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-6">
            <h3 class="text-sm font-medium text-blue-800 mb-2">Document Management Tips:</h3>
            <ul class="text-sm text-blue-700 space-y-1">
                <li>• Documents are reviewed by admin before approval</li>
                <li>• Only approved documents can be downloaded</li>
                <li>• Keep important documents like deeds and registration certificates</li>
                <li>• Use descriptive names for better organization</li>
            </ul>
        </div>
    @endif
</div>

@push('scripts')
<script>
function addDocumentRow() {
    const container = document.getElementById('document-upload-container');
    const rows = container.querySelectorAll('.document-upload-row');
    if (rows.length >= 5) {
        alert('Maximum 5 documents can be uploaded at once.');
        return;
    }

    const newRow = rows[0].cloneNode(true);
    newRow.querySelector('input[type="file"]').value = '';
    newRow.querySelector('select').selectedIndex = 0;
    newRow.querySelector('input[type="date"]').value = '';
    newRow.querySelector('button[onclick*="removeDocumentRow"]').classList.remove('hidden');

    container.appendChild(newRow);
}

function removeDocumentRow(button) {
    const rows = document.querySelectorAll('.document-upload-row');
    if (rows.length > 1) {
        button.closest('.document-upload-row').remove();
    }
}

// Set default upload date to today
document.addEventListener('DOMContentLoaded', function() {
    const dateInputs = document.querySelectorAll('input[name="uploaded_at[]"]');
    const today = new Date().toISOString().split('T')[0];
    dateInputs.forEach(input => {
        if (!input.value) {
            input.value = today;
        }
    });
});
</script>
@endpush

@endsection
