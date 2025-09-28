<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function tenantDashboard()
    {
        // Redirect to homepage for now, or implement separate dashboard
        return redirect()->route('tenant.homepage');
    }

    public function buyerDashboard()
    {
        // Basic buyer dashboard
        $user = Auth::user();
        return view('buyer.dashboard', compact('user'));
    }

    public function maintenanceDashboard()
    {
        // Basic maintenance dashboard
        $user = Auth::user();
        return view('maintenance.dashboard', compact('user'));
    }
}
