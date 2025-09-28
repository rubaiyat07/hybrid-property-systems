{{-- File: resources/views/landlord/units/show-enhanced.blade.php --}}
@extends('layouts.landlord')

@section('title', 'Unit Details - ' . $unit->unit_number)

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">{{ $unit->unit_number }}</h1>
            <p class="text-gray-600 mt-1">{{ $unit->property->name }} - {{ $unit->property->address }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('landlord.units.index') }}"
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md font-medium">
                <i class="fas fa-arrow-left mr-1"></i> Back to Units
            </a>
            <a href="{{ route('landlord.units.edit', $unit) }}"
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md font-medium">
                <i class="fas fa-edit mr-1"></i> Edit Unit
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Unit Status Banner -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Unit Status</h2>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            @if($unit->status === 'vacant')
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i> Vacant
                                </span>
                            @elseif($unit->status === 'occupied')
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i> Occupied
                                </span>
                            @elseif($unit->status === 'maintenance')
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-purple-100 text-purple-800">
                                    <i class="fas fa-wrench mr-1"></i> Under Maintenance
                                </span>
                            @else
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">
                                    {{ ucfirst($unit->status) }}
                                </span>
                            @endif
                            <div class="text-sm text-gray-600">
                                <span class="font-medium">Rent:</span> ${{ number_format($unit->rent_amount, 2) }}/month
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-gray-600">Unit ID</div>
                            <div class="text-sm font-medium text-gray-900">#{{ $unit->id }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Listing Status Section -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h2 class="text-lg font-medium text-gray-900">Listing Status</h2>
                        @if($unit->is_published)
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                <i class="fas fa-bullhorn mr-1"></i> Published
                            </span>
                        @else
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">
                                <i class="fas fa-eye-slash mr-1"></i> Draft
                            </span>
                        @endif
                    </div>
                </div>
                <div class="p-6">
                    @if($unit->is_published)
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-green-800">This unit is published as a rental listing</h3>
                                    <div class="mt-2 text-sm text-green-700">
                                        <p>Your unit is visible to potential tenants on the public rental listings page.</p>
                                        <div class="mt-2 flex space-x-4">
                                            <a href="{{ route('rentals.show', $unit) }}" target="_blank"
                                               class="text-green-800 hover:text-green-900 font-medium">
                                                <i class="fas fa-external-link-alt mr-1"></i> View Public Listing
                                            </a>
                                            <button onclick="unpublishUnit()"
                                                    class="text-red-600 hover:text-red-900 font-medium">
                                                <i class="fas fa-times mr-1"></i> Unpublish
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">This unit is not published</h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <p>Publish this unit to make it visible to potential tenants and start receiving inquiries.</p>
                                        <div class="mt-3">
                                            <button onclick="publishUnit()"
                                                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                                <i class="fas fa-bullhorn mr-1"></i> Publish as Listing
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Listing Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Listing Details</h4>
                            <dl class="space-y-2">
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-600">Security Deposit:</dt>
                                    <dd class="text-sm font-medium text-gray-900">
                                        ${{ number_format($unit->deposit_amount ?? $unit->rent_amount, 2) }}
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-600">Photos:</dt>
                                    <dd class="text-sm font-medium text-gray-900">
                                        {{ $unit->photos ? count($unit->photos) : 0 }} uploaded
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-600">Features:</dt>
                                    <dd class="text-sm font-medium text-gray-900">
                                        {{ $unit->features ? (is_array($unit->features) ? count($unit->features) : 1) : 0 }} listed
                                    </dd>
                                </div>
                            </dl>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Recent Activity</h4>
                            <dl class="space-y-2">
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-600">Inquiries:</dt>
                                    <dd class="text-sm font-medium text-gray-900">
                                        {{ $unit->inquiries()->count() }} received
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-600">Views:</dt>
                                    <dd class="text-sm font-medium text-gray-900">
                                        {{ $unit->views ?? 0 }} this month
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-600">Published:</dt>
                                    <dd class="text-sm font-medium text-gray-900">
                                        {{ $unit->published_at ? $unit->published_at->format('M d, Y') : 'Never' }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Unit Details -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Unit Information</h2>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Unit Number</dt>
                            <dd class="text-sm text-gray-900">{{ $unit->unit_number }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Property</dt>
                            <dd class="text-sm text-gray-900">{{ $unit->property->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Floor</dt>
                            <dd class="text-sm text-gray-900">{{ $unit->floor ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Size</dt>
                            <dd class="text-sm text-gray-900">{{ $unit->size ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Bedrooms</dt>
                            <dd class="text-sm text-gray-900">{{ $unit->bedrooms ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Bathrooms</dt>
                            <dd class="text-sm text-gray-900">{{ $unit->bathrooms ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Rent Amount</dt>
                            <dd class="text-sm font-medium text-gray-900">${{ number_format($unit->rent_amount, 2) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="text-sm text-gray-900">{{ ucfirst($unit->status) }}</dd>
                        </div>
                    </dl>

                    @if($unit->description)
                        <div class="mt-6">
                            <dt class="text-sm font-medium text-gray-500 mb-2">Description</dt>
                            <dd class="text-sm text-gray-900 leading-relaxed">{{ $unit->description }}</dd>
                        </div>
                    @endif

                    @if($unit->features)
                        <div class="mt-6">
                            <dt class="text-sm font-medium text-gray-500 mb-2">Features</dt>
                            <dd class="text-sm text-gray-900">
                                @if(is_array($unit->features))
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($unit->features as $feature)
                                            <span class="inline-flex px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                                {{ $feature }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    {{ $unit->features }}
                                @endif
                            </dd>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Active Lease -->
            @if($unit->leases->where('end_date', '>=', now())->first())
                @php $activeLease = $unit->leases->where('end_date', '>=', now())->first(); @endphp
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Current Lease</h2>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-sm font-medium text-gray-900">
                                    Tenant: {{ $activeLease->tenant->name ?? 'N/A' }}
                                </div>
                                <div class="text-sm text-gray-600">
                                    {{ $activeLease->start_date->format('M d, Y') }} - {{ $activeLease->end_date->format('M d, Y') }}
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-medium text-gray-900">
                                    ${{ number_format($activeLease->monthly_rent, 2) }}/month
                                </div>
                                <div class="text-sm text-gray-600">
                                    {{ $activeLease->status }}
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('landlord.leases.show', $activeLease) }}"
                               class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                View Lease Details →
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Recent Inquiries -->
            @if($unit->inquiries()->count() > 0)
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Recent Inquiries</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($unit->inquiries()->latest()->take(3)->get() as $inquiry)
                                <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $inquiry->name }}</div>
                                        <div class="text-sm text-gray-600">{{ $inquiry->email }}</div>
                                        <div class="text-xs text-gray-500">{{ $inquiry->created_at->format('M d, Y H:i') }}</div>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                            @if($inquiry->status === 'new') bg-blue-100 text-blue-800
                                            @elseif($inquiry->status === 'responded') bg-green-100 text-green-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($inquiry->status ?? 'new') }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('landlord.inquiries.index') }}"
                               class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                View All Inquiries →
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Quick Actions -->
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('landlord.units.edit', $unit) }}"
                       class="block w-full bg-indigo-600 hover:bg-indigo-700 text-white text-center px-4 py-2 rounded-md text-sm">
                        <i class="fas fa-edit mr-1"></i> Edit Unit
                    </a>

                    @if($unit->status === 'vacant')
                        <a href="{{ route('landlord.leases.create', ['unit_id' => $unit->id]) }}"
                           class="block w-full bg-green-600 hover:bg-green-700 text-white text-center px-4 py-2 rounded-md text-sm">
                            <i class="fas fa-file-contract mr-1"></i> Create Lease
                        </a>
                    @endif

                    @if($unit->is_published)
                        <button onclick="unpublishUnit()"
                                class="block w-full bg-red-600 hover:bg-red-700 text-white text-center px-4 py-2 rounded-md text-sm">
                            <i class="fas fa-times mr-1"></i> Unpublish Listing
                        </button>
                    @else
                        <button onclick="publishUnit()"
                                class="block w-full bg-green-600 hover:bg-green-700 text-white text-center px-4 py-2 rounded-md text-sm">
                            <i class="fas fa-bullhorn mr-1"></i> Publish Listing
                        </button>
                    @endif

                    <button onclick="confirmDelete()"
                            class="block w-full bg-red-600 hover:bg-red-700 text-white text-center px-4 py-2 rounded-md text-sm">
                        <i class="fas fa-trash mr-1"></i> Delete Unit
                    </button>
                </div>
            </div>

            <!-- Unit Stats -->
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Unit Statistics</h3>
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Total Leases</span>
                        <span class="text-sm font-medium text-gray-900">{{ $unit->leases->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Active Leases</span>
                        <span class="text-sm font-medium text-gray-900">{{ $unit->leases->where('end_date', '>=', now())->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Inquiries</span>
                        <span class="text-sm font-medium text-gray-900">{{ $unit->inquiries()->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Created</span>
                        <span class="text-sm font-medium text-gray-900">{{ $unit->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Last Updated</span>
                        <span class="text-sm font-medium text-gray-900">{{ $unit->updated_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Property Info -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Property Information</h3>
                <div class="space-y-3">
                    <div>
                        <div class="text-sm font-medium text-gray-900">{{ $unit->property->name }}</div>
                        <div class="text-sm text-gray-600">{{ $unit->property->address }}</div>
                        <div class="text-sm text-gray-600">{{ $unit->property->city }}, {{ $unit->property->state }} {{ $unit->property->zip_code }}</div>
                    </div>
                    <div class="pt-3 border-t">
                        <a href="{{ route('landlord.property.show', $unit->property) }}"
                           class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                            View Property Details →
                        </a>
                    </div>
                </div>
            </div>
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

@push('scripts')
<script>
function confirmDelete() {
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

function publishUnit() {
    if (confirm('Are you sure you want to publish this unit as a rental listing? It will be visible to potential tenants.')) {
        fetch(`/landlord/units/{{ $unit->id }}/publish`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Failed to publish unit: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while publishing the unit.');
        });
    }
}

function unpublishUnit() {
    if (confirm('Are you sure you want to unpublish this listing? It will no longer be visible to potential tenants.')) {
        fetch(`/landlord/units/{{ $unit->id }}/unpublish`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Failed to unpublish unit: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while unpublishing the unit.');
        });
    }
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
