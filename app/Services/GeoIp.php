<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class GeoIp
{
    /**
     * Resolve a country for an IP address. Cached per-IP for 30 days so we only
     * hit the external service once per visitor IP. Never throws — returns
     * ['country' => ?string, 'code' => ?string].
     */
    public static function country(?string $ip): array
    {
        $empty = ['country' => null, 'code' => null];

        if (empty($ip) || in_array($ip, ['127.0.0.1', '::1'], true)) {
            return $empty;
        }

        // Skip private / reserved ranges (local network, etc.)
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
            return $empty;
        }

        return Cache::remember('geoip:' . $ip, now()->addDays(30), function () use ($ip, $empty) {
            try {
                $res = Http::timeout(2)->get("http://ip-api.com/json/{$ip}", [
                    'fields' => 'status,country,countryCode',
                ]);
                if ($res->ok() && ($res->json('status') === 'success')) {
                    return [
                        'country' => $res->json('country'),
                        'code' => $res->json('countryCode'),
                    ];
                }
            } catch (\Throwable $e) {
                // network/timeout — fall through to empty
            }
            return $empty;
        });
    }
}
