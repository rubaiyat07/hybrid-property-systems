{{-- File: resources/views/landlord/dashboard.blade.php --}}
@extends('layouts.landlord')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
            <p class="text-gray-600 mt-1">Overview of your rental property management</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-building text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Properties</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_properties'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-home text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Units</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_units'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <i class="fas fa-user-check text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Occupied Units</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['occupied_units'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <i class="fas fa-door-open text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Vacant Units</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['vacant_units'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Sections -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Leases -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-medium text-gray-900">Recent Leases</h2>
                <a href="{{ route('landlord.leases.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm">
                    View All <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($recentLeases as $lease)
                    <div class="px-6 py-4 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-file-contract text-blue-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $lease->unit->property->name ?? 'Property' }} - {{ $lease->tenant->user->first_name ?? '' }} {{ $lease->tenant->user->last_name ?? 'Tenant' }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $lease->start_date ? $lease->start_date->format('M d, Y') : 'N/A' }} - {{ $lease->end_date ? $lease->end_date->format('M d, Y') : 'N/A' }}
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ ucfirst($lease->status ?? 'active') }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <div class="text-gray-500">
                            <i class="fas fa-file-contract text-4xl mb-4"></i>
                            <p class="text-lg">No recent leases found</p>
                            <p class="text-sm">New lease agreements will appear here</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Payments -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-medium text-gray-900">Recent Payments</h2>
                <a href="{{ route('landlord.payments.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm">
                    View All <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($recentPayments as $payment)
                    <div class="px-6 py-4 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-dollar-sign text-green-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $payment->lease->unit->property->name ?? 'Property' }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        Unit {{ $payment->lease->unit->unit_number ?? 'N/A' }} - {{ $payment->lease->tenant->user->first_name ?? '' }} {{ $payment->lease->tenant->user->last_name ?? 'Tenant' }}
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-medium text-gray-900">${{ number_format($payment->amount ?? 0, 2) }}</div>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    @if($payment->status === 'paid') bg-green-100 text-green-800
                                    @elseif($payment->status === 'pending') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($payment->status ?? 'pending') }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <div class="text-gray-500">
                            <i class="fas fa-dollar-sign text-4xl mb-4"></i>
                            <p class="text-lg">No recent payments found</p>
                            <p class="text-sm">Payment activity will appear here</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <a href="{{ route('landlord.property.create') }}"
               class="flex items-center justify-center px-4 py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                <i class="fas fa-plus mr-2"></i>
                Add Property
            </a>
            <a href="{{ route('landlord.tenants.create') }}"
               class="flex items-center justify-center px-4 py-3 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                <i class="fas fa-user-plus mr-2"></i>
                Add Tenant
            </a>
            <a href="{{ route('landlord.leases.create') }}"
               class="flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                <i class="fas fa-file-contract mr-2"></i>
                Create Lease
            </a>
            <a href="{{ route('landlord.payments.index') }}"
               class="flex items-center justify-center px-4 py-3 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition">
                <i class="fas fa-credit-card mr-2"></i>
                View Payments
            </a>
        </div>
    </div>
</div>
@endsection
