@extends('layouts.landlord')

@section('title', 'Property Taxes')

@section('content')
<h1 class="text-2xl font-bold mb-4">Property Taxes</h1>

@if(session('success'))
    <div class="bg-green-100 text-green-700 p-2 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<table class="min-w-full bg-white border">
    <thead>
        <tr>
            <th class="border px-4 py-2">Property</th>
            <th class="border px-4 py-2">Amount</th>
            <th class="border px-4 py-2">Due Date</th>
            <th class="border px-4 py-2">Status</th>
            <th class="border px-4 py-2">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($taxes as $tax)
        <tr>
            <td class="border px-4 py-2">{{ $tax->property->name }}</td>
            <td class="border px-4 py-2">${{ number_format($tax->amount, 2) }}</td>
            <td class="border px-4 py-2">{{ $tax->due_date->format('M d, Y') }}</td>
            <td class="border px-4 py-2">
                @if($tax->status == 'verified')
                    <span class="text-green-600 font-semibold">Verified</span>
                @elseif($tax->status == 'submitted')
                    <span class="text-blue-600 font-semibold">Submitted</span>
                @elseif($tax->status == 'pending')
                    <span class="text-yellow-600 font-semibold">Pending</span>
                @else
                    <span class="text-red-600 font-semibold">Overdue</span>
                @endif
            </td>
            <td class="border px-4 py-2">
                @if($tax->status == 'pending' && !$tax->receipt_path)
                <form method="POST" action="{{ route('landlord.taxes.upload-receipt', $tax->id) }}" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="receipt" accept=".pdf,.jpg,.jpeg,.png" required class="border px-2 py-1 text-sm">
                    <button type="submit" class="bg-blue-500 text-white px-2 py-1 rounded text-sm">Upload Receipt</button>
                </form>
                @elseif($tax->receipt_path)
                    <a href="{{ route('landlord.taxes.download', $tax->id) }}" class="text-blue-600 hover:underline">Download Receipt</a>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="mt-4">
    {{ $taxes->links() }}
</div>
@endsection
