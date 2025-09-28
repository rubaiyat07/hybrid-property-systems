@extends('layouts.landlord')

@section('title', 'Initiate Property Transfer - ' . $property->name)

@section('content')
<h1 class="text-2xl font-bold mb-4">Initiate Transfer for {{ $property->name }}</h1>

@if(session('success'))
    <div class="bg-green-100 text-green-700 p-2 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<form method="POST" action="{{ route('landlord.property.transfers.store', $property) }}" enctype="multipart/form-data">
    @csrf

    <div class="mb-4">
        <label for="to_user_email" class="block text-sm font-medium text-gray-700">Recipient Email</label>
        <input type="email" name="to_user_email" id="to_user_email" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
    </div>

    <div class="mb-4">
        <label for="transfer_date" class="block text-sm font-medium text-gray-700">Transfer Date</label>
        <input type="date" name="transfer_date" id="transfer_date" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
    </div>

    <div class="mb-4">
        <label for="documents" class="block text-sm font-medium text-gray-700">Transfer Documents (optional)</label>
        <input type="file" name="documents[]" id="documents" multiple accept=".pdf,.jpg,.jpeg,.png" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        <p class="text-sm text-gray-500">Upload any relevant documents for the transfer.</p>
    </div>

    <div class="flex space-x-4">
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Submit Transfer Request</button>
        <a href="{{ route('landlord.property.transfers', $property) }}" class="text-gray-600 hover:underline">Cancel</a>
    </div>
</form>
@endsection
