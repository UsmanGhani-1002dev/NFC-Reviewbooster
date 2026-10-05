<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderCustomEmailMail;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('variants')->orderBy('sort_order')->get();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.form', ['product' => null]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'gallery' => 'nullable|array',
            'gallery.*' => 'nullable|image|max:2048',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
            'features' => 'nullable|array',
            'variants' => 'required|array|min:1',
            'variants.*.name' => 'required|string|max:255',
            'variants.*.quantity' => 'required|integer|min:1',
            'variants.*.price' => 'nullable|numeric|min:0',
            'variants.*.original_price' => 'required|numeric|min:0',
            'variants.*.discount_percent' => 'nullable|integer|min:0|max:100',
            'variants.*.is_best_value' => 'nullable|boolean',
            'variants.*.is_most_popular' => 'nullable|boolean',
            'variants.*.stock' => 'nullable|integer|min:0',
            'variants.*.image' => 'nullable|image|max:2048',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'seo_keywords' => 'nullable|string|max:255',
            'image_alt' => 'nullable|string|max:255',
        ]);

        $data = $request->except(['image', 'gallery', 'variants', 'features']);
        $data['slug'] = Str::slug($request->name);
        $data['is_active'] = $request->has('is_active') ? 1 : 0;
        $data['sort_order'] = $request->sort_order ?? 0;

        // Handle features
        if ($request->features) {
            $data['features'] = array_values(array_filter($request->features));
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $data['image'] = $path;
        }

        // Handle gallery uploads
        if ($request->hasFile('gallery')) {
            $galleryPaths = [];
            foreach ($request->file('gallery') as $file) {
                $galleryPaths[] = $file->store('products/gallery', 'public');
            }
            $data['gallery'] = $galleryPaths;
        }

        // Ensure slug is unique
        $originalSlug = $data['slug'];
        $counter = 1;
        while (Product::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $originalSlug . '-' . $counter++;
        }

        $product = Product::create($data);

        // Create variants
        foreach ($request->variants as $index => $variantData) {
            $originalPrice = (float) $variantData['original_price'];
            $price = (isset($variantData['price']) && $variantData['price'] !== '' && $variantData['price'] !== null)
                ? (float) $variantData['price']
                : $originalPrice;

            $discountPercent = 0;
            if ($originalPrice > 0 && $price < $originalPrice) {
                $discountPercent = (int) round((($originalPrice - $price) / $originalPrice) * 100);
            } elseif (isset($variantData['discount_percent']) && is_numeric($variantData['discount_percent'])) {
                $discountPercent = (int) $variantData['discount_percent'];
            }

            $variant = new ProductVariant([
                'name' => $variantData['name'],
                'quantity' => $variantData['quantity'],
                'price' => $price,
                'original_price' => $originalPrice,
                'discount_percent' => $discountPercent,
                'is_best_value' => isset($variantData['is_best_value']) ? 1 : 0,
                'is_most_popular' => isset($variantData['is_most_popular']) ? 1 : 0,
                'stock' => $variantData['stock'] ?? 50,
                'sort_order' => $index,
            ]);

            // Handle variant image
            if ($request->hasFile("variants.{$index}.image")) {
                $variantPath = $request->file("variants.{$index}.image")->store('variants', 'public');
                $variant->image = $variantPath;
            }

            $product->variants()->save($variant);
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully!');
    }

    public function edit(Product $product)
    {
        $product->load('variants');
        return view('admin.products.form', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'gallery' => 'nullable|array',
            'gallery.*' => 'nullable|image|max:2048',
            'existing_gallery' => 'nullable|array',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
            'features' => 'nullable|array',
            'variants' => 'required|array|min:1',
            'variants.*.id' => 'nullable|exists:product_variants,id',
            'variants.*.name' => 'required|string|max:255',
            'variants.*.quantity' => 'required|integer|min:1',
            'variants.*.price' => 'nullable|numeric|min:0',
            'variants.*.original_price' => 'required|numeric|min:0',
            'variants.*.discount_percent' => 'nullable|integer|min:0|max:100',
            'variants.*.is_best_value' => 'nullable|boolean',
            'variants.*.is_most_popular' => 'nullable|boolean',
            'variants.*.stock' => 'nullable|integer|min:0',
            'variants.*.image' => 'nullable|image|max:2048',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'seo_keywords' => 'nullable|string|max:255',
            'image_alt' => 'nullable|string|max:255',
        ]);

        $data = $request->except(['image', 'gallery', 'existing_gallery', 'variants', 'features', '_method', '_token']);
        $data['is_active'] = $request->has('is_active') ? 1 : 0;
        $data['sort_order'] = $request->sort_order ?? 0;

        // Handle features
        if ($request->features) {
            $data['features'] = array_values(array_filter($request->features));
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $path = $request->file('image')->store('products', 'public');
            $data['image'] = $path;
        }

        // Handle gallery
        $currentGallery = $product->gallery ?? [];
        $existingGallery = $request->existing_gallery ?? []; // Paths to keep
        
        // Delete images that are no longer in existing_gallery
        foreach ($currentGallery as $path) {
            if (!in_array($path, $existingGallery)) {
                Storage::disk('public')->delete($path);
            }
        }
        
        $finalGallery = $existingGallery;
        
        // Upload new images
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $finalGallery[] = $file->store('products/gallery', 'public');
            }
        }
        $data['gallery'] = $finalGallery;

        // Update slug if name changed
        if ($request->name !== $product->name) {
            $slug = Str::slug($request->name);
            $originalSlug = $slug;
            $counter = 1;
            while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                $slug = $originalSlug . '-' . $counter++;
            }
            $data['slug'] = $slug;

            // Create automatic 301 redirect for slug change
            \App\Models\Redirect::updateOrCreate(
                ['source_url' => '/shop/' . $product->slug],
                [
                    'target_url' => route('shop.show', ['product' => $slug]),
                    'status_code' => 301
                ]
            );
        }

        $product->update($data);

        // Update variants
        $existingVariantIds = $product->variants()->pluck('id')->toArray();
        $updatedVariantIds = [];

        foreach ($request->variants as $index => $variantData) {
            $variant = null;
            if (isset($variantData['id']) && in_array($variantData['id'], $existingVariantIds)) {
                $variant = ProductVariant::find($variantData['id']);
                $updatedVariantIds[] = $variant->id;
            } else {
                $variant = new ProductVariant(['product_id' => $product->id]);
            }

            $originalPrice = (float) $variantData['original_price'];
            $price = (isset($variantData['price']) && $variantData['price'] !== '' && $variantData['price'] !== null)
                ? (float) $variantData['price']
                : $originalPrice;

            $discountPercent = 0;
            if ($originalPrice > 0 && $price < $originalPrice) {
                $discountPercent = (int) round((($originalPrice - $price) / $originalPrice) * 100);
            } elseif (isset($variantData['discount_percent']) && is_numeric($variantData['discount_percent'])) {
                $discountPercent = (int) $variantData['discount_percent'];
            }

            $variant->fill([
                'name' => $variantData['name'],
                'quantity' => $variantData['quantity'],
                'price' => $price,
                'original_price' => $originalPrice,
                'discount_percent' => $discountPercent,
                'is_best_value' => isset($variantData['is_best_value']) ? 1 : 0,
                'is_most_popular' => isset($variantData['is_most_popular']) ? 1 : 0,
                'stock' => $variantData['stock'] ?? 50,
                'sort_order' => $index,
            ]);

            // Handle variant image
            if ($request->hasFile("variants.{$index}.image")) {
                // Delete old image if exists
                if ($variant->image) {
                    Storage::disk('public')->delete($variant->image);
                }
                $variantPath = $request->file("variants.{$index}.image")->store('variants', 'public');
                $variant->image = $variantPath;
            }

            $variant->save();
        }

        // Delete variants that were removed
        $variantsToDelete = array_diff($existingVariantIds, $updatedVariantIds);
        foreach ($variantsToDelete as $id) {
            $v = ProductVariant::find($id);
            if ($v->image) {
                Storage::disk('public')->delete($v->image);
            }
            $v->delete();
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }

    /**
     * Orders management
     */
    public function orders(Request $request)
    {
        $query = Order::with('items')->latest();

        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where('order_number', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('customer_name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('customer_email', 'LIKE', "%{$searchTerm}%");
        }

        $orders = $query->paginate(20)->withQueryString();
        return view('admin.products.orders', compact('orders'));
    }

    public function editOrder(Order $order)
    {
        $order->load('items.variant.product');
        return view('admin.products.order_edit', compact('order'));
    }

    /**
     * Printable order details / packing slip (thank-you note, items, address, warranty).
     */
    public function printOrder(Order $order)
    {
        $order->load('items.variant.product');
        return view('admin.products.order_print', compact('order'));
    }

    /**
     * Printable shipping address label.
     */
    public function printLabel(Order $order)
    {
        return view('admin.products.order_label', compact('order'));
    }

    /**
     * Printable review-card / stand artwork (Google Card & Google Stand only).
     * Adds the business/company name at the top and, for stands, a scannable
     * Google-review QR code. Designed to be downloaded/printed directly.
     */
    public function printDesign(Order $order)
    {
        $order->load('items.variant.product');

        // Build a genuine Google review link from an id/name pair (mirrors the
        // logic used on the order edit page). Returns null for placeholders.
        $reviewLinkFor = function ($id, $name) {
            $id = trim((string) $id);
            $name = trim((string) $name);
            foreach ([$id, $name] as $val) {
                if ($val !== '' && (str_starts_with($val, 'http://') || str_starts_with($val, 'https://'))) {
                    return $val;
                }
            }
            if ($id === '' || str_starts_with($id, 'loc-') || str_starts_with($id, 'skip-') || str_starts_with($id, 'direct-')) {
                return null;
            }
            if (strlen($id) < 20) {
                return null;
            }
            return 'https://search.google.com/local/writereview?placeid=' . urlencode($id);
        };

        // Resolve a usable company/business name: reject URLs and "skip for now"
        // placeholders, and trim a trailing " - <address>" so only the company
        // name is shown at the top of the artwork.
        $cleanName = function ($name) {
            $name = trim((string) $name);
            if ($name === '' || str_starts_with($name, 'http')) {
                return null;
            }
            if (preg_match('/skip\s*for\s*now|link\s*later/i', $name)) {
                return null;
            }
            foreach ([' - ', ' – ', ' — '] as $sep) {
                if (str_contains($name, $sep)) {
                    [$head, $tail] = array_pad(explode($sep, $name, 2), 2, '');
                    $head = trim($head);
                    // Only strip the tail when it looks like an address (has a number).
                    if ($head !== '' && preg_match('/\d/', $tail)) {
                        $name = $head;
                        break;
                    }
                }
            }
            return $name;
        };

        $orderBusiness = $cleanName($order->google_place_name);
        $orderReviewUrl = $reviewLinkFor($order->google_place_id, $order->google_place_name);

        $designs = [];
        foreach ($order->items as $item) {
            $haystack = strtolower(trim(($item->product_name ?? '') . ' ' . ($item->variant_name ?? '')));
            if (str_contains($haystack, 'stand')) {
                $type = 'stand';
            } elseif (str_contains($haystack, 'card')) {
                $type = 'card';
            } else {
                continue; // keyrings / other products have no printable artwork
            }

            // Prefer the item's own linked location, else fall back to the order.
            $business = null;
            $reviewUrl = null;
            if (is_array($item->locations)) {
                foreach ($item->locations as $loc) {
                    if (!$reviewUrl) {
                        $reviewUrl = $reviewLinkFor($loc['id'] ?? '', $loc['name'] ?? '');
                    }
                    if (!$business) {
                        $business = $cleanName($loc['name'] ?? '');
                    }
                }
            }

            $designs[] = [
                'type' => $type,
                'dark' => $type === 'stand' && str_contains($haystack, 'black'),
                'business' => $business ?: $orderBusiness ?: 'Your Business',
                'review_url' => $reviewUrl ?: $orderReviewUrl,
                'variant' => $item->variant_name,
                'product' => $item->product_name,
                'qty' => (int) $item->quantity,
            ];
        }

        return view('admin.products.order_design', compact('order', 'designs'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,shipped,completed,cancelled',
            'tracking_number' => 'nullable|string|max:255',
            'carrier' => 'nullable|string|max:255',
            'send_notification' => 'nullable|boolean',
        ]);

        $previousStatus = $order->status;
        $previousTracking = $order->tracking_number;

        $trackingNumber = $request->filled('tracking_number') ? trim($request->tracking_number) : null;
        $carrier = $request->filled('carrier') ? trim($request->carrier) : 'Royal Mail';
        $sendNotification = $request->has('send_notification') ? (bool)$request->send_notification : true;

        $updateData = [
            'status' => $request->status,
            'tracking_number' => $trackingNumber,
            'carrier' => $carrier,
        ];

        if ($request->status === 'shipped' && !$order->shipped_at) {
            $updateData['shipped_at'] = now();
        }

        $order->update($updateData);

        // Check if we should send a tracking notification email
        $mailSent = false;
        $mailError = null;

        if ($sendNotification && !empty($trackingNumber) && in_array($request->status, ['shipped', 'completed'])) {
            try {
                $recipientEmail = $order->customer_email;
                if ($recipientEmail) {
                    \Illuminate\Support\Facades\Mail::to($recipientEmail)
                        ->send(new \App\Mail\OrderShippedMail($order));

                    $order->update(['tracking_notified_at' => now()]);
                    $mailSent = true;
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Order Shipping Email Failed: ' . $e->getMessage(), [
                    'order_id' => $order->id,
                    'email' => $order->customer_email,
                ]);
                $mailError = $e->getMessage();
            }
        }

        $msg = 'Order status updated successfully!';
        if ($mailSent) {
            $msg .= ' Shipping confirmation & Royal Mail tracking email sent to customer (' . $order->customer_email . ').';
        } elseif ($mailError) {
            $msg .= ' (Note: Could not send email notification: ' . $mailError . ')';
        }

        return redirect()->back()->with('success', $msg);
    }

    public function updateOrderLocation(Request $request, Order $order)
    {
        $request->validate([
            'google_place_id' => 'required|string',
            'google_place_name' => 'required|string',
            'google_places' => 'nullable|array',
        ]);

        $order->update([
            'google_place_id' => $request->google_place_id,
            'google_place_name' => $request->google_place_name,
            'google_places' => $request->google_places,
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Order location updated!']);
        }

        return redirect()->back()->with('success', 'Order location updated!');
    }

    /**
     * Set / change the Google review link for a single order line. This is what
     * enables (or replaces) that item's QR code — e.g. for items the customer
     * left as "Skip for now" at checkout.
     */
    public function updateOrderItemLocation(Request $request, Order $order, OrderItem $item)
    {
        if ($item->order_id !== $order->id) {
            abort(404);
        }

        $request->validate([
            'review_link' => 'required|string|max:2000',
            'place_name' => 'nullable|string|max:255',
            'index' => 'nullable|integer|min:0',
        ]);

        $link = trim($request->review_link);
        $name = trim($request->place_name ?: $link);
        $entry = ['id' => $link, 'name' => $name];

        // Stored the same shape as checkout locations: {id, name}. An item can
        // have several linked locations, so we update only the given index and
        // leave the others untouched (append when no index is supplied).
        $locations = is_array($item->locations) ? array_values($item->locations) : [];
        $index = $request->input('index');
        if ($index !== null && isset($locations[$index])) {
            $locations[$index] = $entry;
        } else {
            $locations[] = $entry;
        }

        $item->locations = $locations;
        $item->save();

        // Keep the order-level "Business Location / Review Link" box (used for NFC
        // selection) in sync with the items' locations, so editing an item is
        // reflected there too.
        $order->load('items');
        $aggregated = [];
        foreach ($order->items as $orderItem) {
            foreach (($orderItem->locations ?? []) as $loc) {
                if (!empty($loc['id']) && !empty($loc['name'])) {
                    $aggregated[$loc['id']] = ['id' => $loc['id'], 'name' => $loc['name']];
                }
            }
        }
        $aggregated = array_values($aggregated);
        $order->google_places = $aggregated;

        // If the currently selected order location is gone, point it at the first.
        $ids = array_column($aggregated, 'id');
        if (empty($order->google_place_id) || !in_array($order->google_place_id, $ids, true)) {
            $order->google_place_id = $aggregated[0]['id'] ?? null;
            $order->google_place_name = $aggregated[0]['name'] ?? null;
        }
        $order->save();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Item review link updated!']);
        }

        return redirect()->back()->with('success', 'Item review link updated!');
    }

    /**
     * Send custom email to customer regarding their order.
     */
    public function sendCustomerEmail(Request $request, Order $order)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'include_summary' => 'nullable|boolean',
        ]);

        if (empty($order->customer_email)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Customer email address is missing for this order.'], 422);
            }
            return back()->with('error', 'Customer email address is missing for this order.');
        }

        $order->load('items.variant.product');

        try {
            Mail::to($order->customer_email)->send(new OrderCustomEmailMail(
                order: $order,
                subjectText: trim($request->subject),
                messageText: trim($request->message),
                includeSummary: $request->has('include_summary') ? (bool)$request->include_summary : true
            ));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send order custom email: ' . $e->getMessage());
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Failed to send email: ' . $e->getMessage()], 500);
            }
            return back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Email sent successfully to ' . $order->customer_email]);
        }

        return redirect()->back()->with('success', 'Email sent successfully to ' . $order->customer_email);
    }

    public function destroyOrder(Order $order)
    {
        $order->items()->delete();
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully!');
    }
}
