<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyFacility extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'name',
        'category',
        'description',
        'is_available',
        'status'
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the property that owns the facility
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get predefined facilities by category
     */
    public static function getPredefinedFacilities()
    {
        return [
            'amenity' => [
                'Swimming Pool',
                'Gym/Fitness Center',
                'Garden/Park',
                'Playground',
                'Community Hall',
                'Parking Space',
                'Elevator',
                'Balcony',
                'Terrace',
                'Storage Room'
            ],
            'security' => [
                '24/7 Security',
                'CCTV Surveillance',
                'Security Guard',
                'Intercom System',
                'Access Control',
                'Fire Alarm System',
                'Emergency Exit'
            ],
            'utility' => [
                'Backup Generator',
                'Water Supply',
                'Electricity Backup',
                'Gas Connection',
                'Internet Ready',
                'Cable TV Ready',
                'Waste Management'
            ],
            'service' => [
                'Housekeeping',
                'Maintenance Service',
                'Concierge',
                'Laundry Service',
                'Car Wash',
                'Pet Care'
            ]
        ];
    }

    /**
     * Get available categories
     */
    public static function getCategories()
    {
        return [
            'amenity' => 'Amenities',
            'security' => 'Security Features',
            'utility' => 'Utilities',
            'service' => 'Services'
        ];
    }
}
