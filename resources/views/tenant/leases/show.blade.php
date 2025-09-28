@extends('layouts.tenant')

@section('title', 'Lease Details')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Lease Details</h1>
            <a href="{{ route('tenant.leases.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back to Leases
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <!-- Lease Status -->
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-900">Lease #{{ $lease->id }}</h2>
                    <span class="px-3 py-1 text-sm font-medium rounded-full
                        @if($lease->status == 'active') bg-green-100 text-green-800
                        @elseif($lease->status == 'expired') bg-red-100 text-red-800
                        @else bg-yellow-100 text-yellow-800 @endif">
                        {{ ucfirst($lease->status) }}
                    </span>
                </div>
            </div>

            <!-- Property Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Property Details</h3>
                    <div class="space-y-2">
                        <p><span class="font-medium">Property:</span> {{ $lease->unit->property->address }}</p>
                        <p><span class="font-medium">Unit:</span> {{ $lease->unit->unit_number }}</p>
                        <p><span class="font-medium">Type:</span> {{ $lease->unit->property->type }}</p>
                        <p><span class="font-medium">Size:</span> {{ $lease->unit->size }} sq ft</p>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Lease Terms</h3>
                    <div class="space-y-2">
                        <p><span class="font-medium">Start Date:</span> {{ $lease->start_date->format('F d, Y') }}</p>
                        <p><span class="font-medium">End Date:</span> {{ $lease->end_date->format('F d, Y') }}</p>
                        <p><span class="font-medium">Monthly Rent:</span> ৳{{ number_format($lease->rent_amount, 2) }}</p>
                        <p><span class="font-medium">Security Deposit:</span> ৳{{ number_format($lease->deposit ?? 0, 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Landlord Information -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Landlord Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p><span class="font-medium">Name:</span> {{ $lease->unit->property->owner->name }}</p>
                        <p><span class="font-medium">Email:</span> {{ $lease->unit->property->owner->email }}</p>
                    </div>
                    <div>
                        <p><span class="font-medium">Phone:</span> {{ $lease->unit->property->owner->phone ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-wrap gap-3">
                @if($lease->pdf_path)
                    <a href="{{ Storage::disk('public')->url($lease->pdf_path) }}"
                       target="_blank"
                       class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-file-pdf mr-2"></i>View PDF Agreement
                    </a>
                    <a href="{{ Storage::disk('public')->url($lease->pdf_path) }}"
                       download="lease_agreement_{{ $lease->id }}.pdf"
                       class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-download mr-2"></i>Download PDF
                    </a>
                @endif

                @if($lease->status == 'active')
                    <button onclick="document.getElementById('renewal-modal').classList.remove('hidden')"
                            class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition-colors">
                        <i class="fas fa-redo mr-2"></i>Request Renewal
                    </button>
                    <button onclick="document.getElementById('termination-modal').classList.remove('hidden')"
                            class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                        <i class="fas fa-times mr-2"></i>Request Termination
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Renewal Request Modal -->
<div id="renewal-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
        <h3 class="text-lg font-semibold mb-4">Request Lease Renewal</h3>
        <form method="POST" action="{{ route('tenant.lease.renew-request', $lease->id) }}">
            @csrf
            <div class="mb-4">
                <label for="renewal_notes" class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                <textarea name="renewal_notes" id="renewal_notes" rows="3"
                          class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                          placeholder="Any special requests or notes..."></textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="document.getElementById('renewal-modal').classList.add('hidden')"
                        class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Submit Request
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Termination Request Modal -->
<div id="termination-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
        <h3 class="text-lg font-semibold mb-4">Request Lease Termination</h3>
        <form method="POST" action="{{ route('tenant.lease.terminate-request', $lease->id) }}">
            @csrf
            <div class="mb-4">
                <label for="termination_notes" class="block text-sm font-medium text-gray-700 mb-2">Reason for Termination</label>
                <textarea name="termination_notes" id="termination_notes" rows="3" required
                          class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                          placeholder="Please provide a reason for termination..."></textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="document.getElementById('termination-modal').classList.add('hidden')"
                        class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                    Submit Request
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
