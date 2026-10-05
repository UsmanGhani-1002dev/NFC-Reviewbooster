<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageView extends Model
{
    const UPDATED_AT = null;

    protected $fillable = [
        'visitor_id', 'session_id', 'user_id', 'ip', 'country', 'country_code',
        'path', 'route_name', 'product_id', 'referrer', 'device', 'user_agent',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
