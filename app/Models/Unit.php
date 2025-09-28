<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = [
        'property_id',
        'unit_number',
        'floor',
        'size',
        'rent_amount',
        'status',
        'features',
        'is_published',
        'deposit_amount',
        'photos',
        'description',
        'room_type',
        'bedrooms',
        'bathrooms',
    ];

    protected $casts = [
        'features' => 'array',
        'photos' => 'array',
        'is_published' => 'boolean',
        'rent_amount' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function leases()
    {
        return $this->hasMany(Lease::class);
    }

    public function maintenanceRequests()
    {
        return $this->hasMany(MaintenanceRequest::class);
    }

    public function inquiries()
    {
        return $this->hasMany(UnitInquiry::class);
    }

    // Scopes for listing functionality
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeVacant($query)
    {
        return $query->where('status', 'vacant');
    }

    public function scopeAvailableForListing($query)
    {
        return $query->where('status', 'vacant')
                    ->where('is_published', true)
                    ->whereHas('property', function($q) {
                        $q->where('registration_status', Property::REGISTRATION_APPROVED);
                    });
    }

    // Helper methods for listing
    public function canBePublished()
    {
        return $this->status === 'vacant' && $this->property->is_approved;
    }

    public function publish()
    {
        if ($this->canBePublished()) {
            $this->update(['is_published' => true]);
            return true;
        }
        return false;
    }

    public function unpublish()
    {
        $this->update(['is_published' => false]);
    }

    public function getDisplayPriceAttribute()
    {
        return '৳' . number_format($this->rent_amount, 0, '.', ',');
    }

    public function getDisplayDepositAttribute()
    {
        if ($this->deposit_amount) {
            return '৳' . number_format($this->deposit_amount, 0, '.', ',');
        }
        return '৳' . number_format($this->rent_amount, 0, '.', ','); // Default to 1 month rent
    }

    public function getLocationAttribute()
    {
        return $this->property->city . ', ' . $this->property->state;
    }

    public function getFullAddressAttribute()
    {
        return $this->property->address . ', ' . $this->property->city . ', ' . $this->property->state;
    }

    public function displayTitle()
    {
        return 'Unit ' . $this->unit_number . ' at ' . $this->property->address;
    }

    public function displayPrice()
    {
        return '৳' . number_format($this->rent_amount, 0, '.', ',');
    }
}
