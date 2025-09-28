<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\PropertyFacility;
use Illuminate\Support\Facades\Validator;

class PropertyFacilityController extends Controller
{
    /**
     * Display facilities for a property
     */
    public function index(Property $property)
    {
        // Verify ownership
        if ($property->owner_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $facilities = $property->facilities()->orderBy('category')->orderBy('name')->get();
        $predefinedFacilities = PropertyFacility::getPredefinedFacilities();
        $categories = PropertyFacility::getCategories();

        // Group facilities by category for display
        $facilitiesByCategory = $facilities->groupBy('category');

        return view('landlord.property.facilities', compact('property', 'facilities', 'facilitiesByCategory', 'predefinedFacilities', 'categories'));
    }

    /**
     * Store new property facility
     */
    public function store(Request $request, Property $property)
    {
        // Verify ownership
        if ($property->owner_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|in:amenity,security,utility,service',
            'description' => 'nullable|string|max:1000',
            'is_available' => 'boolean'
        ]);

        $facility = PropertyFacility::create([
            'property_id' => $property->id,
            'name' => $request->name,
            'category' => $request->category,
            'description' => $request->description,
            'is_available' => $request->has('is_available'),
            'status' => 'active'
        ]);

        return redirect()->back()->with('success', 'Facility added successfully.');
    }

    /**
     * Update property facility
     */
    public function update(Request $request, Property $property, PropertyFacility $facility)
    {
        // Verify ownership
        if ($property->owner_id !== auth()->id() || $facility->property_id !== $property->id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|in:amenity,security,utility,service',
            'description' => 'nullable|string|max:1000',
            'is_available' => 'boolean',
            'status' => 'required|string|in:active,maintenance,inactive'
        ]);

        $facility->update($request->only(['name', 'category', 'description', 'is_available', 'status']));

        return redirect()->back()->with('success', 'Facility updated successfully.');
    }

    /**
     * Delete property facility
     */
    public function destroy(Property $property, PropertyFacility $facility)
    {
        // Verify ownership
        if ($property->owner_id !== auth()->id() || $facility->property_id !== $property->id) {
            abort(403, 'Unauthorized action.');
        }

        $facility->delete();

        return redirect()->back()->with('success', 'Facility deleted successfully.');
    }

    /**
     * Toggle facility availability (AJAX)
     */
    public function toggleAvailability(Request $request, Property $property, PropertyFacility $facility)
    {
        // Verify ownership
        if ($property->owner_id !== auth()->id() || $facility->property_id !== $property->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $facility->update([
            'is_available' => !$facility->is_available
        ]);

        return response()->json([
            'success' => true,
            'is_available' => $facility->is_available,
            'message' => 'Facility availability updated successfully.'
        ]);
    }

    /**
     * Add predefined facilities to property
     */
    public function addPredefined(Request $request, Property $property)
    {
        // Verify ownership
        if ($property->owner_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'facilities' => 'required|array',
            'facilities.*.name' => 'required|string|max:255',
            'facilities.*.category' => 'required|string|in:amenity,security,utility,service'
        ]);

        $addedCount = 0;
        foreach ($request->facilities as $facilityData) {
            // Check if facility already exists
            $existing = PropertyFacility::where('property_id', $property->id)
                ->where('name', $facilityData['name'])
                ->where('category', $facilityData['category'])
                ->first();

            if (!$existing) {
                PropertyFacility::create([
                    'property_id' => $property->id,
                    'name' => $facilityData['name'],
                    'category' => $facilityData['category'],
                    'is_available' => true,
                    'status' => 'active'
                ]);
                $addedCount++;
            }
        }

        return redirect()->back()->with('success', $addedCount . ' predefined facility(ies) added successfully.');
    }
}
