@extends('layouts.agent')

@section('title', 'Homepage')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Home</h1>

</div>
<div class="grid grid-cols-4 gap-6 mb-6">
    <div class="bg-white shadow rounded p-4 text-center">
        <h3 class="font-semibold text-gray-700">Total Leads</h3>
        <p class="text-2xl font-bold">{{ $stats['total_leads'] }}</p>
    </div>
    <div class="bg-white shadow rounded p-4 text-center">
        <h3 class="font-semibold text-gray-700">Active Leads</h3>
        <p class="text-2xl font-bold">{{ $stats['active_leads'] }}</p>
    </div>
    <div class="bg-white shadow rounded p-4 text-center">
        <h3 class="font-semibold text-gray-700">Total Transactions</h3>
        <p class="text-2xl font-bold">{{ $stats['total_transactions'] }}</p>
    </div>
    <div class="bg-white shadow rounded p-4 text-center">
        <h3 class="font-semibold text-gray-700">Commission Earned</h3>
        <p class="text-2xl font-bold">${{ number_format($stats['commission_earned'], 2) }}</p>
    </div>
</div>

<div class="grid grid-cols-3 gap-6">
    <!-- Left (Main Content) -->
    <div class="col-span-2 space-y-6">

        <!-- 🔹 Ads Carousel -->
        <div class="bg-white shadow rounded p-4">
            <h3 class="font-semibold mb-4">Sponsored Ads</h3>
            <div id="adsCarousel" class="relative overflow-hidden">
                <div class="flex transition-transform duration-500" id="adsWrapper">
                    @foreach($ads as $ad)
                        <div class="min-w-full flex-shrink-0">
                            <img src="{{ $ad->image_url }}" alt="Ad" class="w-full rounded-lg">
                        </div>
                    @endforeach
                </div>
                <!-- Carousel controls -->
                <button onclick="prevAd()" class="absolute left-0 top-1/2 -translate-y-1/2 bg-gray-800 text-white px-2 py-1 rounded">‹</button>
                <button onclick="nextAd()" class="absolute right-0 top-1/2 -translate-y-1/2 bg-gray-800 text-white px-2 py-1 rounded">›</button>
            </div>
        </div>

        <!-- 🔹 Recent Leads -->
        <div class="bg-white shadow rounded p-4">
            <h3 class="font-semibold mb-4">Recent Leads</h3>
            <table class="w-full text-left border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2">Buyer</th>
                        <th class="px-4 py-2">Property</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentLeads as $lead)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $lead->buyer->user->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2">{{ $lead->property->address ?? 'N/A' }}</td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-700">{{ ucfirst($lead->status) }}</span>
                            </td>
                            <td class="px-4 py-2">{{ $lead->created_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-2 text-center text-gray-500">No recent leads found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- 🔹 Recent Transactions -->
        <div class="bg-white shadow rounded p-4">
            <h3 class="font-semibold mb-4">Recent Transactions</h3>
            <table class="w-full text-left border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2">Property</th>
                        <th class="px-4 py-2">Amount</th>
                        <th class="px-4 py-2">Commission</th>
                        <th class="px-4 py-2">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTransactions as $transaction)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $transaction->property->address ?? 'N/A' }}</td>
                            <td class="px-4 py-2">${{ number_format($transaction->amount, 2) }}</td>
                            <td class="px-4 py-2">${{ number_format($transaction->commission_amount, 2) }}</td>
                            <td class="px-4 py-2">{{ $transaction->created_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-2 text-center text-gray-500">No recent transactions found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    <!-- Right (Sidebar: Profile Brief) -->
    <div class="col-span-1">
        @include('partials.profile-brief')
    </div>
</div>

@endsection

@push('scripts')
<script>
let currentAd = 0;
const adsWrapper = document.getElementById('adsWrapper');
const totalAds = adsWrapper.children.length;

function showAd(index) {
    currentAd = (index + totalAds) % totalAds;
    adsWrapper.style.transform = `translateX(-${currentAd * 100}%)`;
}

function nextAd() { showAd(currentAd + 1); }
function prevAd() { showAd(currentAd - 1); }

setInterval(nextAd, 5000); // auto slide every 5s
</script>
@endpush
