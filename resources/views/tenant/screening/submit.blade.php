@extends('layouts.tenant')

@section('title', 'Submit Screening Documents')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white shadow rounded p-6">
        <h1 class="text-2xl font-bold mb-6">Submit Tenant Screening Documents</h1>

        <p class="text-gray-600 mb-6">
            Please upload the required documents for tenant screening. All documents are required and must be in PDF, JPEG, PNG, or GIF format (max 5MB each).
        </p>

        <form action="{{ route('tenant.screening.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Government ID -->
            <div>
                <label for="id_document" class="block text-sm font-medium text-gray-700 mb-2">
                    Government ID <span class="text-red-500">*</span>
                </label>
                <input type="file" id="id_document" name="id_document" accept=".pdf,.jpg,.jpeg,.png,.gif" required
                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                <p class="text-xs text-gray-500 mt-1">Upload a copy of your government-issued ID (passport, driver's license, etc.)</p>
                @error('id_document')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Proof of Income -->
            <div>
                <label for="income_proof" class="block text-sm font-medium text-gray-700 mb-2">
                    Proof of Income <span class="text-red-500">*</span>
                </label>
                <input type="file" id="income_proof" name="income_proof" accept=".pdf,.jpg,.jpeg,.png,.gif" required
                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                <p class="text-xs text-gray-500 mt-1">Upload recent pay stubs, tax returns, or other proof of income</p>
                @error('income_proof')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Rental References -->
            <div>
                <label for="references" class="block text-sm font-medium text-gray-700 mb-2">
                    Rental References <span class="text-red-500">*</span>
                </label>
                <input type="file" id="references" name="references" accept=".pdf,.jpg,.jpeg,.png,.gif" required
                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                <p class="text-xs text-gray-500 mt-1">Upload references from previous landlords or rental history</p>
                @error('references')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                    Additional Notes
                </label>
                <textarea id="notes" name="notes" rows="4" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                          placeholder="Any additional information you'd like to provide..."></textarea>
                @error('notes')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('tenant.screening.status') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                    Cancel
                </a>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                    Submit Documents
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
