@extends('layouts.admin')

@section('title', 'Leases')

@section('content')
<h1 class="text-2xl font-bold mb-4">Leases</h1>

<table class="min-w-full bg-white border">
    <thead>
        <tr>
            <th class="border px-4 py-2">Tenant</th>
            <th class="border px-4 py-2">Property</th>
            <th class="border px-4 py-2">Unit</th>
            <th class="border px-4 py-2">Start Date</th>
            <th class="border px-4 py-2">End Date</th>
            <th class="border px-4 py-2">Status</th>
            <th class="border px-4 py-2">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($leases as $lease)
        <tr>
            <td class="border px-4 py-2">{{ $lease->tenant->user->name ?? 'N/A' }}</td>
            <td class="border px-4 py-2">{{ $lease->unit->property->address ?? 'N/A' }}</td>
            <td class="border px-4 py-2">{{ $lease->unit->unit_number ?? 'N/A' }}</td>
            <td class="border px-4 py-2">{{ $lease->start_date->format('M d, Y') }}</td>
            <td class="border px-4 py-2">{{ $lease->end_date->format('M d, Y') }}</td>
            <td class="border px-4 py-2">{{ ucfirst($lease->status) }}</td>
            <td class="border px-4 py-2">
                <a href="{{ route('admin.leases.show', $lease->id) }}" class="text-blue-600 hover:underline">View</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="mt-4">
    {{ $leases->links() }}
</div>
@endsection
