@extends('layouts.admin')

@section('title', 'Tenant Screenings')

@section('content')
<h1 class="text-2xl font-bold mb-4">Tenant Screenings</h1>

@if(session('success'))
    <div class="bg-green-100 text-green-700 p-2 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<table class="min-w-full bg-white border">
    <thead>
        <tr>
            <th class="border px-4 py-2">Tenant</th>
            <th class="border px-4 py-2">Document Type</th>
            <th class="border px-4 py-2">Status</th>
            <th class="border px-4 py-2">Submitted At</th>
            <th class="border px-4 py-2">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($screenings as $screening)
        <tr>
            <td class="border px-4 py-2">{{ $screening->tenant->user->name ?? 'N/A' }}</td>
            <td class="border px-4 py-2">{{ $screening->document_type }}</td>
            <td class="border px-4 py-2">
                @if($screening->status == 'approved')
                    <span class="text-green-600 font-semibold">Approved</span>
                @elseif($screening->status == 'rejected')
                    <span class="text-red-600 font-semibold">Rejected</span>
                @else
                    <span class="text-yellow-600 font-semibold">Pending</span>
                @endif
            </td>
            <td class="border px-4 py-2">{{ $screening->created_at->format('M d, Y') }}</td>
            <td class="border px-4 py-2">
                <a href="{{ route('admin.screenings.show', $screening->id) }}" class="text-blue-600 hover:underline">View</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="mt-4">
    {{ $screenings->links() }}
</div>
@endsection
