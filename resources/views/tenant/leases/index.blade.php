@extends('layouts.tenant')

@section('title', 'My Leases')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">My Leases</h1>
        </div>

        @if($leases->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($leases as $lease)
                    <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ $lease->unit->property->address }}
                                </h3>
                                <p class="text-gray-600">Unit {{ $lease->unit->unit_number }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                @if($lease->status == 'active') bg-green-100 text-green-800
                                @elseif($lease->status == 'expired') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ ucfirst($lease->status) }}
                            </span>
                        </div>

                        <div class="space-y-2 mb-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Start Date:</span>
                                <span class="font-medium">{{ $lease->start_date->format('M d, Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">End Date:</span>
                                <span class="font-medium">{{ $lease->end_date->format('M d, Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Monthly Rent:</span>
                                <span class="font-medium">৳{{ number_format($lease->rent_amount, 2) }}</span>
                            </div>
                        </div>

                        <div class="flex space-x-2">
                            <a href="{{ route('tenant.leases.show', $lease) }}"
                               class="flex-1 bg-blue-600 text-white text-center px-3 py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm">
                                <i class="fas fa-eye mr-1"></i>View Details
                            </a>
                            @if($lease->pdf_path)
                                <a href="{{ Storage::disk('public')->url($lease->pdf_path) }}"
                                   target="_blank"
                                   class="bg-green-600 text-white px-3 py-2 rounded-lg hover:bg-green-700 transition-colors text-sm">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <i class="fas fa-file-contract text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No Leases Found</h3>
                <p class="text-gray-600 mb-4">You don't have any active leases at the moment.</p>
                <a href="{{ route('tenant.properties.browse') }}"
                   class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Browse Properties
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
