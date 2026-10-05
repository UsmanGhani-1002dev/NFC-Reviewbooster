<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'user_id', 'customer_name', 'customer_email', 'customer_phone',
        'shipping_address', 'billing_details', 'google_place_id', 'google_place_name', 'google_places',
        'stripe_payment_intent_id', 'status', 'total', 'currency', 'notes',
        'is_dropship', 'partner_role', 'tracking_number', 'carrier', 'tracking_notified_at', 'shipped_at',
        'shipping_method', 'shipping_fee',
    ];

    protected $casts = [
        'shipping_address' => 'array',
        'billing_details' => 'array',
        'google_places' => 'array',
        'total' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'is_dropship' => 'boolean',
        'tracking_notified_at' => 'datetime',
        'shipped_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public static function generateOrderNumber(): string
    {
        do {
            $number = 'RB-' . strtoupper(substr(uniqid(), -8));
        } while (self::where('order_number', $number)->exists());

        return $number;
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'paid' => 'bg-blue-100 text-blue-800',
            'shipped' => 'bg-purple-100 text-purple-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getFormattedAddressAttribute(): string
    {
        if (empty($this->shipping_address)) return 'N/A';
        
        $addr = $this->shipping_address;
        $parts = array_filter([
            $addr['line1'] ?? '',
            $addr['line2'] ?? '',
            $addr['city'] ?? '',
            $addr['postcode'] ?? '',
            $addr['country'] ?? ''
        ]);

        return implode(', ', $parts);
    }

    public function getGoogleMapsLinkAttribute(): ?string
    {
        if (!$this->google_place_id && !$this->google_place_name) return null;

        // Skip for now orders have no maps link
        if ($this->google_place_id && str_starts_with($this->google_place_id, 'skip-')) {
            return null;
        }

        // Direct URL check
        if (filter_var($this->google_place_name, FILTER_VALIDATE_URL)) {
            return $this->google_place_name;
        }
        if ($this->google_place_id && filter_var($this->google_place_id, FILTER_VALIDATE_URL)) {
            return $this->google_place_id;
        }

        $baseUrl = 'https://www.google.com/maps/search/?api=1';
        $query = urlencode($this->google_place_name ?: '');
        
        if ($this->google_place_id && !str_starts_with($this->google_place_id, 'loc-') && !str_starts_with($this->google_place_id, 'direct-')) {
            return "{$baseUrl}&query={$query}&query_place_id={$this->google_place_id}";
        }

        return "{$baseUrl}&query={$query}";
    }

    public function getPartnerRoleLabelAttribute(): ?string
    {
        return match ($this->partner_role) {
            'wholesaler' => 'Wholesaler',
            'retailer' => 'Retailer',
            'corporate' => 'Corporate Account',
            default => null,
        };
    }

    public function getCleanTrackingNumberAttribute(): ?string
    {
        if (empty($this->tracking_number)) return null;
        return preg_replace('/\s+/', '', trim($this->tracking_number));
    }

    public function getHasTrackingAttribute(): bool
    {
        return !empty($this->clean_tracking_number);
    }

    public function getTrackingUrlAttribute(): ?string
    {
        $tracking = $this->clean_tracking_number;
        if (!$tracking) return null;

        // If user pasted a full URL directly, return it
        if (filter_var($this->tracking_number, FILTER_VALIDATE_URL)) {
            return trim($this->tracking_number);
        }

        $carrierName = strtolower(trim($this->carrier ?? 'Royal Mail'));

        if (str_contains($carrierName, 'dpd')) {
            return 'https://track.dpd.co.uk/search?reference=' . urlencode($tracking);
        }
        if (str_contains($carrierName, 'dhl')) {
            return 'https://www.dhl.com/en/express/tracking.html?AWB=' . urlencode($tracking);
        }
        if (str_contains($carrierName, 'evri') || str_contains($carrierName, 'hermes')) {
            return 'https://www.evri.com/track/' . urlencode($tracking);
        }

        // Default to Royal Mail tracking URL format
        return 'https://www.royalmail.com/track-your-item#/tracking-results/' . urlencode($tracking);
    }
}
