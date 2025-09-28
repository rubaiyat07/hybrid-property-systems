@extends('layouts.landlord')

@section('title', 'Property Facilities - ' . $property->name)

@section('content')
<h1 class="text-2xl font-bold mb-4">Facilities for {{ $property->name }}</h1>

@if(session('success'))
    <div class="bg-green-100 text-green-700 p-2 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<div class="mb-4">
    <form method="POST" action="{{ route('landlord.property.facilities.store', $property) }}">
        @csrf
        <div class="flex space-x-2">
            <input type="text" name="name" placeholder="Facility Name" required class="border px-2 py-1 flex-1">
            <input type="text" name="description" placeholder="Description (optional)" class="border px-2 py-1 flex-1">
            <button type="submit" class="bg-blue-500 text-white px-4 py-1 rounded">Add Facility</button>
        </div>
    </form>
</div>

<table class="min-w-full bg-white border">
    <thead>
        <tr>
            <th class="border px-4 py-2">Name</th>
            <th class="border px-4 py-2">Description</th>
            <th class="border px-4 py-2">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($facilities as $facility)
        <tr>
            <td class="border px-4 py-2">{{ $facility->name }}</td>
            <td class="border px-4 py-2">{{ $facility->description }}</td>
            <td class="border px-4 py-2">
                <form method="POST" action="{{ route('landlord.property.facilities.update', [$property, $facility]) }}" class="inline">
                    @csrf
                    @method('PUT')
                    <input type="text" name="name" value="{{ $facility->name }}" required class="border px-2 py-1 text-sm">
                    <input type="text" name="description" value="{{ $facility->description }}" class="border px-2 py-1 text-sm">
                    <button type="submit" class="bg-yellow-500 text-white px-2 py-1 rounded text-sm">Update</button>
                </form>
                <form method="POST" action="{{ route('landlord.property.facilities.destroy', [$property, $facility]) }}" class="inline ml-2" onsubmit="return confirm('Are you sure?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded text-sm">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="mt-4">
    <a href="{{ route('landlord.property.show', $property) }}" class="text-blue-600 hover:underline">Back to Property</a>
</div>
@endsection
