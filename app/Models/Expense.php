<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'category',
        'amount',
        'description',
        'date',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
