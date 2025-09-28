@extends('layouts.admin')

@section('title', 'Occupancy Report')

@section('content')
<h1 class="text-2xl font-bold mb-4">Occupancy Report</h1>

<div class="bg-white p-4 rounded shadow">
    <h2 class="text-xl font-semibold mb-2">Occupancy Rates by Property</h2>
    <table class="min-w-full border">
        <thead>
            <tr>
                <th class="border px-4 py-2">Property</th>
                <th class="border px-4 py-2">Total Units</th>
                <th class="border px-4 py-2">Occupied Units</th>
                <th class="border px-4 py-2">Occupancy Rate</th>
            </tr>
        </thead>
        <tbody>
            @foreach($occupancyData as $data)
            <tr>
                <td class="border px-4 py-2">{{ $data['property'] }}</td>
                <td class="border px-4 py-2">{{ $data['total_units'] }}</td>
                <td class="border px-4 py-2">{{ $data['occupied_units'] }}</td>
                <td class="border px-4 py-2">{{ number_format($data['occupancy_rate'], 2) }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
