@extends('layouts.landlord')

@section('title', 'Tenant Details - HybridEstate')

@section('content')
<div class="tenant-details-page">
    <div class="page-header mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Tenant Details</h1>
        <p class="text-gray-600 mt-1">View detailed information about the tenant</p>
    </div>

    <div class="tenant-info bg-white p-6 rounded-lg shadow-sm border mb-6">
        <h2 class="text-xl font-semibold mb-4">{{ $tenant->user->first_name }} {{ $tenant->user->last_name }}</h2>
        <p><strong>Email:</strong> {{ $tenant->user->email }}</p>
        <p><strong>Emergency Contact:</strong> {{ $tenant->emergency_contact ?? 'N/A' }}</p>
        <p><strong>Move In Date:</strong> {{ $tenant->move_in_date ? \Carbon\Carbon::parse($tenant->move_in_date)->format('M d, Y') : 'N/A' }}</p>
        <p><strong>Move Out Date:</strong> {{ $tenant->move_out_date ? \Carbon\Carbon::parse($tenant->move_out_date)->format('M d, Y') : 'N/A' }}</p>
    </div>

    <div class="leases-section bg-white p-6 rounded-lg shadow-sm border mb-6">
        <h3 class="text-lg font-semibold mb-4">Leases</h3>
        @if($tenant->leases->count() > 0)
            <table class="w-full border-collapse border border-gray-200">
                <thead>
                    <tr>
                        <th class="border border-gray-300 px-4 py-2">Property</th>
                        <th class="border border-gray-300 px-4 py-2">Unit</th>
                        <th class="border border-gray-300 px-4 py-2">Start Date</th>
                        <th class="border border-gray-300 px-4 py-2">End Date</th>
                        <th class="border border-gray-300 px-4 py-2">Monthly Rent</th>
                        <th class="border border-gray-300 px-4 py-2">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tenant->leases as $lease)
                    <tr>
                        <td class="border border-gray-300 px-4 py-2">{{ $lease->unit->property->address ?? 'N/A' }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $lease->unit->unit_number ?? 'N/A' }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ \Carbon\Carbon::parse($lease->start_date)->format('M d, Y') }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ \Carbon\Carbon::parse($lease->end_date)->format('M d, Y') }}</td>
                        <td class="border border-gray-300 px-4 py-2">${{ number_format($lease->monthly_rent, 2) }}</td>
                        <td class="border border-gray-300 px-4 py-2">
                            @if($lease->end_date >= now())
                                <span class="text-green-600 font-semibold">Active</span>
                            @else
                                <span class="text-red-600 font-semibold">Expired</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No leases found for this tenant.</p>
        @endif
    </div>

    <div class="payments-section bg-white p-6 rounded-lg shadow-sm border mb-6">
        <h3 class="text-lg font-semibold mb-4">Payments</h3>
        @if($tenant->payments->count() > 0)
            <table class="w-full border-collapse border border-gray-200">
                <thead>
                    <tr>
                        <th class="border border-gray-300 px-4 py-2">Due Date</th>
                        <th class="border border-gray-300 px-4 py-2">Amount</th>
                        <th class="border border-gray-300 px-4 py-2">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tenant->payments as $payment)
                    <tr>
                        <td class="border border-gray-300 px-4 py-2">{{ \Carbon\Carbon::parse($payment->due_date)->format('M d, Y') }}</td>
                        <td class="border border-gray-300 px-4 py-2">${{ number_format($payment->amount, 2) }}</td>
                        <td class="border border-gray-300 px-4 py-2">
                            @if($payment->status === 'paid')
                                <span class="text-green-600 font-semibold">Paid</span>
                            @elseif($payment->status === 'pending')
                                <span class="text-yellow-600 font-semibold">Pending</span>
                            @else
                                <span class="text-red-600 font-semibold">Overdue</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No payments found for this tenant.</p>
        @endif
    </div>

    <div class="actions mt-6">
        <a href="{{ route('landlord.tenants.edit', $tenant->id) }}" class="btn-primary">Edit Tenant</a>
        <a href="{{ route('landlord.tenants.index') }}" class="btn-secondary">Back to Tenants</a>
    </div>
</div>
@endsection
