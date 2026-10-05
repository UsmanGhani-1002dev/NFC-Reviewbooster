<?php

namespace App\Services;

use App\Models\AnalyticsEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Tracking
{
    public const VISITOR_COOKIE = 'rb_vid';

    // Event type aliases (mirror App\Models\AnalyticsEvent)
    public const ADD_TO_CART = AnalyticsEvent::ADD_TO_CART;
    public const CHECKOUT_STARTED = AnalyticsEvent::CHECKOUT_STARTED;
    public const PURCHASE = AnalyticsEvent::PURCHASE;

    /**
     * Get (or create + queue) the persistent visitor id cookie.
     */
    public static function visitorId(Request $request): string
    {
        $id = $request->cookie(self::VISITOR_COOKIE);
        if (!$id || !is_string($id) || strlen($id) > 64) {
            $id = (string) Str::uuid();
            // 1 year, keep across sessions
            Cookie::queue(Cookie::make(self::VISITOR_COOKIE, $id, 60 * 24 * 365));
        }
        return $id;
    }

    /**
     * Record a funnel event (add_to_cart / checkout_started / purchase).
     * Never throws — analytics must not break the shop.
     */
    public static function event(string $type, Request $request, array $data = []): void
    {
        try {
            $geo = GeoIp::country($request->ip());

            AnalyticsEvent::create(array_merge([
                'type' => $type,
                'visitor_id' => self::visitorId($request),
                'session_id' => $request->hasSession() ? $request->session()->getId() : null,
                'user_id' => optional($request->user())->id,
                'ip' => $request->ip(),
                'country' => $geo['country'],
            ], $data));
        } catch (\Throwable $e) {
            Log::warning('Analytics event failed: ' . $e->getMessage());
        }
    }

    /**
     * Rough device type from the user agent.
     */
    public static function device(?string $agent): string
    {
        $agent = strtolower($agent ?? '');
        if (preg_match('/ipad|tablet/', $agent)) {
            return 'tablet';
        }
        if (preg_match('/mobi|android|iphone|ipod/', $agent)) {
            return 'mobile';
        }
        return 'desktop';
    }
}
