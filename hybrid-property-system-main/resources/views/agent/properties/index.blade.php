@extends('layouts.agent')

@section('title', 'My Properties')

@section('content')
<h1 class="text-2xl font-bold mb-4">My Listed Properties</h1>

<div class="mb-4">
    <a href="{{ route('agent.properties.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Submit New Property</a>
</div>

<table class="min-w-full bg-white border">
    <thead>
        <tr>
            <th class="border px-4 py-2">Name</th>
            <th class="border px-4 py-2">Address</th>
            <th class="border px-4 py-2">Price</th>
            <th class="border px-4 py-2">Status</th>
            <th class="border px-4 py-2">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($properties as $property)
        <tr>
            <td class="border px-4 py-2">{{ $property->name }}</td>
            <td class="border px-4 py-2">{{ $property->address }}</td>
            <td class="border px-4 py-2">${{ number_format($property->price) }}</td>
            <td class="border px-4 py-2">
                <span class="px-2 py-1 text-xs rounded {{ $property->status == 'approved' ? 'bg-green-100 text-green-700' : ($property->status == 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                    {{ ucfirst($property->status) }}
                </span>
            </td>
            <td class="border px-4 py-2">
                <a href="{{ route('agent.properties.show', $property->id) }}" class="text-blue-600 hover:underline">View</a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="border px-4 py-2 text-center text-gray-500">No properties found.</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="mt-4">
    {{ $properties->links() }}
</div>
@endsection
