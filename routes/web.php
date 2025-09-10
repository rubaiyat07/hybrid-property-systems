<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\LeaseController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group.
|
*/

// Welcome page
Route::get('/', function () {
    return view('welcome');
});

// Login redirect based on role
Route::get('/dashboard', function () {
    $user = auth()->user();
    if (!$user) {
        return redirect()->route('login');
    }

    if ($user->hasRole('Admin')) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->hasRole('Landlord')) {
        return redirect()->route('landlord.dashboard');
    } elseif ($user->hasRole('Tenant')) {
        return redirect()->route('tenant.dashboard');
    }
    abort(403); // Unknown role
})->middleware(['auth', 'verified'])->name('dashboard');

// Authenticated user routes
Route::middleware(['auth'])->group(function () {

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Routes
    Route::prefix('admin')->middleware(['role:Admin'])->group(function () {
        Route::get('/dashboard', [AdminController::class,'adminDashboard'])->name('admin.dashboard');
        Route::resource('properties', PropertyController::class);
        Route::resource('tenants', TenantController::class);
    });

    // Landlord Routes
    Route::prefix('landlord')->middleware(['role:Landlord'])->group(function () {
        // Route::get('/dashboard', [UserController::class,'landlordDashboard'])->name('landlord.dashboard');
        // Route::resource('properties', PropertyController::class);
        // Route::resource('units', UnitController::class);
    });

    // Tenant Routes
    Route::prefix('tenant')->middleware(['role:Tenant'])->group(function () {
        // Route::get('/dashboard', [UserController::class,'tenantDashboard'])->name('tenant.dashboard');
        // Route::resource('leases', LeaseController::class);
        // Route::resource('payments', PaymentController::class);
    });
});

require __DIR__.'/auth.php';
