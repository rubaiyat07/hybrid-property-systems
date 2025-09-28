@extends('layouts.admin')

@section('content')
    <h1>Welcome, Admin!</h1>
    <p>Here is a quick overview:</p>

    <div class="row">
        <div class="col-md-3">
            <div class="card" style="background-color: #e3f2fd;">
                <div class="card-body text-center">
                    <i class="fas fa-building fa-2x text-primary mb-2"></i>
                    <h5>Total Properties</h5>
                    <p class="h4">{{ $totalProperties }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card" style="background-color: #f3e5f5;">
                <div class="card-body text-center">
                    <i class="fas fa-users fa-2x text-success mb-2"></i>
                    <h5>Total Users</h5>
                    <p class="h4">{{ $totalUsers }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card" style="background-color: #e8f5e8;">
                <div class="card-body text-center">
                    <i class="fas fa-user-friends fa-2x text-info mb-2"></i>
                    <h5>Total Tenants</h5>
                    <p class="h4">{{ $totalTenants }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card" style="background-color: #fff3e0;">
                <div class="card-body text-center">
                    <i class="fas fa-file-contract fa-2x text-warning mb-2"></i>
                    <h5>Total Leases</h5>
                    <p class="h4">{{ $totalLeases }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card" style="background-color: #ffebee;">
                <div class="card-body text-center">
                    <i class="fas fa-credit-card fa-2x text-danger mb-2"></i>
                    <h5>Total Payments</h5>
                    <p class="h4">{{ $totalPayments }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card" style="background-color: #e8f5e8;">
                <div class="card-body text-center">
                    <i class="fas fa-dollar-sign fa-2x text-success mb-2"></i>
                    <h5>Total Income</h5>
                    <p class="h4">${{ number_format($totalIncome, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card" style="background-color: #e3f2fd;">
                <div class="card-body text-center">
                    <i class="fas fa-search fa-2x text-secondary mb-2"></i>
                    <h5>Pending Screenings</h5>
                    <p class="h4">{{ $pendingScreenings }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card" style="background-color: #f3e5f5;">
                <div class="card-body text-center">
                    <i class="fas fa-receipt fa-2x text-primary mb-2"></i>
                    <h5>Pending Taxes</h5>
                    <p class="h4">{{ $pendingTaxes }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card" style="background-color: #fff3e0;">
                <div class="card-body text-center">
                    <i class="fas fa-file-invoice-dollar fa-2x text-info mb-2"></i>
                    <h5>Pending Bills</h5>
                    <p class="h4">{{ $pendingBills }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card" style="background-color: #ffebee;">
                <div class="card-body text-center">
                    <i class="fas fa-file-alt fa-2x text-warning mb-2"></i>
                    <h5>Pending Documents</h5>
                    <p class="h4">{{ $pendingDocuments }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card" style="background-color: #e8f5e8;">
                <div class="card-body text-center">
                    <i class="fas fa-exchange-alt fa-2x text-danger mb-2"></i>
                    <h5>Pending Transfers</h5>
                    <p class="h4">{{ $pendingTransfers }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
