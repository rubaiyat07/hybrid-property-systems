{{-- File: resources/views/admin/property/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Property Registrations')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Property Registration Management</h1>
        <p class="text-gray-600 mt-1">Review and approve property registration requests</p>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-white shadow rounded p-4 text-center">
        <h3 class="font-semibold text-gray-700">Total Properties</h3>
        <p class="text-2xl font-bold text-indigo-600">{{ $stats['total'] }}</p>
    </div>
    <div class="bg-white shadow rounded p-4 text-center">
        <h3 class="font-semibold text-gray-700">Pending Review</h3>
        <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
    </div>
    <div class="bg-white shadow rounded p-4 text-center">
        <h3 class="font-semibold text-gray-700">Approved</h3>
        <p class="text-2xl font-bold text-green-600">{{ $stats['approved'] }}</p>
    </div>
    <div class="bg-white shadow rounded p-4 text-center">
        <h3 class="font-semibold text-gray-700">Rejected</h3>
        <p class="text-2xl font-bold text-red-600">{{ $stats['rejected'] }}</p>
    </div>
</div>

<!-- Filters and Search -->
<div class="bg-white shadow rounded-lg mb-6">
    <div class="px-6 py-4">
        <form method="GET" action="{{ route('admin.property.index') }}" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-64">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" id="search" 
                       value="{{ request('search') }}"
                       placeholder="Property name, address, or owner name..."
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <div class="min-w-32">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status" 
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All</option>
                </select>
            </div>
            <div>
                <button type="submit" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md">
                    <i class="fas fa-search mr-1"></i> Filter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Properties List -->
<div class="bg-white shadow rounded-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h2 class="text-lg font-medium text-gray-900">Property Registrations</h2>
        
        @if($status === 'pending' && $properties->count() > 0)
            <div class="flex space-x-2">
                <button onclick="openBulkApproveModal()" 
                        class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                    <i class="fas fa-check mr-1"></i> Bulk Approve
                </button>
                <button onclick="openBulkRejectModal()" 
                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                    <i class="fas fa-times mr-1"></i> Bulk Reject
                </button>
            </div>
        @endif
    </div>
    
    @if($properties->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        @if($status === 'pending')
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <input type="checkbox" id="selectAll" class="rounded border-gray-300">
                            </th>
                        @endif
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Property
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Owner
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Location
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Type
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Submitted
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($properties as $property)
                        <tr class="hover:bg-gray-50">
                            @if($status === 'pending')
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox" name="property_ids[]" value="{{ $property->id }}" 
                                           class="property-checkbox rounded border-gray-300">
                                </td>
                            @endif
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-12 w-12">
                                        @if($property->image)
                                            <img class="h-12 w-12 rounded-lg object-cover" 
                                                 src="{{ $property->image }}" alt="{{ $property->name }}">
                                        @else
                                            <div class="h-12 w-12 rounded-lg bg-gray-200 flex items-center justify-center">
                                                <i class="fas fa-building text-gray-400"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $property->name }}</div>
                                        <div class="text-sm text-gray-500">ID: #{{ $property->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $property->owner->name }}</div>
                                <div class="text-sm text-gray-500">{{ $property->owner->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $property->address }}</div>
                                <div class="text-sm text-gray-500">{{ $property->city }}, {{ $property->state }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ ucfirst($property->type) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($property->registration_status === 'pending')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                @elseif($property->registration_status === 'approved')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Approved
                                    </span>
                                @elseif($property->registration_status === 'rejected')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Rejected
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $property->created_at->format('M d, Y') }}
                                <br><small>{{ $property->created_at->diffForHumans() }}</small>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.property.show', $property) }}" 
                                       class="text-indigo-600 hover:text-indigo-900" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if($property->registration_status === 'pending')
                                        <button onclick="openQuickApproveModal({{ $property->id }}, '{{ addslashes($property->name) }}')" 
                                                class="text-green-600 hover:text-green-900" title="Quick Approve">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button onclick="openQuickRejectModal({{ $property->id }}, '{{ addslashes($property->name) }}')" 
                                                class="text-red-600 hover:text-red-900" title="Quick Reject">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @elseif($property->registration_status !== 'pending')
                                        <button onclick="openResetToPendingModal({{ $property->id }}, '{{ addslashes($property->name) }}')" 
                                                class="text-blue-600 hover:text-blue-900" title="Reset to Pending">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if(method_exists($properties, 'links'))
            <div class="px-6 py-4">
                {{ $properties->appends(request()->query())->links() }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <div class="mx-auto h-24 w-24 text-gray-300">
                <i class="fas fa-clipboard-list text-6xl"></i>
            </div>
            <h3 class="mt-4 text-lg font-medium text-gray-900">No properties found</h3>
            <p class="mt-2 text-gray-500">
                @if($status === 'pending')
                    No pending property registrations at the moment.
                @else
                    No properties match the selected criteria.
                @endif
            </p>
        </div>
    @endif
</div>

<!-- Bulk Actions Modals -->
@include('partials.admin.property.bulk-approve-modal')
@include('partials.admin.property.bulk-reject-modal')
@include('partials.admin.property.quick-action-modals')

@push('scripts')
<script>
// Select all checkbox functionality
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.property-checkbox');
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        });
    }
});

// Bulk approve modal
function openBulkApproveModal() {
    const selected = document.querySelectorAll('.property-checkbox:checked');
    if (selected.length === 0) {
        alert('Please select properties to approve.');
        return;
    }
    document.getElementById('bulkApproveModal').classList.remove('hidden');
}

// Bulk reject modal
function openBulkRejectModal() {
    const selected = document.querySelectorAll('.property-checkbox:checked');
    if (selected.length === 0) {
        alert('Please select properties to reject.');
        return;
    }
    document.getElementById('bulkRejectModal').classList.remove('hidden');
}

// Close modal functions
function closeBulkApproveModal() {
    document.getElementById('bulkApproveModal').classList.add('hidden');
}

function closeBulkRejectModal() {
    document.getElementById('bulkRejectModal').classList.add('hidden');
}



// Close modals when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });
    });
});
</script>
@endpush

@endsection