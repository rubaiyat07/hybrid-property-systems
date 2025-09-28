@extends('layouts.landlord')

@section('title', 'Inquiry Details')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Inquiry Details</h1>
            <p class="text-gray-600 mt-1">Review inquiry from {{ $inquiry->inquirer_name }}</p>
        </div>
        <a href="{{ route('landlord.tenants.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md">
            <i class="fas fa-arrow-left mr-2"></i> Back to Tenants
        </a>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Inquiry Details -->
            <div>
                <h2 class="text-lg font-medium text-gray-900 mb-4">Inquiry Information</h2>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Inquirer Name</dt>
                        <dd class="text-sm text-gray-900">{{ $inquiry->inquirer_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="text-sm text-gray-900">{{ $inquiry->inquirer_email }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Phone</dt>
                        <dd class="text-sm text-gray-900">{{ $inquiry->inquirer_phone }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Inquiry Type</dt>
                        <dd class="text-sm text-gray-900">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                @if($inquiry->inquiry_type === 'general_inquiry') bg-blue-100 text-blue-800
                                @elseif($inquiry->inquiry_type === 'viewing_request') bg-green-100 text-green-800
                                @elseif($inquiry->inquiry_type === 'booking_request') bg-purple-100 text-purple-800
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $inquiry->inquiry_type)) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="text-sm text-gray-900">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                @if($inquiry->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($inquiry->status === 'responded') bg-green-100 text-green-800
                                @elseif($inquiry->status === 'closed') bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($inquiry->status) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Submitted</dt>
                        <dd class="text-sm text-gray-900">{{ $inquiry->created_at->format('M d, Y \a\t g:i A') }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Property/Unit Details -->
            <div>
                <h2 class="text-lg font-medium text-gray-900 mb-4">Property & Unit</h2>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Property</dt>
                        <dd class="text-sm text-gray-900">{{ $inquiry->unit->property->address }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Unit Number</dt>
                        <dd class="text-sm text-gray-900">{{ $inquiry->unit->unit_number }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Unit Type</dt>
                        <dd class="text-sm text-gray-900">{{ $inquiry->unit->unit_type }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Monthly Rent</dt>
                        <dd class="text-sm text-gray-900">${{ number_format($inquiry->unit->monthly_rent, 2) }}</dd>
                    </div>
                    @if($inquiry->preferred_viewing_date)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Preferred Viewing Date</dt>
                        <dd class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($inquiry->preferred_viewing_date)->format('M d, Y') }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>

        <!-- Message -->
        @if($inquiry->message)
        <div class="mt-6">
            <h3 class="text-lg font-medium text-gray-900 mb-2">Message</h3>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $inquiry->message }}</p>
            </div>
        </div>
        @endif

        <!-- Actions -->
        <div class="mt-8 flex justify-end space-x-3">
            @if($inquiry->status === 'pending')
                @if($inquiry->inquiry_type === 'booking_request')
                    <form method="POST" action="{{ route('landlord.inquiries.approve', $inquiry->id) }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-check mr-2"></i> Approve
                        </button>
                    </form>
                    <form method="POST" action="{{ route('landlord.inquiries.decline', $inquiry->id) }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-times mr-2"></i> Decline
                        </button>
                    </form>
                @elseif($inquiry->inquiry_type === 'general_inquiry')
                    <a href="{{ route('landlord.inquiries.reply', $inquiry->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-reply mr-2"></i> Reply
                    </a>
                @elseif($inquiry->inquiry_type === 'viewing_request')
                    <form method="POST" action="{{ route('landlord.inquiries.approve', $inquiry->id) }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-check mr-2"></i> Accept
                        </button>
                    </form>
                    <form method="POST" action="{{ route('landlord.inquiries.decline', $inquiry->id) }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-times mr-2"></i> Decline
                        </button>
                    </form>
                    <a href="{{ route('landlord.inquiries.reply', $inquiry->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-reply mr-2"></i> Reply
                    </a>
                @endif
            @endif

            <form method="POST" action="{{ route('landlord.inquiries.close', $inquiry->id) }}" class="inline">
                @csrf
                <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-times-circle mr-2"></i> Close Inquiry
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
