@extends('layouts.admin')

@section('title', 'Income Report')

@section('content')
<h1 class="text-2xl font-bold mb-4">Income Report</h1>

<div class="bg-white p-4 rounded shadow mb-4">
    <h2 class="text-xl font-semibold mb-2">Total Income</h2>
    <p class="text-3xl font-bold text-green-600">${{ number_format($totalIncome, 2) }}</p>
</div>

<div class="bg-white p-4 rounded shadow">
    <h2 class="text-xl font-semibold mb-2">Monthly Income</h2>
    <table class="min-w-full border">
        <thead>
            <tr>
                <th class="border px-4 py-2">Month</th>
                <th class="border px-4 py-2">Income</th>
            </tr>
        </thead>
        <tbody>
            @foreach($monthlyIncome as $month => $income)
            <tr>
                <td class="border px-4 py-2">{{ $month }}</td>
                <td class="border px-4 py-2">${{ number_format($income, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
