@extends('layouts.admin')

@section('title', 'Maintenance Requests')

@section('content')
<h1 class="text-2xl font-bold mb-4">Maintenance Requests</h1>

<table class="min-w-full bg-white border">
    <thead>
        <tr>
            <th class="border px-4 py-2">Request ID</th>
            <th class="border px-4 py-2">Tenant</th>
            <th class="border px-4 py-2">Property</th>
            <th class="border px-4 py-2">Issue</th>
            <th class="border px-4 py-2">Status</th>
            <th class="border px-4 py-2">Date Submitted</th>
            <th class="border px-4 py-2">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($requests as $request)
        <tr>
            <td class="border px-4 py-2">{{ $request->id }}</td>
            <td class="border px-4 py-2">{{ $request->tenant->user->name ?? 'N/A' }}</td>
            <td class="border px-4 py-2">{{ $request->unit->property->address ?? 'N/A' }}</td>
            <td class="border px-4 py-2">{{ \Illuminate\Support\Str::limit($request->description, 50) }}</td>
            <td class="border px-4 py-2">{{ ucfirst($request->status) }}</td>
            <td class="border px-4 py-2">{{ $request->created_at->format('M d, Y') }}</td>
            <td class="border px-4 py-2">
                <a href="{{ route('admin.maintenance_requests.show', $request->id) }}" class="text-blue-600 hover:underline">View</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="mt-4">
    {{ $requests->links() }}
</div>
@endsection
