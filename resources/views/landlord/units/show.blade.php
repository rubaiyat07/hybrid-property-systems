{{-- File: resources/views/landlord/units/show.blade.php --}}
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

            <!-- Maintenance Requests -->
            @if($unit->maintenanceRequests->count() > 0)
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Recent Maintenance Requests</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($unit->maintenanceRequests->take(3) as $request)
                                <div class="flex items-center justify-between py-2">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $request->title }}</div>
                                        <div class="text-sm text-gray-600">{{ $request->created_at->format('M d, Y') }}</div>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                            @if($request->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($request->status === 'in_progress') bg-blue-100 text-blue-800
                                            @elseif($request->status === 'completed') bg-green-100 text-green-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($unit->maintenanceRequests->count() > 3)
                            <div class="mt-4">
                                <a href="{{ route('landlord.maintenance.index') }}"
                                   class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                    View All Maintenance Requests →
                                </a>
                            </div>
                        @endif
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
                        <span class="text-sm text-gray-600">Maintenance Requests</span>
                        <span class="text-sm font-medium text-gray-900">{{ $unit->maintenanceRequests->count() }}</span>
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

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
@endpush

@endsection
