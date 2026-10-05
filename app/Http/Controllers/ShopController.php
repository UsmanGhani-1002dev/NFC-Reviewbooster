<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShopController extends Controller
{
    /**
     * Product listing page - "Our Products" grid
     */
    public function index()
    {
        $products = Product::where('is_active', true)
            ->with(['variants' => function($q) {
                $q->where('name', 'NOT LIKE', '%Plan%')
                  ->where('name', 'NOT LIKE', '%Monthly%')
                  ->where('name', 'NOT LIKE', '%Annual%');
            }])
            ->get();

        return view('shop.index', compact('products'));
    }

    /**
     * Product detail page
     */
    public function show(Request $request, Product $product)
    {
        if (!$product->is_active) {
            abort(404);
        }

        $product->load(['variants' => function ($q) {
            $q->where('name', 'NOT LIKE', '%Billing%');
        }]);

        $variant_id = $request->query('variant');

        if (!$product->variants->pluck('id')->contains($variant_id)) {
            $variant_id = $product->variants->first()?->id;
        }

        $relatedProducts = Product::where('id', '!=', $product->id)
            ->where('is_active', true)
            ->with(['variants' => function ($q) {
                $q->where('name', 'NOT LIKE', '%1 Mo Free%');
            }])
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('shop.show', compact('product', 'variant_id', 'relatedProducts'));
    }

    /**
     * Calculate variant price taking into account logged in approved partner discounts
     */
    private function getFinalVariantPrice(ProductVariant $variant): float
    {
        $price = (float) $variant->price;
        if (auth()->check() && auth()->user()->isApprovedPartner()) {
            $discountPercent = auth()->user()->getPartnerDiscountPercent();
            if ($discountPercent > 0) {
                $price = round($price * (1 - ($discountPercent / 100)), 2);
            }
        }
        return max(0.01, $price);
    }

    /**
     * Normalise a posted cart into authoritative order lines.
     *
     * Accepts the localStorage cart shape ({variantId, qty, ...}) as well as
     * an explicit {variant_id, quantity} shape. Prices are always recomputed
     * server-side (never trusted from the client), and duplicate variants are
     * merged. Returns an array of ['variant' => ProductVariant, 'quantity' => int,
     * 'unit_price' => float] keyed by variant id.
     */
    /**
     * Store a custom logo/artwork uploaded from the product or keyring landing
     * page. Returns the stored path so the front-end can attach it to the cart
     * item, which then flows through checkout onto the order line.
     */
    public function uploadCustomLogo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'logo' => 'required|file|mimes:jpg,jpeg,png,svg,webp,pdf|max:5120',
        ], [
            'logo.mimes' => 'Please upload a JPG, PNG, SVG, WEBP or PDF file.',
            'logo.max' => 'The logo must be 5MB or smaller.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first('logo'),
            ], 422);
        }

        $path = $request->file('logo')->store('custom-logos', 'public');

        return response()->json([
            'success' => true,
            'path' => $path,
            'url' => asset('storage/' . $path),
            'name' => $request->file('logo')->getClientOriginalName(),
        ]);
    }

    private function resolveCartLines(array $items): array
    {
        $lines = [];

        foreach ($items as $raw) {
            if (!is_array($raw)) {
                continue;
            }

            $variantId = $raw['variant_id'] ?? $raw['variantId'] ?? null;
            $qty = (int) ($raw['quantity'] ?? $raw['qty'] ?? 1);

            if (!$variantId || $qty < 1) {
                continue;
            }

            $locations = $this->extractLocations($raw);

            $key = $variantId . '|' . implode(',', array_map(fn($l) => $l['id'], $locations));

            $customLogoPath = $this->extractCustomLogoPath($raw);

            if (isset($lines[$key])) {
                $lines[$key]['quantity'] += $qty;
                if (empty($lines[$key]['custom_logo_path']) && $customLogoPath) {
                    $lines[$key]['custom_logo_path'] = $customLogoPath;
                }
                continue;
            }

            $variant = ProductVariant::with('product')->find($variantId);
            if (!$variant) {
                continue;
            }

            $variantName = (!empty($raw['name']) && is_string($raw['name'])) ? $raw['name'] : $variant->name;
            $unitPrice = $this->getFinalVariantPrice($variant);

            if (isset($raw['price']) && is_numeric($raw['price']) && (float)$raw['price'] > 0) {
                $customPrice = (float) $raw['price'];
                if (auth()->check() && auth()->user()->isApprovedPartner()) {
                    $discountPercent = auth()->user()->getPartnerDiscountPercent();
                    if ($discountPercent > 0) {
                        $customPrice = round($customPrice * (1 - ($discountPercent / 100)), 2);
                    }
                }
                $unitPrice = max(0.01, $customPrice);
            }

            $lines[$key] = [
                'variant' => $variant,
                'custom_name' => $variantName,
                'quantity' => $qty,
                'unit_price' => $unitPrice,
                'locations' => $locations,
                'custom_logo_path' => $customLogoPath,
            ];
        }

        return $lines;
    }

    /**
     * Pull the stored custom-logo path out of a raw cart item, guarding against
     * a client sending anything other than a path inside our upload folder.
     */
    private function extractCustomLogoPath(array $raw): ?string
    {
        $path = $raw['customLogoPath'] ?? $raw['custom_logo_path'] ?? null;

        if (!is_string($path) || $path === '') {
            return null;
        }

        // Only accept paths our own upload endpoint produces.
        if (!str_starts_with($path, 'custom-logos/')) {
            return null;
        }

        return $path;
    }

    /**
     * Pull the normalised Google Business locations out of a raw cart item.
     * Prefers the multi-location `locationList`, falling back to a single
     * `placeId`/`placeName` pair. Returns [] when none are present.
     */
    private function extractLocations(array $raw): array
    {
        $locations = [];

        if (!empty($raw['locationList']) && is_array($raw['locationList'])) {
            foreach ($raw['locationList'] as $loc) {
                if (is_array($loc) && !empty($loc['id']) && !empty($loc['name'])) {
                    $locations[] = ['id' => $loc['id'], 'name' => $loc['name']];
                }
            }
        }

        if (empty($locations) && !empty($raw['placeId']) && !empty($raw['placeName'])) {
            $locations[] = ['id' => $raw['placeId'], 'name' => $raw['placeName']];
        }

        return $locations;
    }

    /**
     * Pull the posted cart items, falling back to a single variant_id for the
     * legacy "Buy Now" path.
     */
    private function itemsFromRequest(Request $request): array
    {
        $items = $request->input('items', []);

        if ((empty($items) || !is_array($items)) && $request->filled('variant_id')) {
            $items = [['variant_id' => $request->variant_id, 'quantity' => 1]];
        }

        return is_array($items) ? $items : [];
    }

    /**
     * Checkout page
     */
    public function checkout(Request $request)
    {
        $variant = ProductVariant::with('product')->findOrFail($request->variant_id);
        $finalPrice = $this->getFinalVariantPrice($variant);
        $stripeKey = Setting::get('stripe_key', config('services.stripe.secret'));
        $deliveryStandard = $this->shippingFee('standard');
        $deliveryExpress = $this->shippingFee('express');
        $freeDeliveryThreshold = $this->freeDeliveryThreshold();

        return view('shop.checkout', compact('variant', 'finalPrice', 'stripeKey', 'deliveryStandard', 'deliveryExpress', 'freeDeliveryThreshold'));
    }

    /**
     * Process payment and create order
     */
    public function processCheckout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'variant_id' => 'nullable|exists:product_variants,id',
            'items' => 'nullable|array',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'county' => 'nullable|string|max:100',
            'postcode' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'google_place_id' => 'nullable|string',
            'google_place_name' => 'nullable|string',
            'payment_intent_id' => 'required|string',
            'is_dropship' => 'nullable|boolean',
            'billing_name' => 'nullable|string|max:255',
            'billing_email' => 'nullable|email|max:255',
            'reseller_reference' => 'nullable|string|max:255',
            'shipping_method' => 'nullable|string|in:standard,express',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $items = $this->itemsFromRequest($request);
            $lines = $this->resolveCartLines($items);

            if (empty($lines)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your cart is empty or contains invalid items.',
                ], 422);
            }

            // Stock availability check (a null stock means the variant is untracked).
            foreach ($lines as $line) {
                $stock = $line['variant']->stock;
                if ($stock !== null && $stock < $line['quantity']) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Insufficient stock for ' . $line['variant']->product->name . ' – ' . $line['variant']->name . '.',
                    ], 422);
                }
            }

            [$computedTotal] = $this->priceLines($lines);

            // Add the chosen delivery fee to the authoritative total so the
            // amount paid via Stripe must match items + shipping.
            $shippingMethod = $this->normaliseShippingMethod($request->input('shipping_method'));
            $shippingFee = $this->resolveShippingFee($shippingMethod, $computedTotal);
            $computedTotal = round($computedTotal + $shippingFee, 2);

            // Verify payment with Stripe
            $stripeSecret = Setting::get('stripe_secret', config('services.stripe.secret'));
            $stripe = new \Stripe\StripeClient($stripeSecret);
            $paymentIntent = $stripe->paymentIntents->retrieve($request->payment_intent_id);

            if ($paymentIntent->status !== 'succeeded') {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment was not successful.',
                ], 400);
            }

            // Guard against client-side price tampering: the captured amount must
            // match the server-computed total (allow a 1p rounding tolerance).
            $paidAmount = round(((int) $paymentIntent->amount) / 100, 2);
            if (abs($paidAmount - $computedTotal) > 0.01) {
                \Illuminate\Support\Facades\Log::warning('Shop Checkout amount mismatch', [
                    'paid' => $paidAmount,
                    'computed' => $computedTotal,
                    'payment_intent' => $paymentIntent->id,
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Payment amount did not match the order total. Please contact support.',
                ], 400);
            }

            // Idempotency: never create two orders for the same payment intent.
            $existing = Order::where('stripe_payment_intent_id', $paymentIntent->id)->first();
            if ($existing) {
                return response()->json([
                    'success' => true,
                    'order_number' => $existing->order_number,
                    'redirect' => route('shop.success', ['order' => $existing->order_number]),
                ]);
            }

            // Aggregate Google Business locations across every cart line.
            $googlePlaces = collect($items)
                ->flatMap(fn($it) => (is_array($it) && !empty($it['locationList'])) ? $it['locationList'] : [])
                ->filter(fn($loc) => is_array($loc) && !empty($loc['id']) && !empty($loc['name']))
                ->unique('id')
                ->values()
                ->all();
            $googlePlaces = !empty($googlePlaces) ? $googlePlaces : null;

            $user = auth()->user();
            $isPartner = $user && $user->isApprovedPartner();

            // Dropship is now an explicit checkout choice, not derived from the
            // account tier. It is only honoured for approved partner accounts.
            $isDropship = $isPartner && $request->boolean('is_dropship');

            // Record the purchasing partner's billing info whenever an approved
            // partner places the order, regardless of dropship vs ship-to-self.
            $billingDetails = null;
            if ($isPartner) {
                $billingDetails = [
                    'partner_name' => $user->name,
                    'partner_email' => $user->email,
                    'partner_company' => $user->company_name,
                    'partner_type' => $user->partner_type_label,
                    'vat_number' => $user->vat_number,
                    'billing_name' => $request->input('billing_name') ?: $user->name,
                    'billing_email' => $request->input('billing_email') ?: $user->email,
                    'reseller_reference' => $request->input('reseller_reference'),
                    'is_dropship' => $isDropship,
                ];
            }

            // Create the order and its lines atomically, decrementing stock per line.
            $order = null;
            \Illuminate\Support\Facades\DB::transaction(function () use (
                $request, $lines, $computedTotal, $billingDetails, $googlePlaces,
                $isDropship, $user, $shippingMethod, $shippingFee, &$order
            ) {
                $order = Order::create([
                    'order_number' => Order::generateOrderNumber(),
                    'user_id' => $user?->id,
                    'customer_name' => $request->customer_name,
                    'customer_email' => $request->customer_email,
                    'customer_phone' => $request->customer_phone,
                    'shipping_address' => [
                        'line1' => $request->address_line1,
                        'line2' => $request->address_line2,
                        'city' => $request->city,
                        'county' => $request->county,
                        'postcode' => $request->postcode,
                        'country' => $request->country,
                    ],
                    'billing_details' => $billingDetails,
                    'google_place_id' => $request->google_place_id,
                    'google_place_name' => $request->google_place_name,
                    'google_places' => $googlePlaces,
                    'stripe_payment_intent_id' => $request->payment_intent_id,
                    'status' => 'paid',
                    'total' => $computedTotal,
                    'shipping_method' => $shippingMethod,
                    'shipping_fee' => $shippingFee,
                    'currency' => 'gbp',
                    'is_dropship' => $isDropship,
                    'partner_role' => $user?->partner_type,
                ]);

                foreach ($lines as $line) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_variant_id' => $line['variant']->id,
                        'product_name' => $line['variant']->product->name,
                        'variant_name' => $line['custom_name'] ?? $line['variant']->name,
                        'quantity' => $line['quantity'],
                        'unit_price' => $line['unit_price'],
                        'total' => round($line['unit_price'] * $line['quantity'], 2),
                        'locations' => !empty($line['locations']) ? $line['locations'] : null,
                        'custom_logo_path' => $line['custom_logo_path'] ?? null,
                    ]);

                    if ($line['variant']->stock !== null) {
                        $line['variant']->decrement('stock', $line['quantity']);
                    }
                }
            });

            // Analytics: record the completed purchase.
            if ($order) {
                \App\Services\Tracking::event(\App\Services\Tracking::PURCHASE, $request, [
                    'order_id' => $order->id,
                    'value' => (float) $order->total,
                ]);
            }

            // Mark the matching abandoned-cart row as converted (recovered) so it
            // is excluded from the abandoned totals and recovery is measurable.
            if ($order) {
                try {
                    \App\Models\AbandonedCart::where('payment_intent_id', $paymentIntent->id)
                        ->update([
                            'status' => \App\Models\AbandonedCart::STATUS_CONVERTED,
                            'converted_order_id' => $order->id,
                            'recovered_at' => now(),
                            'customer_name' => $order->customer_name,
                            'customer_email' => $order->customer_email,
                        ]);
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::warning('Abandoned cart convert failed: ' . $e->getMessage());
                }
            }

            // Checkout finished successfully — drop the re-used PaymentIntent and
            // the checkout-started guard so the next order starts a fresh intent
            // (and is not mistaken for an abandoned cart).
            if ($request->hasSession()) {
                $request->session()->forget(['rb_payment_intent_id', 'rb_checkout_started_logged']);
            }

            // Send Emails
            try {
                // Pre-load items to avoid any lazy-loading issues in mail templates
                $order->load('items');

                // 1. Send confirmation to customer / purchaser
                $recipientEmail = $order->customer_email;
                if ($user && $user->email) {
                    $recipientEmail = $user->email;
                }

                if ($recipientEmail) {
                    \Illuminate\Support\Facades\Mail::to($recipientEmail)
                        ->send(new \App\Mail\OrderConfirmationMail($order));
                }

                // 2. Send notification to all admins
                $admins = \App\Models\User::where('role', 'admin')->get();
                if ($admins->isEmpty()) {
                    \Illuminate\Support\Facades\Mail::to('enovtec1002@gmail.com')
                        ->send(new \App\Mail\NewOrderAdminNotificationMail($order));
                } else {
                    foreach ($admins as $admin) {
                        \Illuminate\Support\Facades\Mail::to($admin->email)
                            ->send(new \App\Mail\NewOrderAdminNotificationMail($order));
                    }
                }
            } catch (\Throwable $mailEx) {
                \Illuminate\Support\Facades\Log::error('Shop Order Email Error: ' . $mailEx->getMessage(), [
                    'order_id' => $order->id,
                    'exception' => $mailEx
                ]);
            }

            return response()->json([
                'success' => true,
                'order_number' => $order->order_number,
                'redirect' => route('shop.success', ['order' => $order->order_number]),
            ]);

        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Shop Checkout Fatal Error: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during checkout processing. Please contact support.',
                'debug_message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Create Stripe PaymentIntent for checkout
     */
    public function createPaymentIntent(Request $request)
    {
        $lines = $this->resolveCartLines($this->itemsFromRequest($request));

        if (empty($lines)) {
            return response()->json(['message' => 'Your cart is empty or contains invalid items.'], 422);
        }

        [$itemsTotal, $breakdown] = $this->priceLines($lines);

        $shippingMethod = $this->normaliseShippingMethod($request->input('shipping_method'));
        $shippingFee = $this->resolveShippingFee($shippingMethod, $itemsTotal);
        $total = round($itemsTotal + $shippingFee, 2);

        // Analytics: record a "checkout attempt" once per session (this endpoint
        // is re-called when the delivery method changes, so we de-dupe).
        if ($request->hasSession() && !$request->session()->get('rb_checkout_started_logged')) {
            \App\Services\Tracking::event(\App\Services\Tracking::CHECKOUT_STARTED, $request, ['value' => $total]);
            $request->session()->put('rb_checkout_started_logged', true);
        }

        try {
            $stripeSecret = Setting::get('stripe_secret', config('services.stripe.secret'));
            $stripe = new \Stripe\StripeClient($stripeSecret);

            $itemCount = count($breakdown);
            $amount = (int) round($total * 100);

            // Human-readable cart summary stored on the PaymentIntent so an
            // abandoned (incomplete) intent is identifiable in the Stripe
            // dashboard and can be reconciled with our abandoned-cart records.
            $cartSummary = collect($breakdown)
                ->map(fn ($b) => $b['quantity'] . '× ' . $b['name'])
                ->implode(', ');
            $metadata = [
                'items_total' => number_format($itemsTotal, 2, '.', ''),
                'shipping_fee' => number_format($shippingFee, 2, '.', ''),
                'shipping_method' => $shippingMethod,
                'item_count' => (string) $itemCount,
                'cart' => \Illuminate\Support\Str::limit($cartSummary, 480, ''),
            ];

            // Re-use one PaymentIntent per checkout session instead of creating a
            // new one on every page load / delivery-method change. This stops the
            // dashboard filling up with duplicate "Incomplete" intents; the single
            // remaining incomplete intent represents a genuinely abandoned cart.
            $paymentIntent = null;
            $existingId = $request->hasSession() ? $request->session()->get('rb_payment_intent_id') : null;
            if ($existingId) {
                try {
                    $existing = $stripe->paymentIntents->retrieve($existingId);
                    if (in_array($existing->status, ['requires_payment_method', 'requires_confirmation', 'requires_action'], true)) {
                        $paymentIntent = $stripe->paymentIntents->update($existingId, [
                            'amount' => $amount,
                            'description' => 'Shop order (' . $itemCount . ' line' . ($itemCount > 1 ? 's' : '') . ')',
                            'metadata' => $metadata,
                        ]);
                    }
                } catch (\Throwable $e) {
                    // Stored id is stale/invalid — fall through and create a fresh one.
                    $paymentIntent = null;
                }
            }

            if (!$paymentIntent) {
                $paymentIntent = $stripe->paymentIntents->create([
                    'amount' => $amount,
                    'currency' => 'gbp',
                    'description' => 'Shop order (' . $itemCount . ' line' . ($itemCount > 1 ? 's' : '') . ')',
                    'payment_method_types' => ['card'],
                    'metadata' => $metadata,
                ]);
                if ($request->hasSession()) {
                    $request->session()->put('rb_payment_intent_id', $paymentIntent->id);
                }
            }

            // Record / refresh the abandoned-cart row for this checkout. It stays
            // "abandoned" until the order is placed (processCheckout marks it
            // "converted"). Never let tracking break the checkout response.
            try {
                \App\Models\AbandonedCart::updateOrCreate(
                    ['payment_intent_id' => $paymentIntent->id],
                    [
                        'session_id' => $request->hasSession() ? $request->session()->getId() : null,
                        'visitor_id' => \App\Services\Tracking::visitorId($request),
                        'user_id' => auth()->id(),
                        'customer_name' => $request->input('customer_name') ?: null,
                        'customer_email' => $request->input('customer_email') ?: null,
                        'items' => $breakdown,
                        'item_count' => $itemCount,
                        'items_total' => $itemsTotal,
                        'shipping_fee' => $shippingFee,
                        'total' => $total,
                        'shipping_method' => $shippingMethod,
                        'status' => \App\Models\AbandonedCart::STATUS_ABANDONED,
                    ]
                );
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Abandoned cart record failed: ' . $e->getMessage());
            }

            return response()->json([
                'client_secret' => $paymentIntent->client_secret,
                'amount' => $total,
                'items_total' => $itemsTotal,
                'shipping_fee' => $shippingFee,
                'shipping_method' => $shippingMethod,
                'free_delivery_threshold' => $this->freeDeliveryThreshold(),
                'items' => $breakdown,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Payment error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Sum resolved lines into an authoritative total and a JSON-friendly
     * breakdown for the checkout UI.
     *
     * @return array{0: float, 1: array<int, array<string, mixed>>}
     */
    private function priceLines(array $lines): array
    {
        $total = 0.0;
        $breakdown = [];

        foreach ($lines as $line) {
            $lineTotal = round($line['unit_price'] * $line['quantity'], 2);
            $total += $lineTotal;

            $breakdown[] = [
                'variant_id' => $line['variant']->id,
                'name' => $line['custom_name'] ?? $line['variant']->name,
                'product_name' => $line['variant']->product->name,
                'quantity' => $line['quantity'],
                'unit_price' => $line['unit_price'],
                'line_total' => $lineTotal,
            ];
        }

        return [round($total, 2), $breakdown];
    }

    /**
     * Normalise a posted shipping method to a known value.
     */
    private function normaliseShippingMethod($method): string
    {
        return in_array($method, ['standard', 'express'], true) ? $method : 'standard';
    }

    /**
     * The delivery fee for a given method, read from settings so the admin can
     * change the prices at any time (with sensible fallbacks).
     */
    private function shippingFee(?string $method): float
    {
        $method = $this->normaliseShippingMethod($method);
        $default = $method === 'express' ? 6.90 : 3.90;
        return round((float) Setting::get('delivery_fee_' . $method, $default), 2);
    }

    /**
     * The order subtotal at (or above) which delivery becomes free. 0 disables
     * the free-delivery offer. Admin-configurable via settings.
     */
    private function freeDeliveryThreshold(): float
    {
        return round((float) Setting::get('free_delivery_threshold', 25), 2);
    }

    /**
     * Final delivery fee for a method, applying the free-delivery threshold
     * against the items subtotal.
     */
    private function resolveShippingFee(?string $method, float $itemsTotal): float
    {
        $method = $this->normaliseShippingMethod($method);
        $threshold = $this->freeDeliveryThreshold();
        if ($method === 'standard' && $threshold > 0 && $itemsTotal >= $threshold) {
            return 0.0;
        }
        return $this->shippingFee($method);
    }

    /**
     * Order success page
     */
    public function success(Request $request)
    {
        $order = Order::where('order_number', $request->order)->with('items')->firstOrFail();

        return view('shop.success', compact('order'));
    }

    /**
     * Public Order Tracking Page
     */
    public function trackOrder(Request $request)
    {
        $order = null;
        $searched = false;
        $query = trim($request->input('q', $request->input('order', '')));

        if (!empty($query)) {
            $searched = true;
            $order = Order::where('order_number', $query)
                ->orWhere('order_number', 'LIKE', "%{$query}%")
                ->orWhere('customer_email', $query)
                ->orWhere('tracking_number', $query)
                ->with('items')
                ->latest()
                ->first();
        }

        return view('shop.track', compact('order', 'searched', 'query'));
    }

    /**
     * Logged-in user's Order History List
     */
    public function userOrders(Request $request)
    {
        $user = auth()->user();
        $orders = Order::where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('customer_email', $user->email);
            })
            ->with(['items.variant.product'])
            ->latest()
            ->paginate(10);

        return view('user.orders.index', compact('orders'));
    }

    /**
     * Logged-in user's Single Order View
     */
    public function userOrderShow(Request $request, $order_number)
    {
        $user = auth()->user();
        $order = Order::where('order_number', $order_number)
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('customer_email', $user->email);
            })
            ->with(['items.variant.product'])
            ->firstOrFail();

        return view('user.orders.show', compact('order'));
    }
}
