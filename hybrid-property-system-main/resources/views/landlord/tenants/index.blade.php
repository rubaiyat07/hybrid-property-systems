{{-- File: resources/views/landlord/tenants/index.blade.php --}}
@extends('layouts.landlord')

@section('title', 'Tenant Management')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Tenant Management</h1>
            <p class="text-gray-600 mt-1">Manage inquiries, applications, and current tenants</p>
        </div>
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

    <!-- Filters -->
    <div class="bg-white shadow rounded-lg p-4 mb-6">
        <form method="GET" action="{{ route('landlord.tenants.index') }}" class="flex flex-wrap gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                       class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                       placeholder="Search tenants...">
            </div>

            <div>
                <label for="property_id" class="block text-sm font-medium text-gray-700 mb-1">Property</label>
                <select name="property_id" id="property_id"
                        class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Properties</option>
                    @foreach($properties as $property)
                        <option value="{{ $property->id }}" {{ request('property_id') == $property->id ? 'selected' : '' }}>
                            {{ $property->address }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Lease Status</label>
                <select name="status" id="status"
                        class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md font-medium">
                    <i class="fas fa-filter mr-1"></i> Filter
                </button>
            </div>

            <div class="flex items-end">
                <a href="{{ route('landlord.tenants.index') }}"
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md font-medium">
                    <i class="fas fa-times mr-1"></i> Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Inquiries & Applications -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Inquiries & Applications</h2>
            <p class="text-sm text-gray-600 mt-1">Review and respond to tenant inquiries and applications</p>
        </div>

        @php
            $applications = collect();
            $pendingInquiries->each(function($inquiry) use (&$applications) {
                $applications->push(['type' => 'inquiry', 'data' => $inquiry]);
            });
            $newLeads->each(function($lead) use (&$applications) {
                $applications->push(['type' => 'lead', 'data' => $lead]);
            });
        @endphp

        @if($applications->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applicant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Property/Unit</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($applications as $application)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        @if($application['type'] === 'inquiry')
                                            {{ $application['data']->inquirer_name }}
                                        @else
                                            {{ $application['data']->first_name }} {{ $application['data']->last_name }}
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        @if($application['type'] === 'inquiry')
                                            {{ $application['data']->inquirer_email }}
                                        @else
                                            {{ $application['data']->email }}
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        @if($application['type'] === 'inquiry')
                                            {{ $application['data']->unit->property->address ?? 'N/A' }} - Unit {{ $application['data']->unit->unit_number ?? 'N/A' }}
                                        @else
                                            {{ $application['data']->property->address ?? $application['data']->unit->property->address ?? 'N/A' }}
                                            @if($application['data']->unit)
                                                - Unit {{ $application['data']->unit->unit_number }}
                                            @endif
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        @if($application['type'] === 'inquiry') bg-blue-100 text-blue-800 @else bg-purple-100 text-purple-800 @endif">
                                        @if($application['type'] === 'inquiry') Inquiry @else Lead @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $application['data']->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @if($application['type'] === 'inquiry')
                                        @if($application['data']->inquiry_type === 'booking_request')
                                            <form method="POST" action="{{ route('landlord.inquiries.approve', $application['data']->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-900 mr-2" title="Approve">
                                                    <i class="fas fa-check"></i> Approve
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('landlord.inquiries.decline', $application['data']->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="text-red-600 hover:text-red-900 mr-2" title="Decline">
                                                    <i class="fas fa-times"></i> Decline
                                                </button>
                                            </form>
                                            <a href="{{ route('landlord.inquiries.show', $application['data']->id) }}" class="text-indigo-600 hover:text-indigo-900" title="View">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        @elseif($application['data']->inquiry_type === 'general_inquiry')
                                            <a href="{{ route('landlord.inquiries.reply', $application['data']->id) }}" class="text-blue-600 hover:text-blue-900 mr-2" title="Reply">
                                                <i class="fas fa-reply"></i> Reply
                                            </a>
                                            <a href="{{ route('landlord.inquiries.show', $application['data']->id) }}" class="text-indigo-600 hover:text-indigo-900" title="View">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        @elseif($application['data']->inquiry_type === 'viewing_request')
                                            <form method="POST" action="{{ route('landlord.inquiries.approve', $application['data']->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-900 mr-2" title="Accept">
                                                    <i class="fas fa-check"></i> Accept
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('landlord.inquiries.decline', $application['data']->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="text-red-600 hover:text-red-900 mr-2" title="Decline">
                                                    <i class="fas fa-times"></i> Decline
                                                </button>
                                            </form>
                                            <a href="{{ route('landlord.inquiries.reply', $application['data']->id) }}" class="text-blue-600 hover:text-blue-900" title="Reply">
                                                <i class="fas fa-reply"></i> Reply
                                            </a>
                                        @endif
                                    @else
                                        <form method="POST" action="{{ route('landlord.leads.approve', $application['data']->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-900 mr-2" title="Approve">
                                                <i class="fas fa-check"></i> Approve
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('landlord.leads.decline', $application['data']->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Decline">
                                                <i class="fas fa-times"></i> Decline
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-clipboard-list text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No pending inquiries or applications</h3>
                <p class="text-gray-500">New inquiries and applications will appear here</p>
            </div>
        @endif
    </div>

    <!-- Current Tenants -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-medium text-gray-900">Current Tenants</h2>
            <a href="{{ route('landlord.tenants.create') }}"
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded text-sm">
                <i class="fas fa-plus mr-1"></i> Add Tenant
            </a>
        </div>

        @if($tenants->count() > 0)
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
                        @foreach($tenants as $tenant)
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
                                    ${{ number_format($tenant->leases->first()->monthly_rent ?? 0, 2) }}
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
                                    <a href="{{ route('landlord.tenants.show', $tenant->id) }}"
                                       class="text-indigo-600 hover:text-indigo-900 mr-3" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('landlord.tenants.edit', $tenant->id) }}"
                                       class="text-yellow-600 hover:text-yellow-900 mr-3" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('landlord.tenants.message', $tenant->id) }}"
                                       class="text-blue-600 hover:text-blue-900 mr-3" title="Message">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                    <button class="text-red-600 hover:text-red-900"
                                            onclick="removeTenant({{ $tenant->id }})" title="Remove">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $tenants->appends(request()->query())->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No tenants found</h3>
                <p class="text-gray-500 mb-6">Add your first tenant to get started</p>
                <a href="{{ route('landlord.tenants.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    <i class="fas fa-plus mr-2"></i> Add Tenant
                </a>
            </div>
        @endif
    </div>
</div>

<script>
// Tenant actions
function removeTenant(tenantId) {
    if (confirm('Are you sure you want to remove this tenant?')) {
        // Handle tenant removal
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

@endsection
