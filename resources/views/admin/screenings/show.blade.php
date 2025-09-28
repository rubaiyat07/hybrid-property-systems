@extends('layouts.admin')

@section('title', 'Screening Details')

@section('content')
<h1 class="text-2xl font-bold mb-4">Screening Details</h1>

<div class="bg-white shadow rounded p-6">
    <div class="grid grid-cols-2 gap-4 mb-4">
        <div>
            <strong>Tenant:</strong> {{ $screening->tenant->user->name ?? 'N/A' }}
        </div>
        <div>
            <strong>Email:</strong> {{ $screening->tenant->user->email ?? 'N/A' }}
        </div>
        <div>
            <strong>Document Type:</strong> {{ $screening->document_type }}
        </div>
        <div>
            <strong>Status:</strong>
            @if($screening->status == 'approved')
                <span class="text-green-600 font-semibold">Approved</span>
            @elseif($screening->status == 'rejected')
                <span class="text-red-600 font-semibold">Rejected</span>
            @else
                <span class="text-yellow-600 font-semibold">Pending</span>
            @endif
        </div>
        <div>
            <strong>Submitted At:</strong> {{ $screening->created_at->format('M d, Y H:i') }}
        </div>
        @if($screening->reviewed_at)
        <div>
            <strong>Reviewed At:</strong> {{ $screening->reviewed_at->format('M d, Y H:i') }}
        </div>
        @endif
    </div>

    @if($screening->file_path)
    <div class="mb-4">
        <strong>Document:</strong>
        <a href="{{ Storage::url($screening->file_path) }}" target="_blank" class="text-blue-600 hover:underline">View Document</a>
    </div>
    @endif

    @if($screening->notes)
    <div class="mb-4">
        <strong>Notes:</strong>
        <p class="mt-2">{{ $screening->notes }}</p>
    </div>
    @endif

    @if($screening->status == 'pending')
    <div class="flex space-x-4">
        <form method="POST" action="{{ route('admin.screenings.approve', $screening->id) }}" class="inline">
            @csrf
            @method('POST')
            <div class="mb-2">
                <label for="notes" class="block text-sm font-medium text-gray-700">Approval Notes (optional)</label>
                <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
            </div>
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Approve</button>
        </form>

        <form method="POST" action="{{ route('admin.screenings.reject', $screening->id) }}" class="inline">
            @csrf
            @method('POST')
            <div class="mb-2">
                <label for="notes" class="block text-sm font-medium text-gray-700">Rejection Notes (required)</label>
                <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required></textarea>
            </div>
            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Reject</button>
        </form>
    </div>
    @endif
</div>

<a href="{{ route('admin.screenings.index') }}" class="mt-4 inline-block text-blue-600 hover:underline">Back to Screenings</a>
@endsection
