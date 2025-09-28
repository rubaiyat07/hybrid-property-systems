@extends('layouts.admin')

@section('content')
    <h1>Welcome, Admin!</h1>
    <p>Here is a quick overview:</p>

    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Total Properties</h5>
                    <p>{{ $totalProperties }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>Total Users</h5>
                    <p>{{ $totalUsers }}</p>
                </div>
            </div>
        </div>
        {{-- Add more cards: tenants, leases, payments, etc. --}}
    </div>
@endsection
