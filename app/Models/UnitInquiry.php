<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitInquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_id',
        'inquirer_name',
        'inquirer_email',
        'inquirer_phone',
        'inquiry_type',
        'message',
        'preferred_viewing_date',
        'preferred_viewing_time',
        'status',
        'response',
    ];

    protected $casts = [
        'preferred_viewing_date' => 'date',
        'preferred_viewing_time' => 'datetime:H:i',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeResponded($query)
    {
        return $query->where('status', 'responded');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    // Helper methods
    public function markAsResponded($response)
    {
        $this->update([
            'status' => 'responded',
            'response' => $response,
            'responded_at' => now(),
        ]);
    }

    public function markAsClosed()
    {
        $this->update(['status' => 'closed']);
    }

    public function getStatusBadgeAttribute()
    {
        switch ($this->status) {
            case 'pending':
                return '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>';
            case 'responded':
                return '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Responded</span>';
            case 'closed':
                return '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Closed</span>';
            default:
                return '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Unknown</span>';
        }
    }
}
