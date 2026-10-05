<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AbandonedCart;
use App\Models\AnalyticsEvent;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PageView;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        // ---- Date range ----------------------------------------------------
        $rangeOptions = ['today' => 'Today', '7' => 'Last 7 days', '30' => 'Last 30 days', '90' => 'Last 90 days', '365' => 'Last 12 months', 'all' => 'All time'];
        $range = (string) $request->input('range', '30');
        if (!array_key_exists($range, $rangeOptions)) {
            $range = '30';
        }
        $now = Carbon::now();
        if ($range === 'all') {
            $start = Carbon::create(2000, 1, 1);
        } elseif ($range === 'today') {
            $start = $now->copy()->startOfDay();
        } else {
            $start = $now->copy()->subDays((int) $range)->startOfDay();
        }
        // Length of the current window in days, used for the previous-period compare.
        $periodDays = $range === 'today' ? 1 : (int) $range;

        $pv = fn () => PageView::where('created_at', '>=', $start);
        $ev = fn (string $type) => AnalyticsEvent::where('type', $type)->where('created_at', '>=', $start);
        $ordersInRange = fn () => Order::where('created_at', '>=', $start);

        // ---- Core KPIs -----------------------------------------------------
        $pageViews = $pv()->count();
        $uniqueVisitors = (int) $pv()->distinct('visitor_id')->count('visitor_id');

        $returningVisitors = (int) PageView::where('created_at', '>=', $start)
            ->whereExists(function ($q) use ($start) {
                $q->select(DB::raw(1))
                  ->from('page_views as pv2')
                  ->whereColumn('pv2.visitor_id', 'page_views.visitor_id')
                  ->where('pv2.created_at', '<', $start);
            })
            ->distinct('visitor_id')->count('visitor_id');
        $newVisitors = max(0, $uniqueVisitors - $returningVisitors);

        $addToCartCount = $ev(AnalyticsEvent::ADD_TO_CART)->count();
        $cartVisitors = (int) $ev(AnalyticsEvent::ADD_TO_CART)->distinct('visitor_id')->count('visitor_id');
        $checkoutVisitors = (int) $ev(AnalyticsEvent::CHECKOUT_STARTED)->distinct('visitor_id')->count('visitor_id');

        $purchaseCount = (int) $ordersInRange()->count();
        $revenue = (float) $ordersInRange()->sum('total');
        $avgOrderValue = $purchaseCount > 0 ? round($revenue / $purchaseCount, 2) : 0.0;

        $conversionRate = $uniqueVisitors > 0 ? round(($purchaseCount / $uniqueVisitors) * 100, 2) : 0.0;

        // ---- Abandoned carts (checkout started but not paid) ---------------
        // Accurate, checkout-level abandonment from the abandoned_carts table:
        // abandoned / all checkouts started (abandoned + recovered).
        $ac = fn () => AbandonedCart::where('created_at', '>=', $start);
        $abandonedCarts   = (int) $ac()->where('status', AbandonedCart::STATUS_ABANDONED)->count();
        $recoveredCarts   = (int) $ac()->where('status', AbandonedCart::STATUS_CONVERTED)->count();
        $checkoutsStarted = $abandonedCarts + $recoveredCarts;
        $abandonedValue   = (float) $ac()->where('status', AbandonedCart::STATUS_ABANDONED)->sum('total');
        $cartAbandonment  = $checkoutsStarted > 0 ? round(($abandonedCarts / $checkoutsStarted) * 100, 1) : 0.0;
        $cartRecoveryRate = $checkoutsStarted > 0 ? round(($recoveredCarts / $checkoutsStarted) * 100, 1) : 0.0;

        // ---- Compare vs previous period -----------------------------------
        $compare = $range !== 'all';
        $deltas = [];
        if ($compare) {
            $days = $periodDays;
            $prevStart = $start->copy()->subDays($days);
            $prevEnd = $start; // exclusive upper bound

            $pvp = fn () => PageView::whereBetween('created_at', [$prevStart, $prevEnd]);
            $evp = fn (string $t) => AnalyticsEvent::where('type', $t)->whereBetween('created_at', [$prevStart, $prevEnd]);
            $ordp = fn () => Order::whereBetween('created_at', [$prevStart, $prevEnd]);

            $pPageViews = $pvp()->count();
            $pUnique = (int) $pvp()->distinct('visitor_id')->count('visitor_id');
            $pCart = (int) $evp(AnalyticsEvent::ADD_TO_CART)->distinct('visitor_id')->count('visitor_id');
            $pCheckout = (int) $evp(AnalyticsEvent::CHECKOUT_STARTED)->distinct('visitor_id')->count('visitor_id');
            $pPurchases = (int) $ordp()->count();
            $pRevenue = (float) $ordp()->sum('total');
            $pConversion = $pUnique > 0 ? round(($pPurchases / $pUnique) * 100, 2) : 0.0;
            // Previous-period checkout-level abandonment from abandoned_carts.
            $pAbCarts = (int) AbandonedCart::where('status', AbandonedCart::STATUS_ABANDONED)->whereBetween('created_at', [$prevStart, $prevEnd])->count();
            $pRecCarts = (int) AbandonedCart::where('status', AbandonedCart::STATUS_CONVERTED)->whereBetween('created_at', [$prevStart, $prevEnd])->count();
            $pStarted = $pAbCarts + $pRecCarts;
            $pAbandon = $pStarted > 0 ? round(($pAbCarts / $pStarted) * 100, 1) : 0.0;

            $delta = function ($cur, $prev) {
                if ($prev == 0) {
                    return ['pct' => null, 'dir' => ($cur > 0 ? 'up' : 'flat'), 'new' => $cur > 0];
                }
                $pct = (int) round((($cur - $prev) / $prev) * 100);
                return ['pct' => abs($pct), 'dir' => ($pct > 0 ? 'up' : ($pct < 0 ? 'down' : 'flat')), 'new' => false];
            };

            $deltas = [
                'page_views' => $delta($pageViews, $pPageViews),
                'unique_visitors' => $delta($uniqueVisitors, $pUnique),
                'added_to_cart' => $delta($cartVisitors, $pCart),
                'checkout' => $delta($checkoutVisitors, $pCheckout),
                'purchases' => $delta($purchaseCount, $pPurchases),
                'revenue' => $delta($revenue, $pRevenue),
                'conversion' => $delta($conversionRate, $pConversion),
                'abandonment' => $delta($cartAbandonment, $pAbandon),
            ];
        }

        // ---- Visitors by country ------------------------------------------
        $byCountry = $pv()
            ->select('country', DB::raw('COUNT(DISTINCT visitor_id) as visitors'), DB::raw('COUNT(*) as views'))
            ->groupBy('country')
            ->orderByDesc('visitors')
            ->limit(6)
            ->get()
            ->map(fn ($r) => [
                'country' => $r->country ?: 'Unknown',
                'visitors' => (int) $r->visitors,
                'views' => (int) $r->views,
            ]);

        // ---- Device breakdown ---------------------------------------------
        $byDevice = $pv()
            ->select('device', DB::raw('COUNT(*) as views'))
            ->groupBy('device')
            ->pluck('views', 'device')
            ->toArray();

        // ---- Most viewed products -----------------------------------------
        $mostViewed = PageView::where('page_views.created_at', '>=', $start)
            ->whereNotNull('product_id')
            ->join('products', 'products.id', '=', 'page_views.product_id')
            ->select('products.name', DB::raw('COUNT(*) as views'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('views')
            ->limit(8)
            ->get();

        // ---- Most added-to-cart products ----------------------------------
        $mostAddedToCart = AnalyticsEvent::where('analytics_events.type', AnalyticsEvent::ADD_TO_CART)
            ->where('analytics_events.created_at', '>=', $start)
            ->whereNotNull('product_id')
            ->join('products', 'products.id', '=', 'analytics_events.product_id')
            ->select('products.name', DB::raw('SUM(COALESCE(quantity,1)) as qty'), DB::raw('COUNT(*) as events'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('qty')
            ->limit(8)
            ->get();

        // ---- Most purchased products --------------------------------------
        $mostPurchased = OrderItem::where('order_items.created_at', '>=', $start)
            ->select('product_name', DB::raw('SUM(quantity) as qty'), DB::raw('SUM(total) as revenue'))
            ->groupBy('product_name')
            ->orderByDesc('qty')
            ->limit(8)
            ->get();

        // ---- Most visited pages -------------------------------------------
        $topPages = $pv()
            ->select('path', DB::raw('COUNT(*) as views'), DB::raw('COUNT(DISTINCT visitor_id) as visitors'))
            ->groupBy('path')
            ->orderByDesc('views')
            ->limit(10)
            ->get();

        // ---- Trends over time (daily, capped to 90 points) ----------------
        $trendDays = $range === 'all' ? 90 : min((int) $range === 0 ? 30 : (int) $range, 90);
        $trendStart = $now->copy()->subDays($trendDays - 1)->startOfDay();

        $viewsByDay = PageView::where('created_at', '>=', $trendStart)
            ->select(DB::raw('DATE(created_at) as d'), DB::raw('COUNT(*) as c'))
            ->groupBy('d')->pluck('c', 'd')->toArray();
        $ordersByDay = Order::where('created_at', '>=', $trendStart)
            ->select(DB::raw('DATE(created_at) as d'), DB::raw('COUNT(*) as c'), DB::raw('SUM(total) as rev'))
            ->groupBy('d')->get()->keyBy('d');

        $labels = [];
        $viewsSeries = [];
        $ordersSeries = [];
        $revenueSeries = [];
        for ($i = 0; $i < $trendDays; $i++) {
            $day = $trendStart->copy()->addDays($i);
            $key = $day->format('Y-m-d');
            $labels[] = $day->format('d M');
            $viewsSeries[] = (int) ($viewsByDay[$key] ?? 0);
            $ordersSeries[] = (int) (optional($ordersByDay->get($key))->c ?? 0);
            $revenueSeries[] = round((float) (optional($ordersByDay->get($key))->rev ?? 0), 2);
        }

        // Recent abandoned carts for the list (latest activity first).
        $recentAbandoned = AbandonedCart::where('status', AbandonedCart::STATUS_ABANDONED)
            ->where('created_at', '>=', $start)
            ->orderByDesc('updated_at')
            ->limit(10)
            ->get();

        return view('admin.analytics.index', compact(
            'rangeOptions', 'range',
            'pageViews', 'uniqueVisitors', 'returningVisitors', 'newVisitors',
            'addToCartCount', 'cartVisitors', 'checkoutVisitors',
            'purchaseCount', 'revenue', 'avgOrderValue', 'cartAbandonment', 'conversionRate',
            'compare', 'deltas',
            'byCountry', 'byDevice', 'topPages', 'mostViewed', 'mostAddedToCart', 'mostPurchased',
            'labels', 'viewsSeries', 'ordersSeries', 'revenueSeries',
            'abandonedCarts', 'recoveredCarts', 'checkoutsStarted', 'abandonedValue',
            'cartRecoveryRate', 'recentAbandoned'
        ));
    }
}
