{{-- File: resources/views/landlord/property/gallery.blade.php --}}
@extends('layouts.landlord')

@section('title', 'Property Gallery - ' . $property->name)

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Property Gallery</h1>
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
        <h2 class="text-lg font-medium text-gray-900 mb-4">Upload Images</h2>
        <form action="{{ route('landlord.property.images.store', $property) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="flex items-center space-x-4">
                <input type="file" name="images[]" multiple accept="image/*"
                       class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md">
                    <i class="fas fa-upload mr-1"></i> Upload Images
                </button>
            </div>
            <p class="text-sm text-gray-500 mt-2">Maximum 5MB per image. Supported formats: JPEG, PNG, JPG, GIF</p>
            @error('images.*')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </form>
    </div>

    <!-- Gallery Section -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-medium text-gray-900">Property Images</h2>
            <span class="text-sm text-gray-500">{{ $images->count() }} image(s)</span>
        </div>

        @if($images->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($images as $image)
                    <div class="relative group">
                        <div class="aspect-square bg-gray-200 rounded-lg overflow-hidden">
                            <img src="{{ $image->file_path }}"
                                 alt="Property Image"
                                 class="w-full h-full object-cover">

                            <!-- Primary Badge -->
                            @if($image->is_primary)
                                <div class="absolute top-2 left-2 bg-green-500 text-white text-xs px-2 py-1 rounded">
                                    Primary
                                </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center space-x-2">
                                @if(!$image->is_primary)
                                    <form action="{{ route('landlord.property.images.set-primary', [$property, $image]) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="bg-green-600 hover:bg-green-700 text-white p-2 rounded"
                                                title="Set as Primary">
                                            <i class="fas fa-star"></i>
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('landlord.property.images.destroy', [$property, $image]) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Are you sure you want to delete this image?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="bg-red-600 hover:bg-red-700 text-white p-2 rounded"
                                            title="Delete Image">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-images text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg mb-2">No images uploaded yet</p>
                <p class="text-gray-400 text-sm">Upload some images to showcase your property</p>
            </div>
        @endif
    </div>

    <!-- Image Management Tips -->
    @if($images->count() > 0)
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-6">
            <h3 class="text-sm font-medium text-blue-800 mb-2">Image Management Tips:</h3>
            <ul class="text-sm text-blue-700 space-y-1">
                <li>• The primary image will be displayed as the main property image</li>
                <li>• Hover over images to see management options</li>
                <li>• You can set any image as primary by clicking the star icon</li>
                <li>• Delete images you no longer need using the trash icon</li>
            </ul>
        </div>
    @endif
</div>

@push('scripts')
<script>
// Add any JavaScript for image preview or drag-and-drop functionality
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.querySelector('input[name="images[]"]');

    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const files = e.target.files;
            if (files.length > 0) {
                console.log(`${files.length} file(s) selected`);
            }
        });
    }
});
</script>
@endpush

@endsection
