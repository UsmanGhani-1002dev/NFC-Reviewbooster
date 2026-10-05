<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AbandonedCart extends Model
{
    public const STATUS_ABANDONED = 'abandoned';
    public const STATUS_CONVERTED = 'converted';

    protected $fillable = [
        'payment_intent_id', 'session_id', 'visitor_id', 'user_id',
        'customer_name', 'customer_email', 'items', 'item_count',
        'items_total', 'shipping_fee', 'total', 'shipping_method',
        'status', 'converted_order_id', 'recovered_at',
    ];

    protected $casts = [
        'items' => 'array',
        'item_count' => 'integer',
        'items_total' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'total' => 'decimal:2',
        'recovered_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'converted_order_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeAbandoned($query)
    {
        return $query->where('status', self::STATUS_ABANDONED);
    }

    public function scopeConverted($query)
    {
        return $query->where('status', self::STATUS_CONVERTED);
    }
}
