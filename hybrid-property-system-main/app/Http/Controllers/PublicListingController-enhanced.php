<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;
use App\Models\Property;

class PublicListingController extends Controller
{
    /**
     * Display the rental listings page with search and filter functionality
     */
    public function index(Request $request)
    {
        $query = Unit::with(['property', 'property.images'])
            ->availableForListing()
            ->select([
                'units.id',
                'units.unit_number',
                'units.floor',
                'units.size',
                'units.rent_amount',
                'units.deposit_amount',
                'units.photos',
                'units.description',
                'units.room_type',
                'units.bedrooms',
                'units.bathrooms',
                'units.features',
                'units.property_id',
                'units.created_at'
            ]);

        // Apply filters
        if ($request->filled('location')) {
            $query->whereHas('property', function($q) use ($request) {
                $q->where('city', 'like', '%' . $request->location . '%')
                  ->orWhere('state', 'like', '%' . $request->location . '%')
                  ->orWhere('address', 'like', '%' . $request->location . '%');
            });
        }

        if ($request->filled('min_rent') || $request->filled('max_rent')) {
            if ($request->filled('min_rent')) {
                $query->where('rent_amount', '>=', $request->min_rent);
            }
            if ($request->filled('max_rent')) {
                $query->where('rent_amount', '<=', $request->max_rent);
            }
        }

        if ($request->filled('room_type')) {
            $query->where('room_type', $request->room_type);
        }

        if ($request->filled('bedrooms')) {
            $query->where('bedrooms', '>=', $request->bedrooms);
        }

        if ($request->filled('property_type')) {
            $query->whereHas('property', function($q) use ($request) {
                $q->where('type', $request->property_type);
            });
        }

        // Sort options
        $sortBy = $request->get('sort', 'newest');
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('rent_amount', 'asc');
                break;
            case 'price_high':
                $query->orderBy('rent_amount', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $units = $query->paginate(12);

        // Get filter options
        $cities = Property::whereHas('units', function($q) {
            $q->availableForListing();
        })->distinct()->pluck('city');

        $propertyTypes = Property::whereHas('units', function($q) {
            $q->availableForListing();
        })->distinct()->pluck('type');

        $roomTypes = Unit::availableForListing()
            ->whereNotNull('room_type')
            ->distinct()
            ->pluck('room_type');

        return view('public_pages.rentals', compact(
            'units',
            'cities',
            'propertyTypes',
            'roomTypes'
        ));
    }

    /**
     * Display a specific unit listing
     */
    public function show(Unit $unit)
    {
        // Ensure the unit is published and available
        if (!$unit->is_published || $unit->status !== 'vacant') {
            abort(404, 'Listing not found or no longer available.');
        }

        $unit->load([
            'property',
            'property.images',
            'property.owner',
            'inquiries' => function($query) {
                $query->latest()->limit(5);
            }
        ]);

        // Get similar listings
        $similarUnits = Unit::availableForListing()
            ->where('id', '!=', $unit->id)
            ->where(function($query) use ($unit) {
                $query->where('property_id', $unit->property_id)
                      ->orWhere('rent_amount', '>=', $unit->rent_amount * 0.8)
                      ->orWhere('rent_amount', '<=', $unit->rent_amount * 1.2);
            })
            ->limit(3)
            ->get();

        return view('public_pages.rentals.show', compact('unit', 'similarUnits'));
    }

    /**
     * Search units via AJAX
     */
    public function search(Request $request)
    {
        $query = Unit::availableForListing()
            ->with(['property', 'property.images']);

        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $query->where(function($q) use ($searchTerm) {
                $q->where('unit_number', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('property', function($propertyQuery) use ($searchTerm) {
                      $propertyQuery->where('name', 'like', '%' . $searchTerm . '%')
                                   ->orWhere('address', 'like', '%' . $searchTerm . '%')
                                   ->orWhere('city', 'like', '%' . $searchTerm . '%')
                                   ->orWhere('state', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        $units = $query->limit(10)->get();

        return response()->json($units);
    }

    /**
     * AJAX search and filter for rentals
     */
    public function ajaxSearch(Request $request)
    {
        $query = Unit::with(['property', 'property.images'])
            ->availableForListing()
            ->select([
                'units.id',
                'units.unit_number',
                'units.floor',
                'units.size',
                'units.rent_amount',
                'units.deposit_amount',
                'units.photos',
                'units.description',
                'units.room_type',
                'units.bedrooms',
                'units.bathrooms',
                'units.features',
                'units.property_id',
                'units.created_at'
            ]);

        // Apply search term
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('unit_number', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('property', function($propertyQuery) use ($searchTerm) {
                      $propertyQuery->where('name', 'like', '%' . $searchTerm . '%')
                                   ->orWhere('address', 'like', '%' . $searchTerm . '%')
                                   ->orWhere('city', 'like', '%' . $searchTerm . '%')
                                   ->orWhere('state', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        // Apply location filter
        if ($request->filled('location')) {
            $query->whereHas('property', function($q) use ($request) {
                $q->where('city', 'like', '%' . $request->location . '%')
                  ->orWhere('state', 'like', '%' . $request->location . '%')
                  ->orWhere('address', 'like', '%' . $request->location . '%');
            });
        }

        // Apply property type filter
        if ($request->filled('property_type')) {
            $query->whereHas('property', function($q) use ($request) {
                $q->where('type', $request->property_type);
            });
        }

        // Apply room type filter
        if ($request->filled('room_type')) {
            $query->where('room_type', $request->room_type);
        }

        // Apply price range filter
        if ($request->filled('price_range')) {
            $priceRange = $request->price_range;
            switch ($priceRange) {
                case '0-15000':
                    $query->where('rent_amount', '<', 15000);
                    break;
                case '15000-30000':
                    $query->whereBetween('rent_amount', [15000, 30000]);
                    break;
                case '30000-50000':
                    $query->whereBetween('rent_amount', [30000, 50000]);
                    break;
                case '50000-100000':
                    $query->whereBetween('rent_amount', [50000, 100000]);
                    break;
                case '100000+':
                    $query->where('rent_amount', '>', 100000);
                    break;
            }
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'newest');
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('rent_amount', 'asc');
                break;
            case 'price_high':
                $query->orderBy('rent_amount', 'desc');
                break;
            case 'size':
                $query->orderBy('size', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Get pagination parameters
        $perPage = 12;
        $page = $request->get('page', 1);

        // Get units for current page
        $units = $query->paginate($perPage, ['*'], 'page', $page);

        // Calculate statistics
        $stats = [
            'total' => $query->count(),
            'average_price' => round($query->avg('rent_amount') ?? 0),
            'locations' => $query->distinct('property.city')->count('property.city')
        ];

        // Generate HTML for units
        $html = '';
        foreach ($units as $unit) {
            $html .= $this->generateUnitCard($unit);
        }

        return response()->json([
            'success' => true,
            'html' => $html,
            'units' => $units->items(),
            'stats' => $stats,
            'has_more' => $units->hasMorePages(),
            'current_page' => $units->currentPage(),
            'total_pages' => $units->lastPage()
        ]);
    }

    /**
     * Generate HTML for a unit card
     */
    private function generateUnitCard($unit)
    {
        $photos = $unit->photos ?? [];
        $features = $unit->features ?? [];
        $imageUrl = !empty($photos) ? asset('storage/' . $photos[0]) : asset('images/no-image.png');

        $html = '<div class="rental-card">';
        $html .= '<div class="rental-image">';
        $html .= '<img src="' . $imageUrl . '" alt="' . $unit->unit_number . '">';
        $html .= '<div class="rental-badge">Available</div>';
        $html .= '</div>';
        $html .= '<div class="rental-content">';
        $html .= '<h3 class="rental-title">' . $unit->unit_number . '</h3>';
        $html .= '<div class="rental-location">';
        $html .= '<i class="fas fa-map-marker-alt"></i> ' . $unit->property->city . ', ' . $unit->property->state;
        $html .= '</div>';

        if (!empty($features)) {
            $html .= '<div class="rental-features">';
            foreach (array_slice($features, 0, 3) as $feature) {
                $html .= '<span class="rental-feature">' . $feature . '</span>';
            }
            if (count($features) > 3) {
                $html .= '<span class="rental-feature">+' . (count($features) - 3) . ' more</span>';
            }
            $html .= '</div>';
        }

        $html .= '<div class="rental-price">' . $unit->display_price . '</div>';
        $html .= '<div class="rental-actions">';
        $html .= '<button class="btn-inquire" data-unit-id="' . $unit->id . '">Inquire Now</button>';
        $html .= '<button class="btn-favorite" data-unit-id="' . $unit->id . '">';
        $html .= '<i class="far fa-heart"></i>';
        $html .= '</button>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    /**
     * Get filter options for AJAX
     */
    public function getFilterOptions()
    {
        $minRent = Unit::availableForListing()->min('rent_amount') ?? 0;
        $maxRent = Unit::availableForListing()->max('rent_amount') ?? 100000;

        return response()->json([
            'price_range' => [
                'min' => $minRent,
                'max' => $maxRent
            ],
            'bedrooms' => range(1, 5),
            'room_types' => Unit::availableForListing()
                ->whereNotNull('room_type')
                ->distinct()
                ->pluck('room_type')
        ]);
    }
}
