@extends('layouts.app')

@section('content')
@php
    $fmt = fn ($n) => number_format($n);
@endphp

<div class="p-4 sm:p-6">
    {{-- Header + range filter --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Analytics</h1>
            <p class="text-sm text-gray-400 mt-1">User behaviour, product performance &amp; conversions</p>
        </div>
        <div class="flex flex-wrap gap-2">
            @foreach($rangeOptions as $val => $label)
                <a href="{{ route('admin.analytics', ['range' => $val]) }}"
                   class="px-3.5 py-2 rounded-xl text-xs font-bold border transition-all
                   {{ $range === (string) $val ? 'bg-blue-600 text-white border-blue-600 shadow-sm' : 'bg-white text-gray-600 border-gray-200 hover:border-blue-300' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- KPI cards --}}
    @php
        $cards = [
            ['label' => 'Page Views', 'value' => $fmt($pageViews), 'sub' => 'total in period', 'color' => 'blue', 'icon' => 'eye', 'key' => 'page_views'],
            ['label' => 'Unique Visitors', 'value' => $fmt($uniqueVisitors), 'sub' => $fmt($newVisitors).' new · '.$fmt($returningVisitors).' returning', 'color' => 'indigo', 'icon' => 'users', 'key' => 'unique_visitors'],
            ['label' => 'Added to Cart', 'value' => $fmt($cartVisitors), 'sub' => $fmt($addToCartCount).' add events', 'color' => 'amber', 'icon' => 'shopping-cart', 'key' => 'added_to_cart'],
            ['label' => 'Checkout Attempts', 'value' => $fmt($checkoutVisitors), 'sub' => 'visitors who started', 'color' => 'purple', 'icon' => 'credit-card', 'key' => 'checkout'],
            ['label' => 'Purchases', 'value' => $fmt($purchaseCount), 'sub' => 'completed orders', 'color' => 'green', 'icon' => 'package-check', 'key' => 'purchases'],
            ['label' => 'Revenue', 'value' => '£'.number_format($revenue, 2), 'sub' => 'AOV £'.number_format($avgOrderValue, 2), 'color' => 'green', 'icon' => 'pound-sterling', 'key' => 'revenue'],
            ['label' => 'Conversion Rate', 'value' => $conversionRate.'%', 'sub' => 'visitors → purchase', 'color' => 'teal', 'icon' => 'trending-up', 'key' => 'conversion'],
            ['label' => 'Cart Abandonment', 'value' => $cartAbandonment.'%', 'sub' => 'checkout not completed', 'color' => 'red', 'icon' => 'shopping-bag', 'key' => 'abandonment', 'invert' => true],
        ];
        $colorMap = [
            'blue' => 'bg-blue-50 text-blue-600', 'indigo' => 'bg-indigo-50 text-indigo-600',
            'amber' => 'bg-amber-50 text-amber-600', 'purple' => 'bg-purple-50 text-purple-600',
            'green' => 'bg-green-50 text-green-600', 'teal' => 'bg-teal-50 text-teal-600',
            'red' => 'bg-red-50 text-red-600',
        ];
    @endphp
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @foreach($cards as $c)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400">{{ $c['label'] }}</span>
                    <span class="w-9 h-9 rounded-xl flex items-center justify-center {{ $colorMap[$c['color']] }}">
                        <i data-lucide="{{ $c['icon'] }}" class="w-5 h-5"></i>
                    </span>
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    <div class="text-2xl font-extrabold text-gray-900">{{ $c['value'] }}</div>
                    @if($compare && isset($deltas[$c['key']]))
                        @php
                            $d = $deltas[$c['key']];
                            $invert = $c['invert'] ?? false;
                            // For most metrics "up" is good; for abandonment it's bad.
                            $isGood = $d['dir'] === 'flat' ? null : (($d['dir'] === 'up') !== $invert);
                            $badgeClass = $isGood === null ? 'bg-gray-100 text-gray-500' : ($isGood ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600');
                            $arrow = $d['dir'] === 'up' ? '▲' : ($d['dir'] === 'down' ? '▼' : '—');
                        @endphp
                        <span class="inline-flex items-center gap-0.5 text-[11px] font-bold px-1.5 py-0.5 rounded-md {{ $badgeClass }}">
                            {{ $arrow }} {{ $d['new'] ? 'New' : ($d['pct'] === 0 ? '0%' : $d['pct'].'%') }}
                        </span>
                    @endif
                </div>
                <div class="text-[11px] text-gray-400 mt-1">
                    {{ $c['sub'] }}@if($compare && isset($deltas[$c['key']])) <span class="text-gray-300">· vs prev</span>@endif
                </div>
            </div>
        @endforeach
    </div>

    {{-- Funnel --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-8">
        <h3 class="text-sm font-bold text-gray-700 mb-5">Conversion Funnel</h3>
        @php
            $funnel = [
                ['Visitors', $uniqueVisitors, 'bg-blue-500'],
                ['Added to cart', $cartVisitors, 'bg-amber-500'],
                ['Checkout started', $checkoutVisitors, 'bg-purple-500'],
                ['Purchased', $purchaseCount, 'bg-green-500'],
            ];
            $funnelMax = max(1, $uniqueVisitors, $cartVisitors, $checkoutVisitors, $purchaseCount);
        @endphp
        <div class="space-y-3">
            @foreach($funnel as [$label, $val, $bar])
                <div class="flex items-center gap-3">
                    <div class="w-32 text-xs font-semibold text-gray-600 shrink-0">{{ $label }}</div>
                    <div class="flex-1 bg-gray-100 rounded-full h-6 overflow-hidden">
                        <div class="{{ $bar }} h-6 rounded-full flex items-center justify-end px-2" style="width: {{ max(2, round($val / $funnelMax * 100)) }}%">
                            <span class="text-[11px] font-bold text-white">{{ $fmt($val) }}</span>
                        </div>
                    </div>
                    <div class="w-14 text-right text-[11px] text-gray-400">{{ round($val / $funnelMax * 100) }}%</div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Abandoned carts --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
            <i data-lucide="shopping-cart" class="h-4 w-4 text-gray-500" stroke-width="2.5"></i>
            <h3 class="text-sm font-bold text-gray-700">Abandoned Carts</h3>
            <span class="text-[11px] text-gray-400">(checkout started but not paid)</span>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 divide-x divide-y lg:divide-y-0 divide-gray-100">
            <div class="p-5">
                <div class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Abandoned</div>
                <div class="text-2xl font-extrabold text-red-600 mt-1">{{ $fmt($abandonedCarts) }}</div>
                <div class="text-[11px] text-gray-500 mt-0.5">of {{ $fmt($checkoutsStarted) }} checkouts</div>
            </div>
            <div class="p-5">
                <div class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Abandon Rate</div>
                <div class="text-2xl font-extrabold text-gray-900 mt-1">{{ $cartAbandonment }}%</div>
                <div class="text-[11px] text-gray-500 mt-0.5">of started checkouts</div>
            </div>
            <div class="p-5">
                <div class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Recovered</div>
                <div class="text-2xl font-extrabold text-green-600 mt-1">{{ $fmt($recoveredCarts) }}</div>
                <div class="text-[11px] text-gray-500 mt-0.5">{{ $cartRecoveryRate }}% recovery rate</div>
            </div>
            <div class="p-5">
                <div class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Lost Value</div>
                <div class="text-2xl font-extrabold text-gray-900 mt-1">£{{ number_format($abandonedValue, 2) }}</div>
                <div class="text-[11px] text-gray-500 mt-0.5">in abandoned carts</div>
            </div>
        </div>

        <div class="overflow-x-auto border-t border-gray-100">
            <table class="w-full text-sm">
                <thead class="text-[10px] uppercase tracking-wider text-gray-400 bg-gray-50/60">
                    <tr>
                        <th class="px-5 py-3 text-left">When</th>
                        <th class="px-5 py-3 text-left">Customer</th>
                        <th class="px-5 py-3 text-left">Cart</th>
                        <th class="px-5 py-3 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($recentAbandoned as $cart)
                        @php
                            $itemsSummary = collect($cart->items ?? [])
                                ->map(fn ($i) => ($i['quantity'] ?? 1) . '× ' . ($i['name'] ?? 'Item'))
                                ->implode(', ');
                        @endphp
                        <tr>
                            <td class="px-5 py-3 text-gray-500 whitespace-nowrap">{{ $cart->updated_at->diffForHumans() }}</td>
                            <td class="px-5 py-3 text-gray-700">
                                {{ $cart->customer_email ?: $cart->customer_name ?: 'Guest / unknown' }}
                            </td>
                            <td class="px-5 py-3 text-gray-600 truncate max-w-xs" title="{{ $itemsSummary }}">{{ $itemsSummary ?: '—' }}</td>
                            <td class="px-5 py-3 text-right font-bold text-gray-800 whitespace-nowrap">£{{ number_format((float) $cart->total, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-5 py-8 text-center text-gray-400 italic">No abandoned carts in this period. 🎉</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Traffic trend --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-8">
        <h3 class="text-sm font-bold text-gray-700 mb-4">Traffic (page views)</h3>
        <div style="position:relative; height:300px;">
            <canvas id="trafficChart"></canvas>
        </div>
    </div>

    {{-- Tables row 1: countries + devices --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100"><h3 class="text-sm font-bold text-gray-700">Visitors by Country</h3></div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="text-[10px] uppercase tracking-wider text-gray-400 bg-gray-50/60">
                        <tr><th class="px-5 py-3 text-left">Country</th><th class="px-5 py-3 text-right">Visitors</th><th class="px-5 py-3 text-right">Views</th></tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($byCountry as $row)
                            <tr>
                                <td class="px-5 py-3 font-medium text-gray-700">{{ $row['country'] }}</td>
                                <td class="px-5 py-3 text-right font-bold text-gray-800">{{ $fmt($row['visitors']) }}</td>
                                <td class="px-5 py-3 text-right text-gray-500">{{ $fmt($row['views']) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-5 py-8 text-center text-gray-400 italic">No visitor data yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="text-sm font-bold text-gray-700 mb-4">Devices</h3>
            <div style="position:relative; height:220px;">
                <canvas id="deviceChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Top pages --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-8">
        <div class="px-5 py-4 border-b border-gray-100"><h3 class="text-sm font-bold text-gray-700">Most Visited Pages</h3></div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-[10px] uppercase tracking-wider text-gray-400 bg-gray-50/60">
                    <tr><th class="px-5 py-3 text-left">Page</th><th class="px-5 py-3 text-right">Views</th><th class="px-5 py-3 text-right">Visitors</th></tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($topPages as $p)
                        @php $label = ($p->path === '/' || $p->path === '' || is_null($p->path)) ? 'Home' : '/' . ltrim($p->path, '/'); @endphp
                        <tr>
                            <td class="px-5 py-3 font-medium text-gray-700 truncate max-w-xs" title="{{ $label }}">{{ $label }}</td>
                            <td class="px-5 py-3 text-right font-bold text-gray-800">{{ $fmt($p->views) }}</td>
                            <td class="px-5 py-3 text-right text-gray-500">{{ $fmt($p->visitors) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-5 py-8 text-center text-gray-400 italic">No page data yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tables row 2: product performance --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @php
            $productTables = [
                ['title' => 'Most Viewed Products', 'rows' => $mostViewed, 'metric' => 'views', 'unit' => ''],
                ['title' => 'Most Added to Cart', 'rows' => $mostAddedToCart, 'metric' => 'qty', 'unit' => ''],
                ['title' => 'Most Purchased', 'rows' => $mostPurchased, 'metric' => 'qty', 'unit' => ''],
            ];
        @endphp
        @foreach($productTables as $t)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100"><h3 class="text-sm font-bold text-gray-700">{{ $t['title'] }}</h3></div>
                <div class="p-2">
                    @forelse($t['rows'] as $i => $r)
                        <div class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-50">
                            <span class="flex-1 text-sm text-gray-700 truncate" title="{{ $r->name ?? $r->product_name }}">{{ $r->name ?? $r->product_name }}</span>
                            <span class="text-sm font-bold text-gray-900">{{ $fmt($r->{$t['metric']}) }}</span>
                            @if(isset($r->revenue))<span class="text-[10px] text-green-600 font-semibold">£{{ number_format($r->revenue, 0) }}</span>@endif
                        </div>
                    @empty
                        <div class="px-4 py-8 text-center text-gray-400 italic text-sm">No data yet.</div>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
(function () {
    const labels = @json($labels);
    const views = @json($viewsSeries);
    const orders = @json($ordersSeries);
    const revenue = @json($revenueSeries);
    const devices = @json($byDevice);

    const gridColor = 'rgba(0,0,0,0.05)';
    const baseOpts = {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: { x: { grid: { display: false }, ticks: { maxTicksLimit: 8, font: { size: 10 } } }, y: { beginAtZero: true, grid: { color: gridColor }, ticks: { font: { size: 10 } } } }
    };

    const t = document.getElementById('trafficChart');
    if (t) new Chart(t, {
        type: 'line',
        data: { labels, datasets: [{ data: views, label: 'Page views', borderColor: '#2563eb', backgroundColor: 'rgba(37,99,235,.10)', fill: true, tension: .35, pointRadius: 0, borderWidth: 2 }] },
        options: baseOpts
    });

    const d = document.getElementById('deviceChart');
    const dLabels = Object.keys(devices);
    if (d && dLabels.length) new Chart(d, {
        type: 'doughnut',
        data: { labels: dLabels.map(x => x.charAt(0).toUpperCase() + x.slice(1)), datasets: [{ data: Object.values(devices), backgroundColor: ['#2563eb', '#16a34a', '#f59e0b', '#a855f7'] }] },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { font: { size: 11 }, boxWidth: 12 } } } }
    });

    // Render the Lucide KPI icons on this page
    if (window.lucide && typeof window.lucide.createIcons === 'function') {
        window.lucide.createIcons();
    }
})();
</script>
@endsection
