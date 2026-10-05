<?php

namespace App\Http\Controllers;

use App\Models\ProductVariant;
use App\Services\Tracking;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    /**
     * Public analytics beacon. Currently only accepts add_to_cart events from
     * the shop front-end (checkout_started / purchase are recorded server-side).
     */
    public function event(Request $request)
    {
        $type = $request->input('type');
        if ($type !== 'add_to_cart') {
            return response()->json(['ok' => false], 204);
        }

        $variantId = (int) $request->input('variant_id');
        $productId = $request->input('product_id');
        $quantity = (int) ($request->input('quantity') ?? 1);
        $value = $request->input('value');

        // Resolve product id from the variant when possible (authoritative).
        if (!$productId && $variantId) {
            $productId = ProductVariant::where('id', $variantId)->value('product_id');
        }

        Tracking::event(Tracking::ADD_TO_CART, $request, [
            'product_id' => $productId ?: null,
            'product_variant_id' => $variantId ?: null,
            'quantity' => max(1, $quantity),
            'value' => is_numeric($value) ? round((float) $value, 2) : null,
        ]);

        return response()->json(['ok' => true]);
    }
}
