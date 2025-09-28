@extends('layouts.tenant')

@section('title', 'Find Rentals')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Find Rentals</h1>
        <a href="{{ route('tenant.homepage') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Back to Dashboard
        </a>
    </div>

    <!-- Search and Filter Form -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <form method="GET" action="{{ route('tenant.rentals.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                <input type="text" name="location" id="location" value="{{ request('location') }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                       placeholder="City, Address">
            </div>
            <div>
                <label for="max_price" class="block text-sm font-medium text-gray-700">Max Rent</label>
                <input type="number" name="max_price" id="max_price" value="{{ request('max_price') }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                       placeholder="Max monthly rent">
            </div>
            <div>
                <label for="bedrooms" class="block text-sm font-medium text-gray-700">Min Bedrooms</label>
                <select name="bedrooms" id="bedrooms"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Any</option>
                    <option value="1" {{ request('bedrooms') == '1' ? 'selected' : '' }}>1+</option>
                    <option value="2" {{ request('bedrooms') == '2' ? 'selected' : '' }}>2+</option>
                    <option value="3" {{ request('bedrooms') == '3' ? 'selected' : '' }}>3+</option>
                    <option value="4" {{ request('bedrooms') == '4' ? 'selected' : '' }}>4+</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                    Search
                </button>
            </div>
        </form>
    </div>

    <!-- Results -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($vacantUnits as $unit)
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <!-- Property Image Placeholder -->
                <div class="h-48 bg-gray-200 flex items-center justify-center">
                    @if($unit->property->images && count($unit->property->images) > 0)
                        <img src="{{ asset('storage/' . $unit->property->images[0]) }}" alt="Property" class="w-full h-full object-cover">
                    @else
                        <span class="text-gray-500">No Image</span>
                    @endif
                </div>

                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $unit->property->address }}</h3>
                    <p class="text-sm text-gray-600">{{ $unit->property->city }}, {{ $unit->property->state }}</p>

                    <div class="mt-2 flex items-center justify-between">
                        <span class="text-xl font-bold text-indigo-600">{{ $unit->getDisplayPriceAttribute() }}/month</span>
                        <span class="text-sm text-gray-500">Unit {{ $unit->unit_number }}</span>
                    </div>

                    <div class="mt-2 text-sm text-gray-600">
                        @if($unit->bedrooms) <span>{{ $unit->bedrooms }} bed</span> @endif
                        @if($unit->bathrooms) <span>{{ $unit->bathrooms }} bath</span> @endif
                        @if($unit->size) <span>{{ $unit->size }} sq ft</span> @endif
                    </div>

                    @if($unit->description)
                        <p class="mt-2 text-sm text-gray-700 line-clamp-2">{{ Str::limit($unit->description, 100) }}</p>
                    @endif

                    <div class="mt-4 flex justify-between items-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Available
                        </span>
                        <a href="{{ route('inquiry.create', $unit) }}"
                           class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold py-2 px-4 rounded">
                            Inquire
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <p class="text-gray-500 text-lg">No rentals found matching your criteria.</p>
                <p class="text-gray-400 mt-2">Try adjusting your search filters.</p>
            </div>
        @endforelse
    </div>

    <!-- My Inquiries Section -->
    @if($myInquiries->count() > 0)
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">My Inquiries</h2>
        <div class="space-y-4">
            @foreach($myInquiries as $inquiry)
                <div class="border rounded-lg p-4">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">
                                {{ $inquiry->unit->property->address }} - Unit {{ $inquiry->unit->unit_number }}
                            </h3>
                            <p class="text-sm text-gray-600">
                                Inquiry Type: {{ ucfirst(str_replace('_', ' ', $inquiry->inquiry_type)) }} |
                                Status: 
                                @if($inquiry->status == 'pending')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                @elseif($inquiry->status == 'responded')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Responded</span>
                                @elseif($inquiry->status == 'closed')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Closed</span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ ucfirst($inquiry->status) }}</span>
                                @endif
                            </p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-500">{{ $inquiry->created_at->format('M d, Y') }}</span>
                            <a href="{{ route('tenant.inquiries.show', $inquiry->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white text-xs font-semibold py-1 px-3 rounded">
                                View Details
                            </a>
                        </div>
                    </div>
                    
                    @if($inquiry->message)
                        <p class="text-sm text-gray-700 mb-2">
                            <strong>Your Message:</strong> {{ Str::limit($inquiry->message, 200) }}
                        </p>
                    @endif
                    
                    @if($inquiry->response)
                        <div class="bg-green-50 border-l-4 border-green-400 p-4 mt-3">
                            <div class="flex">
                                <div class="ml-3">
                                    <p class="text-sm text-green-700">
                                        <strong>Landlord Reply:</strong> {{ $inquiry->response }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Pagination -->
    @if($vacantUnits->hasPages())
        <div class="mt-6">
            {{ $vacantUnits->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection
