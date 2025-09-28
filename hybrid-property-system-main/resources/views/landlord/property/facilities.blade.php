{{-- File: resources/views/landlord/property/facilities.blade.php --}}
@extends('layouts.landlord')

@section('title', 'Property Facilities - ' . $property->name)

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Property Facilities</h1>
            <p class="text-gray-600 mt-1">{{ $property->name }} - {{ $property->address }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('landlord.property.show', $property) }}"
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md font-medium">
                <i class="fas fa-arrow-left mr-1"></i> Back to Property
            </a>
        </div>
    </div>

    <!-- Add Custom Facility Section -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Add Custom Facility</h2>
        <form action="{{ route('property.facilities.store', $property) }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Facility Name</label>
                    <input type="text" name="name" required
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select name="category" required
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Select Category</option>
                        @foreach(\App\Models\PropertyFacility::getCategories() as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="active">Active</option>
                        <option value="maintenance">Under Maintenance</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_available" value="1" checked
                               class="mr-2 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label class="text-sm font-medium text-gray-700">Available</label>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Description (Optional)</label>
                <textarea name="description" rows="2"
                          class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
            </div>
            <div class="mt-4">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md">
                    <i class="fas fa-plus mr-1"></i> Add Facility
                </button>
            </div>
        </form>
    </div>

    <!-- Add Predefined Facilities Section -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Add Predefined Facilities</h2>
        <form action="{{ route('property.facilities.add-predefined', $property) }}" method="POST" id="predefined-facilities-form">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                @foreach(\App\Models\PropertyFacility::getPredefinedFacilities() as $category => $facilities)
                    <div>
                        <h4 class="font-medium text-gray-800 mb-2">{{ \App\Models\PropertyFacility::getCategories()[$category] }}</h4>
                        <div class="space-y-2">
                            @foreach($facilities as $facility)
                                <label class="flex items-center">
                                    <input type="checkbox" name="facilities[]" value="{{ json_encode(['name' => $facility, 'category' => $category]) }}"
                                           class="mr-2 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <span class="text-sm text-gray-700">{{ $facility }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
                <i class="fas fa-plus-circle mr-1"></i> Add Selected Facilities
            </button>
        </form>
    </div>

    <!-- Current Facilities Section -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-medium text-gray-900">Current Facilities</h2>
            <span class="text-sm text-gray-500">{{ $facilities->count() }} facility(ies)</span>
        </div>

        @if($facilities->count() > 0)
            @foreach($facilitiesByCategory as $category => $categoryFacilities)
                <div class="mb-6">
                    <h3 class="text-md font-medium text-gray-800 mb-3">
                        {{ \App\Models\PropertyFacility::getCategories()[$category] ?? ucfirst($category) }}
                        ({{ $categoryFacilities->count() }})
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($categoryFacilities as $facility)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center mb-2">
                                            <h4 class="text-sm font-medium text-gray-900">{{ $facility->name }}</h4>
                                            <span class="ml-2 inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                @if($facility->status === 'active') bg-green-100 text-green-800
                                                @elseif($facility->status === 'maintenance') bg-yellow-100 text-yellow-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ ucfirst($facility->status) }}
                                            </span>
                                        </div>

                                        @if($facility->description)
                                            <p class="text-xs text-gray-600 mb-2">{{ $facility->description }}</p>
                                        @endif

                                        <div class="flex items-center space-x-4">
                                            <label class="flex items-center">
                                                <input type="checkbox" class="toggle-availability mr-2 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                                       data-facility-id="{{ $facility->id }}" {{ $facility->is_available ? 'checked' : '' }}>
                                                <span class="text-xs text-gray-600">Available</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="flex space-x-1">
                                        <button onclick="editFacility({{ $facility->id }}, '{{ $facility->name }}', '{{ $facility->category }}', '{{ $facility->description }}', '{{ $facility->status }}', {{ $facility->is_available ? 'true' : 'false' }})"
                                                class="bg-yellow-600 hover:bg-yellow-700 text-white p-2 rounded"
                                                title="Edit Facility">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <form action="{{ route('property.facilities.destroy', [$property, $facility]) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Are you sure you want to delete this facility?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="bg-red-600 hover:bg-red-700 text-white p-2 rounded"
                                                    title="Delete Facility">
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
                <i class="fas fa-cogs text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg mb-2">No facilities added yet</p>
                <p class="text-gray-400 text-sm">Add facilities to showcase your property's amenities and features</p>
            </div>
        @endif
    </div>

    <!-- Edit Facility Modal -->
    <div id="editFacilityModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex justify-center items-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Edit Facility</h3>
                </div>
                <form id="editFacilityForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Facility Name</label>
                            <input type="text" id="edit_name" name="name" required
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select id="edit_category" name="category" required
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                @foreach(\App\Models\PropertyFacility::getCategories() as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea id="edit_description" name="description" rows="3"
                                      class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select id="edit_status" name="status"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="active">Active</option>
                                <option value="maintenance">Under Maintenance</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="edit_is_available" name="is_available" value="1"
                                   class="mr-2 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label class="text-sm font-medium text-gray-700">Available</label>
                        </div>
                    </div>
                    <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                        <button type="button" onclick="closeEditModal()"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md">
                            Cancel
                        </button>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md">
                            Update Facility
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Facility Management Tips -->
    @if($facilities->count() > 0)
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-6">
            <h3 class="text-sm font-medium text-blue-800 mb-2">Facility Management Tips:</h3>
            <ul class="text-sm text-blue-700 space-y-1">
                <li>• Mark facilities as unavailable when under maintenance</li>
                <li>• Use categories to organize facilities logically</li>
                <li>• Add detailed descriptions for better tenant understanding</li>
                <li>• Predefined facilities can be added quickly in bulk</li>
            </ul>
        </div>
    @endif
</div>

@push('scripts')
<script>
function editFacility(id, name, category, description, status, isAvailable) {
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_category').value = category;
    document.getElementById('edit_description').value = description || '';
    document.getElementById('edit_status').value = status;
    document.getElementById('edit_is_available').checked = isAvailable;

    // Update form action
    const form = document.getElementById('editFacilityForm');
    form.action = `/landlord/property/{{ $property->id }}/facilities/${id}`;

    document.getElementById('editFacilityModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editFacilityModal').classList.add('hidden');
}

// Toggle availability
document.addEventListener('DOMContentLoaded', function() {
    const toggles = document.querySelectorAll('.toggle-availability');

    toggles.forEach(toggle => {
        toggle.addEventListener('change', function() {
            const facilityId = this.dataset.facilityId;

            fetch(`/landlord/property/{{ $property->id }}/facilities/${facilityId}/toggle-availability`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the status badge
                    const badge = this.closest('.border').querySelector('.rounded-full');
                    if (data.is_available) {
                        badge.className = badge.className.replace(/bg-\w+/, 'bg-green').replace(/text-\w+/, 'text-green');
                        badge.textContent = 'Active';
                    } else {
                        badge.className = badge.className.replace(/bg-\w+/, 'bg-yellow').replace(/text-\w+/, 'text-yellow');
                        badge.textContent = 'Maintenance';
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.checked = !this.checked; // Revert the toggle
            });
        });
    });
});
</script>
@endpush

@endsection
