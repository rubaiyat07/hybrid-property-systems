{{-- File: resources/views/landlord/units/edit.blade.php --}}
@extends('layouts.landlord')

@section('title', 'Edit Unit - ' . $unit->unit_number)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Unit</h1>
            <p class="text-gray-600 mt-1">Update the details for {{ $unit->unit_number }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('landlord.units.show', $unit) }}"
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md font-medium">
                <i class="fas fa-arrow-left mr-1"></i> Back to Unit
            </a>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg">
        <form action="{{ route('landlord.units.update', $unit) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Unit Information</h2>
            </div>

            <div class="p-6 space-y-6">
                <!-- Unit Number -->
                <div>
                    <label for="unit_number" class="block text-sm font-medium text-gray-700 mb-1">
                        Unit Number <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="unit_number" id="unit_number"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('unit_number') border-red-500 @enderror"
                           value="{{ old('unit_number', $unit->unit_number) }}" placeholder="e.g., 101, A1, Ground Floor" required>
                    @error('unit_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Floor -->
                <div>
                    <label for="floor" class="block text-sm font-medium text-gray-700 mb-1">
                        Floor
                    </label>
                    <input type="text" name="floor" id="floor"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('floor') border-red-500 @enderror"
                           value="{{ old('floor', $unit->floor) }}" placeholder="e.g., 1st Floor, Ground Floor">
                    @error('floor')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Size -->
                <div>
                    <label for="size" class="block text-sm font-medium text-gray-700 mb-1">
                        Size
                    </label>
                    <input type="text" name="size" id="size"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('size') border-red-500 @enderror"
                           value="{{ old('size', $unit->size) }}" placeholder="e.g., 1200 sq ft, 2BHK">
                    @error('size')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Bedrooms and Bathrooms -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="bedrooms" class="block text-sm font-medium text-gray-700 mb-1">
                            Bedrooms
                        </label>
                        <input type="number" name="bedrooms" id="bedrooms" min="0"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('bedrooms') border-red-500 @enderror"
                               value="{{ old('bedrooms', $unit->bedrooms) }}" placeholder="0">
                        @error('bedrooms')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="bathrooms" class="block text-sm font-medium text-gray-700 mb-1">
                            Bathrooms
                        </label>
                        <input type="number" name="bathrooms" id="bathrooms" min="0" step="0.5"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('bathrooms') border-red-500 @enderror"
                               value="{{ old('bathrooms', $unit->bathrooms) }}" placeholder="0">
                        @error('bathrooms')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Rent Amount -->
                <div>
                    <label for="rent_amount" class="block text-sm font-medium text-gray-700 mb-1">
                        Rent Amount <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">$</span>
                        <input type="number" name="rent_amount" id="rent_amount" min="0" step="0.01"
                               class="w-full border border-gray-300 rounded-md pl-8 pr-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('rent_amount') border-red-500 @enderror"
                               value="{{ old('rent_amount', $unit->rent_amount) }}" placeholder="0.00" required>
                    </div>
                    @error('rent_amount')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" id="status"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('status') border-red-500 @enderror" required>
                        <option value="vacant" {{ old('status', $unit->status) == 'vacant' ? 'selected' : '' }}>Vacant</option>
                        <option value="occupied" {{ old('status', $unit->status) == 'occupied' ? 'selected' : '' }}>Occupied</option>
                        <option value="maintenance" {{ old('status', $unit->status) == 'maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Features -->
                <div>
                    <label for="features" class="block text-sm font-medium text-gray-700 mb-1">
                        Features (Optional)
                    </label>
                    <textarea name="features" id="features" rows="3"
                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('features') border-red-500 @enderror"
                              placeholder="Enter unit features separated by commas (e.g., Parking, Gym, Balcony)">{{ old('features', is_array($unit->features) ? implode(', ', $unit->features) : $unit->features) }}</textarea>
                    @error('features')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                        Description (Optional)
                    </label>
                    <textarea name="description" id="description" rows="4"
                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('description') border-red-500 @enderror"
                              placeholder="Additional description or notes about the unit">{{ old('description', $unit->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Property Info (Read-only) -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Property Information</h3>
                    <div class="text-sm text-gray-600">
                        <div><strong>Property:</strong> {{ $unit->property->name }}</div>
                        <div><strong>Address:</strong> {{ $unit->property->address }}</div>
                        <div><strong>Location:</strong> {{ $unit->property->city }}, {{ $unit->property->state }} {{ $unit->property->zip_code }}</div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3 rounded-b-lg">
                <a href="{{ route('landlord.units.show', $unit) }}"
                   class="bg-white text-gray-700 px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50 font-medium">
                    Cancel
                </a>
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md font-medium">
                    <i class="fas fa-save mr-1"></i> Update Unit
                </button>
            </div>
        </form>
    </div>

    <!-- Danger Zone -->
    <div class="mt-8 bg-white shadow rounded-lg border border-red-200">
        <div class="px-6 py-4 border-b border-red-200">
            <h2 class="text-lg font-medium text-red-900">Danger Zone</h2>
        </div>
        <div class="p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-sm font-medium text-red-900">Delete this unit</h3>
                    <p class="text-sm text-red-700 mt-1">
                        Once you delete a unit, there is no going back. Please be certain.
                        <br><strong>Note:</strong> Units with active leases cannot be deleted.
                    </p>
                </div>
                <button type="button" onclick="confirmDelete()"
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-trash mr-1"></i> Delete Unit
                </button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg max-w-md w-full">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                    </div>
                    <div class="text-center">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Delete Unit</h3>
                        <p class="text-sm text-gray-500 mb-4">
                            Are you sure you want to delete unit "{{ $unit->unit_number }}"?
                            @if($unit->leases->where('end_date', '>=', now())->count() > 0)
                                <br><strong class="text-red-600">Warning:</strong> This unit has active leases.
                            @endif
                            This action cannot be undone.
                        </p>
                        <div class="flex justify-center space-x-3">
                            <button type="button" onclick="closeDeleteModal()"
                                    class="bg-white text-gray-700 px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50">
                                Cancel
                            </button>
                            <form method="POST" action="{{ route('landlord.units.destroy', $unit) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                                    Delete Unit
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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

function confirmDelete() {
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
@endpush

@endsection
