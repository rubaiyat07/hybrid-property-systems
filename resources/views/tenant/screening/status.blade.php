@extends('layouts.tenant')

@section('title', 'Screening Status')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow rounded p-6">
        <h1 class="text-2xl font-bold mb-6">Tenant Screening Status</h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if($screenings->isEmpty())
            <div class="text-center py-8">
                <p class="text-gray-500 mb-4">You haven't submitted any screening documents yet.</p>
                <a href="{{ route('tenant.screening.submit') }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                    Submit Screening Documents
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach($screenings as $screening)
                    <div class="border rounded p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-semibold">{{ $screening->document_type }}</h3>
                                <p class="text-sm text-gray-600">Submitted: {{ $screening->created_at->format('M d, Y') }}</p>
                                @if($screening->notes)
                                    <p class="text-sm text-gray-600 mt-2">Notes: {{ $screening->notes }}</p>
                                @endif
                            </div>
                            <div class="text-right">
                                <span class="px-2 py-1 text-xs rounded
                                    @if($screening->status == 'approved') bg-green-100 text-green-800
                                    @elseif($screening->status == 'rejected') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800
                                    @endif">
                                    {{ ucfirst($screening->status) }}
                                </span>
                                @if($screening->reviewed_at)
                                    <p class="text-xs text-gray-500 mt-1">Reviewed: {{ $screening->reviewed_at->format('M d, Y') }}</p>
                                @endif
                            </div>
                        </div>
                        @if($screening->file_path)
                            <div class="mt-3">
                                <a href="{{ asset('storage/' . $screening->file_path) }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 text-sm">
                                    View Document
                                </a>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                <a href="{{ route('tenant.screening.submit') }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                    Submit Additional Documents
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
