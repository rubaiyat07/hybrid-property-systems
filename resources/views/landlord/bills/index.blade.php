@extends('layouts.landlord')

@section('title', 'Utility Bills')

@section('content')
<h1 class="text-2xl font-bold mb-4">Utility Bills</h1>

@if(session('success'))
    <div class="bg-green-100 text-green-700 p-2 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<table class="min-w-full bg-white border">
    <thead>
        <tr>
            <th class="border px-4 py-2">Property</th>
            <th class="border px-4 py-2">Type</th>
            <th class="border px-4 py-2">Amount</th>
            <th class="border px-4 py-2">Due Date</th>
            <th class="border px-4 py-2">Status</th>
            <th class="border px-4 py-2">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($bills as $bill)
        <tr>
            <td class="border px-4 py-2">{{ $bill->property->name }}</td>
            <td class="border px-4 py-2">{{ ucfirst($bill->type) }}</td>
            <td class="border px-4 py-2">${{ number_format($bill->amount, 2) }}</td>
            <td class="border px-4 py-2">{{ $bill->due_date->format('M d, Y') }}</td>
            <td class="border px-4 py-2">
                @if($bill->status == 'paid')
                    <span class="text-green-600 font-semibold">Paid</span>
                @elseif($bill->status == 'verified')
                    <span class="text-blue-600 font-semibold">Verified</span>
                @elseif($bill->status == 'pending')
                    <span class="text-yellow-600 font-semibold">Pending</span>
                @else
                    <span class="text-red-600 font-semibold">Overdue</span>
                @endif
            </td>
            <td class="border px-4 py-2">
                @if($bill->status == 'pending' && !$bill->receipt_path)
                <form method="POST" action="{{ route('landlord.bills.pay', $bill->id) }}" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="receipt" accept=".pdf,.jpg,.jpeg,.png" required class="border px-2 py-1 text-sm">
                    <button type="submit" class="bg-green-500 text-white px-2 py-1 rounded text-sm">Pay & Upload Receipt</button>
                </form>
                @elseif($bill->receipt_path)
                    <a href="{{ route('landlord.bills.download', $bill->id) }}" class="text-blue-600 hover:underline">Download Receipt</a>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="mt-4">
    {{ $bills->links() }}
</div>
@endsection
