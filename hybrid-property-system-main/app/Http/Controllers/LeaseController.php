<?php

namespace App\Http\Controllers;

use App\Models\Lease;
use App\Models\Tenant;
use App\Models\Unit;
use App\Http\Requests\StoreLeaseRequest;
use App\Http\Requests\UpdateLeaseRequest;
use App\Http\Requests\LeaseDocumentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LeaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Lease::with(['tenant.user', 'unit.property']);

        // Filter by status if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by tenant if provided
        if ($request->filled('tenant_id')) {
            $query->where('tenant_id', $request->tenant_id);
        }

        // Filter by unit if provided
        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        // Search by tenant name or property address
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('tenant.user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('unit.property', function($q) use ($search) {
                $q->where('address', 'like', "%{$search}%");
            });
        }

        $leases = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get filter options
        $tenants = Tenant::with('user')->get();
        $units = Unit::with('property')->get();
        $properties = \App\Models\Property::where('owner_id', auth()->id())->get();

        if (auth()->user()->hasRole('Admin')) {
            return view('admin.leases.index', compact('leases', 'tenants', 'units'));
        }

        return view('landlord.leases.index', compact('leases', 'tenants', 'units', 'properties'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // Get available tenants and units
        if (auth()->user()->hasRole('Landlord')) {
            $tenants = Tenant::where(function($q) {
                $q->whereHas('leases.unit.property', function($subQ) {
                    $subQ->where('owner_id', auth()->id());
                })->orWhereHas('user.unitInquiries', function($subQ) {
                    $subQ->whereHas('unit.property', function($subSubQ) {
                        $subSubQ->where('owner_id', auth()->id());
                    })->where('status', 'approved');
                });
            })->with('user')->get();
            $units = Unit::where('status', 'available')
                ->whereHas('property', function($q) {
                    $q->where('owner_id', auth()->id());
                })
                ->with('property')->get();
        } else {
            $tenants = Tenant::with('user')->get();
            $units = Unit::where('status', 'available')->with('property')->get();
        }

        // If unit_id is provided (from approval redirect), filter to only that unit if available
        if ($request->old('unit_id')) {
            $unitId = $request->old('unit_id');
            $unit = Unit::where('id', $unitId)->where('status', 'available')->with('property')->first();
            if ($unit) {
                $units = collect([$unit]);
            }
        }

        // If property_id is provided, filter units by property
        if ($request->filled('property_id')) {
            $units = $units->where('property_id', $request->property_id);
        }

        $inquiry = null;
        if ($request->old('inquiry_id')) {
            $inquiry = \App\Models\UnitInquiry::find($request->old('inquiry_id'));
        }

        if (auth()->user()->hasRole('Admin')) {
            return view('admin.leases.create', compact('tenants', 'units', 'inquiry'));
        }

        return view('landlord.leases.create', compact('tenants', 'units', 'inquiry'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreLeaseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLeaseRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();

            // If inquiry_id is provided, create new tenant from inquiry
            if ($request->filled('inquiry_id')) {
                $inquiry = \App\Models\UnitInquiry::findOrFail($request->inquiry_id);

                // Check if inquiry belongs to landlord's property
                if ($inquiry->unit->property->owner_id !== auth()->id()) {
                    throw new \Exception('Unauthorized inquiry.');
                }

                // Create user if not exists
                $user = \App\Models\User::firstOrCreate(
                    ['email' => $inquiry->inquirer_email],
                    [
                        'name' => $inquiry->inquirer_name,
                        'phone' => $inquiry->inquirer_phone ?? '',
                        'password' => bcrypt('password123'), // Temporary password
                    ]
                );

                // Assign tenant role if not already
                if (!$user->hasRole('Tenant')) {
                    $user->assignRole('Tenant');
                }

                // Create tenant if not exists
                $tenant = \App\Models\Tenant::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'first_name' => $inquiry->inquirer_name,
                        'last_name' => '',
                        'phone' => $inquiry->inquirer_phone ?? '',
                        'email' => $inquiry->inquirer_email,
                    ]
                );

                $data['tenant_id'] = $tenant->id;

                // Update inquiry status to leased
                $inquiry->update(['status' => 'leased']);
            }

            // Handle file upload
            if ($request->hasFile('document')) {
                $documentPath = $request->file('document')->store('leases', 'public');
                $data['document_path'] = $documentPath;
            }

            // Set default status
            $data['status'] = $data['status'] ?? 'active';

            // Create the lease
            $lease = Lease::create($data);

            // Update unit status to occupied
            $lease->unit->update(['status' => 'occupied']);

            DB::commit();

            $message = 'Lease created successfully!';
            if (auth()->user()->hasRole('Admin')) {
                return redirect()->route('admin.leases.show', $lease)->with('success', $message);
            }

            return redirect()->route('landlord.leases.show', $lease)->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();

            if (auth()->user()->hasRole('Admin')) {
                return redirect()->back()->with('error', 'Failed to create lease: ' . $e->getMessage())->withInput();
            }

            return redirect()->back()->with('error', 'Failed to create lease: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lease  $lease
     * @return \Illuminate\Http\Response
     */
    public function show(Lease $lease)
    {
        $lease->load(['tenant.user', 'unit.property', 'payments', 'invoices']);

        if (auth()->user()->hasRole('Admin')) {
            return view('admin.leases.show', compact('lease'));
        }

        return view('landlord.leases.show', compact('lease'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Lease  $lease
     * @return \Illuminate\Http\Response
     */
    public function edit(Lease $lease)
    {
        $tenants = Tenant::with('user')->get();
        $units = Unit::with('property')->get();

        if (auth()->user()->hasRole('Admin')) {
            return view('admin.leases.edit', compact('lease', 'tenants', 'units'));
        }

        return view('landlord.leases.edit', compact('lease', 'tenants', 'units'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateLeaseRequest  $request
     * @param  \App\Models\Lease  $lease
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLeaseRequest $request, Lease $lease)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();

            // Handle file upload
            if ($request->hasFile('document')) {
                // Delete old document if exists
                if ($lease->document_path) {
                    Storage::disk('public')->delete($lease->document_path);
                }

                $documentPath = $request->file('document')->store('leases', 'public');
                $data['document_path'] = $documentPath;
            }

            // Update the lease
            $lease->update($data);

            DB::commit();

            $message = 'Lease updated successfully!';
            if (auth()->user()->hasRole('Admin')) {
                return redirect()->route('admin.leases.show', $lease)->with('success', $message);
            }

            return redirect()->route('landlord.leases.show', $lease)->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();

            if (auth()->user()->hasRole('Admin')) {
                return redirect()->back()->with('error', 'Failed to update lease: ' . $e->getMessage())->withInput();
            }

            return redirect()->back()->with('error', 'Failed to update lease: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Lease  $lease
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lease $lease)
    {
        try {
            DB::beginTransaction();

            // Delete document file if exists
            if ($lease->document_path) {
                Storage::disk('public')->delete($lease->document_path);
            }

            // Update unit status back to available
            $lease->unit->update(['status' => 'available']);

            // Delete the lease
            $lease->delete();

            DB::commit();

            $message = 'Lease deleted successfully!';
            if (auth()->user()->hasRole('Admin')) {
                return redirect()->route('admin.leases.index')->with('success', $message);
            }

            return redirect()->route('landlord.leases.index')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();

            if (auth()->user()->hasRole('Admin')) {
                return redirect()->back()->with('error', 'Failed to delete lease: ' . $e->getMessage());
            }

            return redirect()->back()->with('error', 'Failed to delete lease: ' . $e->getMessage());
        }
    }

    /**
     * Renew the specified lease.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lease  $lease
     * @return \Illuminate\Http\Response
     */
    public function renew(Request $request, Lease $lease)
    {
        $request->validate([
            'new_end_date' => 'required|date|after:' . $lease->end_date,
            'new_rent_amount' => 'nullable|numeric|min:0|max:999999.99',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            // Create new lease with updated dates
            $newLeaseData = [
                'tenant_id' => $lease->tenant_id,
                'unit_id' => $lease->unit_id,
                'start_date' => Carbon::parse($lease->end_date)->addDay(),
                'end_date' => $request->new_end_date,
                'rent_amount' => $request->new_rent_amount ?? $lease->rent_amount,
                'deposit' => $lease->deposit,
                'status' => 'active',
                'notes' => $request->notes,
            ];

            $newLease = Lease::create($newLeaseData);

            // Mark old lease as expired
            $lease->update(['status' => 'expired']);

            DB::commit();

            $message = 'Lease renewed successfully!';
            if (auth()->user()->hasRole('Admin')) {
                return redirect()->route('admin.leases.show', $newLease)->with('success', $message);
            }

            return redirect()->route('landlord.leases.show', $newLease)->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();

            if (auth()->user()->hasRole('Admin')) {
                return redirect()->back()->with('error', 'Failed to renew lease: ' . $e->getMessage())->withInput();
            }

            return redirect()->back()->with('error', 'Failed to renew lease: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Terminate the specified lease.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lease  $lease
     * @return \Illuminate\Http\Response
     */
    public function terminate(Request $request, Lease $lease)
    {
        $request->validate([
            'termination_date' => 'required|date|after_or_equal:' . $lease->start_date,
            'termination_reason' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            // Update lease status and termination details
            $lease->update([
                'status' => 'terminated',
                'actual_end_date' => $request->termination_date,
                'termination_reason' => $request->termination_reason,
                'notes' => $request->notes,
            ]);

            // Update unit status to available
            $lease->unit->update(['status' => 'available']);

            DB::commit();

            $message = 'Lease terminated successfully!';
            if (auth()->user()->hasRole('Admin')) {
                return redirect()->route('admin.leases.show', $lease)->with('success', $message);
            }

            return redirect()->route('landlord.leases.show', $lease)->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();

            if (auth()->user()->hasRole('Admin')) {
                return redirect()->back()->with('error', 'Failed to terminate lease: ' . $e->getMessage())->withInput();
            }

            return redirect()->back()->with('error', 'Failed to terminate lease: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Upload a document for the specified lease.
     *
     * @param  \App\Http\Requests\LeaseDocumentRequest  $request
     * @param  \App\Models\Lease  $lease
     * @return \Illuminate\Http\Response
     */
    public function uploadDocument(LeaseDocumentRequest $request, Lease $lease)
    {
        try {
            if ($request->hasFile('document')) {
                $documentPath = $request->file('document')->store('leases', 'public');

                // Store document information (you might want to create a separate model for this)
                // For now, we'll just update the document_path if it's empty or create a new document record

                if (empty($lease->document_path)) {
                    $lease->update(['document_path' => $documentPath]);
                }

                $message = 'Document uploaded successfully!';
                if (auth()->user()->hasRole('Admin')) {
                    return redirect()->route('admin.leases.show', $lease)->with('success', $message);
                }

                return redirect()->route('landlord.leases.show', $lease)->with('success', $message);
            }

            return redirect()->back()->with('error', 'No document file provided.');

        } catch (\Exception $e) {
            if (auth()->user()->hasRole('Admin')) {
                return redirect()->back()->with('error', 'Failed to upload document: ' . $e->getMessage());
            }

            return redirect()->back()->with('error', 'Failed to upload document: ' . $e->getMessage());
        }
    }

    /**
     * Download the lease document.
     *
     * @param  \App\Models\Lease  $lease
     * @return \Illuminate\Http\Response
     */
    public function downloadDocument(Lease $lease)
    {
        if (!$lease->document_path) {
            abort(404, 'Document not found.');
        }

        if (!Storage::disk('public')->exists($lease->document_path)) {
            abort(404, 'Document file not found.');
        }

        return Storage::disk('public')->download($lease->document_path);
    }

    /**
     * Get current lease for the authenticated tenant.
     *
     * @return \Illuminate\Http\Response
     */
    public function currentLease()
    {
        $tenant = auth()->user()->tenant;
        if (!$tenant) {
            return redirect()->route('tenant.homepage')->with('error', 'Tenant profile not found.');
        }

        $currentLease = $tenant->leases()
            ->where('status', 'active')
            ->where('end_date', '>=', now())
            ->with(['unit.property'])
            ->first();

        if (!$currentLease) {
            return view('tenant.current-lease', ['lease' => null]);
        }

        return view('tenant.current-lease', compact('currentLease'));
    }

    /**
     * Get all leases for the authenticated tenant.
     *
     * @return \Illuminate\Http\Response
     */
    public function tenantLeases()
    {
        $tenant = auth()->user()->tenant;
        if (!$tenant) {
            return redirect()->route('tenant.homepage')->with('error', 'Tenant profile not found.');
        }

        $leases = $tenant->leases()
            ->with(['unit.property'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('tenant.leases.index', compact('leases'));
    }

    /**
     * Show lease details for tenant.
     *
     * @param  \App\Models\Lease  $lease
     * @return \Illuminate\Http\Response
     */
    public function tenantShow(Lease $lease)
    {
        // Ensure tenant can only view their own leases
        if ($lease->tenant->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to lease.');
        }

        $lease->load(['unit.property', 'payments', 'invoices']);

        return view('tenant.leases.show', compact('lease'));
    }
}
