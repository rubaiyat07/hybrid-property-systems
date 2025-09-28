<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\LeaseController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AdminPropertyController as AdminPropertyManagementController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PropertyImageController;
use App\Http\Controllers\PublicListingController;
use App\Http\Controllers\TenantInquiryController;

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
    return view('public_pages.welcome');
})->name('public_pages.welcome');

// Login redirect based on role
Route::get('/dashboard', function () {
    $user = auth()->user();
    if (!$user) {
        return redirect()->route('login');
    }

    if ($user->hasRole('Admin')) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->hasRole('Landlord')) {
        return redirect()->route('landlord.homepage');
    } elseif ($user->hasRole('Tenant')) {
        return redirect()->route('tenant.homepage');
    } elseif ($user->hasRole('Agent')) {
        return redirect()->route('agent.homepage');
    } elseif ($user->hasRole('Buyer')) {
        return redirect()->route('buyer.homepage');
    } elseif ($user->hasRole('Maintenance')) {
        return redirect()->route('maintenance.homepage');
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
    Route::prefix('admin')->name('admin.')->middleware(['role:Admin'])->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'adminDashboard'])->name('dashboard');
        
        // User Management
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::post('/users/{user}/ban', [UserController::class, 'ban'])->name('users.ban');
        Route::post('/users/{user}/unban', [UserController::class, 'unban'])->name('users.unban');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::post('/users/bulk-action', [UserController::class, 'bulkAction'])->name('users.bulk-action');
        
        // Property Registration Management
        Route::prefix('property')->name('property.')->group(function () {
            Route::get('/', [AdminPropertyManagementController::class, 'index'])->name('index');
            Route::get('/{property}', [AdminPropertyManagementController::class, 'show'])->name('show');
            Route::get('/{property}/quick-view', [AdminPropertyManagementController::class, 'quickView'])->name('quick-view');
            Route::post('/{property}/approve', [AdminPropertyManagementController::class, 'approve'])->name('approve');
            Route::post('/{property}/reject', [AdminPropertyManagementController::class, 'reject'])->name('reject');
            Route::put('/{property}/reset-pending', [AdminPropertyManagementController::class, 'resetToPending'])->name('reset-pending');

            // Bulk Property Actions
            Route::post('/bulk-approve', [AdminPropertyManagementController::class, 'bulkApprove'])->name('bulk-approve');
            Route::post('/bulk-reject', [AdminPropertyManagementController::class, 'bulkReject'])->name('bulk-reject');

            // Property Registration Dashboard
            Route::get('/dashboard/stats', [AdminPropertyManagementController::class, 'dashboard'])->name('dashboard');
        });
        
        // Original Property Management (for direct admin management)
        Route::resource('properties', PropertyController::class);
        
        // Tenant Management
        Route::resource('tenants', TenantController::class);
        
        // Unit Management
        Route::resource('units', UnitController::class);
        
        // Lease Management
        Route::resource('leases', LeaseController::class);

        // Additional Lease Routes
        Route::post('leases/{lease}/renew', [LeaseController::class, 'renew'])->name('leases.renew');
        Route::post('leases/{lease}/terminate', [LeaseController::class, 'terminate'])->name('leases.terminate');
        Route::post('leases/{lease}/upload-document', [LeaseController::class, 'uploadDocument'])->name('leases.upload-document');
        Route::get('leases/{lease}/download-document', [LeaseController::class, 'downloadDocument'])->name('leases.download-document');
        
        // Payment Management
        Route::resource('payments', PaymentController::class);
        
        // Reports
        Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
        Route::get('/reports/income', [AdminController::class, 'incomeReport'])->name('reports.income');
        Route::get('/reports/occupancy', [AdminController::class, 'occupancyReport'])->name('reports.occupancy');
        
        // Tax Management
        Route::get('/taxes', [AdminController::class, 'taxes'])->name('taxes.index');
        Route::post('/taxes/{tax}/verify', [AdminController::class, 'verifyTax'])->name('taxes.verify');
        
        // Utility Bills Management
        Route::get('/bills', [AdminController::class, 'bills'])->name('bills.index');
        Route::post('/bills/{bill}/verify', [AdminController::class, 'verifyBill'])->name('bills.verify');
        
        // Property Documents Management
        Route::get('/documents', [AdminController::class, 'documents'])->name('documents.index');
        Route::post('/documents/{document}/approve', [AdminController::class, 'approveDocument'])->name('documents.approve');
        Route::post('/documents/{document}/reject', [AdminController::class, 'rejectDocument'])->name('documents.reject');
        
        // Property Transfers Management
        Route::get('/transfers', [AdminController::class, 'transfers'])->name('transfers.index');
        Route::post('/transfers/{transfer}/approve', [AdminController::class, 'approveTransfer'])->name('transfers.approve');
        Route::post('/transfers/{transfer}/reject', [AdminController::class, 'rejectTransfer'])->name('transfers.reject');
        
        // Tenant Screening Management
        Route::get('/screenings', [AdminController::class, 'screenings'])->name('screenings.index');
        Route::get('/screenings/{screening}', [AdminController::class, 'showScreening'])->name('screenings.show');
        Route::post('/screenings/{screening}/approve', [AdminController::class, 'approveScreening'])->name('screenings.approve');
        Route::post('/screenings/{screening}/reject', [AdminController::class, 'rejectScreening'])->name('screenings.reject');

        // Agent Management
        Route::get('/agents', [\App\Http\Controllers\UserController::class, 'agents'])->name('agents.index');
        Route::get('/agents/{agent}', [\App\Http\Controllers\UserController::class, 'showAgent'])->name('agents.show');

        // Maintenance Requests Management
        Route::get('/maintenance-requests', [\App\Http\Controllers\MaintenanceController::class, 'adminIndex'])->name('maintenance_requests.index');
        Route::get('/maintenance-requests/{request}', [\App\Http\Controllers\MaintenanceController::class, 'adminShow'])->name('maintenance_requests.show');
        Route::put('/maintenance-requests/{request}', [\App\Http\Controllers\MaintenanceController::class, 'adminUpdate'])->name('maintenance_requests.update');

        // Settings
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
        Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
    });

    // Landlord Routes
    Route::prefix('landlord')->name('landlord.')->middleware(['role:Landlord'])->group(function () {
        // Dashboard
        Route::get('/homepage', [\App\Http\Controllers\LandlordController::class, 'landlordHomepage'])->name('homepage');
        
        // Property Registration and Management
        Route::resource('property', PropertyController::class);
        
        // Property Image Management
    Route::get('/property/{property}/images', [PropertyImageController::class, 'index'])
        ->name('property.images.index');
    Route::post('/property/{property}/images', [PropertyImageController::class, 'store'])
        ->name('property.images.store');
    Route::put('/property/{property}/images/{image}/primary', [PropertyImageController::class, 'setPrimary'])
        ->name('property.images.set-primary');
    Route::delete('/property/{property}/images/{image}', [PropertyImageController::class, 'destroy'])
        ->name('property.images.destroy');
    Route::post('/property/{property}/images/reorder', [PropertyImageController::class, 'reorder'])
        ->name('property.images.reorder');

        // Property Resubmission
        Route::post('property/{property}/resubmit', [PropertyController::class, 'resubmit'])
             ->name('property.resubmit');
        
        // Units (under landlord's approved properties only)
        Route::resource('units', UnitController::class)->except(['destroy']); 
        Route::delete('units/{unit}', [UnitController::class, 'destroy'])->name('units.destroy');
        Route::get('units/create/{property_id?}', [UnitController::class, 'create'])
             ->name('units.create.property');
        Route::get('property/{property}/units', [UnitController::class, 'getPropertyUnits'])
             ->name('property.units');
        
        // Tenant Management (Full CRUD for landlord's tenants)
        Route::get('/tenants', [TenantController::class, 'index'])->name('tenants.index');
        Route::get('/tenants/create', [TenantController::class, 'create'])->name('tenants.create');
        Route::post('/tenants', [TenantController::class, 'store'])->name('tenants.store');
        Route::get('/tenants/{tenant}', [TenantController::class, 'show'])->name('tenants.show');
        // Edit and update removed - landlords cannot edit tenants
        Route::delete('/tenants/{tenant}', [TenantController::class, 'destroy'])->name('tenants.destroy');
        
        // AJAX Routes for Tenant Management
        Route::get('/properties/{property}/units', [TenantController::class, 'getUnits'])->name('tenants.get-units');
        Route::get('/tenants/search', [TenantController::class, 'search'])->name('tenants.search');

        // Inquiry Approval/Decline for Tenants
        Route::post('/tenants/{tenant}/inquiries/{inquiry}/approve', [TenantController::class, 'approveInquiry'])->name('tenants.approve-inquiry');
        Route::post('/tenants/{tenant}/inquiries/{inquiry}/decline', [TenantController::class, 'declineInquiry'])->name('tenants.decline-inquiry');
        Route::post('/tenants/{tenant}/leads/{lead}/approve', [TenantController::class, 'approveLead'])->name('tenants.approve-lead');
        Route::post('/tenants/{tenant}/leads/{lead}/decline', [TenantController::class, 'declineLead'])->name('tenants.decline-lead');

        // Inquiry management from index page (applications)
        Route::post('/inquiries/{inquiry}/approve', [TenantController::class, 'approveInquiryFromIndex'])->name('inquiries.approve');
        Route::post('/inquiries/{inquiry}/decline', [TenantController::class, 'declineInquiryFromIndex'])->name('inquiries.decline');
        Route::get('/inquiries/{inquiry}/reply', [TenantController::class, 'replyInquiryFromIndex'])->name('inquiries.reply');
        Route::post('/inquiries/{inquiry}/send-reply', [TenantController::class, 'sendReplyFromIndex'])->name('inquiries.send-reply');

        // Lead management from index page
        Route::post('/leads/{lead}/approve', [TenantController::class, 'approveLeadFromIndex'])->name('leads.approve');
        Route::post('/leads/{lead}/decline', [TenantController::class, 'declineLeadFromIndex'])->name('leads.decline');
        
        // Tenant Communication
        Route::get('/tenants/{tenant}/message', [TenantController::class, 'messageForm'])->name('tenants.message');
        Route::post('/tenants/{tenant}/message', [TenantController::class, 'sendMessage'])->name('tenants.send-message');
        
        // Leases (landlord creates leases for tenants)
        Route::resource('leases', LeaseController::class)->only(['index', 'show', 'create', 'store', 'edit', 'update', 'destroy']);

        // Additional Lease Routes for Landlord
        Route::post('leases/{lease}/renew', [LeaseController::class, 'renew'])->name('leases.renew');
        Route::post('leases/{lease}/terminate', [LeaseController::class, 'terminate'])->name('leases.terminate');
        Route::post('leases/{lease}/upload-document', [LeaseController::class, 'uploadDocument'])->name('leases.upload-document');
        Route::get('leases/{lease}/download-document', [LeaseController::class, 'downloadDocument'])->name('leases.download-document');
        
        // Payments (landlord can view payments received)
        Route::resource('payments', PaymentController::class)->only(['index', 'show']);

        // Tax Management
        Route::resource('taxes', \App\Http\Controllers\LandlordTaxController::class)->only(['index']);
        Route::post('taxes/{tax}/upload-receipt', [\App\Http\Controllers\LandlordTaxController::class, 'uploadReceipt'])->name('taxes.upload-receipt');

        // Bill Management
        Route::resource('bills', \App\Http\Controllers\LandlordBillController::class)->only(['index']);
        Route::post('bills/{bill}/pay', [\App\Http\Controllers\LandlordBillController::class, 'pay'])->name('bills.pay');

        // Property Documents
        Route::prefix('property/{property}')->name('property.')->group(function () {
            Route::get('documents', [\App\Http\Controllers\LandlordDocumentController::class, 'index'])->name('documents.index');
            Route::post('documents', [\App\Http\Controllers\LandlordDocumentController::class, 'store'])->name('documents.store');
            Route::get('documents/{document}/download', [\App\Http\Controllers\LandlordDocumentController::class, 'download'])->name('documents.download');
        });

        // Property Transfers
        Route::prefix('property/{property}')->name('property.')->group(function () {
            Route::get('transfers', [\App\Http\Controllers\LandlordTransferController::class, 'index'])->name('transfers.index');
            Route::get('transfers/create', [\App\Http\Controllers\LandlordTransferController::class, 'create'])->name('transfers.create');
            Route::post('transfers', [\App\Http\Controllers\LandlordTransferController::class, 'store'])->name('transfers.store');
        });

        // Property Facilities
        Route::prefix('property/{property}')->name('property.')->group(function () {
            Route::get('facilities', [PropertyController::class, 'facilities'])->name('facilities.index');
            Route::post('facilities', [PropertyController::class, 'storeFacility'])->name('facilities.store');
            Route::put('facilities/{facility}', [PropertyController::class, 'updateFacility'])->name('facilities.update');
            Route::delete('facilities/{facility}', [PropertyController::class, 'destroyFacility'])->name('facilities.destroy');
        });
    });

    // Agent Routes (only approved properties)
    Route::prefix('agent')->name('agent.')->middleware(['role:Agent'])->group(function () {
        // Dashboard
        Route::get('/homepage', [\App\Http\Controllers\AgentController::class, 'agentHomepage'])->name('homepage');
        Route::get('/dashboard', [\App\Http\Controllers\AgentController::class, 'agentDashboard'])->name('dashboard');

        // Property Registration and Management
        Route::get('/properties', [PropertyController::class, 'agentIndex'])->name('properties.index');
        Route::get('/properties/create', [PropertyController::class, 'create'])->name('properties.create');
        Route::post('/properties', [PropertyController::class, 'store'])->name('properties.store');
        Route::get('/properties/{property}', [PropertyController::class, 'agentShow'])->name('properties.show');

        // Leads (agent manages potential buyers/tenants)
        Route::resource('leads', \App\Http\Controllers\LeadController::class)->except(['destroy']);

        // Transactions (sales/rental transactions handled by agent)
        Route::resource('transactions', \App\Http\Controllers\TransactionController::class)->only(['index', 'show', 'create', 'store']);
    });

    // Tenant Routes (Individual Tenant Dashboard)
    Route::prefix('tenant')->name('tenant.')->middleware(['role:Tenant'])->group(function () {
        Route::get('/homepage', [TenantController::class, 'homepage'])->name('homepage');
        Route::get('/dashboard', [DashboardController::class, 'tenantDashboard'])->name('dashboard');
        Route::get('/rentals', [TenantController::class, 'rentalsIndex'])->name('rentals.index');
        
        // Tenant's Property Browsing
        Route::get('/properties/browse', [PropertyController::class, 'tenantBrowse'])->name('properties.browse');
        Route::get('/properties/{property}', [PropertyController::class, 'tenantShow'])->name('properties.show');
        
        // Tenant's Current Lease
        Route::get('/lease/current', [LeaseController::class, 'currentLease'])->name('lease.current');
        Route::get('/lease/agreement', [TenantController::class, 'leaseAgreement'])->name('lease.agreement');
        Route::get('/lease/{lease}/download-agreement', [TenantController::class, 'downloadAgreement'])->name('lease.download-agreement');
        Route::post('/lease/{lease}/renew-request', [TenantController::class, 'renewLease'])->name('lease.renew-request');
        Route::post('/lease/{lease}/terminate-request', [TenantController::class, 'terminateLease'])->name('lease.terminate-request');
        Route::get('/leases', [LeaseController::class, 'tenantLeases'])->name('leases.index');
        Route::get('/leases/{lease}', [LeaseController::class, 'tenantShow'])->name('leases.show');
        
        // Tenant's Payments
        Route::get('/payments', [PaymentController::class, 'tenantPayments'])->name('payments.index');
        Route::get('/payments/{payment}', [PaymentController::class, 'tenantShow'])->name('payments.show');
        Route::post('/payments/{payment}/pay', [PaymentController::class, 'processPayment'])->name('payments.pay');
        
        // Maintenance Requests
        Route::get('/maintenance', [TenantController::class, 'maintenanceRequests'])->name('maintenance.index');
        Route::get('/maintenance/create', [TenantController::class, 'createMaintenanceRequest'])->name('maintenance.create');
        Route::post('/maintenance', [TenantController::class, 'storeMaintenanceRequest'])->name('maintenance.store');
        Route::get('/maintenance/{request}', [TenantController::class, 'showMaintenanceRequest'])->name('maintenance.show');
        
        // Tenant Documents
        Route::get('/documents', [TenantController::class, 'documents'])->name('documents.index');
        Route::post('/documents', [TenantController::class, 'uploadDocument'])->name('documents.upload');
        
        // Tenant Support
        Route::get('/support', [TenantController::class, 'support'])->name('support.index');
        Route::post('/support', [TenantController::class, 'submitSupportTicket'])->name('support.submit');
        
        // Tenant Screening & Applications
        Route::get('/screening/status', [TenantController::class, 'screeningStatus'])->name('screening.status');
        Route::get('/screening/submit', [TenantController::class, 'screeningSubmitForm'])->name('screening.submit');
        Route::post('/screening/store', [TenantController::class, 'storeScreeningDocuments'])->name('screening.store');
        Route::get('/applications', [TenantController::class, 'applications'])->name('applications.index');
        Route::post('/applications/{property}', [TenantController::class, 'submitApplication'])->name('applications.submit');
        
        // Tenant Inquiries
        Route::get('/inquiries/{inquiry}', [TenantInquiryController::class, 'tenantShow'])->name('inquiries.show');
        
        // Tenant Profile & Notifications
        Route::get('/profile/edit', [TenantController::class, 'editProfile'])->name('profile.edit');
        Route::put('/profile', [TenantController::class, 'updateProfile'])->name('profile.update');
        Route::get('/notifications', [TenantController::class, 'notifications'])->name('notifications.index');

        // Bills
        Route::get('/bills', [TenantController::class, 'bills'])->name('bills.index');
        Route::post('/bills/{bill}/pay', [TenantController::class, 'payBill'])->name('bills.pay');
        Route::get('/bills/{bill}/download', [TenantController::class, 'downloadBill'])->name('bills.download');

        // Taxes
        Route::get('/taxes', [TenantController::class, 'taxes'])->name('taxes.index');
        Route::post('/taxes/{tax}/upload-receipt', [TenantController::class, 'uploadTaxReceipt'])->name('taxes.upload-receipt');
        Route::get('/taxes/{tax}/download', [TenantController::class, 'downloadTax'])->name('taxes.download');
    });

    // Buyer Routes (only approved, active properties)
    Route::prefix('buyer')->name('buyer.')->middleware(['role:Buyer'])->group(function () {
        Route::get('/homepage', [DashboardController::class, 'buyerDashboard'])->name('homepage');
        Route::get('/dashboard', [DashboardController::class, 'buyerDashboard'])->name('dashboard');
        Route::get('/properties', [PropertyController::class, 'buyerIndex'])->name('properties.index');
        Route::get('/properties/{property}', [PropertyController::class, 'buyerShow'])->name('properties.show');
        Route::get('/favorites', [\App\Http\Controllers\BuyerController::class, 'favorites'])->name('favorites');
        Route::post('/favorites/{property}', [\App\Http\Controllers\BuyerController::class, 'toggleFavorite'])->name('favorites.toggle');
    });

    // Maintenance Routes
    Route::prefix('maintenance')->name('maintenance.')->middleware(['role:Maintenance'])->group(function () {
        Route::get('/homepage', [DashboardController::class, 'maintenanceDashboard'])->name('homepage');
        Route::get('/dashboard', [DashboardController::class, 'maintenanceDashboard'])->name('dashboard');
        Route::get('/requests', [\App\Http\Controllers\MaintenanceController::class, 'index'])->name('requests.index');
        Route::get('/requests/{request}', [\App\Http\Controllers\MaintenanceController::class, 'show'])->name('requests.show');
        Route::put('/requests/{request}', [\App\Http\Controllers\MaintenanceController::class, 'update'])->name('requests.update');
        Route::get('/schedule', [\App\Http\Controllers\MaintenanceController::class, 'schedule'])->name('schedule');
    });

    // Common routes for multiple roles
    Route::middleware(['role:Admin,Landlord,Tenant,Agent,Buyer,Maintenance'])->group(function () {
        Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/{notification}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
        
        Route::get('/messages', [\App\Http\Controllers\MessageController::class, 'index'])->name('messages.index');
        Route::post('/messages', [\App\Http\Controllers\MessageController::class, 'store'])->name('messages.store');
        Route::get('/messages/{userId}', [\App\Http\Controllers\MessageController::class, 'conversation'])->name('messages.conversation');
    });
});

