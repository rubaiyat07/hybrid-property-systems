@extends('layouts.tenant')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Payment Details</h1>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6">
            <div class="mb-4">
                <p><strong>Date:</strong> {{ $payment->date }}</p>
                <p><strong>Property:</strong> {{ $payment->lease->unit->property->name ?? 'N/A' }}</p>
                <p><strong>Unit:</strong> {{ $payment->lease->unit->unit_number ?? 'N/A' }}</p>
                <p><strong>Amount:</strong> ${{ number_format($payment->amount, 2) }}</p>
                <p><strong>Method:</strong> {{ $payment->method ?: 'N/A' }}</p>
                <p><strong>Status:</strong> 
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $payment->status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ ucfirst($payment->status) }}
                    </span>
                </p>
            </div>

            @if($payment->status == 'pending')
            <p class="text-yellow-600">Payment submitted. Waiting for landlord approval.</p>
            @elseif($payment->status != 'paid')
            <form action="{{ route('tenant.payments.pay', $payment->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="transaction_id" class="block text-sm font-medium text-gray-700">Transaction ID</label>
                    <input type="text" name="transaction_id" id="transaction_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                </div>
                <div class="mb-4">
                    <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                    <input type="number" name="amount" id="amount" value="{{ $payment->amount }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" readonly>
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Submit Payment
                </button>
            </form>
            @else
            <p class="text-green-600">This payment has been accepted and marked as paid.</p>
            @endif
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('tenant.payments.index') }}" class="text-indigo-600 hover:text-indigo-900">Back to Payments</a>
    </div>
</div>
@endsection
