@extends('layouts.admin')

@section('title', 'Agents')

@section('content')
<h1 class="text-2xl font-bold mb-4">Agents</h1>

<table class="min-w-full bg-white border">
    <thead>
        <tr>
            <th class="border px-4 py-2">Name</th>
            <th class="border px-4 py-2">Email</th>
            <th class="border px-4 py-2">Phone</th>
            <th class="border px-4 py-2">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($agents as $agent)
        <tr>
            <td class="border px-4 py-2">{{ $agent->user->name ?? 'N/A' }}</td>
            <td class="border px-4 py-2">{{ $agent->user->email ?? 'N/A' }}</td>
            <td class="border px-4 py-2">{{ $agent->user->phone ?? 'N/A' }}</td>
            <td class="border px-4 py-2">
                <a href="{{ route('admin.agents.show', $agent->id) }}" class="text-blue-600 hover:underline">View</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="mt-4">
    {{ $agents->links() }}
</div>
@endsection
