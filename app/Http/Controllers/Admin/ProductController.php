<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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
            'variants.*.price' => 'required|numeric|min:0',
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
            $variant = new ProductVariant([
                'name' => $variantData['name'],
                'quantity' => $variantData['quantity'],
                'price' => $variantData['price'],
                'original_price' => $variantData['original_price'],
                'discount_percent' => $variantData['discount_percent'] ?? 0,
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
            'variants.*.price' => 'required|numeric|min:0',
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

            $variant->fill([
                'name' => $variantData['name'],
                'quantity' => $variantData['quantity'],
                'price' => $variantData['price'],
                'original_price' => $variantData['original_price'],
                'discount_percent' => $variantData['discount_percent'] ?? 0,
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
        $order->load('items');
        return view('admin.products.order_edit', compact('order'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,shipped,completed,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Order status updated!');
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

    public function destroyOrder(Order $order)
    {
        $order->items()->delete();
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully!');
    }
}
