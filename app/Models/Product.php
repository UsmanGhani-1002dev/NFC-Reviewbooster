<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name', 'slug', 'subtitle', 'description', 'image', 'image_alt',
        'features', 'gallery', 'is_active', 'sort_order',
        'seo_title', 'seo_description', 'seo_keywords',
    ];

    protected $casts = [
        'features' => 'array',
        'gallery' => 'array',
        'is_active' => 'boolean',
    ];

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class)->orderBy('sort_order');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getLowestPriceAttribute()
    {
        return $this->variants->min('price');
    }

    public function getHighestDiscountAttribute()
    {
        return $this->variants->max('discount_percent');
    }
}
