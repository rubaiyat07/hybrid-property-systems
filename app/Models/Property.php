<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $fillable = [
        'owner_id', 
        'name', 
        'address', 
        'city', 
        'state', 
        'zip',
        'type', 
        'status', 
        'description', 
        'price_or_rent',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
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
}
