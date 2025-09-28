<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyTransferDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_transfer_id',
        'file_path',
        'file_name',
        'file_size',
        'mime_type'
    ];

    protected $casts = [
        'file_size' => 'integer'
    ];

    /**
     * Get the transfer that owns the document
     */
    public function transfer()
    {
        return $this->belongsTo(PropertyTransfer::class, 'property_transfer_id');
    }

    /**
     * Get file size in human readable format
     */
    public function getHumanReadableFileSizeAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Get file icon based on mime type
     */
    public function getFileIconAttribute()
    {
        $mimeType = $this->mime_type;

        if (str_contains($mimeType, 'pdf')) {
            return 'fas fa-file-pdf text-red-500';
        } elseif (str_contains($mimeType, 'document') || str_contains($mimeType, 'word')) {
            return 'fas fa-file-word text-blue-500';
        } elseif (str_contains($mimeType, 'image')) {
            return 'fas fa-file-image text-green-500';
        } else {
            return 'fas fa-file text-gray-500';
        }
    }
}
