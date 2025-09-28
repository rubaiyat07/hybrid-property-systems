@extends('layouts.admin')

@section('title', 'Tenants')

@section('content')
<h1 class="text-2xl font-bold mb-4">Tenants</h1>

<table class="min-w-full bg-white border">
    <thead>
        <tr>
            <th class="border px-4 py-2">Name</th>
            <th class="border px-4 py-2">Email</th>
            <th class="border px-4 py-2">Active Leases</th>
            <th class="border px-4 py-2">Pending Screening</th>
            <th class="border px-4 py-2">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tenants as $tenant)
        <tr>
            <td class="border px-4 py-2">{{ $tenant->user->name ?? 'N/A' }}</td>
            <td class="border px-4 py-2">{{ $tenant->user->email ?? 'N/A' }}</td>
            <td class="border px-4 py-2">{{ $tenant->leases->where('end_date', '>=', now())->count() }}</td>
            <td class="border px-4 py-2">{{ $tenant->is_screened ? 'No' : 'Yes' }}</td>
            <td class="border px-4 py-2">
                <a href="{{ route('admin.tenants.show', $tenant->id) }}" class="text-blue-600 hover:underline">View</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="mt-4">
    {{ $tenants->links() }}
</div>
@endsection
