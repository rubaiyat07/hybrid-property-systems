@extends('layouts.landlord')

@section('title', 'Property Transfer - ' . $property->name)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Property Transfer</h1>
            <p class="text-gray-600 mt-1">{{ $property->name }} - {{ $property->address }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('landlord.property.show', $property) }}"
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md font-medium">
                <i class="fas fa-arrow-left mr-1"></i> Back to Property
            </a>
        </div>
    </div>

    <!-- Transfer Form -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Initiate Property Transfer</h3>

        <form action="{{ route('landlord.property.transfer.store', $property) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Transfer Type -->
                <div>
                    <label for="transfer_type" class="block text-sm font-medium text-gray-700 mb-2">
                        Transfer Type *
                    </label>
                    <select name="transfer_type" id="transfer_type" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Select Transfer Type</option>
                        <option value="sale">Property Sale</option>
                        <option value="lease_transfer">Lease Transfer</option>
                        <option value="ownership_transfer">Ownership Transfer</option>
                    </select>
                    @error('transfer_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Proposed Buyer -->
                <div>
                    <label for="proposed_buyer_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Proposed Buyer *
                    </label>
                    <select name="proposed_buyer_id" id="proposed_buyer_id" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Select Buyer</option>
                        @foreach($buyers as $buyer)
                            <option value="{{ $buyer->id }}">{{ $buyer->name }} ({{ $buyer->email }})</option>
                        @endforeach
                    </select>
                    @error('proposed_buyer_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Proposed Price -->
                <div>
                    <label for="proposed_price" class="block text-sm font-medium text-gray-700 mb-2">
                        Proposed Price *
                    </label>
                    <input type="number" name="proposed_price" id="proposed_price" step="0.01" min="0" required
                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="Enter proposed price">
                    @error('proposed_price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Transfer Date -->
                <div>
                    <label for="transfer_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Proposed Transfer Date *
                    </label>
                    <input type="date" name="transfer_date" id="transfer_date" required
                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    @error('transfer_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Terms and Conditions -->
            <div class="mt-6">
                <label for="terms_conditions" class="block text-sm font-medium text-gray-700 mb-2">
                    Terms and Conditions
                </label>
                <textarea name="terms_conditions" id="terms_conditions" rows="4"
                          class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                          placeholder="Enter terms and conditions for the transfer..."></textarea>
                @error('terms_conditions')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Supporting Documents -->
            <div class="mt-6">
                <label for="documents" class="block text-sm font-medium text-gray-700 mb-2">
                    Supporting Documents
                </label>
                <input type="file" name="documents[]" id="documents" multiple
                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                <p class="mt-1 text-sm text-gray-500">Upload relevant documents (PDF, DOC, DOCX, JPG, PNG)</p>
                @error('documents.*')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="mt-6">
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md font-medium">
                    <i class="fas fa-paper-plane mr-2"></i> Initiate Transfer Request
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
