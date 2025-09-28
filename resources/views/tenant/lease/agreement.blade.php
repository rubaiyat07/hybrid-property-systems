@extends('layouts.tenant')

@section('title', 'Lease Agreement')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Lease Agreement</h1>

    @if($lease)
        <div class="mb-4">
            <p><strong>Property:</strong> {{ $lease->unit->property->name }}</p>
            <p><strong>Unit:</strong> {{ $lease->unit->name }}</p>
            <p><strong>Start Date:</strong> {{ $lease->start_date->format('M d, Y') }}</p>
            <p><strong>End Date:</strong> {{ $lease->end_date->format('M d, Y') }}</p>
            <p><strong>Monthly Rent:</strong> ${{ number_format($lease->rent_amount, 2) }}</p>
            <p><strong>Security Deposit:</strong> ${{ number_format($lease->security_deposit, 2) }}</p>
            <p><strong>Status:</strong> 
                @if($lease->status == 'active')
                    <span class="text-green-600">Active</span>
                @elseif($lease->status == 'expired')
                    <span class="text-red-600">Expired</span>
                @else
                    <span class="text-yellow-600">{{ ucfirst($lease->status) }}</span>
                @endif
            </p>
        </div>

        @if($lease->agreement_path)
            <div class="mb-4">
                <a href="{{ route('tenant.lease.download-agreement', $lease->id) }}" class="bg-blue-500 text-white px-4 py-2 rounded">Download PDF Agreement</a>
            </div>
        @else
            <p class="text-gray-600">No agreement document available.</p>
        @endif

        @if($lease->status == 'active')
            <div class="space-x-4">
                <form method="POST" action="{{ route('tenant.lease.renew-request', $lease->id) }}" class="inline">
                    @csrf
                    <input type="text" name="renewal_notes" placeholder="Renewal request notes" class="border px-2 py-1">
                    <button type="submit" class="bg-green-500 text-white px-2 py-1 rounded">Request Renewal</button>
                </form>
                <form method="POST" action="{{ route('tenant.lease.terminate-request', $lease->id) }}" class="inline">
                    @csrf
                    <input type="text" name="termination_notes" placeholder="Termination request notes" class="border px-2 py-1">
                    <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded" onclick="return confirm('Are you sure you want to request termination?')">Request Termination</button>
                </form>
            </div>
        @endif
    @else
        <p>No active lease found.</p>
    @endif

    <div class="mt-4">
        <a href="{{ route('tenant.homepage') }}" class="text-blue-600 hover:underline">Back to Dashboard</a>
    </div>
</div>
@endsection
