<?php

namespace App\Http\Middleware;

use App\Models\PageView;
use App\Models\Product;
use App\Services\GeoIp;
use App\Services\Tracking;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TrackPageView
{
    /** Path prefixes we never track. */
    protected array $skip = [
        'admin', 'api', 'track', 'livewire', '_debugbar', 'up', 'broadcasting',
        'oauth', 'sanctum', 'login', 'register', 'logout', 'password', 'email',
        'verify', 'two-factor', 'reset-password', 'forgot-password', 'dashboard',
        'storage', 'build', 'vendor',
    ];

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($this->shouldTrack($request)) {
            $this->record($request, $response);
        }

        return $response;
    }

    protected function record(Request $request, $response): void
    {
        try {
            if (method_exists($response, 'getStatusCode') && $response->getStatusCode() !== 200) {
                return;
            }

            $contentType = method_exists($response, 'headers') ? (string) $response->headers->get('Content-Type') : 'text/html';
            if ($contentType && !str_contains($contentType, 'text/html')) {
                return;
            }

            $agent = (string) $request->userAgent();
            if ($this->isBot($agent)) {
                return;
            }

            $visitorId = Tracking::visitorId($request); // reads/queues the cookie
            $geo = GeoIp::country($request->ip());

            // Which product (if this is a product detail page)?
            $productId = null;
            if ($request->route() && $request->route()->getName() === 'shop.show') {
                $param = $request->route('product');
                if ($param instanceof Product) {
                    $productId = $param->id;
                } elseif (is_string($param)) {
                    $productId = Product::where('slug', $param)->value('id');
                }
            }

            PageView::create([
                'visitor_id' => $visitorId,
                'session_id' => $request->hasSession() ? $request->session()->getId() : null,
                'user_id' => optional($request->user())->id,
                'ip' => $request->ip(),
                'country' => $geo['country'],
                'country_code' => $geo['code'],
                'path' => substr($request->path(), 0, 500),
                'route_name' => optional($request->route())->getName(),
                'product_id' => $productId,
                'referrer' => substr((string) $request->headers->get('referer'), 0, 500) ?: null,
                'device' => Tracking::device($agent),
                'user_agent' => substr($agent, 0, 500),
            ]);
        } catch (\Throwable $e) {
            Log::warning('Page view tracking failed: ' . $e->getMessage());
        }
    }

    protected function shouldTrack(Request $request): bool
    {
        if (!$request->isMethod('GET') || $request->ajax() || $request->wantsJson()) {
            return false;
        }

        $path = ltrim($request->path(), '/');
        if ($path === '' ) {
            return true; // home page
        }

        $first = strtolower(explode('/', $path)[0]);
        return !in_array($first, $this->skip, true);
    }

    protected function isBot(string $agent): bool
    {
        if ($agent === '') {
            return true;
        }
        return (bool) preg_match('/bot|crawl|spider|slurp|bingpreview|facebookexternalhit|embedly|quora|pinterest|monitor|uptime|curl|wget|python-requests|headless/i', $agent);
    }
}
