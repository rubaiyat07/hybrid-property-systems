<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property;

class AdminPropertyController extends Controller
{
    /**
     * Display a listing of property registration requests.
     */
    public function index(Request $request)
    {
        $query = Property::with(['owner', 'approver']);

        // Filter by status
        $status = $request->get('status', 'pending');
        switch ($status) {
            case 'pending':
                $query->pending();
                break;
            case 'approved':
                $query->approved();
                break;
            case 'rejected':
                $query->rejected();
                break;
            default:
                // Show all if 'all' is selected
                break;
        }

        // Search functionality
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('address', 'like', '%' . $request->search . '%')
                  ->orWhere('city', 'like', '%' . $request->search . '%')
                  ->orWhereHas('owner', function($ownerQuery) use ($request) {
                      $ownerQuery->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $properties = $query->orderBy('created_at', 'desc')->paginate(10);

        // Statistics
        $stats = [
            'pending' => Property::pending()->count(),
            'approved' => Property::approved()->count(),
            'rejected' => Property::rejected()->count(),
            'total' => Property::count()
        ];

        return view('admin.property.index', compact('properties', 'stats', 'status'));
    }

    /**
     * Display the specified property for review.
     */
    public function show(Property $property)
    {
        $property->load(['owner', 'approver', 'documents', 'images']);
        return view('admin.property.show', compact('property'));
    }

    /**
     * Approve a property registration.
     */
    public function approve(Request $request, Property $property)
    {
        $request->validate([
            'notes' => 'nullable|string|max:1000'
        ]);

        if ($property->registration_status !== Property::REGISTRATION_PENDING) {
            return redirect()->back()->with('error', 'Only pending properties can be approved.');
        }

        $property->approve(auth()->id(), $request->notes);

        // You can add notification logic here
        // Notification::create([
        //     'user_id' => $property->owner_id,
        //     'title' => 'Property Approved',
        //     'message' => "Your property '{$property->name}' has been approved for listing.",
        //     'type' => 'info'
        // ]);

        return redirect()->route('admin.property.index')
            ->with('success', "Property '{$property->name}' has been approved successfully.");
    }

    /**
     * Reject a property registration.
     */
    public function reject(Request $request, Property $property)
    {
        $request->validate([
            'notes' => 'required|string|max:1000'
        ]);

        if ($property->registration_status !== Property::REGISTRATION_PENDING) {
            return redirect()->back()->with('error', 'Only pending properties can be rejected.');
        }

        $property->reject(auth()->id(), $request->notes);

        // You can add notification logic here
        // Notification::create([
        //     'user_id' => $property->owner_id,
        //     'title' => 'Property Registration Rejected',
        //     'message' => "Your property '{$property->name}' registration has been rejected.",
        //     'type' => 'warning'
        // ]);

        return redirect()->route('admin.property.index')
            ->with('success', "Property '{$property->name}' has been rejected.");
    }

    /**
     * Reset property status back to pending (for re-review).
     */
    public function resetToPending(Property $property)
    {
        if ($property->registration_status === Property::REGISTRATION_PENDING) {
            return redirect()->back()->with('error', 'Property is already pending.');
        }

        $property->update([
            'registration_status' => Property::REGISTRATION_PENDING,
            'approved_by' => null,
            'approved_at' => null,
            'registration_notes' => null
        ]);

        return redirect()->route('admin.property.index')
            ->with('success', "Property '{$property->name}' has been reset to pending status.");
    }

    /**
     * Bulk approve properties.
     */
    public function bulkApprove(Request $request)
    {
        $request->validate([
            'property_ids' => 'required|array',
            'property_ids.*' => 'exists:properties,id',
            'notes' => 'nullable|string|max:1000'
        ]);

        $properties = Property::whereIn('id', $request->property_ids)
            ->where('registration_status', Property::REGISTRATION_PENDING)
            ->get();

        $approvedCount = 0;
        foreach ($properties as $property) {
            $property->approve(auth()->id(), $request->notes);
            $approvedCount++;
        }

        return redirect()->route('admin.property.index')
            ->with('success', "{$approvedCount} properties have been approved successfully.");
    }

    /**
     * Bulk reject properties.
     */
    public function bulkReject(Request $request)
    {
        $request->validate([
            'property_ids' => 'required|array',
            'property_ids.*' => 'exists:properties,id',
            'notes' => 'required|string|max:1000'
        ]);

        $properties = Property::whereIn('id', $request->property_ids)
            ->where('registration_status', Property::REGISTRATION_PENDING)
            ->get();

        $rejectedCount = 0;
        foreach ($properties as $property) {
            $property->reject(auth()->id(), $request->notes);
            $rejectedCount++;
        }

        return redirect()->route('admin.property.index')
            ->with('success', "{$rejectedCount} properties have been rejected.");
    }

    /**
     * Get quick view data for property (AJAX).
     */
    public function quickView(Property $property)
    {
        $property->load(['owner', 'approver']);

        return response()->json([
            'success' => true,
            'property' => [
                'id' => $property->id,
                'name' => $property->name,
                'address' => $property->address,
                'city' => $property->city,
                'state' => $property->state,
                'zip_code' => $property->zip_code,
                'type' => $property->type,
                'description' => $property->description,
                'price_or_rent' => $property->price_or_rent,
                'status' => $property->status,
                'availability_status' => $property->availability_status,
                'registration_status' => $property->registration_status,
                'image' => $property->image,
                'created_at' => $property->created_at,
                'approved_at' => $property->approved_at,
                'registration_notes' => $property->registration_notes,
                'owner' => $property->owner ? [
                    'id' => $property->owner->id,
                    'name' => $property->owner->name,
                ] : null,
                'approver' => $property->approver ? [
                    'id' => $property->approver->id,
                    'name' => $property->approver->name,
                ] : null,
                'units_count' => $property->units()->count(),
            ]
        ]);
    }

    /**
     * Dashboard statistics for admin.
     */
    public function dashboard()
    {
        $stats = [
            'total_properties' => Property::count(),
            'pending_registrations' => Property::pending()->count(),
            'approved_properties' => Property::approved()->count(),
            'rejected_properties' => Property::rejected()->count(),
            'active_properties' => Property::approved()->where('availability_status', Property::STATUS_ACTIVE)->count(),
            'recent_registrations' => Property::pending()->orderBy('created_at', 'desc')->take(5)->get(),
        ];

        // Monthly registration trends
        $monthlyStats = Property::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.property.dashboard', compact('stats', 'monthlyStats'));
    }
}