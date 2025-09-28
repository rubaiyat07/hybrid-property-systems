<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'agent_id',
        'name',
        'address',
        'city',
        'state',
        'zip_code',
        'type',
        'status',
        'availability_status',
        'registration_status',
        'description',
        'price_or_rent',
        'image',
        'registration_notes',
        'approved_at',
        'approved_by'
    ];

    protected $dates = [
        'approved_at'
    ];

    // Registration status constants
    const REGISTRATION_PENDING = 'pending';
    const REGISTRATION_APPROVED = 'approved';
    const REGISTRATION_REJECTED = 'rejected';

    // Property status constants
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_MAINTENANCE = 'maintenance';

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    public function images()
    {
        return $this->hasMany(PropertyImage::class);
    }

    public function documents()
    {
        return $this->hasMany(PropertyDocument::class);
    }

    public function taxes()
    {
        return $this->hasMany(PropertyTax::class);
    }

    public function bills()
    {
        return $this->hasMany(PropertyBill::class);
    }

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function leases()
    {
        return $this->hasManyThrough(Lease::class, Unit::class);
    }

    public function facilities()
    {
        return $this->hasMany(PropertyFacility::class);
    }

    public function transfers()
    {
        return $this->hasMany(PropertyTransfer::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('registration_status', self::REGISTRATION_PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('registration_status', self::REGISTRATION_APPROVED);
    }

    public function scopeRejected($query)
    {
        return $query->where('registration_status', self::REGISTRATION_REJECTED);
    }

    // Accessors
    public function getIsApprovedAttribute()
    {
        return $this->registration_status === self::REGISTRATION_APPROVED;
    }

    public function getIsPendingAttribute()
    {
        return $this->registration_status === self::REGISTRATION_PENDING;
    }

    public function getIsRejectedAttribute()
    {
        return $this->registration_status === self::REGISTRATION_REJECTED;
    }

    public function getRegistrationStatusBadgeAttribute()
    {
        switch ($this->registration_status) {
            case self::REGISTRATION_PENDING:
                return '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>';
            case self::REGISTRATION_APPROVED:
                return '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Approved</span>';
            case self::REGISTRATION_REJECTED:
                return '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>';
            default:
                return '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Unknown</span>';
        }
    }

    // Methods
    public function canAddUnits()
    {
        return $this->is_approved;
    }

    public function approve($adminId, $notes = null)
    {
        $this->update([
            'registration_status' => self::REGISTRATION_APPROVED,
            'approved_by' => $adminId,
            'approved_at' => now(),
            'registration_notes' => $notes,
            'availability_status' => self::STATUS_ACTIVE
        ]);
    }

    public function reject($adminId, $notes)
    {
        $this->update([
            'registration_status' => self::REGISTRATION_REJECTED,
            'approved_by' => $adminId,
            'registration_notes' => $notes,
            'availability_status' => self::STATUS_INACTIVE
        ]);
    }

    public function fullAddress()
    {
        $parts = [];

        if ($this->address) {
            $parts[] = $this->address;
        }

        if ($this->city) {
            $parts[] = $this->city;
        }

        $stateZip = [];
        if ($this->state) {
            $stateZip[] = $this->state;
        }
        if ($this->zip_code) {
            $stateZip[] = $this->zip_code;
        }

        if (!empty($stateZip)) {
            $parts[] = implode(' ', $stateZip);
        }

        return implode(', ', $parts);
    }
}
