<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id', 'name', 'quantity', 'price', 'original_price',
        'discount_percent', 'is_best_value', 'is_most_popular', 'stock', 'sort_order',
        'image',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'is_best_value' => 'boolean',
        'is_most_popular' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getSavingsAttribute()
    {
        return $this->original_price - $this->price;
    }
}
