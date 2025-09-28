<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantScreening extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'document_type',
        'file_path',
        'status', // pending/approved/rejected
        'reviewed_by', // admin/user id
        'reviewed_at',
        'notes',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
