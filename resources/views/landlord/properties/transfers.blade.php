@extends('layouts.landlord')

@section('title', 'Property Transfers - ' . $property->name)

@section('content')
<h1 class="text-2xl font-bold mb-4">Transfers for {{ $property->name }}</h1>

@if(session('success'))
    <div class="bg-green-100 text-green-700 p-2 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<div class="mb-4">
    <a href="{{ route('landlord.property.transfers.create', $property) }}" class="bg-blue-500 text-white px-4 py-2 rounded">Initiate Transfer</a>
</div>

<table class="min-w-full bg-white border">
    <thead>
        <tr>
            <th class="border px-4 py-2">To User</th>
            <th class="border px-4 py-2">Transfer Date</th>
            <th class="border px-4 py-2">Status</th>
            <th class="border px-4 py-2">Created At</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transfers as $transfer)
        <tr>
            <td class="border px-4 py-2">{{ $transfer->toUser->name }} ({{ $transfer->toUser->email }})</td>
            <td class="border px-4 py-2">{{ $transfer->transfer_date->format('M d, Y') }}</td>
            <td class="border px-4 py-2">
                @if($transfer->status == 'approved')
                    <span class="text-green-600 font-semibold">Approved</span>
                @elseif($transfer->status == 'rejected')
                    <span class="text-red-600 font-semibold">Rejected</span>
                @else
                    <span class="text-yellow-600 font-semibold">Pending</span>
                @endif
            </td>
            <td class="border px-4 py-2">{{ $transfer->created_at->format('M d, Y') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="mt-4">
    <a href="{{ route('landlord.property.show', $property) }}" class="text-blue-600 hover:underline">Back to Property</a>
</div>
@endsection
