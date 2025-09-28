{{-- File: resources/views/landlord/units/create.blade.php --}}
@extends('layouts.landlord')

@section('title', 'Add New Unit')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Add New Unit
            </h1>
            <p class="text-gray-600 mt-1">
                Create a new unit for your property
            </p>
        </div>
        <a href="{{ route('landlord.property.index') }}"
           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md font-medium">
            <i class="fas fa-arrow-left mr-1"></i> Back to Properties
        </a>
    </div>

    <div class="unit-form">
        <form action="{{ route('landlord.units.store') }}"
              method="POST">
            @csrf

            <div class="form-section">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Unit Information</h2>

                <div class="space-y-6">
                    <!-- Property Selection -->
                    <div>
                        <label for="property_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Property <span class="text-red-500">*</span>
                        </label>
                        <select name="property_id" id="property_id"
                                class="w-full border border-gray-300 rounded-md px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('property_id') border-red-500 @enderror"
                                required>
                            <option value="">Select Property</option>
                            @foreach($properties as $propertyOption)
                                <option value="{{ $propertyOption->id }}"
                                        {{ old('property_id', $property?->id) == $propertyOption->id ? 'selected' : '' }}>
                                    {{ $propertyOption->name }} - {{ $propertyOption->address }}
                                </option>
                            @endforeach
                        </select>
                        @error('property_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Unit Number -->
                    <div>
                        <label for="unit_number" class="block text-sm font-medium text-gray-700 mb-2">
                            Unit Number <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="unit_number" id="unit_number"
                               class="w-full border border-gray-300 rounded-md px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('unit_number') border-red-500 @enderror"
                               value="{{ old('unit_number') }}" placeholder="e.g., 101, A1, Ground Floor" required>
                        @error('unit_number')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Floor -->
                    <div>
                        <label for="floor" class="block text-sm font-medium text-gray-700 mb-2">
                            Floor
                        </label>
                        <input type="text" name="floor" id="floor"
                               class="w-full border border-gray-300 rounded-md px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('floor') border-red-500 @enderror"
                               value="{{ old('floor') }}" placeholder="e.g., 1st Floor, Ground Floor">
                        @error('floor')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Size -->
                    <div>
                        <label for="size" class="block text-sm font-medium text-gray-700 mb-2">
                            Size
                        </label>
                        <input type="text" name="size" id="size"
                               class="w-full border border-gray-300 rounded-md px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('size') border-red-500 @enderror"
                               value="{{ old('size') }}" placeholder="e.g., 1200 sq ft, 2BHK">
                        @error('size')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bedrooms and Bathrooms -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="bedrooms" class="block text-sm font-medium text-gray-700 mb-2">
                                Bedrooms
                            </label>
                            <input type="number" name="bedrooms" id="bedrooms" min="0"
                                   class="w-full border border-gray-300 rounded-md px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('bedrooms') border-red-500 @enderror"
                                   value="{{ old('bedrooms') }}" placeholder="0">
                            @error('bedrooms')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="bathrooms" class="block text-sm font-medium text-gray-700 mb-2">
                                Bathrooms
                            </label>
                            <input type="number" name="bathrooms" id="bathrooms" min="0" step="0.5"
                                   class="w-full border border-gray-300 rounded-md px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('bathrooms') border-red-500 @enderror"
                                   value="{{ old('bathrooms') }}" placeholder="0">
                            @error('bathrooms')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Rent Amount -->
                    <div>
                        <label for="rent_amount" class="block text-sm font-medium text-gray-700 mb-2">
                            Rent Amount <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
                            <input type="number" name="rent_amount" id="rent_amount" min="0" step="0.01"
                                   class="w-full border border-gray-300 rounded-md pl-8 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('rent_amount') border-red-500 @enderror"
                                   value="{{ old('rent_amount') }}" placeholder="0.00" required>
                        </div>
                        @error('rent_amount')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Features -->
                    <div>
                        <label for="features" class="block text-sm font-medium text-gray-700 mb-2">
                            Features (Optional)
                        </label>
                        <textarea name="features" id="features" rows="3"
                                  class="w-full border border-gray-300 rounded-md px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('features') border-red-500 @enderror"
                                  placeholder="Enter unit features separated by commas (e.g., Parking, Gym, Balcony)">{{ old('features') }}</textarea>
                        @error('features')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description (Optional)
                        </label>
                        <textarea name="description" id="description" rows="4"
                                  class="w-full border border-gray-300 rounded-md px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('description') border-red-500 @enderror"
                                  placeholder="Additional description or notes about the unit">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3 rounded-b-lg mt-4">
                <a href="{{ route('landlord.property.index') }}"
                   class="bg-white text-gray-700 px-6 py-3 border border-gray-300 rounded-md hover:bg-gray-50 font-medium transition duration-200">
                    Cancel
                </a>
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-md font-medium transition duration-200">
                    <i class="fas fa-plus mr-2"></i> Create Unit
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-format rent amount input
    const rentInput = document.getElementById('rent_amount');
    rentInput.addEventListener('blur', function() {
        let value = parseFloat(this.value);
        if (!isNaN(value)) {
            this.value = value.toFixed(2);
        }
    });

    // Validate bedrooms and bathrooms are non-negative
    const bedroomsInput = document.getElementById('bedrooms');
    const bathroomsInput = document.getElementById('bathrooms');

    [bedroomsInput, bathroomsInput].forEach(input => {
        input.addEventListener('input', function() {
            if (parseInt(this.value) < 0) {
                this.value = 0;
            }
        });
    });
});
</script>
@endpush

@endsection
