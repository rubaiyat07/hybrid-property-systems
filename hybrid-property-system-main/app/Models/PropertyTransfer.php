<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'current_owner_id',
        'proposed_buyer_id',
        'transfer_type',
        'proposed_price',
        'transfer_date',
        'terms_conditions',
        'status',
        'initiated_at',
        'buyer_response_at',
        'buyer_response_notes',
        'cancelled_at',
        'completed_at',
        'completion_notes'
    ];

    protected $casts = [
        'proposed_price' => 'decimal:2',
        'transfer_date' => 'date',
        'initiated_at' => 'datetime',
        'buyer_response_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'completed_at' => 'datetime'
    ];

    /**
     * Get the property being transferred
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get the current owner
     */
    public function currentOwner()
    {
        return $this->belongsTo(User::class, 'current_owner_id');
    }

    /**
     * Get the proposed buyer
     */
    public function proposedBuyer()
    {
        return $this->belongsTo(User::class, 'proposed_buyer_id');
    }

    /**
     * Get transfer documents
     */
    public function documents()
    {
        return $this->hasMany(PropertyTransferDocument::class);
    }

    /**
     * Get status badge HTML
     */
    public function getStatusBadgeAttribute()
    {
        switch ($this->status) {
            case 'pending':
                return '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>';
            case 'accepted':
                return '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Accepted</span>';
            case 'rejected':
                return '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>';
            case 'completed':
                return '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Completed</span>';
            case 'cancelled':
                return '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Cancelled</span>';
            default:
                return '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Unknown</span>';
        }
    }

    /**
     * Get transfer type label
     */
    public function getTransferTypeLabelAttribute()
    {
        switch ($this->transfer_type) {
            case 'sale':
                return 'Property Sale';
            case 'lease_transfer':
                return 'Lease Transfer';
            case 'ownership_transfer':
                return 'Ownership Transfer';
            default:
                return ucfirst(str_replace('_', ' ', $this->transfer_type));
        }
    }

    /**
     * Check if transfer can be edited
     */
    public function canBeEdited()
    {
        return $this->status === 'pending' && $this->current_owner_id === auth()->id();
    }

    /**
     * Check if transfer can be accepted
     */
    public function canBeAccepted()
    {
        return $this->status === 'pending' && $this->proposed_buyer_id === auth()->id();
    }

    /**
     * Check if transfer can be rejected
     */
    public function canBeRejected()
    {
        return $this->status === 'pending' && $this->proposed_buyer_id === auth()->id();
    }

    /**
     * Check if transfer can be cancelled
     */
    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'accepted']) && $this->current_owner_id === auth()->id();
    }

    /**
     * Check if transfer can be completed
     */
    public function canBeCompleted()
    {
        return $this->status === 'accepted' && auth()->check() && auth()->user()->hasRole('Admin');
    }
}
