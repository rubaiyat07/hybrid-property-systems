{{-- File: resources/views/landlord/property/edit.blade.php --}}
@extends('layouts.landlord')

@section('title', 'Edit Property - ' . $property->name)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Property</h1>
            <p class="text-gray-600 mt-1">Update the details for {{ $property->name }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('landlord.property.show', $property) }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md font-medium">
                <i class="fas fa-arrow-left mr-1"></i> Back to Property
            </a>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg">
        <form action="{{ route('landlord.property.update', $property) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Property Information</h2>
            </div>

            <div class="p-6 space-y-6">
                <!-- Property Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        Property Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('name') border-red-500 @enderror"
                           value="{{ old('name', $property->name) }}" placeholder="e.g., Sunset Apartments" required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Property Type -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">
                        Property Type <span class="text-red-500">*</span>
                    </label>
                    <select name="type" id="type" 
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('type') border-red-500 @enderror" required>
                        <option value="">Select Property Type</option>
                        <option value="apartment" {{ old('type', $property->type) == 'apartment' ? 'selected' : '' }}>Apartment</option>
                        <option value="house" {{ old('type', $property->type) == 'house' ? 'selected' : '' }}>House</option>
                        <option value="condo" {{ old('type', $property->type) == 'condo' ? 'selected' : '' }}>Condo</option>
                        <option value="townhouse" {{ old('type', $property->type) == 'townhouse' ? 'selected' : '' }}>Townhouse</option>
                        <option value="commercial" {{ old('type', $property->type) == 'commercial' ? 'selected' : '' }}>Commercial</option>
                        <option value="other" {{ old('type', $property->type) == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Address -->
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">
                        Street Address <span class="text-red-500">*</span>
                    </label>
                    <textarea name="address" id="address" rows="2"
                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('address') border-red-500 @enderror"
                              placeholder="Enter full street address" required>{{ old('address', $property->address) }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- City, State, ZIP -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-1">
                            City <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="city" id="city" 
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('city') border-red-500 @enderror"
                               value="{{ old('city', $property->city) }}" placeholder="City" required>
                        @error('city')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="state" class="block text-sm font-medium text-gray-700 mb-1">
                            State <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="state" id="state" 
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('state') border-red-500 @enderror"
                               value="{{ old('state', $property->state) }}" placeholder="State" required>
                        @error('state')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="zip_code" class="block text-sm font-medium text-gray-700 mb-1">
                            ZIP Code <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="zip_code" id="zip_code" 
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('zip_code') border-red-500 @enderror"
                               value="{{ old('zip_code', $property->zip_code) }}" placeholder="ZIP Code" required>
                        @error('zip_code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                        Property Description
                    </label>
                    <textarea name="description" id="description" rows="4"
                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('description') border-red-500 @enderror"
                              placeholder="Describe the property features, amenities, etc.">{{ old('description', $property->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Current Image Display -->
                @if($property->image)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Current Image</label>
                        <div class="mb-4">
                            <img src="{{ $property->image }}" alt="{{ $property->name }}" 
                                 class="h-32 w-48 object-cover rounded-lg border border-gray-300">
                        </div>
                    </div>
                @endif

            <!-- Property Image Upload -->
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700 mb-1">
                    {{ $property->image ? 'Update Property Image' : 'Property Image' }}
                </label>
                <div class="flex items-center justify-center w-full">
                    <label for="image" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-3"></i>
                            <p class="mb-2 text-sm text-gray-500">
                                <span class="font-semibold">Click to {{ $property->image ? 'replace' : 'upload' }}</span> or drag and drop
                            </p>
                            <p class="text-xs text-gray-500">PNG, JPG or GIF (MAX. 2MB)</p>
                        </div>
                        <input type="file" name="image" id="image" class="hidden" accept="image/*">
                    </label>
                </div>
                @error('image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Multiple Images Upload Link -->
            <div class="mt-6">
                <a href="{{ route('landlord.property.images.index', $property) }}"                   class="inline-block bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md font-medium">
                    <i class="fas fa-images mr-2"></i> Manage Photo Gallery
                </a>
            </div>

                <!-- Property Availability Status -->
                <div>
                    <label for="availability_status" class="block text-sm font-medium text-gray-700 mb-1">
                        Property Availability Status
                    </label>
                    <select name="availability_status" id="availability_status"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('availability_status') border-red-500 @enderror">
                        <option value="active" {{ old('availability_status', $property->availability_status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('availability_status', $property->availability_status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="maintenance" {{ old('availability_status', $property->availability_status) == 'maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                    </select>
                    @error('availability_status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3 rounded-b-lg">
                <a href="{{ route('landlord.property.show', $property) }}" 
                   class="bg-white text-gray-700 px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50 font-medium">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md font-medium">
                    <i class="fas fa-save mr-1"></i> Update Property
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
                    <h3 class="text-sm font-medium text-red-900">Delete this property</h3>
                    <p class="text-sm text-red-700 mt-1">
                        Once you delete a property, there is no going back. Please be certain.
                        <br><strong>Note:</strong> Properties with active leases cannot be deleted.
                    </p>
                </div>
                <button type="button" onclick="confirmDelete()" 
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-trash mr-1"></i> Delete Property
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
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Delete Property</h3>
                        <p class="text-sm text-gray-500 mb-4">
                            Are you sure you want to delete "{{ $property->name }}"? This action cannot be undone.
                        </p>
                        <div class="flex justify-center space-x-3">
                            <button type="button" onclick="closeDeleteModal()" 
                                    class="bg-white text-gray-700 px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50">
                                Cancel
                            </button>
                            <form method="POST" action="{{ route('landlord.property.destroy', $property) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                                    Delete Property
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
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // You can add image preview functionality here if needed
            console.log('File selected:', file.name);
        };
        reader.readAsDataURL(file);
    }
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