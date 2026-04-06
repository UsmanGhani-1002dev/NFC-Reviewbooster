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
        'role'
    ];
    
    protected $casts = [
        'ends_at' => 'datetime',
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
    
    
}
