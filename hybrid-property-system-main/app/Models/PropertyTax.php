<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyTax extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'year',
        'amount',
        'due_date',
        'status',        // pending, paid, overdue
        'receipt_path',
        'paid_at'
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
