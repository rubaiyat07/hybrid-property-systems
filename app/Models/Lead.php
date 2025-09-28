<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'inquirer_type', // 'buyer', 'tenant'
        'inquirer_id', // buyer_id or tenant_id
        'property_id',
        'unit_id', // for tenant inquiries
        'assigned_to', // agent or landlord
        'status',
        'inquiry_type', // 'property_purchase', 'rental_inquiry'
        'message',
        'contact_info',
        'notes',
    ];

    protected $casts = [
        'contact_info' => 'array',
    ];

    // Relationships
    public function buyer()
    {
        return $this->belongsTo(Buyer::class, 'inquirer_id')->when($this->inquirer_type === 'buyer');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'inquirer_id')->when($this->inquirer_type === 'tenant');
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function assignedAgent()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Scopes
    public function scopeForBuyers($query)
    {
        return $query->where('inquirer_type', 'buyer');
    }

    public function scopeForTenants($query)
    {
        return $query->where('inquirer_type', 'tenant');
    }

    public function scopeRentalInquiries($query)
    {
        return $query->where('inquiry_type', 'rental_inquiry');
    }

    public function scopePropertyPurchases($query)
    {
        return $query->where('inquiry_type', 'property_purchase');
    }

    // Helper methods
    public function getInquirerNameAttribute()
    {
        if ($this->inquirer_type === 'buyer' && $this->buyer) {
            return $this->buyer->user->name ?? 'Unknown Buyer';
        } elseif ($this->inquirer_type === 'tenant' && $this->tenant) {
            return $this->tenant->user->name ?? 'Unknown Tenant';
        }
        return 'Unknown';
    }

    public function getInquirerEmailAttribute()
    {
        if ($this->inquirer_type === 'buyer' && $this->buyer) {
            return $this->buyer->user->email ?? '';
        } elseif ($this->inquirer_type === 'tenant' && $this->tenant) {
            return $this->tenant->user->email ?? '';
        }
        return '';
    }
}
