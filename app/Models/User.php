<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'status',
        'profile_photo',
        'role_id',
        'bio',
        'phone_verified',
        'documents_verified',
        'screening_verified',
    ];

    
    protected $hidden = [
        'password',
        'remember_token',
    ];

    //Relationships
    public function properties()
    {
        return $this->hasMany(Property::class, 'owner_id');
    }

    public function tenant()
    {
        return $this->hasOne(Tenant::class);
    }

    public function agent()
    {
        return $this->hasOne(Agent::class);
    }

    public function buyer()
    {
        return $this->hasOne(Buyer::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function unitInquiries()
    {
        return $this->hasMany(\App\Models\UnitInquiry::class, 'inquirer_email', 'email');
    }

    
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
