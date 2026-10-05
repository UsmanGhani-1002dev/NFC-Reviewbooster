<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
   protected $fillable = ['name', 'description', 'price', 'duration_days', 'card_limit', 'review_limit', 'has_ai_replies'];

    protected $casts = [
        'has_ai_replies' => 'boolean',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
