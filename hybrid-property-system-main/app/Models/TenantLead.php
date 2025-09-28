<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantLead extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'unit_id',
        'property_id',
        'preferred_move_in_date',
        'budget_range',
        'group_size',
        'message',
        'status',
        'assigned_to',
        'source',
        'ip_address',
        'user_agent',
        'notes',
        'follow_up_date',
        'priority'
    ];

    protected $casts = [
        'preferred_move_in_date' => 'date',
        'follow_up_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Status constants
    const STATUS_NEW = 'new';
    const STATUS_CONTACTED = 'contacted';
    const STATUS_QUALIFIED = 'qualified';
    const STATUS_CONVERTED = 'converted';
    const STATUS_REJECTED = 'rejected';
    const STATUS_CLOSED = 'closed';

    // Priority constants
    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_URGENT = 'urgent';

    // Source constants
    const SOURCE_WEBSITE = 'website';
    const SOURCE_REFERRAL = 'referral';
    const SOURCE_SOCIAL_MEDIA = 'social_media';
    const SOURCE_ADVERTISING = 'advertising';
    const SOURCE_AGENT = 'agent';
    const SOURCE_OTHER = 'other';

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function assignedAgent()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Scopes
    public function scopeNew($query)
    {
        return $query->where('status', self::STATUS_NEW);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', [self::STATUS_NEW, self::STATUS_CONTACTED, self::STATUS_QUALIFIED]);
    }

    public function scopeHighPriority($query)
    {
        return $query->whereIn('priority', [self::PRIORITY_HIGH, self::PRIORITY_URGENT]);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeBySource($query, $source)
    {
        return $query->where('source', $source);
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        switch ($this->status) {
            case self::STATUS_NEW:
                return '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">New</span>';
            case self::STATUS_CONTACTED:
                return '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Contacted</span>';
            case self::STATUS_QUALIFIED:
                return '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Qualified</span>';
            case self::STATUS_CONVERTED:
                return '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">Converted</span>';
            case self::STATUS_REJECTED:
                return '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>';
            case self::STATUS_CLOSED:
                return '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Closed</span>';
            default:
                return '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Unknown</span>';
        }
    }

    public function getPriorityBadgeAttribute()
    {
        switch ($this->priority) {
            case self::PRIORITY_LOW:
                return '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Low</span>';
            case self::PRIORITY_MEDIUM:
                return '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Medium</span>';
            case self::PRIORITY_HIGH:
                return '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">High</span>';
            case self::PRIORITY_URGENT:
                return '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Urgent</span>';
            default:
                return '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Unknown</span>';
        }
    }

    public function getSourceBadgeAttribute()
    {
        switch ($this->source) {
            case self::SOURCE_WEBSITE:
                return '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Website</span>';
            case self::SOURCE_REFERRAL:
                return '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Referral</span>';
            case self::SOURCE_SOCIAL_MEDIA:
                return '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">Social Media</span>';
            case self::SOURCE_ADVERTISING:
                return '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Advertising</span>';
            case self::SOURCE_AGENT:
                return '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-800">Agent</span>';
            case self::SOURCE_OTHER:
                return '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Other</span>';
            default:
                return '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Unknown</span>';
        }
    }

    // Methods
    public function markAsContacted($notes = null)
    {
        $this->update([
            'status' => self::STATUS_CONTACTED,
            'notes' => $notes ? ($this->notes . "\n\nContacted: " . $notes) : $this->notes
        ]);
    }

    public function markAsQualified($notes = null)
    {
        $this->update([
            'status' => self::STATUS_QUALIFIED,
            'notes' => $notes ? ($this->notes . "\n\nQualified: " . $notes) : $this->notes
        ]);
    }

    public function markAsConverted($notes = null)
    {
        $this->update([
            'status' => self::STATUS_CONVERTED,
            'notes' => $notes ? ($this->notes . "\n\nConverted: " . $notes) : $this->notes
        ]);
    }

    public function markAsRejected($notes = null)
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
            'notes' => $notes ? ($this->notes . "\n\nRejected: " . $notes) : $this->notes
        ]);
    }

    public function close($notes = null)
    {
        $this->update([
            'status' => self::STATUS_CLOSED,
            'notes' => $notes ? ($this->notes . "\n\nClosed: " . $notes) : $this->notes
        ]);
    }

    public function assignTo($agentId)
    {
        $this->update(['assigned_to' => $agentId]);
    }

    public function setFollowUpDate($date)
    {
        $this->update(['follow_up_date' => $date]);
    }

    public function addNote($note)
    {
        $this->update([
            'notes' => $this->notes ? ($this->notes . "\n\n" . $note) : $note
        ]);
    }

    public function isOverdue()
    {
        return $this->follow_up_date && $this->follow_up_date < now();
    }

    public function getDaysSinceCreatedAttribute()
    {
        return $this->created_at->diffInDays(now());
    }

    public function getFullNameAttribute()
    {
        return $this->name;
    }

    public function getContactInfoAttribute()
    {
        return [
            'email' => $this->email,
            'phone' => $this->phone
        ];
    }
}
