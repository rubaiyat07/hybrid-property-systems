<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'buyer_id',
        'property_id',
        'assigned_to',
        'status',
        'notes',
    ];

    public function buyer()
    {
        return $this->belongsTo(Buyer::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
