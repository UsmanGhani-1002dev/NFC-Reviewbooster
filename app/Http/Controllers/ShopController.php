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
            ->with('variants')
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

        $product->load('variants');
        $variant_id = $request->query('variant');

        $relatedProducts = Product::where('id', '!=', $product->id)
            ->where('is_active', true)
            ->with('variants')
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('shop.show', compact('product', 'variant_id', 'relatedProducts'));
    }

    /**
     * Checkout page
     */
    public function checkout(Request $request)
    {
        $variant = ProductVariant::with('product')->findOrFail($request->variant_id);
        $stripeKey = Setting::get('stripe_key', config('services.stripe.key'));

        return view('shop.checkout', compact('variant', 'stripeKey'));
    }

    /**
     * Process payment and create order
     */
    public function processCheckout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'variant_id' => 'required|exists:product_variants,id',
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
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $variant = ProductVariant::with('product')->findOrFail($request->variant_id);

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

            // Extract multiple locations if available in the items array
            $items = $request->input('items', []);
            $googlePlaces = null;
            if (!empty($items) && isset($items[0]['locationList'])) {
                // Filter out empty or duplicate locations
                $googlePlaces = collect($items[0]['locationList'])
                    ->filter(fn($loc) => !empty($loc['id']) && !empty($loc['name']))
                    ->unique('id')
                    ->values()
                    ->all();
            }

            // Create the order
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
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
                'google_place_id' => $request->google_place_id,
                'google_place_name' => $request->google_place_name,
                'google_places' => $googlePlaces,
                'stripe_payment_intent_id' => $request->payment_intent_id,
                'status' => 'paid',
                'total' => $variant->price,
                'currency' => 'gbp',
            ]);

            // Create order item
            OrderItem::create([
                'order_id' => $order->id,
                'product_variant_id' => $variant->id,
                'product_name' => $variant->product->name,
                'variant_name' => $variant->name,
                'quantity' => 1,
                'unit_price' => $variant->price,
                'total' => $variant->price,
            ]);

            // Decrease stock
            $variant->decrement('stock');

            // Send Emails
            try {
                // Pre-load items to avoid any lazy-loading issues in mail templates
                $order->load('items');

                // 1. Send confirmation to customer
                if ($order->customer_email) {
                    \Illuminate\Support\Facades\Mail::to($order->customer_email)
                        ->send(new \App\Mail\OrderConfirmationMail($order));
                }

                // 2. Send notification to all admins
                $admins = \App\Models\User::where('role', 'admin')->get();
                if ($admins->isEmpty()) {
                    // Fallback to a fixed email if no admin role users found
                    \Illuminate\Support\Facades\Mail::to('enovtec1002@gmail.com')
                        ->send(new \App\Mail\NewOrderAdminNotificationMail($order));
                } else {
                    foreach ($admins as $admin) {
                        \Illuminate\Support\Facades\Mail::to($admin->email)
                            ->send(new \App\Mail\NewOrderAdminNotificationMail($order));
                    }
                }
            } catch (\Throwable $mailEx) {
                // We don't want to fail the whole order if emails fail
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
                'debug_message' => $e->getMessage(), // Helpful for debugging on live
            ], 500);
        }
    }

    /**
     * Create Stripe PaymentIntent for checkout
     */
    public function createPaymentIntent(Request $request)
    {
        $variant = ProductVariant::findOrFail($request->variant_id);

        try {
            $stripeSecret = Setting::get('stripe_secret', config('services.stripe.secret'));
            $stripe = new \Stripe\StripeClient($stripeSecret);

            $paymentIntent = $stripe->paymentIntents->create([
                'amount' => (int)($variant->price * 100),
                'currency' => 'gbp',
                'description' => 'Purchase: ' . $variant->name,
                'payment_method_types' => ['card'],
            ]);

            return response()->json([
                'client_secret' => $paymentIntent->client_secret,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Payment error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Order success page
     */
    public function success(Request $request)
    {
        $order = Order::where('order_number', $request->order)->with('items')->firstOrFail();

        return view('shop.success', compact('order'));
    }
}
