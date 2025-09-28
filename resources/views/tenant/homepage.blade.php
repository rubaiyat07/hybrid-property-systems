@extends('layouts.tenant')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
            <p class="text-gray-600 mt-1">Welcome back! Here's an overview of your leases and payments.</p>
        </div>
        @if($profile->screening_verified)
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                Verified Tenant
            </span>
        @endif
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-file-contract text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active Leases</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['active_leases'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-file-contract text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Leases</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_leases'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending Payments</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['pending_payments'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <i class="fas fa-dollar-sign text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Paid</p>
                    <p class="text-3xl font-bold text-gray-900">৳{{ number_format($stats['total_paid'] ?? 0, 0, '.', ',') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Current Lease Summary -->
            @if($currentLease)
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Current Lease</h2>
                </div>
                <div class="p-6">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-16 h-16 bg-indigo-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-home text-indigo-600 text-2xl"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900">{{ $currentLease->unit->property->address }}</h3>
                            <p class="text-sm text-gray-600">Unit {{ $currentLease->unit->unit_number }} | {{ $currentLease->unit->getDisplayPriceAttribute() }}/month</p>
                            <p class="text-sm text-gray-600">{{ $currentLease->start_date->format('M d, Y') }} - {{ $currentLease->end_date->format('M d, Y') }}</p>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                {{ ucfirst($currentLease->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Sponsored Ads Carousel -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Sponsored Ads</h2>
                </div>
                <div class="p-6">
                    <div id="adsCarousel" class="relative overflow-hidden rounded-lg">
                        <div class="flex transition-transform duration-500" id="adsWrapper">
                            @forelse($ads as $ad)
                                <div class="min-w-full flex-shrink-0">
                                    <img src="{{ $ad->image_url ?? asset('images/placeholder-ad.jpg') }}" alt="Ad" class="w-full h-48 object-cover rounded-lg">
                                </div>
                            @empty
                                <div class="min-w-full flex-shrink-0">
                                    <div class="w-full h-48 bg-gray-100 rounded-lg flex items-center justify-center">
                                        <div class="text-center text-gray-500">
                                            <i class="fas fa-ad text-4xl mb-2"></i>
                                            <p>No ads available</p>
                                        </div>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                        @if($ads->count() > 1)
                            <!-- Carousel controls -->
                            <button onclick="prevAd()" class="absolute left-4 top-1/2 -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75 transition">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button onclick="nextAd()" class="absolute right-4 top-1/2 -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75 transition">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Active Listings (Available Rentals) -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-lg font-medium text-gray-900">Available Rentals</h2>
                    <a href="{{ route('tenant.rentals.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Property</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rent</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($activeListings as $listing)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                                    <i class="fas fa-building text-indigo-600"></i>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $listing->property->address }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Unit {{ $listing->unit_number }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $listing->getDisplayPriceAttribute() }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $listing->property->city }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="text-gray-500">
                                            <i class="fas fa-search text-4xl mb-4"></i>
                                            <p class="text-lg">No available rentals</p>
                                            <p class="text-sm">Browse rentals to find your next home</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Active Rentals (Tenant's Leases) -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-lg font-medium text-gray-900">Your Active Leases</h2>
                    <a href="{{ route('tenant.leases.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Property</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rent</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($activeRentals as $rental)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $rental->unit->property->address }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Unit {{ $rental->unit->unit_number }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $rental->unit->getDisplayPriceAttribute() }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ ucfirst($rental->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="text-gray-500">
                                            <i class="fas fa-file-contract text-4xl mb-4"></i>
                                            <p class="text-lg">No active leases</p>
                                            <p class="text-sm">Your leases will appear here</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            @include('partials.profile-brief')

            <!-- Quick Actions -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('tenant.rentals.index') }}"
                       class="flex items-center w-full px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                        <i class="fas fa-search mr-3"></i>
                        Find Rentals
                    </a>
                    <a href="{{ route('tenant.maintenance.create') }}"
                       class="flex items-center w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                        <i class="fas fa-tools mr-3"></i>
                        Submit Maintenance
                    </a>
                    <a href="{{ route('tenant.payments.index') }}"
                       class="flex items-center w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                        <i class="fas fa-credit-card mr-3"></i>
                        View Payments
                    </a>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Activity</h3>
                <div class="space-y-3">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-dollar-sign text-green-600 text-sm"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-900">Payment confirmed</p>
                            <p class="text-xs text-gray-500">2 hours ago</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-tools text-blue-600 text-sm"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-900">Maintenance request submitted</p>
                            <p class="text-xs text-gray-500">1 day ago</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-search text-purple-600 text-sm"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-900">Viewed rental listing</p>
                            <p class="text-xs text-gray-500">3 days ago</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Maintenance Requests -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Recent Maintenance</h3>
                </div>
                <div class="p-4 space-y-2 max-h-48 overflow-y-auto">
                    @forelse($recentMaintenance->take(3) as $request)
                        <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-b-0">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ Str::limit($request->description, 40) }}</p>
                                <p class="text-xs text-gray-500">{{ $request->unit->property->address }}</p>
                            </div>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $request->status == 'resolved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($request->status) }}
                            </span>
                        </div>
                    @empty
                        <div class="text-center py-4 text-gray-500">
                            <i class="fas fa-tools text-2xl mb-2"></i>
                            <p>No recent requests</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Inquiries -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Recent Inquiries</h3>
                </div>
                <div class="p-4 space-y-2 max-h-48 overflow-y-auto">
                    @forelse($recentInquiries as $inquiry)
                        <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-b-0">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ $inquiry->unit->property->address }}</p>
                                <p class="text-xs text-gray-500">Unit {{ $inquiry->unit->unit_number }}</p>
                            </div>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                @if($inquiry->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($inquiry->status == 'responded') bg-blue-100 text-blue-800
                                @elseif($inquiry->status == 'closed') bg-gray-100 text-gray-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($inquiry->status) }}
                            </span>
                        </div>
                    @empty
                        <div class="text-center py-4 text-gray-500">
                            <i class="fas fa-envelope text-2xl mb-2"></i>
                            <p>No recent inquiries</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let currentAd = 0;
const adsWrapper = document.getElementById('adsWrapper');
const totalAds = adsWrapper ? adsWrapper.children.length : 0;

function showAd(index) {
    if (totalAds === 0) return;
    currentAd = (index + totalAds) % totalAds;
    adsWrapper.style.transform = `translateX(-${currentAd * 100}%)`;
}

function nextAd() { showAd(currentAd + 1); }
function prevAd() { showAd(currentAd - 1); }

if (totalAds > 1) {
    setInterval(nextAd, 5000); // auto slide every 5s
}
</script>
@endpush
