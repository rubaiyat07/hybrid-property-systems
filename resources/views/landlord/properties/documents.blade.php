@extends('layouts.landlord')

@section('title', 'Property Documents - ' . $property->name)

@section('content')
<h1 class="text-2xl font-bold mb-4">Documents for {{ $property->name }}</h1>

@if(session('success'))
    <div class="bg-green-100 text-green-700 p-2 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<div class="mb-4">
    <form method="POST" action="{{ route('landlord.property.documents.store', $property) }}" enctype="multipart/form-data">
        @csrf
        <div class="flex space-x-2">
            <select name="type" required class="border px-2 py-1">
                <option value="">Select Type</option>
                <option value="deed">Deed</option>
                <option value="certificate">Certificate</option>
                <option value="mutation">Mutation</option>
                <option value="tax_receipt">Tax Receipt</option>
            </select>
            <input type="file" name="document" accept=".pdf,.jpg,.jpeg,.png" required class="border px-2 py-1">
            <button type="submit" class="bg-blue-500 text-white px-4 py-1 rounded">Upload Document</button>
        </div>
    </form>
</div>

<table class="min-w-full bg-white border">
    <thead>
        <tr>
            <th class="border px-4 py-2">Type</th>
            <th class="border px-4 py-2">Status</th>
            <th class="border px-4 py-2">Uploaded At</th>
            <th class="border px-4 py-2">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($documents as $document)
        <tr>
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
                <a href="{{ route('landlord.property.documents.download', [$property, $document]) }}" class="text-blue-600 hover:underline">Download</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="mt-4">
    <a href="{{ route('landlord.property.show', $property) }}" class="text-blue-600 hover:underline">Back to Property</a>
</div>
@endsection
