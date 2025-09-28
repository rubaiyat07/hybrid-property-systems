@extends('layouts.admin')

@section('title', 'Property Documents')

@section('content')
<h1 class="text-2xl font-bold mb-4">Property Documents</h1>

@if(session('success'))
    <div class="bg-green-100 text-green-700 p-2 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<table class="min-w-full bg-white border">
    <thead>
        <tr>
            <th class="border px-4 py-2">Property</th>
            <th class="border px-4 py-2">Owner</th>
            <th class="border px-4 py-2">Type</th>
            <th class="border px-4 py-2">Status</th>
            <th class="border px-4 py-2">Uploaded At</th>
            <th class="border px-4 py-2">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($documents as $document)
        <tr>
            <td class="border px-4 py-2">{{ $document->property->name ?? 'N/A' }}</td>
            <td class="border px-4 py-2">{{ $document->property->owner->name ?? 'N/A' }}</td>
            <td class="border px-4 py-2">{{ ucfirst($document->type) }}</td>
            <td class="border px-4 py-2">
                @if($document->status == 'approved')
                    <span class="text-green-600 font-semibold">Approved</span>
                @elseif($document->status == 'rejected')
                    <span class="text-red-600 font-semibold">Rejected</span>
                @else
                    <span class="text-yellow-600 font-semibold">Pending</span>
                @endif
            </td>
            <td class="border px-4 py-2">{{ $document->created_at->format('M d, Y') }}</td>
            <td class="border px-4 py-2">
                @if($document->status == 'pending')
                <div class="space-x-2">
                    <form method="POST" action="{{ route('admin.documents.approve', $document->id) }}" class="inline">
                        @csrf
                        <input type="text" name="notes" placeholder="Approval notes" class="border px-2 py-1 text-sm">
                        <button type="submit" class="bg-green-500 text-white px-2 py-1 rounded text-sm">Approve</button>
                    </form>
                    <form method="POST" action="{{ route('admin.documents.reject', $document->id) }}" class="inline">
                        @csrf
                        <input type="text" name="notes" placeholder="Rejection notes" class="border px-2 py-1 text-sm">
                        <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded text-sm">Reject</button>
                    </form>
                </div>
                @else
                    <a href="{{ $document->file_path }}" class="text-blue-600 hover:underline">Download</a>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="mt-4">
    {{ $documents->links() }}
</div>
@endsection
