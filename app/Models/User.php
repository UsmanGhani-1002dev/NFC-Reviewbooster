<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Subscription;
use App\Models\Card;
use App\Models\Review;
use Carbon\Carbon;
use Exception;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable; 

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'company_name',
        'email',
        'password',
        'is_active',
        'role',
        'partner_type',
        'partner_status',
        'vat_number',
        'partner_discount_override',
    ];

    protected $casts = [
        'ends_at' => 'datetime',
        'partner_discount_override' => 'decimal:2',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    public function cards()
    {
        return $this->hasMany(Card::class);
    }

    public function reviews()
    {
        return $this->hasManyThrough(Review::class, Card::class);
    }
    
    public function businesses()
    {
        return $this->hasMany(ManageBusiness::class);
    }
    
    // Get the current active subscription
    public function subscription()
    {
        return $this->hasOne(Subscription::class)->latest();
    }

    // Get all subscriptions
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    // Get the current active subscription that hasn't expired
    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)
                    ->where('ends_at', '>', now())
                    ->latest();
    }
    
    // Subscription Logic
    public function hasActiveSubscription()
    {
        return $this->subscription
            && $this->subscription->stripe_status === 'succeeded'
            && $this->subscription->ends_at > now();
    }

    public function hasExpiredSubscription()
    {
        return $this->subscription
            && $this->subscription->stripe_status === 'succeeded'
            && $this->subscription->ends_at <= now();
    }

    public function subscriptionExpiresIn2Days()
    {
        if (!$this->subscription) {
            return false;
        }

        $now = now();
        $endsAt = $this->subscription->ends_at;

        return $this->subscription->stripe_status === 'succeeded'
            && $endsAt > $now
            && $endsAt <= $now->copy()->addDays(2);
    }

    public function subscriptionExpiresIn48Hours()
    {
        if (!$this->subscription) {
            return false;
        }

        $now = now();
        $endsAt = $this->subscription->ends_at;

        return $this->subscription->stripe_status === 'succeeded'
            && $endsAt > $now
            && $endsAt <= $now->copy()->addHours(48);
    }

    public function shouldShowSubscriptionNotification()
    {
        return $this->hasExpiredSubscription()
            || $this->subscriptionExpiresIn2Days()
            || $this->subscriptionExpiresIn48Hours();
    }

    public function getTimeLeftForExpiry()
    {
        if (!$this->subscription) {
            return null;
        }

        return Carbon::parse($this->subscription->ends_at)->diffForHumans();
    }

    public function getDetailedTimeLeft()
    {
        if (!$this->hasActiveSubscription()) {
            return null;
        }

        try {
            $endsAt = $this->parseEndDate($this->subscription->ends_at);
            $now = now();

            if ($endsAt <= $now) {
                return 'Expired';
            }

            $totalHours = $now->diffInHours($endsAt);
            $totalDays = $now->diffInDays($endsAt);

            if ($totalDays > 0) {
                return $totalDays . ' day' . ($totalDays > 1 ? 's' : '') . ' left';
            } elseif ($totalHours > 0) {
                return $totalHours . ' hour' . ($totalHours > 1 ? 's' : '') . ' left';
            } else {
                $minutes = $now->diffInMinutes($endsAt);
                return $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' left';
            }
        } catch (Exception $e) {
            return 'Time calculation error';
        }
    }

    public function debugSubscriptionDate()
    {
        if (!$this->subscription) {
            return 'No subscription found';
        }

        $endsAt = $this->subscription->ends_at;

        return [
            'original' => $endsAt,
            'type' => gettype($endsAt),
            'is_carbon' => $endsAt instanceof Carbon,
            'parsed' => Carbon::parse($endsAt)->toDateTimeString(),
        ];
    }

    // Helper
    private function parseEndDate($date)
    {
        if ($date instanceof Carbon) {
            return $date;
        }

        if (is_string($date)) {
            try {
                return Carbon::parse($date);
            } catch (\Exception $e) {
                report($e);
                return now(); // fallback
            }
        }

        return now();
    }

    public function isApprovedPartner(): bool
    {
        return $this->partner_status === 'approved' && in_array($this->partner_type, ['wholesaler', 'retailer', 'corporate']);
    }

    public function isPendingPartner(): bool
    {
        return $this->partner_status === 'pending' && in_array($this->partner_type, ['wholesaler', 'retailer', 'corporate']);
    }

    public function getPartnerDiscountPercent(): float
    {
        if (!$this->isApprovedPartner()) {
            return 0.0;
        }

        // A per-customer override (set by admin) always wins over the tier default.
        if ($this->partner_discount_override !== null && $this->partner_discount_override !== '') {
            return max(0.0, min(100.0, (float) $this->partner_discount_override));
        }

        $key = match ($this->partner_type) {
            'wholesaler' => 'wholesaler_discount_percent',
            'retailer' => 'retailer_discount_percent',
            'corporate' => 'corporate_discount_percent',
            default => null,
        };

        if (!$key) return 0.0;

        return (float) \App\Models\Setting::get($key, 0);
    }

    /**
     * True when this partner's discount comes from a per-customer override
     * rather than the global tier default. Used for admin display.
     */
    public function hasCustomDiscount(): bool
    {
        return $this->isApprovedPartner()
            && $this->partner_discount_override !== null
            && $this->partner_discount_override !== '';
    }

    public function getPartnerTypeLabelAttribute(): string
    {
        return match ($this->partner_type) {
            'wholesaler' => 'Wholesaler',
            'retailer' => 'Retailer',
            'corporate' => 'Corporate Account',
            default => 'Standard Customer',
        };
    }

    public function getPartnerStatusBadgeAttribute(): string
    {
        if (!$this->partner_type || $this->partner_type === 'standard') {
            return 'bg-gray-100 text-gray-700';
        }

        return match ($this->partner_status) {
            'approved' => 'bg-green-100 text-green-800',
            'pending' => 'bg-amber-100 text-amber-800',
            'rejected' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-700',
        };
    }
}
