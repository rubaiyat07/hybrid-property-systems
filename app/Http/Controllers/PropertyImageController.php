<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PropertyImageController extends Controller
{
    /**
     * Display image gallery for a property
     */
    public function index(Property $property)
    {
        // Verify ownership
        if ($property->owner_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $images = $property->images()->orderBy('is_primary', 'desc')->orderBy('created_at')->get();

        return view('landlord.property.gallery', compact('property', 'images'));
    }

    /**
     * Store new property images
     */
    public function store(Request $request, Property $property)
    {
        // Verify ownership
        if ($property->owner_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
        ]);

        $uploadedImages = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('property_gallery', 'public');

                $image = PropertyImage::create([
                    'property_id' => $property->id,
                    'file_path' => '/storage/' . $path,
                    'is_primary' => $property->images()->count() === 0, // First image becomes primary
                ]);

                $uploadedImages[] = $image;
            }
        }

        return redirect()->back()->with('success', count($uploadedImages) . ' image(s) uploaded successfully.');
    }

    /**
     * Set primary image
     */
    public function setPrimary(Property $property, PropertyImage $image)
    {
        // Verify ownership
        if ($property->owner_id !== auth()->id() || $image->property_id !== $property->id) {
            abort(403, 'Unauthorized action.');
        }

        // Remove primary flag from all images of this property
        PropertyImage::where('property_id', $property->id)->update(['is_primary' => false]);

        // Set this image as primary
        $image->update(['is_primary' => true]);

        return redirect()->back()->with('success', 'Primary image updated successfully.');
    }

    /**
     * Delete property image
     */
    public function destroy(Property $property, PropertyImage $image)
    {
        // Verify ownership
        if ($property->owner_id !== auth()->id() || $image->property_id !== $property->id) {
            abort(403, 'Unauthorized action.');
        }

        // Delete file from storage
        if ($image->file_path && Storage::exists(str_replace('/storage/', 'public/', $image->file_path))) {
            Storage::delete(str_replace('/storage/', 'public/', $image->file_path));
        }

        $image->delete();

        return redirect()->back()->with('success', 'Image deleted successfully.');
    }

    /**
     * Reorder images (AJAX)
     */
    public function reorder(Request $request, Property $property)
    {
        // Verify ownership
        if ($property->owner_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'image_order' => 'required|array',
            'image_order.*' => 'exists:property_images,id'
        ]);

        foreach ($request->image_order as $order => $imageId) {
            PropertyImage::where('id', $imageId)
                ->where('property_id', $property->id)
                ->update(['sort_order' => $order + 1]);
        }

        return response()->json(['success' => true]);
    }
}
