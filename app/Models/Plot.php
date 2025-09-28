<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plot extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'plot_number',
        'size',
        'status',
        'map_coordinates',
    ];

    protected $casts = [
        'map_coordinates' => 'array',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
