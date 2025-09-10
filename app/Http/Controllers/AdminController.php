<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Property;

class AdminController extends Controller
{
    // Admin dashboard
    public function adminDashboard()
    {
        // Example data to show on dashboard
        $totalUsers = User::count();
        $totalProperties = Property::count();

        return view('admin.dashboard', compact('totalUsers', 'totalProperties'));
    }
}
