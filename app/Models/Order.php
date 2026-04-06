<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'customer_name', 'customer_email', 'customer_phone',
        'shipping_address', 'google_place_id', 'google_place_name', 'google_places',
        'stripe_payment_intent_id', 'status', 'total', 'currency', 'notes',
    ];

    protected $casts = [
        'shipping_address' => 'array',
        'google_places' => 'array',
        'total' => 'decimal:2',
    ];

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

        $baseUrl = 'https://www.google.com/maps/search/?api=1';
        $query = urlencode($this->google_place_name);
        
        if ($this->google_place_id) {
            return "{$baseUrl}&query={$query}&query_place_id={$this->google_place_id}";
        }

        return "{$baseUrl}&query={$query}";
    }
}
