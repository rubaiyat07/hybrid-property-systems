<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;
use App\Models\Property;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Unit::with('property')
            ->whereHas('property', function($q) {
                $q->where('owner_id', auth()->id())
                  ->where('registration_status', Property::REGISTRATION_APPROVED);
            });

        if ($request->filled('property_id')) {
            $query->where('property_id', $request->property_id);
        }

        $units = $query->orderBy('created_at', 'desc')->paginate(10);

        // Get approved properties for filter dropdown
        $properties = Property::where('owner_id', auth()->id())
            ->approved()
            ->get();

        return view('landlord.units.index', compact('units', 'properties'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $propertyId = $request->get('property_id');
        $property = null;

        if ($propertyId) {
            $property = Property::where('id', $propertyId)
                ->where('owner_id', auth()->id())
                ->approved()
                ->firstOrFail();
        }

        // Get all approved properties owned by the user for the dropdown
        $properties = Property::where('owner_id', auth()->id())
            ->approved()
            ->get();

        if ($properties->isEmpty()) {
            return redirect()->route('landlord.property.index')
                ->with('error', 'You need to have at least one approved property before creating units.');
        }

        return view('landlord.units.create', compact('properties', 'property'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Process features: convert comma-separated string to array
        $features = $request->input('features');
        if (is_string($features) && !empty($features)) {
            $features = array_filter(array_map('trim', explode(',', $features)));
        } else {
            $features = [];
        }
        $request->merge(['features' => $features]);

        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'unit_number' => 'required|string|max:255',
            'floor' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:255',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'rent_amount' => 'required|numeric|min:0',
            'features' => 'nullable|array',
            'description' => 'nullable|string',
        ]);

        // Verify property ownership and approval
        $property = Property::where('id', $request->property_id)
            ->where('owner_id', auth()->id())
            ->approved()
            ->firstOrFail();

        // Check if unit number already exists for this property
        $existingUnit = Unit::where('property_id', $request->property_id)
            ->where('unit_number', $request->unit_number)
            ->first();

        if ($existingUnit) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['unit_number' => 'Unit number already exists for this property.']);
        }

        $unit = Unit::create([
            'property_id' => $request->property_id,
            'unit_number' => $request->unit_number,
            'floor' => $request->floor,
            'size' => $request->size,
            'bedrooms' => $request->bedrooms,
            'bathrooms' => $request->bathrooms,
            'rent_amount' => $request->rent_amount,
            'status' => 'vacant',
            'features' => $request->features ?? [],
            'description' => $request->description,
        ]);

        return redirect()->route('landlord.property.show', $property)
            ->with('success', 'Unit created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Unit $unit)
    {
        // Verify ownership through property
        if ($unit->property->owner_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $unit->load(['property', 'leases.tenant', 'maintenanceRequests']);

        return view('landlord.units.show', compact('unit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Unit $unit)
    {
        // Verify ownership and property approval
        if ($unit->property->owner_id !== auth()->id() || !$unit->property->is_approved) {
            abort(403, 'Unauthorized action.');
        }

        return view('landlord.units.edit', compact('unit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Unit $unit)
    {
        // Verify ownership and property approval
        if ($unit->property->owner_id !== auth()->id() || !$unit->property->is_approved) {
            abort(403, 'Unauthorized action.');
        }

        // Process features: convert comma-separated string to array
        $features = $request->input('features');
        if (is_string($features) && !empty($features)) {
            $features = array_filter(array_map('trim', explode(',', $features)));
        } else {
            $features = [];
        }
        $request->merge(['features' => $features]);

        $request->validate([
            'unit_number' => 'required|string|max:255',
            'floor' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:255',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'rent_amount' => 'required|numeric|min:0',
            'status' => 'required|in:vacant,occupied,maintenance',
            'features' => 'nullable|array',
            'description' => 'nullable|string',
        ]);

        // Check if unit number already exists for this property (excluding current unit)
        $existingUnit = Unit::where('property_id', $unit->property_id)
            ->where('unit_number', $request->unit_number)
            ->where('id', '!=', $unit->id)
            ->first();

        if ($existingUnit) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['unit_number' => 'Unit number already exists for this property.']);
        }

        $unit->update([
            'unit_number' => $request->unit_number,
            'floor' => $request->floor,
            'size' => $request->size,
            'bedrooms' => $request->bedrooms,
            'bathrooms' => $request->bathrooms,
            'rent_amount' => $request->rent_amount,
            'status' => $request->status,
            'features' => $request->features ?? [],
            'description' => $request->description,
        ]);

        return redirect()->route('landlord.units.show', $unit)
            ->with('success', 'Unit updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unit $unit)
    {
        // Verify ownership and property approval
        if ($unit->property->owner_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if unit has active leases
        $activeLeases = $unit->leases()->where('end_date', '>=', now())->count();
        
        if ($activeLeases > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete unit with active leases.');
        }

        $propertyId = $unit->property_id;
        $unit->delete();

        return redirect()->route('landlord.property.show', $propertyId)
            ->with('success', 'Unit deleted successfully.');
    }

    /**
     * Get units for a specific property (AJAX endpoint)
     */
    public function getPropertyUnits(Property $property)
    {
        // Verify ownership and approval
        if ($property->owner_id !== auth()->id() || !$property->is_approved) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $units = $property->units()->select('id', 'unit_number', 'status', 'rent_amount')->get();

        return response()->json($units);
    }

    /**
     * Publish a unit as a listing
     */
    public function publish(Unit $unit)
    {
        // Verify ownership and property approval
        if ($unit->property->owner_id !== auth()->id() || !$unit->property->is_approved) {
            abort(403, 'Unauthorized action.');
        }

        if ($unit->publish()) {
            return redirect()->back()->with('success', 'Unit published successfully and is now visible to tenants.');
        } else {
            return redirect()->back()->with('error', 'Unit cannot be published. Make sure it is vacant and belongs to an approved property.');
        }
    }

    /**
     * Unpublish a unit listing
     */
    public function unpublish(Unit $unit)
    {
        // Verify ownership and property approval
        if ($unit->property->owner_id !== auth()->id() || !$unit->property->is_approved) {
            abort(403, 'Unauthorized action.');
        }

        $unit->unpublish();

        return redirect()->back()->with('success', 'Unit unpublished successfully and is no longer visible to tenants.');
    }

    /**
     * Toggle unit listing status
     */
    public function toggleListing(Unit $unit)
    {
        // Verify ownership and property approval
        if ($unit->property->owner_id !== auth()->id() || !$unit->property->is_approved) {
            abort(403, 'Unauthorized action.');
        }

        if ($unit->is_published) {
            $unit->unpublish();
            $message = 'Unit unpublished successfully and is no longer visible to tenants.';
        } else {
            if ($unit->publish()) {
                $message = 'Unit published successfully and is now visible to tenants.';
            } else {
                $message = 'Unit cannot be published. Make sure it is vacant and belongs to an approved property.';
            }
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Update unit listing details
     */
    public function updateListing(Request $request, Unit $unit)
    {
        // Verify ownership and property approval
        if ($unit->property->owner_id !== auth()->id() || !$unit->property->is_approved) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'deposit_amount' => 'nullable|numeric|min:0',
            'photos' => 'nullable|array',
            'photos.*' => 'string',
            'description' => 'nullable|string|max:1000',
            'room_type' => 'nullable|string|max:50',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
        ]);

        $unit->update([
            'deposit_amount' => $request->deposit_amount,
            'photos' => $request->photos,
            'description' => $request->description,
            'room_type' => $request->room_type,
            'bedrooms' => $request->bedrooms,
            'bathrooms' => $request->bathrooms,
        ]);

        return redirect()->back()->with('success', 'Unit listing details updated successfully.');
    }
}
