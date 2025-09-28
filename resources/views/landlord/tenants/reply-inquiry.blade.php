@extends('layouts.landlord')

@section('title', 'Reply to Inquiry')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Reply to Inquiry</h1>
            <p class="text-gray-600 mt-1">Respond to inquiry from {{ $inquiry->inquirer_name }}</p>
        </div>
        <a href="{{ route('landlord.tenants.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md">
            <i class="fas fa-arrow-left mr-2"></i> Back to Tenants
        </a>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <!-- Inquiry Summary -->
        <div class="mb-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Inquiry Details</h2>
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">From:</p>
                        <p class="font-medium">{{ $inquiry->inquirer_name }}</p>
                        <p class="text-sm text-gray-600">{{ $inquiry->inquirer_email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Property:</p>
                        <p class="font-medium">{{ $inquiry->unit->property->address }}</p>
                        <p class="text-sm text-gray-600">Unit {{ $inquiry->unit->unit_number }}</p>
                    </div>
                </div>
                @if($inquiry->message)
                <div class="mt-4">
                    <p class="text-sm text-gray-600">Original Message:</p>
                    <p class="text-sm bg-white p-3 rounded border mt-1">{{ $inquiry->message }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Reply Form -->
        <form method="POST" action="{{ route('landlord.inquiries.send-reply', $inquiry->id) }}">
            @csrf
            <div class="mb-4">
                <label for="reply_message" class="block text-sm font-medium text-gray-700 mb-2">
                    Your Reply <span class="text-red-500">*</span>
                </label>
                <textarea id="reply_message" name="reply_message" rows="6"
                          class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                          placeholder="Type your response to the inquiry..." required></textarea>
                <p class="text-sm text-gray-500 mt-1">Maximum 1000 characters</p>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('landlord.tenants.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md">
                    Cancel
                </a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md">
                    <i class="fas fa-paper-plane mr-2"></i> Send Reply
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