// Public property listings (no authentication required) - show only approved properties
Route::get('/public-properties', [PropertyController::class, 'buyerIndex'])->name('public.properties.index');
Route::get('/public-properties/{property}', [PropertyController::class, 'buyerShow'])->name('public.properties.show');

// Public rental listings (no authentication required)
Route::prefix('rentals')->name('rentals.')->group(function () {
    Route::get('/', [PublicListingController::class, 'index'])->name('index');
    Route::get('/{unit}', [PublicListingController::class, 'show'])->name('show');
    Route::get('/search/ajax', [PublicListingController::class, 'search'])->name('search.ajax');
    Route::get('/filters/options', [PublicListingController::class, 'getFilterOptions'])->name('filters.options');
});

// Tenant inquiry routes (no authentication required for form submission)
Route::prefix('inquiry')->name('inquiry.')->group(function () {
    Route::get('/unit/{unit}', [TenantInquiryController::class, 'create'])->name('create');
    Route::post('/unit/{unit}', [TenantInquiryController::class, 'store'])->name('store');
});

// Landlord inquiry management routes (authenticated)
Route::middleware(['auth'])->group(function () {
    Route::prefix('landlord')->name('landlord.')->middleware(['role:Landlord'])->group(function () {
        // Unit listing management
        Route::post('units/{unit}/publish', [UnitController::class, 'publish'])->name('units.publish');
        Route::post('units/{unit}/unpublish', [UnitController::class, 'unpublish'])->name('units.unpublish');
        Route::post('units/{unit}/toggle-listing', [UnitController::class, 'toggleListing'])->name('units.toggle-listing');
        Route::put('units/{unit}/update-listing', [UnitController::class, 'updateListing'])->name('units.update-listing');

        // Inquiry management
        Route::get('/inquiries', [TenantInquiryController::class, 'index'])->name('inquiries.index');
        Route::get('/inquiries/{inquiry}', [TenantInquiryController::class, 'show'])->name('inquiries.show');
        Route::post('/inquiries/{inquiry}/respond', [TenantInquiryController::class, 'respond'])->name('inquiries.respond');
        Route::post('/inquiries/{inquiry}/close', [TenantInquiryController::class, 'close'])->name('inquiries.close');
    });
});

// Menu Management Routes (assuming for restaurant or something)
// Route::middleware(['auth'])->group(function () {
//     Route::resource('menus', MenuController::class);
//     Route::post('menus/{menu}/items', [MenuController::class, 'addItem'])->name('menus.add-item');
//     Route::resource('menu-items', MenuItemController::class);
// });

require __DIR__.'/auth.php';
