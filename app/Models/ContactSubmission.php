<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ContactSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'business_name',
        'inquiry_type',
        'subject',
        'message',
        'ip_address',
        'user_agent',
        'read_at',
        'admin_notes',
        'status',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Admin replies sent for this submission (oldest first for a thread view).
     */
    public function replies()
    {
        return $this->hasMany(ContactReply::class)->oldest();
    }

    /**
     * Get the full name attribute
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Get formatted inquiry type
     */
    public function getFormattedInquiryTypeAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->inquiry_type));
    }

    /**
     * Check if submission is unread
     */
    public function getIsUnreadAttribute()
    {
        return is_null($this->read_at);
    }

    /**
     * Mark submission as read
     */
    public function markAsRead()
    {
        if (is_null($this->read_at)) {
            $this->update(['read_at' => now()]);
        }
    }

    /**
     * Scope for unread submissions
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope for recent submissions
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', Carbon::now()->subDays($days));
    }

    /**
     * Scope by inquiry type
     */
    public function scopeByInquiryType($query, $type)
    {
        return $query->where('inquiry_type', $type);
    }

    /**
     * Scope by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Get submissions count by type
     */
    public static function getCountByType()
    {
        return static::selectRaw('inquiry_type, COUNT(*) as count')
            ->groupBy('inquiry_type')
            ->pluck('count', 'inquiry_type')
            ->toArray();
    }

    /**
     * Get submissions count by status
     */
    public static function getCountByStatus()
    {
        return static::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    }
}