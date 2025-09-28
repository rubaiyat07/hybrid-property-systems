@extends('layouts.landlord')

@section('title', 'Lease Details')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Lease Details</h1>
            <div class="flex space-x-3">
                <a href="{{ route('landlord.leases.edit', $lease) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-edit mr-2"></i>Edit Lease
                </a>
                <a href="{{ route('landlord.leases.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Leases
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Lease Information -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-semibold mb-4">Lease Information</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Lease ID</label>
                            <p class="text-lg font-semibold text-gray-900">#{{ $lease->id }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <span class="px-3 py-1 text-sm font-medium rounded-full
                                @if($lease->status == 'active') bg-green-100 text-green-800
                                @elseif($lease->status == 'expired') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($lease->status) }}
                            </span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Start Date</label>
                            <p class="text-gray-900">{{ $lease->start_date->format('M d, Y') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">End Date</label>
                            <p class="text-gray-900">{{ $lease->end_date->format('M d, Y') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Rent Amount</label>
                            <p class="text-gray-900 font-semibold">৳{{ number_format($lease->rent_amount, 2) }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Security Deposit</label>
                            <p class="text-gray-900">৳{{ number_format($lease->deposit ?? 0, 2) }}</p>
                        </div>
                    </div>

                    @if($lease->notes)
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700">Notes</label>
                            <p class="text-gray-900 bg-gray-50 p-3 rounded">{{ $lease->notes }}</p>
                        </div>
                    @endif
                </div>

                <!-- Tenant Information -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-semibold mb-4">Tenant Information</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <p class="text-gray-900">{{ $lease->tenant->user->name }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <p class="text-gray-900">{{ $lease->tenant->user->email }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Phone</label>
                            <p class="text-gray-900">{{ $lease->tenant->user->phone ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Emergency Contact</label>
                            <p class="text-gray-900">{{ $lease->tenant->emergency_contact ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Unit Information -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-semibold mb-4">Unit Information</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Property Address</label>
                            <p class="text-gray-900">{{ $lease->unit->property->address }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Unit Number</label>
                            <p class="text-gray-900">{{ $lease->unit->unit_number }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Floor</label>
                            <p class="text-gray-900">{{ $lease->unit->floor ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Size</label>
                            <p class="text-gray-900">{{ $lease->unit->size }} sq ft</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Actions -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-lg font-semibold mb-4">Actions</h3>

                    <div class="space-y-3">
                        @if($lease->document_path)
                            <a href="{{ route('landlord.leases.download-document', $lease) }}"
                               class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors text-center block">
                                <i class="fas fa-download mr-2"></i>Download Document
                            </a>
                        @endif

                        <button onclick="showRenewModal()" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-sync-alt mr-2"></i>Renew Lease
                        </button>

                        <button onclick="showTerminateModal()" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                            <i class="fas fa-times mr-2"></i>Terminate Lease
                        </button>

                        <form action="{{ route('landlord.leases.destroy', $lease) }}" method="POST" class="inline"
                              onsubmit="return confirm('Are you sure you want to delete this lease? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                                <i class="fas fa-trash mr-2"></i>Delete Lease
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Payment Summary -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold mb-4">Payment Summary</h3>

                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Payments:</span>
                            <span class="font-semibold">{{ $lease->payments->count() }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Received:</span>
                            <span class="font-semibold text-green-600">৳{{ number_format($lease->payments->sum('amount'), 2) }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-600">Pending Invoices:</span>
                            <span class="font-semibold">{{ $lease->invoices->where('status', 'pending')->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Renew Lease Modal -->
<div id="renewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Renew Lease</h3>

            <form action="{{ route('landlord.leases.renew', $lease) }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="new_end_date" class="block text-sm font-medium text-gray-700 mb-2">New End Date *</label>
                    <input type="date" name="new_end_date" id="new_end_date" required
                           class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label for="new_rent_amount" class="block text-sm font-medium text-gray-700 mb-2">New Rent Amount (৳)</label>
                    <input type="number" name="new_rent_amount" id="new_rent_amount" value="{{ $lease->rent_amount }}"
                           step="0.01" min="0" max="999999.99"
                           class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label for="renewal_notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea name="notes" id="renewal_notes" rows="3"
                              class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                              placeholder="Any renewal terms or conditions..."></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeRenewModal()" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        Renew Lease
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Terminate Lease Modal -->
<div id="terminateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Terminate Lease</h3>

            <form action="{{ route('landlord.leases.terminate', $lease) }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="termination_date" class="block text-sm font-medium text-gray-700 mb-2">Termination Date *</label>
                    <input type="date" name="termination_date" id="termination_date" required
                           class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label for="termination_reason" class="block text-sm font-medium text-gray-700 mb-2">Termination Reason *</label>
                    <select name="termination_reason" id="termination_reason" required
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Select reason</option>
                        <option value="mutual_agreement">Mutual Agreement</option>
                        <option value="tenant_request">Tenant Request</option>
                        <option value="landlord_request">Landlord Request</option>
                        <option value="breach_of_contract">Breach of Contract</option>
                        <option value="property_sale">Property Sale</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="termination_notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea name="notes" id="termination_notes" rows="3"
                              class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                              placeholder="Additional details about termination..."></textarea>
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded p-3 mb-4">
                    <p class="text-sm text-yellow-800">
                        <strong>Warning:</strong> Terminating this lease will make the unit available for new tenants and may result in financial adjustments.
                    </p>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeTerminateModal()" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                        Terminate Lease
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showRenewModal() {
    document.getElementById('renewModal').classList.remove('hidden');
}

function closeRenewModal() {
    document.getElementById('renewModal').classList.add('hidden');
}

function showTerminateModal() {
    document.getElementById('terminateModal').classList.remove('hidden');
}

function closeTerminateModal() {
    document.getElementById('terminateModal').classList.add('hidden');
}

// Close modals when clicking outside
window.onclick = function(event) {
    const renewModal = document.getElementById('renewModal');
    const terminateModal = document.getElementById('terminateModal');

    if (event.target == renewModal) {
        renewModal.classList.add('hidden');
    }

    if (event.target == terminateModal) {
        terminateModal.classList.add('hidden');
    }
}
</script>
@endsection
