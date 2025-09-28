@extends('layouts.landlord')

@section('title', 'Edit Tenant - HybridEstate')

@section('content')
<div class="tenant-edit-page">
    <div class="page-header mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Edit Tenant</h1>
        <p class="text-gray-600 mt-1">Update tenant information</p>
    </div>

    <form action="{{ route('landlord.tenants.update', $tenant->id) }}" method="POST" class="bg-white p-6 rounded-lg shadow-sm border max-w-3xl">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="emergency_contact" class="block text-sm font-medium text-gray-700 mb-1">Emergency Contact</label>
            <input type="text" name="emergency_contact" id="emergency_contact" value="{{ old('emergency_contact', $tenant->emergency_contact) }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
            @error('emergency_contact')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="move_in_date" class="block text-sm font-medium text-gray-700 mb-1">Move In Date</label>
            <input type="date" name="move_in_date" id="move_in_date" value="{{ old('move_in_date', $tenant->move_in_date ? $tenant->move_in_date->format('Y-m-d') : '') }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
            @error('move_in_date')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="move_out_date" class="block text-sm font-medium text-gray-700 mb-1">Move Out Date</label>
            <input type="date" name="move_out_date" id="move_out_date" value="{{ old('move_out_date', $tenant->move_out_date ? $tenant->move_out_date->format('Y-m-d') : '') }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
            @error('move_out_date')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('landlord.tenants.index') }}" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary">Update Tenant</button>
        </div>
    </form>
</div>
@endsection
