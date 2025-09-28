@extends('layouts.tenant')

@section('title', 'Inquiry Details')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Inquiry Details</h1>
        <a href="{{ route('tenant.rentals.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Back to Rentals
        </a>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <div class="mb-6">
            <h2 class="text-2xl font-semibold text-gray-900 mb-2">
                {{ $inquiry->unit->property->address }} - Unit {{ $inquiry->unit->unit_number }}
            </h2>
            <div class="flex items-center space-x-4 text-sm text-gray-600">
                <span>Inquiry Type: {{ ucfirst(str_replace('_', ' ', $inquiry->inquiry_type)) }}</span>
                <span>Status:
                    @if($inquiry->status == 'pending')
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                    @elseif($inquiry->status == 'responded')
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Responded</span>
                    @elseif($inquiry->status == 'closed')
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Closed</span>
                    @else
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ ucfirst($inquiry->status) }}</span>
                    @endif
                </span>
                <span>Submitted: {{ $inquiry->created_at->format('M d, Y \a\t g:i A') }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Your Information</h3>
                <div class="space-y-2">
                    <p><strong>Name:</strong> {{ $inquiry->inquirer_name }}</p>
                    <p><strong>Email:</strong> {{ $inquiry->inquirer_email }}</p>
                    <p><strong>Phone:</strong> {{ $inquiry->inquirer_phone }}</p>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Property Details</h3>
                <div class="space-y-2">
                    <p><strong>Property:</strong> {{ $inquiry->unit->property->address }}</p>
                    <p><strong>Unit:</strong> {{ $inquiry->unit->unit_number }}</p>
                    <p><strong>Monthly Rent:</strong> {{ $inquiry->unit->getDisplayPriceAttribute() }}</p>
                    @if($inquiry->unit->bedrooms || $inquiry->unit->bathrooms)
                        <p><strong>Size:</strong>
                            @if($inquiry->unit->bedrooms) {{ $inquiry->unit->bedrooms }} bed @endif
                            @if($inquiry->unit->bathrooms) {{ $inquiry->unit->bathrooms }} bath @endif
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Your Message</h3>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-gray-700 whitespace-pre-wrap">{{ $inquiry->message }}</p>
            </div>
        </div>

        @if($inquiry->preferred_viewing_date)
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Preferred Viewing Date</h3>
                <p class="text-gray-700">{{ $inquiry->preferred_viewing_date->format('F j, Y') }}</p>
            </div>
        @endif

        @if($inquiry->response)
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Landlord Response</h3>
                <div class="bg-green-50 border-l-4 border-green-400 p-4">
                    <div class="flex">
                        <div class="ml-3">
                            <p class="text-sm text-green-700 whitespace-pre-wrap">{{ $inquiry->response }}</p>
                            @if($inquiry->responded_at)
                                <p class="text-xs text-green-600 mt-2">Responded on {{ $inquiry->responded_at->format('M d, Y \a\t g:i A') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="mb-6">
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                    <div class="flex">
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                <strong>Status:</strong> Your inquiry is still pending. The landlord will respond within 24 hours.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="flex justify-end space-x-4">
            <a href="{{ route('tenant.rentals.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to Rentals
            </a>
            @if($inquiry->status !== 'closed')
                <button onclick="openChatWithUser({{ $inquiry->unit->property->owner->id }}, '{{ $inquiry->unit->property->owner->name }}')"
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Contact Landlord
                </button>
            @endif
        </div>
    </div>
</div>
@endsection
