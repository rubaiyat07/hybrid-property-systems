<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Unit;
use App\Models\Lease;
use App\Models\Payment;
use App\Models\MaintenanceRequest;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Property;


class TenantController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display tenant homepage.
     */
    public function homepage()
    {
        $user = Auth::user();
        $tenant = $user->tenant()->firstOrCreate(
            ['user_id' => $user->id],
            [
                'first_name' => explode(' ', $user->name)[0] ?? $user->name,
                'last_name' => trim(str_replace(explode(' ', $user->name)[0] ?? '', $user->name, '')),
                'email' => $user->email,
                'phone' => $user->phone,
            ]
        );

        $profile = $tenant;

        // Stats
        $stats = [
            'active_leases' => Lease::where('tenant_id', $tenant->id)
                ->where('status', 'active')
                ->count(),
            'total_leases' => Lease::where('tenant_id', $tenant->id)->count(),
            'pending_payments' => Payment::where('tenant_id', $tenant->id)
                ->where('status', 'pending')
                ->count(),
            'total_paid' => Payment::where('tenant_id', $tenant->id)
                ->where('status', 'paid')
                ->sum('amount'),
            'pending_taxes' => \App\Models\PropertyTax::whereHas('property.units.leases', function($q) use ($tenant) {
                $q->where('tenant_id', $tenant->id)->where('status', 'active');
            })->where('status', 'pending')->count(),
            'pending_bills' => \App\Models\PropertyBill::whereHas('property.units.leases', function($q) use ($tenant) {
                $q->where('tenant_id', $tenant->id)->where('status', 'active');
            })->where('status', 'pending')->count(),
        ];

        // Current lease
        $currentLease = Lease::where('tenant_id', $tenant->id)
            ->where('status', 'active')
            ->with('unit.property')
            ->first();

        // Recent payments
        $recentPayments = Payment::where('tenant_id', $tenant->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Upcoming payments
        $upcomingPayments = Payment::where('tenant_id', $tenant->id)
            ->where('due_date', '>', now())
            ->orderBy('due_date')
            ->take(5)
            ->get();

        // Recent maintenance
        $recentMaintenance = MaintenanceRequest::where('tenant_id', $tenant->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Property details for current lease
        $propertyDetails = null;
        $facilities = collect();
        $documents = collect();
        if ($currentLease) {
            $propertyDetails = $currentLease->unit->property;
            $facilities = $propertyDetails->facilities ?? collect();
            $documents = $propertyDetails->documents ?? collect();
        }

        // Sponsored ads (reuse from landlord or public)
        $ads = collect(); // Placeholder, no Ad model yet

        // Active listings for tenants (top 5 vacant units from approved properties)
        $activeListings = Unit::where('status', 'vacant')
            ->whereHas('property', function($q) {
                $q->where('registration_status', \App\Models\Property::REGISTRATION_APPROVED);
            })
            ->with('property')
            ->take(5)
            ->get();

        // Active rentals (top 5 active leases for this tenant)
        $activeRentals = Lease::where('tenant_id', $tenant->id)
            ->where('status', 'active')
            ->with(['tenant.user', 'unit.property'])
            ->take(5)
            ->get();

        return view('tenant.homepage', compact(
            'profile', 'stats', 'currentLease', 'recentPayments',
            'upcomingPayments', 'recentMaintenance', 'ads',
            'activeListings', 'activeRentals', 'propertyDetails',
            'facilities', 'documents'
        ));
    }

    /**
     * Display tenant bills.
     */
    public function bills()
    {
        $user = Auth::user();
        $tenant = $user->tenant;

        $bills = \App\Models\PropertyBill::whereHas('property.units.leases', function($q) use ($tenant) {
            $q->where('tenant_id', $tenant->id)->where('status', 'active');
        })->with('property')->paginate(15);

        return view('tenant.bills.index', compact('bills'));
    }

    /**
     * Pay bill and upload receipt.
     */
    public function payBill(Request $request, $billId)
    {
        $request->validate([
            'receipt' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $bill = \App\Models\PropertyBill::findOrFail($billId);

        // Check if bill belongs to tenant's property
        $tenant = auth()->user()->tenant;
        if (!$bill->property->units->contains(function($unit) use ($tenant) {
            return $unit->leases->where('tenant_id', $tenant->id)->where('status', 'active')->count() > 0;
        })) {
            abort(403);
        }

        $path = $request->file('receipt')->store('receipts', 'public');
        $bill->update([
            'receipt_path' => $path,
            'status' => 'paid',
        ]);

        return redirect()->back()->with('success', 'Bill paid and receipt uploaded successfully.');
    }

    /**
     * Download bill receipt.
     */
    public function downloadBill($billId)
    {
        $bill = \App\Models\PropertyBill::findOrFail($billId);

        // Check if bill belongs to tenant's property
        $tenant = auth()->user()->tenant;
        if (!$bill->property->units->contains(function($unit) use ($tenant) {
            return $unit->leases->where('tenant_id', $tenant->id)->where('status', 'active')->count() > 0;
        })) {
            abort(403);
        }

        return response()->download(storage_path('app/public/' . $bill->receipt_path));
    }

    /**
     * Display tenant taxes.
     */
    public function taxes()
    {
        $user = Auth::user();
        $tenant = $user->tenant;

        $taxes = \App\Models\PropertyTax::whereHas('property.units.leases', function($q) use ($tenant) {
            $q->where('tenant_id', $tenant->id)->where('status', 'active');
        })->with('property')->paginate(15);

        return view('tenant.taxes.index', compact('taxes'));
    }

    /**
     * Upload tax receipt.
     */
    public function uploadTaxReceipt(Request $request, $taxId)
    {
        $request->validate([
            'receipt' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $tax = \App\Models\PropertyTax::findOrFail($taxId);

        // Check if tax belongs to tenant's property
        $tenant = auth()->user()->tenant;
        if (!$tax->property->units->contains(function($unit) use ($tenant) {
            return $unit->leases->where('tenant_id', $tenant->id)->where('status', 'active')->count() > 0;
        })) {
            abort(403);
        }

        $path = $request->file('receipt')->store('receipts', 'public');
        $tax->update([
            'receipt_path' => $path,
            'status' => 'submitted',
        ]);

        return redirect()->back()->with('success', 'Tax receipt uploaded successfully.');
    }

    /**
     * Download tax receipt.
     */
    public function downloadTax($taxId)
    {
        $tax = \App\Models\PropertyTax::findOrFail($taxId);

        // Check if tax belongs to tenant's property
        $tenant = auth()->user()->tenant;
        if (!$tax->property->units->contains(function($unit) use ($tenant) {
            return $unit->leases->where('tenant_id', $tenant->id)->where('status', 'active')->count() > 0;
        })) {
            abort(403);
        }

        return response()->download(storage_path('app/public/' . $tax->receipt_path));
    }

    /**
     * Display lease agreement.
     */
    public function leaseAgreement()
    {
        $user = Auth::user();
        $tenant = $user->tenant;

        $lease = Lease::where('tenant_id', $tenant->id)
            ->where('status', 'active')
            ->with('unit.property')
            ->first();

        return view('tenant.lease.agreement', compact('lease'));
    }

    /**
     * Download lease agreement PDF.
     */
    public function downloadAgreement($leaseId)
    {
        $lease = Lease::findOrFail($leaseId);

        // Check if lease belongs to tenant
        if ($lease->tenant_id !== auth()->user()->tenant->id) {
            abort(403);
        }

        return response()->download(storage_path('app/public/' . $lease->agreement_path));
    }

    /**
     * Request lease renewal.
     */
    public function renewLease(Request $request, $leaseId)
    {
        $lease = Lease::findOrFail($leaseId);

        // Check if lease belongs to tenant
        if ($lease->tenant_id !== auth()->user()->tenant->id) {
            abort(403);
        }

        $lease->update([
            'renewal_requested' => true,
            'renewal_notes' => $request->renewal_notes,
        ]);

        return redirect()->back()->with('success', 'Renewal request submitted.');
    }

    /**
     * Request lease termination.
     */
    public function terminateLease(Request $request, $leaseId)
    {
        $lease = Lease::findOrFail($leaseId);

        // Check if lease belongs to tenant
        if ($lease->tenant_id !== auth()->user()->tenant->id) {
            abort(403);
        }

        $lease->update([
            'termination_requested' => true,
            'termination_notes' => $request->termination_notes,
        ]);

        return redirect()->back()->with('success', 'Termination request submitted.');
    }

    /**
     * Display find rentals page for tenants.
     */
    public function rentalsIndex(Request $request)
    {
        $query = Unit::where('status', 'vacant')
            ->whereHas('property', function($q) {
                $q->where('registration_status', \App\Models\Property::REGISTRATION_APPROVED);
            })
            ->with('property.images') // Assuming Property has images
            ->orderBy('created_at', 'desc');

        // Basic filters (can be expanded)
        if ($request->filled('location')) {
            $query->whereHas('property', function ($q) use ($request) {
                $q->where('city', 'like', '%' . $request->location . '%')
                  ->orWhere('address', 'like', '%' . $request->location . '%');
            });
        }

        if ($request->filled('max_price')) {
            $query->where('rent_amount', '<=', $request->max_price);
        }

        if ($request->filled('bedrooms')) {
            $query->where('bedrooms', '>=', $request->bedrooms);
        }

        $vacantUnits = $query->paginate(10);

        // Fetch tenant's inquiries
        $myInquiries = \App\Models\UnitInquiry::where('inquirer_email', auth()->user()->email)
            ->with('unit.property')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('tenant.rentals.index', compact('vacantUnits', 'myInquiries'));
    }

    // Additional methods can be added for inquiries, etc.
    public function createInquiry(Request $request, Unit $unit)
    {
        // Handle inquiry creation (create UnitInquiry)
        // Redirect back with success
    }

    // Landlord tenant management methods
    public function index(Request $request)
    {
        $landlordId = auth()->id();

        // Get all tenants for the landlord (those with leases on their properties)
        $allTenantsQuery = Tenant::with('user', 'leases.unit.property')
            ->whereHas('leases.unit.property', function ($q) use ($landlordId) {
                $q->where('owner_id', $landlordId);
            });

        // Apply filters to all tenants
        if ($request->filled('search')) {
            $search = $request->search;
            $allTenantsQuery->where(function ($q) use ($search) {
                $q->where('first_name', 'like', '%' . $search . '%')
                  ->orWhere('last_name', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('email', 'like', '%' . $search . '%');
                  });
            });
        }

        if ($request->filled('property_id')) {
            $allTenantsQuery->whereHas('leases.unit.property', function ($q) use ($request) {
                $q->where('id', $request->property_id);
            });
        }

        if ($request->filled('status')) {
            $allTenantsQuery->whereHas('leases', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        $allTenants = $allTenantsQuery->paginate(10);

        // Get pending inquiries for the landlord's properties
        $pendingInquiries = \App\Models\UnitInquiry::with('unit.property')
            ->whereHas('unit.property', function ($q) use ($landlordId) {
                $q->where('owner_id', $landlordId);
            })
            ->where('status', 'pending')
            ->get();

        // Get new tenant leads for the landlord's properties
        $newLeads = \App\Models\TenantLead::with('property', 'unit')
            ->where(function ($q) use ($landlordId) {
                $q->whereHas('property', function ($sub) use ($landlordId) {
                    $sub->where('owner_id', $landlordId);
                })->orWhereHas('unit.property', function ($sub) use ($landlordId) {
                    $sub->where('owner_id', $landlordId);
                });
            })
            ->where('status', 'new')
            ->get();

        // Get active tenants (with active leases) - filter from all tenants
        $activeTenantsQuery = Tenant::with('user', 'leases.unit.property', 'payments')
            ->whereHas('leases', function ($q) {
                $q->where('status', 'active');
            })
            ->whereHas('leases.unit.property', function ($q) use ($landlordId) {
                $q->where('owner_id', $landlordId);
            });

        // Apply same filters
        if ($request->filled('search')) {
            $search = $request->search;
            $activeTenantsQuery->where(function ($q) use ($search) {
                $q->where('first_name', 'like', '%' . $search . '%')
                  ->orWhere('last_name', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('email', 'like', '%' . $search . '%');
                  });
            });
        }

        if ($request->filled('property_id')) {
            $activeTenantsQuery->whereHas('leases.unit.property', function ($q) use ($request) {
                $q->where('id', $request->property_id);
            });
        }

        $activeTenants = $activeTenantsQuery->paginate(10);

        // Calculate dynamic stats for active tenants
        $activeTenantIds = $activeTenantsQuery->pluck('id');
        $activeLeasesCount = Lease::whereIn('tenant_id', $activeTenantIds)
            ->where('status', 'active')
            ->count();

        $pendingPaymentsCount = Payment::whereIn('tenant_id', $activeTenantIds)
            ->where('status', 'pending')
            ->count();

        $overduePaymentsCount = Payment::whereIn('tenant_id', $activeTenantIds)
            ->where('status', 'pending')
            ->where('due_date', '<', now())
            ->count();

        // Get approved properties for filter dropdown
        $properties = Property::where('owner_id', auth()->id())
            ->approved()
            ->get();

        // Prepare variables for view
        $tenants = $allTenants;
        $stats = [
            'total_tenants' => $allTenants->total(),
            'active_leases' => $activeLeasesCount,
            'pending_screening' => $pendingInquiries->count() + $newLeads->count(),
            'overdue_payments' => $overduePaymentsCount,
        ];

        return view('landlord.tenants.index', compact('tenants', 'pendingInquiries', 'newLeads', 'activeTenants', 'properties', 'stats'));
    }

    public function create()
    {
        return view('landlord.tenants.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create user first
        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
        ]);

        // Assign tenant role
        $user->assignRole('Tenant');

        // Create tenant record
        $tenant = Tenant::create([
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return redirect()->route('landlord.tenants.index')->with('success', 'Tenant created successfully.');
    }

    public function show(Tenant $tenant)
    {
        $tenant->load('user', 'leases.unit.property', 'payments');

        // Load inquiries for this tenant
        $inquiries = \App\Models\UnitInquiry::where('inquirer_email', $tenant->user->email)
            ->with('unit.property')
            ->get();

        $tenantLeads = \App\Models\TenantLead::where('email', $tenant->user->email)
            ->with('unit.property', 'property')
            ->get();

        return view('landlord.tenants.show', compact('tenant', 'inquiries', 'tenantLeads'));
    }

    /**
     * Approve a unit inquiry for lease application
     */
    public function approveInquiry(\App\Models\UnitInquiry $inquiry)
    {
        // Check if inquiry belongs to landlord's property
        if ($inquiry->unit->property->owner_id !== auth()->id()) {
            abort(403);
        }

        // Create tenant from inquiry
        $user = \App\Models\User::firstOrCreate(
            ['email' => $inquiry->inquirer_email],
            [
                'name' => $inquiry->inquirer_name,
                'phone' => $inquiry->inquirer_phone ?? '',
                'password' => bcrypt('password123'), // Temporary password
            ]
        );

        $tenant = \App\Models\Tenant::firstOrCreate(
            ['user_id' => $user->id],
            [
                'first_name' => $inquiry->inquirer_name,
                'last_name' => '',
                'phone' => $inquiry->inquirer_phone ?? '',
                'email' => $inquiry->inquirer_email,
                'move_in_date' => now(),
            ]
        );

        // Assign tenant role if not already
        if (!$user->hasRole('Tenant')) {
            $user->assignRole('Tenant');
        }

        // Update inquiry status
        $inquiry->update(['status' => 'approved']);

        // Redirect to create lease for this unit with prefilled tenant
        return redirect()->route('landlord.leases.create')
            ->withInput(['tenant_id' => $tenant->id, 'unit_id' => $inquiry->unit_id])
            ->with('success', 'Inquiry approved and tenant created. Proceed to create lease.');
    }

    /**
     * Decline a unit inquiry
     */
    public function declineInquiry(Tenant $tenant, \App\Models\UnitInquiry $inquiry)
    {
        if ($inquiry->inquirer_email !== $tenant->user->email) {
            return redirect()->back()->with('error', 'Inquiry does not match tenant.');
        }

        $inquiry->update(['status' => 'declined', 'response' => 'Lease application declined by landlord.']);

        return redirect()->back()->with('success', 'Inquiry declined.');
    }

    /**
     * Approve a tenant lead for lease application
     */
    public function approveLead(Tenant $tenant, \App\Models\TenantLead $lead)
    {
        if ($lead->email !== $tenant->user->email) {
            return redirect()->back()->with('error', 'Lead does not match tenant.');
        }

        // Mark lead as approved
        $lead->update(['status' => \App\Models\TenantLead::STATUS_QUALIFIED]);

        // Always redirect to create lease; if no unit_id, form will allow selection
        return redirect()->route('landlord.leases.create')
            ->withInput(['tenant_id' => $tenant->id, 'unit_id' => $lead->unit_id])
            ->with('success', 'Lead approved. Proceed to create lease.');
    }

    /**
     * Decline a tenant lead
     */
    public function declineLead(Tenant $tenant, \App\Models\TenantLead $lead)
    {
        if ($lead->email !== $tenant->user->email) {
            return redirect()->back()->with('error', 'Lead does not match tenant.');
        }

        $lead->update(['status' => \App\Models\TenantLead::STATUS_REJECTED]);

        return redirect()->back()->with('success', 'Lead declined.');
    }

    /**
     * Show reply form for general inquiry
     */
    public function replyInquiry(Tenant $tenant, \App\Models\UnitInquiry $inquiry)
    {
        if ($inquiry->inquirer_email !== $tenant->user->email || $inquiry->inquiry_type !== 'general_inquiry') {
            return redirect()->back()->with('error', 'Invalid inquiry.');
        }

        return view('landlord.tenants.reply-inquiry', compact('tenant', 'inquiry'));
    }

    /**
     * Send reply to general inquiry
     */
    public function sendReply(Request $request, Tenant $tenant, \App\Models\UnitInquiry $inquiry)
    {
        if ($inquiry->inquirer_email !== $tenant->user->email || $inquiry->inquiry_type !== 'general_inquiry') {
            return redirect()->back()->with('error', 'Invalid inquiry.');
        }

        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        // Update inquiry with response
        $inquiry->update([
            'status' => 'replied',
            'response' => $request->message,
        ]);

        return redirect()->route('landlord.tenants.show', $tenant)->with('success', 'Reply sent successfully.');
    }

    // Edit and update methods removed - landlords cannot edit tenants

    public function destroy(Tenant $tenant)
    {
        $tenant->user->delete(); // This will cascade delete tenant due to foreign key
        return redirect()->route('landlord.tenants.index')->with('success', 'Tenant deleted successfully.');
    }

    // Index page inquiry management methods
    public function approveInquiryFromIndex(Request $request, \App\Models\UnitInquiry $inquiry)
    {
        // Check if inquiry belongs to landlord's property
        if ($inquiry->unit->property->owner_id !== auth()->id()) {
            abort(403);
        }

        // Create tenant from inquiry
        $user = User::firstOrCreate(
            ['email' => $inquiry->inquirer_email],
            [
                'name' => $inquiry->inquirer_name,
                'phone' => $inquiry->inquirer_phone ?? '',
                'password' => bcrypt('password123'), // Temporary password
            ]
        );

        $tenant = Tenant::firstOrCreate(
            ['user_id' => $user->id],
            [
                'first_name' => $inquiry->inquirer_name,
                'last_name' => '',
                'phone' => $inquiry->inquirer_phone ?? '',
                'email' => $inquiry->inquirer_email,
                'move_in_date' => now(),
            ]
        );

        // Assign tenant role if not already
        if (!$user->hasRole('Tenant')) {
            $user->assignRole('Tenant');
        }

        // Update inquiry status
        $inquiry->update(['status' => 'approved']);

        return redirect()->route('landlord.leases.create')
            ->withInput(['tenant_id' => $tenant->id, 'unit_id' => $inquiry->unit_id])
            ->with('success', 'Inquiry approved and tenant created. Proceed to create lease.');
    }

    public function declineInquiryFromIndex(Request $request, \App\Models\UnitInquiry $inquiry)
    {
        // Check if inquiry belongs to landlord's property
        if ($inquiry->unit->property->owner_id !== auth()->id()) {
            abort(403);
        }

        $inquiry->update(['status' => 'closed']);

        return redirect()->back()->with('success', 'Inquiry declined.');
    }

    public function replyInquiryFromIndex(\App\Models\UnitInquiry $inquiry)
    {
        // Check if inquiry belongs to landlord's property
        if ($inquiry->unit->property->owner_id !== auth()->id()) {
            abort(403);
        }

        return view('landlord.tenants.reply-inquiry', compact('inquiry'));
    }

    public function sendReplyFromIndex(Request $request, \App\Models\UnitInquiry $inquiry)
    {
        // Check if inquiry belongs to landlord's property
        if ($inquiry->unit->property->owner_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'reply_message' => 'required|string|max:1000',
        ]);

        // Send reply (implement email sending here)
        $inquiry->update([
            'status' => 'responded',
            'response' => $request->reply_message,
            'responded_at' => now()
        ]);

        return redirect()->route('landlord.tenants.index')->with('success', 'Reply sent successfully.');
    }

    public function approveLeadFromIndex(Request $request, \App\Models\TenantLead $lead)
    {
        // Check if lead belongs to landlord's property
        if (($lead->property && $lead->property->owner_id !== auth()->id()) ||
            ($lead->unit && $lead->unit->property->owner_id !== auth()->id())) {
            abort(403);
        }

        $lead->update(['status' => \App\Models\TenantLead::STATUS_QUALIFIED]);

        return redirect()->route('landlord.tenants.index')->with('success', 'Lead approved.');
    }

    public function declineLeadFromIndex(Request $request, \App\Models\TenantLead $lead)
    {
        // Check if lead belongs to landlord's property
        if (($lead->property && $lead->property->owner_id !== auth()->id()) ||
            ($lead->unit && $lead->unit->property->owner_id !== auth()->id())) {
            abort(403);
        }

        $lead->update(['status' => \App\Models\TenantLead::STATUS_REJECTED]);

        return redirect()->route('landlord.tenants.index')->with('success', 'Lead declined.');
    }
}
