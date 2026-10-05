<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnalyticsEvent extends Model
{
    const UPDATED_AT = null;

    // Event types
    public const ADD_TO_CART = 'add_to_cart';
    public const CHECKOUT_STARTED = 'checkout_started';
    public const PURCHASE = 'purchase';

    protected $fillable = [
        'type', 'visitor_id', 'session_id', 'user_id', 'product_id',
        'product_variant_id', 'quantity', 'value', 'order_id', 'ip', 'country',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
