@extends('layouts.app')

@section('content')
<div class="p-4 sm:p-6 bg-white rounded-xl shadow-xl" x-data="productForm()">
    <div class="flex items-center gap-4 mb-10">
        <a href="{{ route('admin.products.index') }}" class="p-2 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h2 class="text-3xl font-bold text-gray-900 tracking-tight">{{ $product ? 'Edit Product' : 'Create Product' }}</h2>
            <p class="text-sm font-medium text-gray-400 mt-1">{{ $product ? 'Update product details and variants' : 'Add a new product to the shop' }}</p>
        </div>
    </div>

    @if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-xl">
        <ul class="list-disc list-inside space-y-1 text-sm">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ $product ? route('admin.products.update', $product) : route('admin.products.store') }}" enctype="multipart/form-data">
        @csrf
        @if($product) @method('PUT') @endif

        <!-- Basic Info -->
        <div class="bg-gray-50 rounded-2xl p-6 mb-6 border border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Basic Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Product Name *</label>
                    <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-400 focus:border-green-400" placeholder="e.g. Rating Card" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subtitle</label>
                    <input type="text" name="subtitle" value="{{ old('subtitle', $product->subtitle ?? '') }}" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-400 focus:border-green-400" placeholder="e.g. NFC Review Card">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="3" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-400 focus:border-green-400" placeholder="Product description">{{ old('description', $product->description ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $product->sort_order ?? 0) }}" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-400 focus:border-green-400">
                </div>
            </div>
        </div>

        <!-- Image -->
        <div class="bg-gray-50 rounded-2xl p-6 mb-6 border border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Main Product Image</h3>
            @if($product && $product->image)
                <div class="mb-4">
                    <img src="{{ asset('storage/' . $product->image) }}" class="w-32 h-32 rounded-xl object-cover" alt="Current image">
                </div>
            @endif
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Upload Image</label>
                    <input type="file" name="image" accept="image/*" class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Image Alt Text (SEO)</label>
                    <input type="text" name="image_alt" value="{{ old('image_alt', $product->image_alt ?? '') }}" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-400 focus:border-green-400" placeholder="e.g. NFC Google Review Card for Restaurants">
                    <p class="text-[10px] text-gray-400 mt-1">Describe the image for search engines and accessibility.</p>
                </div>
            </div>
        </div>

        <!-- Gallery -->
        <div class="bg-gray-50 rounded-2xl p-6 mb-6 border border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-2">Product Gallery</h3>
            <p class="text-sm text-gray-400 mb-6">Upload additional images for the product thumbnails</p>
            
            <!-- Existing Gallery Images -->
            <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-4 mb-6" x-show="gallery.length > 0">
                <template x-for="(img, index) in gallery" :key="index">
                    <div class="relative group">
                        <img :src="'/storage/' + img" class="w-full h-32 object-cover rounded-xl border border-gray-200">
                        <input type="hidden" name="existing_gallery[]" :value="img">
                        <button type="button" @click="gallery.splice(index, 1)" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 shadow-lg opacity-0 group-hover:opacity-100 transition-opacity">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </template>
            </div>

            <div class="relative">
                <input type="file" name="gallery[]" multiple accept="image/*" class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
            </div>
        </div>

        <!-- Features -->
        <div class="bg-gray-50 rounded-2xl p-6 mb-6 border border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Features</h3>
            <div class="space-y-3">
                <template x-for="(feature, index) in features" :key="index">
                    <div class="flex gap-2">
                        <input type="text" :name="'features[' + index + ']'" x-model="features[index]" class="flex-1 border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-400 focus:border-green-400" placeholder="e.g. Works on iPhone & Android">
                        <button type="button" @click="features.splice(index, 1)" class="p-3 bg-red-50 text-red-500 rounded-xl hover:bg-red-100 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </template>
            </div>
            <button type="button" @click="features.push('')" class="mt-3 text-sm text-green-600 font-semibold hover:text-green-700 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Feature
            </button>
        </div>

        <!-- Active Toggle -->
        <div class="bg-gray-50 rounded-2xl p-6 mb-6 border border-gray-100">
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}
                       class="w-5 h-5 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                <span class="font-medium text-gray-700">Product is Active (visible in shop)</span>
            </label>
        </div>

       

        <!-- Variants -->
        <div class="bg-gray-50 rounded-2xl p-6 mb-6 border border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Product Variants</h3>
            <div class="space-y-4">
                <template x-for="(variant, index) in variants" :key="index">
                    <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-sm font-bold text-gray-500">Variant #<span x-text="index + 1"></span></span>
                            <button type="button" @click="variants.length > 1 ? variants.splice(index, 1) : null" x-show="variants.length > 1" class="text-red-500 hover:text-red-700 text-sm font-semibold">Remove</button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                            <input type="hidden" :name="'variants[' + index + '][id]'" x-model="variant.id">
                            <div class="md:col-span-2">
                                <label class="block text-xs font-medium text-gray-600 mb-1">Variant Name *</label>
                                <input type="text" :name="'variants[' + index + '][name]'" x-model="variant.name" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-400" placeholder="e.g. 1 Rating Card + free stand" required>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Cards Per Pack *</label>
                                <input type="number" :name="'variants[' + index + '][quantity]'" x-model="variant.quantity" min="1" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-400" required>
                                <p class="mt-1 text-[11px] text-gray-400">How many cards this pack contains (not the purchase quantity).</p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Stock</label>
                                <input type="number" :name="'variants[' + index + '][stock]'" x-model="variant.stock" min="0" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-400">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Original Price (£) *</label>
                                <input type="number" step="0.01" :name="'variants[' + index + '][original_price]'" x-model="variant.original_price" @input="calculateDiscount(variant)" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-400" placeholder="e.g. 49.99" required>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Sale Price (£) <span class="text-gray-400 font-normal">(Optional)</span></label>
                                <input type="number" step="0.01" :name="'variants[' + index + '][price]'" x-model="variant.price" @input="calculateDiscount(variant)" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-400" placeholder="Leave blank if no sale">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Discount % <span class="text-gray-400 font-normal">(Auto)</span></label>
                                <input type="number" :name="'variants[' + index + '][discount_percent]'" x-model="variant.discount_percent" @input="calculateSalePrice(variant)" min="0" max="100" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-400">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Variant Image</label>
                                <div class="flex items-center gap-2">
                                    <template x-if="variant.image">
                                        <img :src="'/storage/' + variant.image" class="w-8 h-8 rounded object-cover border border-gray-200">
                                    </template>
                                    <input type="file" :name="'variants[' + index + '][image]'" accept="image/*" class="w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-4 mt-3">
                            <label class="flex items-center gap-2 cursor-pointer text-sm">
                                <input type="checkbox" :name="'variants[' + index + '][is_most_popular]'" value="1" :checked="variant.is_most_popular" class="w-4 h-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="text-gray-600">Most Popular</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer text-sm">
                                <input type="checkbox" :name="'variants[' + index + '][is_best_value]'" value="1" :checked="variant.is_best_value" class="w-4 h-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                <span class="text-gray-600">Best Value</span>
                            </label>
                        </div>
                    </div>
                </template>
            </div>
            <button type="button" @click="addVariant()" class="mt-4 inline-flex items-center gap-2 text-sm text-green-600 font-semibold hover:text-green-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Another Variant
            </button>
        </div>

         <!-- SEO Settings -->
        <div class="bg-blue-50/50 rounded-2xl p-6 mb-6 border border-blue-100">
            <div class="flex items-center gap-2 mb-4">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="text-lg font-bold text-gray-800">SEO Settings (Custom Metadata)</h3>
            </div>
            
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label class="block text-sm font-medium text-gray-700">Meta Title</label>
                        <span class="text-[10px] text-gray-400">Recommended: 60 characters</span>
                    </div>
                    <input type="text" name="seo_title" value="{{ old('seo_title', $product->seo_title ?? '') }}" 
                           class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-400 focus:border-blue-400" 
                           placeholder="Custom Browser Tab Title">
                </div>

                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label class="block text-sm font-medium text-gray-700">Meta Description</label>
                        <span class="text-[10px] text-gray-400">Recommended: 160 characters</span>
                    </div>
                    <textarea name="seo_description" rows="2" 
                              class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-400 focus:border-blue-400" 
                              placeholder="Search engine result description">{{ old('seo_description', $product->seo_description ?? '') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Meta Keywords</label>
                    <input type="text" name="seo_keywords" value="{{ old('seo_keywords', $product->seo_keywords ?? '') }}" 
                           class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-400 focus:border-blue-400" 
                           placeholder="keyword1, keyword2, keyword3">
                </div>
            </div>
        </div>

        <!-- Submit -->
        <div class="flex gap-4">
            <button type="submit" class="bg-green-600 text-white px-8 py-3.5 rounded-2xl font-bold hover:bg-green-700 transition-all duration-300 transform hover:-translate-y-1">
                {{ $product ? 'Update Product' : 'Create Product' }}
            </button>
            <a href="{{ route('admin.products.index') }}" class="bg-gray-200 text-gray-700 px-8 py-3.5 rounded-2xl font-bold hover:bg-gray-300 transition-all duration-300">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
function productForm() {
    @php
        $features = old('features', ($product && $product->features) ? $product->features : ['']);
        $gallery = old('existing_gallery', ($product && $product->gallery) ? $product->gallery : []);
        $variants = old('variants', ($product && $product->variants->count() > 0) ? $product->variants->map(function($v) {
            return [
                'id' => $v->id,
                'name' => $v->name,
                'quantity' => $v->quantity,
                'price' => $v->price,
                'original_price' => $v->original_price,
                'discount_percent' => $v->discount_percent,
                'is_best_value' => $v->is_best_value,
                'is_most_popular' => $v->is_most_popular,
                'stock' => $v->stock,
                'image' => $v->image,
            ];
        })->toArray() : [['id' => null, 'name' => '', 'quantity' => 1, 'price' => '', 'original_price' => '', 'discount_percent' => 0, 'is_best_value' => false, 'is_most_popular' => false, 'stock' => 50, 'image' => null]]);
    @endphp

    const existingFeatures = @json($features);
    const existingVariants = @json($variants);

    return {
        features: existingFeatures.length ? existingFeatures : [''],
        gallery: @json($gallery),
        variants: existingVariants.length ? existingVariants : [{ id: null, name: '', quantity: 1, price: '', original_price: '', discount_percent: 0, is_best_value: false, is_most_popular: false, stock: 50, image: null }],

        addVariant() {
            this.variants.push({ id: null, name: '', quantity: 1, price: '', original_price: '', discount_percent: 0, is_best_value: false, is_most_popular: false, stock: 50, image: null });
        },

        calculateDiscount(variant) {
            const orig = parseFloat(variant.original_price);
            const sale = parseFloat(variant.price);

            if (!isNaN(orig) && orig > 0 && !isNaN(sale) && sale >= 0 && sale < orig) {
                variant.discount_percent = Math.round(((orig - sale) / orig) * 100);
            } else {
                variant.discount_percent = 0;
            }
        },

        calculateSalePrice(variant) {
            const orig = parseFloat(variant.original_price);
            const disc = parseFloat(variant.discount_percent);

            if (!isNaN(orig) && orig > 0 && !isNaN(disc) && disc > 0 && disc <= 100) {
                variant.price = (orig * (1 - disc / 100)).toFixed(2);
            } else if (disc === 0 || isNaN(disc)) {
                variant.price = '';
            }
        }
    };
}
</script>
@endsection
