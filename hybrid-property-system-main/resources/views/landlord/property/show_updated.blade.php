{{-- File: resources/views/landlord/property/show.blade.php --}}
@extends('layouts.landlord')

@section('title', 'Property Details - ' . $property->name)

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">{{ $property->name }}</h1>
            <p class="text-gray-600 mt-1">Property ID: #{{ $property->id }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('landlord.property.index') }}"
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md font-medium">
                <i class="fas fa-arrow-left mr-1"></i> Back to Properties
            </a>

            @if($property->registration_status !== 'rejected')
                <a href="{{ route('landlord.property.edit', $property) }}"
                   class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md font-medium">
                    <i class="fas fa-edit mr-1"></i> Edit Property
                </a>
            @endif
        </div>
    </div>

    <!-- Registration Status Banner -->
    <div class="mb-6">
        @if($property->registration_status === 'pending')
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
                <div class="flex items-center">
                    <i class="fas fa-clock mr-2"></i>
                    <div>
                        <strong>Registration Pending:</strong> Your property registration is under review by our admin team.
                        You'll be notified once it's approved.
                    </div>
                </div>
            </div>
        @elseif($property->registration_status === 'approved')
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <div>
                            <strong>Property Approved:</strong> This property is approved and ready for unit management.
                            @if($property->approved_at)
                                <br><small>Approved on {{ $property->approved_at->format('M d, Y \a\t g:i A') }}</small>
                            @endif
                        </div>
                    </div>
                    @if($property->canAddUnits())
                        <a href="{{ route('landlord.units.create', ['property_id' => $property->id]) }}"
                           class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                            <i class="fas fa-plus mr-1"></i> Add Unit
                        </a>
                    @endif
                </div>
            </div>
        @elseif($property->registration_status === 'rejected')
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-times-circle mr-2"></i>
                        <div>
                            <strong>Registration Rejected:</strong> This property registration was not approved.
                            @if($property->registration_notes)
                                <br><strong>Reason:</strong> {{ $property->registration_notes }}
                            @endif
                        </div>
                    </div>
                    <form action="{{ route('landlord.property.resubmit', $property) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm"
                                onclick="return confirm('Are you sure you want to resubmit this property for review?')">
                            <i class="fas fa-redo mr-1"></i> Resubmit
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Property Image Gallery -->
            @if($property->images && $property->images->count() > 0)
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h2 class="text-lg font-medium text-gray-900">Property Gallery</h2>
                        <div class="flex space-x-2">
                            <a href="{{ route('landlord.property.images.index', $property) }}"
                               class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded text-sm">
                                <i class="fas fa-images mr-1"></i> Manage Gallery
                            </a>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach($property->images->take(8) as $image)
                                <div class="relative group">
                                    <img src="{{ $image->image_path }}"
                                         alt="Property Image"
                                         class="w-full h-24 object-cover rounded-lg">
                                    @if($image->is_primary)
                                        <div class="absolute top-1 right-1 bg-green-500 text-white text-xs px-2 py-1 rounded">
                                            Primary
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        @if($property->images->count() > 8)
