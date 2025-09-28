{{-- File: resources/views/landlord/property/create.blade.php --}}
@extends('layouts.landlord')

@section('title', isset($property) ? 'Edit Property' : 'Add New Property')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                {{ isset($property) ? 'Edit Property' : 'Add New Property' }}
            </h1>
            <p class="text-gray-600 mt-1">
                {{ isset($property) ? 'Update the property details' : 'Fill in the details to add a new property to your portfolio' }}
            </p>
        </div>
        <a href="{{ route('landlord.property.index') }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md font-medium">
            <i class="fas fa-arrow-left mr-1"></i> Back to Properties
        </a>
    </div>

    <div class="property-form">
        <form action="{{ isset($property) ? route('landlord.property.update', $property) : route('landlord.property.store') }}" 
              method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($property))
                @method('PUT')
            @endif
            
            <div class="form-section">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Property Information</h2>

                <div class="space-y-6">
                    <!-- Property Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Property Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" 
                               class="w-full border border-gray-300 rounded-md px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('name') border-red-500 @enderror"
                               value="{{ old('name', $property->name ?? '') }}" placeholder="e.g., Sunset Apartments" required>
                        @error('name')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Property Type -->
<div>
    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
        Property Type <span class="text-red-500">*</span>
    </label>
    <select name="type" id="type"
            class="w-full border border-gray-300 rounded-md px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('type') border-red-500 @enderror"
            required>
        <option value="">Select Property Type</option>
        @foreach($propertyTypes as $value => $label)
            <option value="{{ $value }}" {{ old('type', $property->type ?? '') == $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
    @error('type')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

                    <!-- Address -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                            Street Address <span class="text-red-500">*</span>
                        </label>
                        <textarea name="address" id="address" rows="3"
                                  class="w-full border border-gray-300 rounded-md px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('address') border-red-500 @enderror"
                                  placeholder="Enter full street address" required>{{ old('address', $property->address ?? '') }}</textarea>
                        @error('address')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- City, State, ZIP -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                                City <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="city" id="city" 
                                   class="w-full border border-gray-300 rounded-md px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('city') border-red-500 @enderror"
                                   value="{{ old('city', $property->city ?? '') }}" placeholder="City" required>
                            @error('city')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="state" class="block text-sm font-medium text-gray-700 mb-2">
                                State <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="state" id="state" 
                                   class="w-full border border-gray-300 rounded-md px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('state') border-red-500 @enderror"
                                   value="{{ old('state', $property->state ?? '') }}" placeholder="State" required>
                            @error('state')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="zip_code" class="block text-sm font-medium text-gray-700 mb-2">
                                ZIP Code <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="zip_code" id="zip_code" 
                                   class="w-full border border-gray-300 rounded-md px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('zip_code') border-red-500 @enderror"
                                   value="{{ old('zip_code', $property->zip_code ?? '') }}" placeholder="ZIP Code" required>
                            @error('zip_code')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Property Description
                        </label>
                        <textarea name="description" id="description" rows="4"
                                  class="w-full border border-gray-300 rounded-md px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('description') border-red-500 @enderror"
                                  placeholder="Describe the property features, amenities, etc.">{{ old('description', $property->description ?? '') }}</textarea>
                        @error('description')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Property Image -->
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                            Property Image
                        </label>
                        <div class="file-upload-area" id="fileUploadArea">
                            <div class="file-upload-icon">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <p class="mb-2 text-sm text-gray-600">
                                <span class="font-semibold">Click to upload</span> or drag and drop
                            </p>
                            <p class="text-xs text-gray-500">PNG, JPG or GIF (MAX. 2MB)</p>
                            <input type="file" name="image" id="image" class="hidden" accept="image/*">
                        </div>
                        @if(isset($property) && $property->image)
                            <div class="mt-2">
                                <img src="{{ $property->image }}" alt="Property Image" class="h-32 rounded-md border">
                            </div>
                        @endif
                        @error('image')
                            <p class="error-message">{{ $message }}</p>
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
                    <i class="fas fa-save mr-2"></i> {{ isset($property) ? 'Update Property' : 'Create Property' }}
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileUploadArea = document.getElementById('fileUploadArea');
    const fileInput = document.getElementById('image');

    fileUploadArea.addEventListener('click', () => fileInput.click());

    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        const fileName = file.name;
        fileUploadArea.innerHTML = `
            <div class="text-center">
                <i class="fas fa-check-circle text-green-500 text-2xl mb-2"></i>
                <p class="text-sm font-medium text-gray-700">${fileName}</p>
                <p class="text-xs text-gray-500">Click to change file</p>
            </div>
            <input type="file" name="image" id="image" class="hidden" accept="image/*">
        `;

        document.getElementById('image').addEventListener('change', arguments.callee);
    });

    fileUploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        fileUploadArea.classList.add('dragover');
    });

    fileUploadArea.addEventListener('dragleave', function() {
        fileUploadArea.classList.remove('dragover');
    });

    fileUploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        fileUploadArea.classList.remove('dragover');

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            fileInput.dispatchEvent(new Event('change'));
        }
    });
});
</script>
@endpush

@endsection
