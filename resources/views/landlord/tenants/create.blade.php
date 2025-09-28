@extends('layouts.landlord')

@section('title', 'Add Tenant - HybridEstate')

@section('content')
<div class="tenant-create-page">
    <div class="page-header mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Add New Tenant</h1>
        <p class="text-gray-600 mt-1">Add an existing user as a tenant</p>
    </div>

    <form action="{{ route('landlord.tenants.store') }}" method="POST" class="bg-white p-6 rounded-lg shadow-sm border max-w-3xl">
        @csrf

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">User Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                   placeholder="Enter the email of the user to add as tenant">
            @error('email')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="property_id" class="block text-sm font-medium text-gray-700 mb-1">Property</label>
            <select name="property_id" id="property_id" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                <option value="">Select Property</option>
                @foreach($properties as $property)
                    <option value="{{ $property->id }}" {{ old('property_id') == $property->id ? 'selected' : '' }}>
                        {{ $property->address }}
                    </option>
                @endforeach
            </select>
            @error('property_id')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="unit_id" class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
            <select name="unit_id" id="unit_id" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                <option value="">Select Unit</option>
            </select>
            @error('unit_id')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="move_in_date" class="block text-sm font-medium text-gray-700 mb-1">Move In Date</label>
            <input type="date" name="move_in_date" id="move_in_date" value="{{ old('move_in_date') }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
            @error('move_in_date')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('landlord.tenants.index') }}" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary">Add Tenant</button>
        </div>
    </form>
</div>

<script>
document.getElementById('property_id').addEventListener('change', function() {
    const propertyId = this.value;
    const unitSelect = document.getElementById('unit_id');

    if (propertyId) {
        fetch(`/landlord/properties/${propertyId}/units`)
            .then(response => response.json())
            .then(data => {
                unitSelect.innerHTML = '<option value="">Select Unit</option>';
                data.forEach(unit => {
                    const option = document.createElement('option');
                    option.value = unit.id;
                    option.textContent = `${unit.unit_number} - $${unit.rent_amount}`;
                    unitSelect.appendChild(option);
                });
            });
    } else {
        unitSelect.innerHTML = '<option value="">Select Unit</option>';
    }
});
</script>

@endsection
