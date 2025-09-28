@extends('layouts.landlord')

@section('title', 'Transfer Details - ' . $transfer->property->name)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Transfer Details</h1>
            <p class="text-gray-600 mt-1">{{ $transfer->property->name }} - {{ $transfer->transfer_type_label }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('landlord.property.transfers.index') }}"
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md font-medium">
                <i class="fas fa-arrow-left mr-1"></i> Back to Transfers
            </a>
        </div>
    </div>

    <!-- Transfer Status -->
    <div class="mb-6">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg {{ $transfer->status === 'completed' ? 'bg-green-100' : ($transfer->status === 'accepted' ? 'bg-blue-100' : ($transfer->status === 'rejected' ? 'bg-red-100' : 'bg-yellow-100')) }}">
                        <i class="fas fa-exchange-alt text-2xl {{ $transfer->status === 'completed' ? 'text-green-600' : ($transfer->status === 'accepted' ? 'text-blue-600' : ($transfer->status === 'rejected' ? 'text-red-600' : 'text-yellow-600')) }}"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Transfer Status</h3>
                        <p class="text-sm text-gray-500">Current status of this transfer request</p>
                    </div>
                </div>
                <div class="text-right">
                    {!! $transfer->status_badge !!}
                    <p class="text-sm text-gray-500 mt-1">
                        Initiated {{ $transfer->created_at->format('M d, Y \a\t g:i A') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Transfer Details -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Property Information -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Property Information</h3>
            <dl class="space-y-3">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Property Name</dt>
                    <dd class="text-sm text-gray-900">{{ $transfer->property->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Address</dt>
                    <dd class="text-sm text-gray-900">{{ $transfer->property->address }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Type</dt>
                    <dd class="text-sm text-gray-900">{{ ucfirst($transfer->property->type) }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Current Owner</dt>
                    <dd class="text-sm text-gray-900">{{ $transfer->currentOwner->name }}</dd>
                </div>
            </dl>
        </div>

        <!-- Transfer Information -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Transfer Information</h3>
            <dl class="space-y-3">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Transfer Type</dt>
                    <dd class="text-sm text-gray-900">{{ $transfer->transfer_type_label }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Proposed Buyer</dt>
                    <dd class="text-sm text-gray-900">{{ $transfer->proposedBuyer->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Proposed Price</dt>
                    <dd class="text-sm text-gray-900 font-medium">${{ number_format($transfer->proposed_price, 2) }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Transfer Date</dt>
                    <dd class="text-sm text-gray-900">{{ $transfer->transfer_date->format('M d, Y') }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Terms and Conditions -->
    @if($transfer->terms_conditions)
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Terms and Conditions</h3>
            <div class="prose prose-sm max-w-none">
                <p class="text-gray-700 whitespace-pre-line">{{ $transfer->terms_conditions }}</p>
            </div>
        </div>
    @endif

    <!-- Supporting Documents -->
    @if($transfer->documents->count() > 0)
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Supporting Documents</h3>
            <div class="space-y-3">
                @foreach($transfer->documents as $document)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-file-pdf text-red-500 mr-3"></i>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $document->original_filename ?? 'Document' }}</p>
                                <p class="text-xs text-gray-500">Uploaded {{ $document->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                        <a href="{{ route('landlord.property.transfer.document.download', [$transfer, $document]) }}"
                           class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                            <i class="fas fa-download mr-1"></i> Download
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Transfer Timeline -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Transfer Timeline</h3>
        <div class="space-y-4">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-plus text-yellow-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-900">Transfer Initiated</p>
                    <p class="text-sm text-gray-500">{{ $transfer->created_at->format('M d, Y \a\t g:i A') }}</p>
                </div>
            </div>

            @if($transfer->buyer_response_at)
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 {{ $transfer->status === 'accepted' ? 'bg-blue-100' : 'bg-red-100' }} rounded-full flex items-center justify-center">
                            <i class="fas {{ $transfer->status === 'accepted' ? 'fa-check' : 'fa-times' }} {{ $transfer->status === 'accepted' ? 'text-blue-600' : 'text-red-600' }}"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-900">
                            Buyer {{ ucfirst($transfer->status) }}
                        </p>
                        <p class="text-sm text-gray-500">{{ $transfer->buyer_response_at->format('M d, Y \a\t g:i A') }}</p>
                        @if($transfer->buyer_response_notes)
                            <p class="text-sm text-gray-700 mt-1">{{ $transfer->buyer_response_notes }}</p>
                        @endif
                    </div>
                </div>
            @endif

            @if($transfer->completed_at)
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-900">Transfer Completed</p>
                        <p class="text-sm text-gray-500">{{ $transfer->completed_at->format('M d, Y \a\t g:i A') }}</p>
                        @if($transfer->completion_notes)
                            <p class="text-sm text-gray-700 mt-1">{{ $transfer->completion_notes }}</p>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Actions</h3>
        <div class="flex flex-wrap gap-3">
            @if($transfer->canBeEdited())
                <a href="{{ route('landlord.property.transfer.edit', $transfer) }}"
                   class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-md font-medium">
                    <i class="fas fa-edit mr-1"></i> Edit Transfer
                </a>
            @endif

            @if($transfer->canBeCancelled())
                <form action="{{ route('landlord.property.transfer.cancel', $transfer) }}" method="POST" class="inline">
                    @csrf
                    @method('PUT')
                    <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md font-medium"
                            onclick="return confirm('Are you sure you want to cancel this transfer?')">
                        <i class="fas fa-times mr-1"></i> Cancel Transfer
                    </button>
                </form>
            @endif

            @if($transfer->canBeAccepted())
                <div class="flex space-x-3">
                    <form action="{{ route('landlord.property.transfer.accept', $transfer) }}" method="POST" class="inline">
                        @csrf
                        @method('PUT')
                        <button type="submit"
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md font-medium">
                            <i class="fas fa-check mr-1"></i> Accept Transfer
                        </button>
                    </form>

                    <form action="{{ route('landlord.property.transfer.reject', $transfer) }}" method="POST" class="inline">
                        @csrf
                        @method('PUT')
                        <button type="submit"
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md font-medium">
                            <i class="fas fa-times mr-1"></i> Reject Transfer
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
