<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'doc_type',        // deed, mutation, registration, tax_receipt, others
        'file_path',
        'status',          // pending, approved, rejected
        'uploaded_at'
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
