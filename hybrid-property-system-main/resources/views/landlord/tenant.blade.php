{{-- File: resources/views/landlord/tenant.blade.php --}}
@extends('layouts.landlord')

@section('title', 'My Tenants - HybridEstate')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">My Tenants</h1>
            <p class="text-gray-600 mt-1">Manage and view all your tenants</p>
        </div>
        <a href="{{ route('landlord.tenants.create') }}"
           class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md font-medium">
            <i class="fas fa-plus mr-1"></i> Add Tenant
        </a>
    </div>

    <!-- Tenants Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-users text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Tenants</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_tenants'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-file-contract text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active Leases</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['active_leases'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <i class="fas fa-clock text-yellow-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending Screening</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['pending_screening'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-4">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 rounded-lg">
                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Overdue Payments</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['overdue_payments'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex flex-wrap gap-4 items-center">
            <div class="relative">
                <input type="text" id="tenant-search" placeholder="Search tenants..."
                       class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
            </div>

            <select id="property-filter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                <option value="">All Properties</option>
                @foreach($properties as $property)
                    <option value="{{ $property->id }}">{{ $property->address }}</option>
                @endforeach
            </select>

            <select id="status-filter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="pending">Pending</option>
                <option value="expired">Expired</option>
            </select>

            <button class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md font-medium" onclick="clearFilters()">
                <i class="fas fa-times mr-1"></i> Clear
            </button>
        </div>
    </div>

    <!-- Tenants Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Tenant List</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tenant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Property</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lease Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rent Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Move In Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($tenants as $tenant)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    @if($tenant->user->profile_photo_url)
                                        <img class="h-10 w-10 rounded-full object-cover"
                                             src="{{ $tenant->user->profile_photo_url }}" alt="">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                            <i class="fas fa-user text-gray-600"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $tenant->user->first_name }} {{ $tenant->user->last_name }}
                                    </div>
                                    <div class="text-sm text-gray-500">{{ $tenant->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $tenant->leases->first()->unit->property->address ?? 'N/A' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $tenant->leases->first()->unit->unit_number ?? 'N/A' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($tenant->leases->where('end_date', '>=', now())->count() > 0)
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            @elseif($tenant->leases->count() > 0)
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    Expired
                                </span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                    No Lease
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            ${{ number_format($tenant->leases->first()->rent_amount ?? 0, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $latestPayment = $tenant->payments->sortByDesc('due_date')->first();
                                $isOverdue = $latestPayment && $latestPayment->status === 'pending' && $latestPayment->due_date < now();
                            @endphp

                            @if($isOverdue)
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    Overdue
                                </span>
                            @elseif($latestPayment && $latestPayment->status === 'paid')
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Paid
                                </span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Pending
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $tenant->move_in_date ? \Carbon\Carbon::parse($tenant->move_in_date)->format('M d, Y') : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('landlord.tenants.show', $tenant) }}"
                                   class="text-indigo-600 hover:text-indigo-900" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('landlord.tenants.edit', $tenant) }}"
                                   class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="text-blue-600 hover:text-blue-900"
                                        onclick="sendMessage({{ $tenant->id }})" title="Send Message">
                                    <i class="fas fa-envelope"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-900"
                                        onclick="removeTenant({{ $tenant->id }})" title="Remove">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <i class="fas fa-users text-4xl mb-4"></i>
                                <p class="text-lg">No tenants found</p>
                                <p class="text-sm">Add your first tenant to get started</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(isset($tenants) && method_exists($tenants, 'links'))
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $tenants->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
// Tenant actions
function sendMessage(tenantId) {
    window.location.href = `/landlord/tenants/${tenantId}/message`;
}

function removeTenant(tenantId) {
    if (confirm('Are you sure you want to remove this tenant?')) {
        fetch(`/landlord/tenants/${tenantId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        }).then(response => {
            if (response.ok) {
                location.reload();
            }
        });
    }
}

// Filter functions
function clearFilters() {
    document.getElementById('tenant-search').value = '';
    document.getElementById('property-filter').value = '';
    document.getElementById('status-filter').value = '';
}

// Search functionality
document.getElementById('tenant-search').addEventListener('input', function() {
    // Implement search logic
});
</script>
@endpush

@endsection
