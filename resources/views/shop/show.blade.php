@extends('layouts.guest')

@section('title', $product->seo_title ?? ($product->name . ' | NFC Google Review Card UK | Tap Review Cards'))
@section('meta_description', $product->seo_description ?? ('Buy the ' . $product->name . ' — a premium NFC Google review card trusted by 1,000+ UK businesses. One tap, instant reviews. No app needed. Works on iPhone & Android. Free UK shipping. 30-day money back guarantee.'))
@section('meta_keywords', $product->seo_keywords ?? ('google review card, NFC google review card, ' . strtolower($product->name) . ', google review tap card UK, buy NFC review card, review card for business'))

@section('schema')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Product",
    "name": "{{ $product->name }}",
    "description": "{{ $product->description ?? 'Premium NFC-enabled Google review card for UK businesses. One tap to collect verified reviews instantly.' }}",
    "image": "{{ $product->image ? asset('storage/' . $product->image) : '' }}",
    "brand": {
        "@type": "Brand",
        "name": "Tap Review Cards"
    },
    "offers": {
        "@type": "AggregateOffer",
        "lowPrice": "{{ $product->variants->min('price') }}",
        "highPrice": "{{ $product->variants->max('price') }}",
        "priceCurrency": "GBP",
        "offerCount": "{{ $product->variants->count() }}",
        "availability": "https://schema.org/InStock",
        "seller": {
            "@type": "Organization",
            "name": "Tap Review Cards"
        }
    },
    "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "4.8",
        "reviewCount": "1024",
        "bestRating": "5",
        "worstRating": "1"
    }
}
</script>
@endsection

@section('content')

<style>[x-cloak]{display:none!important;}</style>

<div class="bg-gray-50 py-12 -mt-[100px] pt-[140px]">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">


        <div id="product-shop-container" class="grid grid-cols-1 lg:grid-cols-2 gap-12" x-data="productPage()" @place-selected.window="tempPlaceId = $event.detail.id; tempPlaceName = $event.detail.name;">
            <!-- Left: Product Image -->
            <div>
                <div class="bg-[#d8e4ef] rounded-3xl flex items-center justify-center h-[500px] shadow-inner relative overflow-hidden">
                    <img :src="mainImage" alt="{{ $product->image_alt ?? $product->name }}" class="w-full h-full object-contain transition-all duration-500 transform hover:scale-105" x-show="mainImage" x-transition>
                    <div x-show="!mainImage" class="text-center">
                        <svg class="w-32 h-32 text-gray-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                </div>

                <!-- Variant Image Gallery Thumbnails -->
                <div class="flex gap-3 mt-4 overflow-x-auto pb-2" x-show="galleryImages.length > 1">
                    <template x-for="(img, idx) in galleryImages" :key="idx">
                        <div @click="mainImage = img.src; activeGalleryIdx = idx"
                             :class="activeGalleryIdx === idx ? 'border-[#00A0FF] shadow-md' : 'border-transparent hover:border-gray-300'"
                             class="w-20 h-20 bg-[#D8E4EF] rounded-lg flex items-center justify-center cursor-pointer border-2 transition-all duration-200 flex-shrink-0">
                            <img :src="img.src" :alt="img.label" class="w-16 h-16 object-contain">
                        </div>
                    </template>
                </div>
            </div>

            <!-- Right: Product Details -->
            <div>
                <h1 class="text-3xl md:text-4xl font-bold text-[#1800ad] mb-3 font-ubuntu">{{ $product->name }}</h1>

                <div class="inline-block bg-green-500 text-white px-6 py-2 rounded-full font-semibold text-sm mb-5 shadow-sm">
                    #1 Google Review Card in the UK
                </div>

                <!-- Trust Indicators -->
                <div class="flex items-center gap-2 mb-6">
                    <div class="flex text-yellow-400">
                        @for($i = 0; $i < 5; $i++)
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <span class="text-gray-600 font-mulish">Trusted by 1,000+ businesses</span>
                </div>

                <!-- Features List -->
                <div class="space-y-3 mb-6">
                    @forelse($product->features ?? [] as $feature)
                    <div class="flex items-center gap-3 text-gray-700 font-medium">
                        <div class="w-5 h-5 bg-[#0cc0df] rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <span class="font-mulish">{{ $feature }}</span>
                    </div>
                    @empty
                    @php 
                        $defaultFeatures = ['Works on iPhone & Android', 'No fees ever', 'Ready in 60 seconds'];
                    @endphp
                    @foreach($defaultFeatures as $feature)
                    <div class="flex items-center gap-3 text-gray-700 font-medium">
                        <div class="w-5 h-5 bg-[#0cc0df] rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <span class="font-mulish">{{ $feature }}</span>
                    </div>
                    @endforeach
                    @endforelse
                </div>

                <!-- Variant Selector -->
                @php
                    $isPartner = auth()->check() && auth()->user()->isApprovedPartner();
                    $partnerDiscount = $isPartner ? auth()->user()->getPartnerDiscountPercent() : 0;
                @endphp
                <div class="-mb-4">
                    <span class="text-base font-semibold font-ubuntu text-[#6e6e6e]">Select Your Style & Option:</span>
                </div>
                <div class="space-y-3 my-6">
                    @foreach($product->variants->sortByDesc('price') as $index => $variant)
                    @php
                        $vFinalPrice = $partnerDiscount > 0 ? round($variant->price * (1 - ($partnerDiscount / 100)), 2) : $variant->price;
                    @endphp
                    <div @click="selectVariant({{ $variant->id }}, '{{ $variant->name }}', {{ $vFinalPrice }}, {{ $variant->original_price }}, {{ $variant->discount_percent }}, {{ $variant->stock }}, '{{ $variant->image ? asset('storage/' . $variant->image) : asset('storage/' . $product->image) }}', {{ $variant->quantity ?? 1 }})"
                         :class="selectedVariantId === {{ $variant->id }} ? 'ring-2 ring-[#142D63] bg-[#142D63] text-white' : 'bg-white border border-gray-200 hover:border-[#00A0FF]'"
                         class="relative flex items-center gap-4 p-4 rounded-xl cursor-pointer transition-all duration-300 group">

                        <!-- Product Mini Image -->
                        <div class="w-16 h-16 rounded-lg flex items-center justify-center flex-shrink-0"
                             :class="selectedVariantId === {{ $variant->id }} ? '' : ''">
                            @if($variant->image)
                                <img src="{{ asset('storage/' . $variant->image) }}" class="w-16 h-16 object-contain" alt="{{ $variant->name }}">
                            @elseif($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" class="w-16 h-16 object-contain" alt="{{ $variant->name }}">
                            @else
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            @endif
                        </div>

                        <!-- Variant Info -->
                        <div class="flex-1">
                            <div class="font-semibold font-ubuntu" :class="selectedVariantId === {{ $variant->id }} ? 'text-white' : 'text-[#142D63]'">{{ $variant->name }}</div>
                            <div class="flex items-center gap-3">
                                <span class="text-lg font-bold" :class="selectedVariantId === {{ $variant->id }} ? 'text-white' : 'text-[#142D63]'">£{{ number_format($vFinalPrice, 2) }}</span>
                                @if($partnerDiscount > 0)
                                <span class="bg-purple-600 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $partnerDiscount }}% Partner Off</span>
                                @elseif($variant->discount_percent > 0)
                                <span class="bg-[#0cc0df] text-white text-xs font-bold px-2 py-0.5 rounded-full">save {{ $variant->discount_percent }}%</span>
                                @endif
                            </div>
                        </div>

                        <!-- Badges -->
                        @if($variant->is_most_popular)
                        <div class="flex items-center gap-1 text-sm">
                            <svg class="w-4 h-4 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span :class="selectedVariantId === {{ $variant->id }} ? 'text-green-300' : 'text-green-600'" class="font-medium">Most popular</span>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>

                <!-- Custom Logo Add-on Swatch / Checkbox -->
                @php
                    $productSearchText = strtolower(($product->name ?? '') . ' ' . ($product->slug ?? '') . ' ' . ($product->subtitle ?? ''));
                    $isKeyringProduct = \Illuminate\Support\Str::contains($productSearchText, ['keyring', 'key ring', 'keychain', 'key chain', 'rating tag']);
                @endphp

                @if($isKeyringProduct)
                <div class="mb-6 bg-gradient-to-r from-blue-50/80 via-indigo-50/60 to-cyan-50/80 p-4 rounded-2xl border border-blue-200 shadow-sm transition-all duration-300 hover:border-blue-400">
                    <label class="flex items-start gap-3.5 cursor-pointer select-none">
                        <input type="checkbox" x-model="hasCustomLogo" @change="updateFinalPrice()" 
                               class="mt-1 w-5 h-5 rounded border-gray-300 text-[#142D63] focus:ring-[#00A0FF] transition-all">
                        <div class="flex-1">
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-[#142D63] font-ubuntu text-sm sm:text-base flex items-center gap-2">
                                    <span>🎨 Custom Company Logo & Design</span>
                                    <span class="bg-[#00A0FF] text-white text-[10px] uppercase font-extrabold px-2 py-0.5 rounded-full shadow-sm">Add-on</span>
                                </span>
                                <span class="font-extrabold text-[#142D63] text-sm sm:text-base">+£2.99</span>
                            </div>
                            <p class="text-xs text-gray-600 font-mulish mt-1">
                                Print your custom business logo, branding colors, and unique artwork onto your review products.
                            </p>
                        </div>
                    </label>

                    <!-- Logo File Uploader (shown when add-on selected) -->
                    <div x-show="hasCustomLogo" x-transition x-cloak class="mt-4 pt-4 border-t border-blue-200/70">
                        <label class="block text-xs font-bold text-[#142D63] font-ubuntu mb-2">
                            Upload your logo / artwork <span class="text-red-500">*</span>
                        </label>

                        <!-- Empty state: choose file -->
                        <label x-show="!customLogoPath && !customLogoUploading"
                               class="flex flex-col items-center justify-center gap-2 w-full border-2 border-dashed border-blue-300 rounded-xl p-5 cursor-pointer bg-white/60 hover:bg-white hover:border-[#00A0FF] transition-all text-center">
                            <svg class="w-8 h-8 text-[#00A0FF]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.9A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                            <span class="text-xs font-semibold text-gray-600 font-mulish">Click to upload your logo</span>
                            <span class="text-[10px] text-gray-400 font-mulish">PNG, JPG, SVG, WEBP or PDF · max 5MB</span>
                            <input type="file" class="hidden" accept=".jpg,.jpeg,.png,.svg,.webp,.pdf,image/*,application/pdf" @change="uploadLogo($event)">
                        </label>

                        <!-- Uploading state -->
                        <div x-show="customLogoUploading" class="flex items-center gap-3 w-full border border-blue-200 rounded-xl p-4 bg-white">
                            <svg class="animate-spin h-5 w-5 text-[#00A0FF]" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                            <span class="text-xs font-semibold text-gray-600 font-mulish">Uploading your logo…</span>
                        </div>

                        <!-- Uploaded state -->
                        <div x-show="customLogoPath && !customLogoUploading" class="flex items-center justify-between gap-3 w-full border border-green-200 rounded-xl p-3 bg-green-50">
                            <div class="flex items-center gap-3 overflow-hidden">
                                <div class="w-10 h-10 rounded-lg bg-white border border-green-100 flex items-center justify-center overflow-hidden flex-shrink-0">
                                    <template x-if="customLogoIsImage">
                                        <img :src="customLogoUrl" class="w-full h-full object-contain" alt="Logo preview">
                                    </template>
                                    <template x-if="!customLogoIsImage">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </template>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold text-green-800 font-mulish truncate" x-text="customLogoName"></p>
                                    <p class="text-[10px] text-green-600 font-mulish">Logo attached ✓</p>
                                </div>
                            </div>
                            <button type="button" @click="removeLogo()" class="text-gray-400 hover:text-red-500 transition-colors p-1 flex-shrink-0" title="Remove logo">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <p x-show="customLogoError" x-text="customLogoError" class="text-xs text-red-600 font-mulish mt-2"></p>
                    </div>
                </div>
                @endif

                <!-- Stock Urgency -->
                <div class="mb-6" x-show="selectedStock > 0">
                    <p class="text-sm text-gray-500 uppercase tracking-wider font-mulish">
                        Hurry, only <span class="font-bold text-orange-500" x-text="selectedStock"></span> items left in stock!
                    </p>
                    <div class="w-full bg-gray-200 rounded-full h-1.5 mt-2 overflow-hidden">
                        <div class="bg-orange-400 h-1.5 rounded-full transition-all duration-1000" :style="'width: ' + Math.min(Math.max((selectedStock / 30 * 100), 10), 95) + '%'"></div>
                    </div>
                </div>

                <!-- Quantity Selector -->
                <div class="mb-6 flex flex-wrap items-center justify-between gap-4 p-4 bg-gray-100/70 rounded-2xl border border-gray-200 shadow-sm">
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-bold text-[#142D63] font-ubuntu uppercase tracking-wider">Quantity:</span>
                        <div class="inline-flex items-center bg-white border border-gray-300 rounded-xl overflow-hidden shadow-sm">
                            <button type="button" @click="decreaseQty()" 
                                    :disabled="selectedQty <= 1"
                                    :class="selectedQty <= 1 ? 'text-gray-300 cursor-not-allowed' : 'text-[#142D63] hover:bg-gray-100 cursor-pointer'"
                                    class="w-10 h-10 flex items-center justify-center text-lg font-bold transition-colors select-none">
                                −
                            </button>
                            <input type="number" x-model.number="selectedQty" @input="validateQty()" min="1" :max="selectedStock > 0 ? selectedStock : 99"
                                   class="w-12 h-10 text-center font-bold text-[#142D63] font-ubuntu border-none focus:ring-0 p-0 text-base [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                            <button type="button" @click="increaseQty()" 
                                    :disabled="selectedStock > 0 && selectedQty >= selectedStock"
                                    :class="(selectedStock > 0 && selectedQty >= selectedStock) ? 'text-gray-300 cursor-not-allowed' : 'text-[#142D63] hover:bg-gray-100 cursor-pointer'"
                                    class="w-10 h-10 flex items-center justify-center text-lg font-bold transition-colors select-none">
                                +
                            </button>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-gray-500 font-mulish block">Total Price</span>
                        <span class="text-xl font-extrabold text-[#142D63] font-ubuntu" x-text="'£' + (parseFloat(selectedPrice) * selectedQty).toFixed(2)"></span>
                    </div>
                </div>

                <!-- Location Selection -->
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <p class="text-gray-700 font-bold font-ubuntu text-lg">Where should we link your cards?</p>
                            <p class="text-xs text-gray-500 font-mulish mt-0.5" x-show="!skipForNow">
                                Locations: <span class="font-bold text-[#142D63]" x-text="locations.length"></span> / <span class="font-bold text-[#142D63]" x-text="maxAllowedLocations()"></span> max
                            </p>
                        </div>
                        
                        <!-- Small Toggle -->
                        <div class="inline-flex p-0.5 bg-gray-100 rounded-lg border border-gray-200 text-xs font-semibold">
                            <button type="button" @click="setLinkMode('search')"
                                    :class="linkMode === 'search' ? 'bg-white text-[#142D63] shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                                    class="px-2.5 py-1 rounded-md transition-all flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span>Search</span>
                            </button>
                            <button type="button" @click="setLinkMode('direct')"
                                    :class="linkMode === 'direct' ? 'bg-white text-[#142D63] shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                                    class="px-2.5 py-1 rounded-md transition-all flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                <span>Paste Direct URL</span>
                            </button>
                        </div>
                    </div>

                    <!-- Location Limit Max Reached Notice -->
                    <div x-show="!skipForNow && locations.length >= maxAllowedLocations()" class="p-3 bg-amber-50 border border-amber-200 rounded-xl mb-3 text-xs text-amber-900 font-mulish flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Max location limit (<strong x-text="maxAllowedLocations()"></strong>) reached for quantity <strong x-text="selectedQty"></strong>.</span>
                        </div>
                        <button type="button" @click="increaseQty()" class="text-xs font-bold text-amber-900 underline hover:text-amber-700 cursor-pointer flex-shrink-0 ml-2">
                            + Add Qty
                        </button>
                    </div>
                    
                    <!-- Added Locations List -->
                    <div class="space-y-3 mb-4" x-show="!skipForNow && locations.length > 0">
                        <template x-for="(loc, index) in locations" :key="index">
                            <div class="flex items-center justify-between bg-white border border-gray-200 p-3 rounded-xl shadow-sm animate-fade-in group hover:border-green-400 transition-all duration-200">
                                <div class="flex items-center gap-3 overflow-hidden">
                                    <div class="w-8 h-8 bg-green-50 text-green-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </div>
                                    <span class="text-sm font-semibold text-[#142D63] font-mulish truncate" x-text="loc.name"></span>
                                </div>
                                <button @click="removeLocation(index)" class="text-gray-300 hover:text-red-500 transition-colors p-1 group-hover:bg-red-50 rounded-lg flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </template>
                    </div>

                    <!-- Skip for now active notice -->
                    <div x-show="skipForNow" class="p-3.5 bg-blue-50/80 border border-blue-200 rounded-xl mb-4 animate-fade-in flex items-start gap-3">
                        <div class="w-7 h-7 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="flex-1 text-xs font-mulish text-[#142D63]">
                            <p class="font-bold text-sm text-[#142D63] mb-0.5">Linking skipped for now</p>
                            <p class="text-gray-600">No problem! You can order now and our team will contact you for your Google review link before dispatch, or you can supply it via your order confirmation.</p>
                        </div>
                        <button type="button" @click="skipForNow = false" class="text-xs font-bold text-blue-600 hover:underline">
                            Change
                        </button>
                    </div>

                    <!-- Location Input Container (hidden when skipForNow is true or locations limit reached) -->
                    <div x-show="!skipForNow && locations.length < maxAllowedLocations()">
                        <p class="text-gray-600 font-mulish mb-2 text-sm font-bold" 
                           x-text="locations.length > 0 ? 'Add another location' : (linkMode === 'search' ? 'Search your business location' : 'Paste Your Website link or business name')"></p>
                        
                        <div class="relative flex gap-2">
                            <div class="relative flex-1">
                                <!-- Mode 1: Search (Google Autocomplete) -->
                                <input x-show="linkMode === 'search'" type="text" id="google-places-input" x-model="tempPlaceName"
                                       :placeholder="locations.length > 0 ? 'e.g. 2nd branch address' : 'Please search your business here'"
                                       class="w-full border border-gray-300 rounded-xl px-4 py-3 text-gray-700 focus:ring-2 focus:ring-green-400 focus:border-green-400 transition-all font-mulish pr-10">
                                
                                <!-- Mode 2: Direct Link / Service Business -->
                                <input x-show="linkMode === 'direct'" type="text" id="direct-link-input" x-model="tempDirectInput"
                                       placeholder="e.g. Google link, Trustpilot, Instagram, or website URL"
                                       @keydown.enter.prevent="addLocation()"
                                       class="w-full border border-gray-300 rounded-xl px-4 py-3 text-gray-700 focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition-all font-mulish pr-10">

                                <div class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400" x-show="(linkMode === 'search' && !tempPlaceName) || (linkMode === 'direct' && !tempDirectInput)">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </div>
                            </div>
                            <button type="button" @click="addLocation()" 
                                    :disabled="(linkMode === 'search' && !tempPlaceName) || (linkMode === 'direct' && !tempDirectInput)"
                                    :class="((linkMode === 'search' && !tempPlaceName) || (linkMode === 'direct' && !tempDirectInput)) ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-[#142D63] text-white hover:bg-[#00A0FF] cursor-pointer'"
                                    class="px-6 py-3 rounded-xl font-bold transition-all duration-300 flex items-center justify-center shadow-md">
                                Confirm
                            </button>
                        </div>

                        <!-- Helper notes -->
                        <p class="text-xs text-gray-400 mt-2 font-mulish" x-show="linkMode === 'search' && locations.length === 0">
                            Example: 38 Mayfair Row, London 1BX456
                        </p>
                        <p class="text-xs text-blue-600 mt-2 font-mulish flex items-center gap-1" x-show="linkMode === 'direct' && locations.length === 0">
                            <span>💡 Works with Google, Trustpilot, Instagram, TripAdvisor, or any custom URL/business name.</span>
                        </p>
                        <input type="hidden" id="google-place-id" x-model="tempPlaceId">
                    </div>

                    <!-- Skip for now button/toggle -->
                    <div class="mt-3 flex items-center justify-between pt-2 border-t border-gray-100">
                        <button type="button" @click="toggleSkip()" 
                                class="inline-flex items-center gap-1.5 text-xs font-semibold transition-colors cursor-pointer"
                                :class="skipForNow ? 'text-gray-500 hover:text-gray-700' : 'text-blue-600 hover:text-blue-800 hover:underline'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/></svg>
                            <span x-text="skipForNow ? '← Add a location or link instead' : 'Don\'t have your link ready? Skip for now and set up later'"></span>
                        </button>
                    </div>
                </div>

                <!-- Buy Now Button -->
                <button @click="addToCart()"
                   :class="selectedVariantId ? 'bg-[#0cc0df] hover:bg-[#0cc0df] cursor-pointer' : 'bg-gray-300 cursor-not-allowed'"
                   class="block w-full text-center text-white py-4 rounded-xl font-bold text-lg transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 font-ubuntu">
                    BUY NOW
                </button>

                <!-- Guarantee Badge -->
                <div class="flex items-center justify-center gap-2 mt-4 text-gray-500 font-mulish">
                    <svg class="w-5 h-5 text-[#0cc0df]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span>30-day money back guarantee</span>
                </div>
            </div>
        </div>


        <!-- Trust Section -->
        <div class="mt-12 grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
            <div class="bg-[#F1F5F9] rounded-3xl p-6 md:p-8 flex flex-col items-center text-center">
                <div class="text-[#00A0FF] mb-4">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M20 7h-4V5c0-1.103-.897-2-2-2h-4c-1.103 0-2 .897-2 2v2H4c-1.103 0-2 .897-2 2v10c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2V9c0-1.103-.897-2-2-2zM10 5h4v2h-4V5zm10 14H4V9h16v10z"/></svg>
                </div>
                <h3 class="text-sm md:text-xl font-bold text-[#1800ad] mb-1 font-ubuntu">1000+ businesses</h3>
                <p class="text-gray-500 font-mulish text-[10px] md:text-sm">in the UK use Rating Card</p>
            </div>
            <div class="bg-[#F1F5F9] rounded-3xl p-6 md:p-8 flex flex-col items-center text-center">
                <div class="text-[#00A0FF] mb-4">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                </div>
                <h3 class="text-sm md:text-xl font-bold text-[#1800ad] mb-1 font-ubuntu">100% ready to use</h3>
                <p class="text-gray-500 font-mulish text-[10px] md:text-sm">cards arrive fully programmed</p>
            </div>
            <div class="bg-[#F1F5F9] rounded-3xl p-6 md:p-8 flex flex-col items-center text-center">
                <div class="text-[#00A0FF] mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M12 21V3m9 6H3m12 0L9 21m0-12l6 12"/></svg>
                </div>
                <h3 class="text-sm md:text-xl font-bold text-[#1800ad] mb-1 font-ubuntu">Based in the UK</h3>
                <p class="text-gray-500 font-mulish text-[10px] md:text-sm">Delivered within 48 hours</p>
            </div>
            <div class="bg-[#F1F5F9] rounded-3xl p-6 md:p-8 flex flex-col items-center text-center">
                <div class="text-[#00A0FF] mb-4">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M20.995 6.9a.998.998 0 0 0-.547-.795l-8-4a1.002 1.002 0 0 0-.895 0l-8 4a1.002 1.002 0 0 0-.547.795c-.015.178-1 12.73 8.547 15.056a.932.932 0 0 0 .447 0c9.547-2.326 8.562-14.878 8.547-15.056zM12 18.966c-5.877-1.742-5.912-10.026-5.908-11.45L12 4.542l5.908 2.973c.004 1.424-.031 9.708-5.908 11.451z"/><path d="M11 7h2v4h4v2h-4v4h-2v-4H7v-2h4V7z"/></svg>
                </div>
                <h3 class="text-sm md:text-xl font-bold text-[#1800ad] mb-1 font-ubuntu">Free returns</h3>
                <p class="text-gray-500 font-mulish text-[10px] md:text-sm">30-day money back guarantee</p>
            </div>
        </div>

        <!-- SEO Content: About This Product -->
        <div class="mt-16 bg-white rounded-2xl p-8 md:p-12 shadow-sm border border-gray-100">
            <h2 class="text-2xl md:text-3xl font-bold text-[#1800ad] mb-6 font-ubuntu">About the {{ $product->name }}</h2>
            <div class="prose max-w-none text-gray-600 font-mulish leading-relaxed space-y-4">
                <p>
                    The <strong>{{ $product->name }}</strong> is a premium NFC-enabled Google review card designed for UK businesses that want to collect more verified customer reviews effortlessly. Simply tap the card on your customer's smartphone — no app download needed — and they'll be taken straight to your Google review page in seconds.
                </p>
                <p>
                    Whether you run a salon, restaurant, dental practice, or trade business, our NFC review cards help you build a stronger online reputation with real, authentic reviews. Each card is pre-programmed with your business details and arrives ready to use straight out of the box.
                </p>
                @if($product->description)
                <p>{!! nl2br(e($product->description)) !!}</p>
                @endif
            </div>
        </div>

        <!-- SEO Content: Key Benefits -->
        <div class="mt-8 bg-white rounded-2xl p-8 md:p-12 shadow-sm border border-gray-100">
            <h2 class="text-2xl font-bold text-[#1800ad] mb-8 font-ubuntu">Key Benefits of NFC Google Review Cards</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-[#1800ad] font-ubuntu mb-1">Instant Review Collection</h3>
                        <p class="text-gray-600 font-mulish text-sm">One tap takes your customer directly to your Google review page. No typing URLs, no searching — just tap and review.</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-[#1800ad] font-ubuntu mb-1">Works on All Smartphones</h3>
                        <p class="text-gray-600 font-mulish text-sm">Compatible with both iPhone and Android devices. For older phones, a QR code is included as a fallback.</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-[#1800ad] font-ubuntu mb-1">Smart Dashboard Included</h3>
                        <p class="text-gray-600 font-mulish text-sm">Track every review, monitor staff performance with leaderboards, and respond to reviews using AI — all from one dashboard.</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-[#1800ad] font-ubuntu mb-1">Customer Service Focus</h3>
                        <p class="text-gray-600 font-mulish text-sm">Our Smart Feedback Loop gives every customer a private channel to share concerns directly with you — so you can resolve issues fast and invite satisfied customers to share their experience on Google.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- SEO Content: Compatibility & Delivery -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100">
                <h2 class="text-xl font-bold text-[#1800ad] mb-4 font-ubuntu">Compatibility</h2>
                <ul class="space-y-3 text-gray-600 font-mulish text-sm">
                    <li class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        iPhone 7 and newer (iOS 13+)
                    </li>
                    <li class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Most Android phones with NFC
                    </li>
                    <li class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        QR code fallback for non-NFC phones
                    </li>
                    <li class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        No app install required
                    </li>
                </ul>
            </div>
            <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100">
                <h2 class="text-xl font-bold text-[#1800ad] mb-4 font-ubuntu">Delivery & Returns</h2>
                <ul class="space-y-3 text-gray-600 font-mulish text-sm">
                    <li class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8"/></svg>
                        Free UK shipping on all orders
                    </li>
                    <li class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Dispatched within 48 hours
                    </li>
                    <li class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        30-day money back guarantee
                    </li>
                    <li class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        Secure payment via Stripe
                    </li>
                </ul>
            </div>
        </div>

        <!-- SEO Content: Who It's For -->
        <div class="mt-8 bg-white rounded-2xl p-8 md:p-12 shadow-sm border border-gray-100">
            <h2 class="text-2xl font-bold text-[#1800ad] mb-6 font-ubuntu">Who Are NFC Review Cards For?</h2>
            <p class="text-gray-600 font-mulish mb-6">Our NFC Google review cards work for any business that meets customers face to face. Here are some of the industries that love our cards:</p>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @php
                    $industries = [
                        ['icon' => '✂️', 'name' => 'Hair & Beauty Salons'],
                        ['icon' => '🍽️', 'name' => 'Restaurants & Cafés'],
                        ['icon' => '🔧', 'name' => 'Plumbers & Electricians'],
                        ['icon' => '🦷', 'name' => 'Dentists & Clinics'],
                        ['icon' => '🏋️', 'name' => 'Gyms & Studios'],
                        ['icon' => '🏠', 'name' => 'Estate Agents'],
                        ['icon' => '🐾', 'name' => 'Vets & Pet Grooming'],
                        ['icon' => '🚗', 'name' => 'Garages & Car Washes'],
                    ];
                @endphp
                @foreach($industries as $industry)
                <div class="bg-gray-50 rounded-xl p-4 text-center hover:bg-blue-50 transition-colors">
                    <div class="text-2xl mb-2">{{ $industry['icon'] }}</div>
                    <p class="text-sm font-semibold text-[#1800ad] font-ubuntu">{{ $industry['name'] }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <!-- SEO Content: Product FAQs -->
        <div class="mt-8 bg-white rounded-2xl p-8 md:p-12 shadow-sm border border-gray-100">
            <h2 class="text-2xl font-bold text-[#1800ad] mb-8 font-ubuntu">Frequently Asked Questions About This Product</h2>
            <div class="space-y-4" x-data="{ openFaq: null }">
                @php
                    $productFaqs = [
                        ['q' => 'Do I need to download an app to use this ' . $product->name . '?', 'a' => 'No. Your customers simply tap the card on their phone and their browser opens directly to your Google review page. No app download is needed on either side.'],
                        ['q' => 'Will it work on my customers\' phones?', 'a' => 'Yes — it works on all iPhones from iPhone 7 onwards and most Android phones with NFC. For older phones without NFC, every card also includes a QR code that can be scanned instead.'],
                        ['q' => 'How quickly will I receive my order?', 'a' => 'We dispatch all orders within 48 hours. UK delivery is typically 1-3 business days. Your cards arrive fully programmed and ready to use immediately.'],
                        ['q' => 'Can I track who leaves reviews?', 'a' => 'Yes. Every card has a unique tracking ID linked to your dashboard. You can see which staff member collected which review and monitor performance with leaderboards.'],
                        ['q' => 'Can I use the card for multiple locations?', 'a' => 'Each card is programmed for one business location. If you have multiple locations, simply order additional cards and assign them during checkout.'],
                    ];
                @endphp
                @foreach($productFaqs as $index => $faq)
                <div class="border border-gray-200 rounded-xl overflow-hidden">
                    <button @click="openFaq === {{ $index }} ? openFaq = null : openFaq = {{ $index }}" class="w-full flex items-center justify-between p-5 text-left hover:bg-gray-50 transition-colors">
                        <span class="font-semibold text-[#1800ad] font-ubuntu pr-4">{{ $faq['q'] }}</span>
                        <svg class="w-5 h-5 text-gray-400 flex-shrink-0 transform transition-transform" :class="openFaq === {{ $index }} ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="openFaq === {{ $index }}" x-transition class="px-5 pb-5 text-gray-600 font-mulish">
                        {{ $faq['a'] }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        @if(isset($relatedProducts) && $relatedProducts->count() > 0)
        <!-- SEO Content: Related Products -->
        <div class="mt-12 mb-8">
            <h2 class="text-2xl font-bold text-[#1800ad] mb-6 font-ubuntu px-2">Related NFC Review Products</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                @foreach($relatedProducts as $related)
                    @php
                        $relVariant = $related->variants->sortBy('price')->first();
                    @endphp
                    @if($relVariant)
                    <a href="{{ route('shop.show', ['product' => $related->slug, 'variant' => $relVariant->id]) }}" class="group block bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 overflow-hidden transform hover:-translate-y-1">
                        <div class="bg-[#d8e4ef] p-6 flex flex-col items-center justify-center h-48">
                            <img src="{{ $relVariant->image ? asset('storage/' . $relVariant->image) : ($related->image ? asset('storage/' . $related->image) : '') }}" alt="{{ $related->name }}" class="max-h-32 object-contain group-hover:scale-105 transition-transform duration-300" loading="lazy">
                        </div>
                        <div class="p-5 text-center">
                            <h3 class="font-bold text-[#1800ad] text-sm mb-2 font-ubuntu">{{ $related->name }}</h3>
                            <p class="text-[#0cc0df] font-bold">From £{{ number_format($relVariant->price, 2) }}</p>
                        </div>
                    </a>
                    @endif
                @endforeach
            </div>
        </div>
        @endif

        <!-- SEO Content: Internal Links -->
        <div class="mt-8 bg-gradient-to-br from-[#1800ad] to-[#0a0060] rounded-2xl p-8 md:p-12 text-white text-center">
            <h2 class="text-2xl font-bold mb-4 font-ubuntu">Explore More From Tap Review Cards</h2>
            <p class="text-white/80 font-mulish mb-8">Discover our full range of NFC review cards, stands, and keyrings designed for UK businesses.</p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('shop.index') }}" class="bg-white text-[#1800ad] px-6 py-3 rounded-xl font-bold text-sm hover:bg-gray-100 transition-colors font-ubuntu">Browse all Google Review Cards & Stands</a>
            </div>
        </div>

    </div>
</div><!-- Google Places API -->
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_places.api_key', env('GOOGLE_PLACES_API_KEY')) }}&libraries=places&callback=initAutocomplete" async defer></script>

<script>
function productPage() {
    @php
        $initialVariant = $product->variants->where('id', $variant_id)->first() ?? $product->variants->first();

        // Build gallery: product main image + gallery images + variant images
        $galleryItems = [];
        if ($product->image) {
            $galleryItems[] = ['src' => asset('storage/' . $product->image), 'label' => $product->name];
        }
        
        // Add dedicated gallery images
        if ($product->gallery && is_array($product->gallery)) {
            foreach ($product->gallery as $galleryImg) {
                $galleryItems[] = ['src' => asset('storage/' . $galleryImg), 'label' => 'Gallery Image'];
            }
        }

        foreach ($product->variants->sortByDesc('price') as $v) {
            if ($v->image) {
                $src = asset('storage/' . $v->image);
                // Avoid duplicate if variant image matches an existing gallery image
                $isDuplicate = false;
                foreach ($galleryItems as $existing) {
                    if ($existing['src'] === $src) { $isDuplicate = true; break; }
                }
                if (!$isDuplicate) {
                    $galleryItems[] = ['src' => $src, 'label' => $v->name];
                }
            }
        }

        // Determine initial gallery index
        $initialImage = $initialVariant?->image ? asset('storage/' . $initialVariant->image) : ($product->image ? asset('storage/' . $product->image) : '');
        $initialGalleryIdx = 0;
        foreach ($galleryItems as $idx => $item) {
            if ($item['src'] === $initialImage) { $initialGalleryIdx = $idx; break; }
        }
    @endphp
    return {
        selectedVariantId: {{ $initialVariant?->id ?? 'null' }},
        selectedVariantName: '{{ $initialVariant?->name ?? '' }}',
        variantPackQty: {{ $initialVariant?->quantity ?? 1 }},
        selectedQty: 1,
        basePrice: {{ $initialVariant ? ($partnerDiscount > 0 ? round($initialVariant->price * (1 - ($partnerDiscount / 100)), 2) : $initialVariant->price) : 0 }},
        selectedPrice: {{ $initialVariant ? ($partnerDiscount > 0 ? round($initialVariant->price * (1 - ($partnerDiscount / 100)), 2) : $initialVariant->price) : 0 }},
        selectedOriginalPrice: {{ $initialVariant?->original_price ?? 0 }},
        selectedDiscount: {{ $initialVariant?->discount_percent ?? 0 }},
        selectedStock: {{ $initialVariant?->stock ?? 0 }},
        hasCustomLogo: false,
        customLogoPath: '',
        customLogoUrl: '',
        customLogoName: '',
        customLogoUploading: false,
        customLogoError: '',
        get customLogoIsImage() {
            return /\.(png|jpe?g|svg|webp)$/i.test((this.customLogoName || this.customLogoUrl || '').toLowerCase());
        },
        mainImage: '{{ $initialImage }}',
        galleryImages: @json($galleryItems),
        activeGalleryIdx: {{ $initialGalleryIdx }},
        locations: [],
        tempPlaceName: '',
        tempPlaceId: '',
        linkMode: 'search',
        skipForNow: false,
        tempDirectInput: '',

        maxAllowedLocations() {
            const qty = parseInt(this.selectedQty) || 1;
            const packQty = parseInt(this.variantPackQty) || 1;
            return qty * packQty;
        },

        enforceLocationLimit() {
            const maxLocs = this.maxAllowedLocations();
            if (this.locations.length > maxLocs) {
                this.locations = this.locations.slice(0, maxLocs);
            }
        },

        increaseQty() {
            if (this.selectedStock > 0 && this.selectedQty >= this.selectedStock) {
                alert(`Sorry, only ${this.selectedStock} item(s) available in stock.`);
                return;
            }
            this.selectedQty++;
        },

        decreaseQty() {
            if (this.selectedQty > 1) {
                this.selectedQty--;
                this.enforceLocationLimit();
            }
        },

        validateQty() {
            if (!this.selectedQty || this.selectedQty < 1) {
                this.selectedQty = 1;
            } else if (this.selectedStock > 0 && this.selectedQty > this.selectedStock) {
                this.selectedQty = this.selectedStock;
            }
            this.enforceLocationLimit();
        },

        setLinkMode(mode) {
            this.linkMode = mode;
            if (this.skipForNow) this.skipForNow = false;
        },

        toggleSkip() {
            this.skipForNow = !this.skipForNow;
            if (this.skipForNow) {
                this.tempPlaceName = '';
                this.tempDirectInput = '';
                this.tempPlaceId = '';
            }
        },

        init() {
            window.addEventListener('place-selected', (e) => {
                this.tempPlaceId = e.detail.id;
                this.tempPlaceName = e.detail.name;
                const input = document.getElementById('google-places-input');
                if (input) input.value = e.detail.name;
            });
        },

        updateFinalPrice() {
            const base = parseFloat(this.basePrice) || 0;
            this.selectedPrice = (this.hasCustomLogo ? (base + 2.99) : base).toFixed(2);
            // Clear any attached logo when the add-on is switched off.
            if (!this.hasCustomLogo) {
                this.removeLogo();
            }
        },

        async uploadLogo(event) {
            const file = event.target.files && event.target.files[0];
            if (!file) return;

            this.customLogoError = '';
            this.customLogoUploading = true;

            const formData = new FormData();
            formData.append('logo', file);

            try {
                const res = await fetch('{{ route('shop.upload-logo') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });
                const data = await res.json();
                if (data.success) {
                    this.customLogoPath = data.path;
                    this.customLogoUrl = data.url;
                    this.customLogoName = data.name || 'logo';
                } else {
                    this.customLogoError = data.message || 'Upload failed. Please try again.';
                }
            } catch (e) {
                console.error('Logo upload failed:', e);
                this.customLogoError = 'Upload failed. Please check your connection and try again.';
            } finally {
                this.customLogoUploading = false;
                if (event.target) event.target.value = '';
            }
        },

        removeLogo() {
            this.customLogoPath = '';
            this.customLogoUrl = '';
            this.customLogoName = '';
            this.customLogoError = '';
        },

        addLocation() {
            if (this.locations.length >= this.maxAllowedLocations()) {
                alert(`Maximum allowed locations (${this.maxAllowedLocations()}) reached for quantity ${this.selectedQty}. Please increase item quantity to add more locations.`);
                return;
            }

            let placeName = '';
            let placeId = '';

            if (this.linkMode === 'direct') {
                placeName = (this.tempDirectInput || '').trim();
                if (!placeName) return;
                placeId = (placeName.startsWith('http://') || placeName.startsWith('https://'))
                    ? placeName
                    : ('direct-' + Date.now());
                this.tempDirectInput = '';
            } else {
                const input = document.getElementById('google-places-input');
                placeName = (this.tempPlaceName || (input ? input.value : '')).trim();
                if (!placeName) return;
                placeId = this.tempPlaceId || ('loc-' + Date.now());
                this.tempPlaceId = '';
                this.tempPlaceName = '';
                if (input) input.value = '';
            }

            // Check for duplicates
            const exists = this.locations.some(loc => loc.id === placeId || loc.name === placeName);
            if (exists) return;

            this.locations.push({
                id: placeId,
                name: placeName
            });
            this.skipForNow = false;
        },

        removeLocation(index) {
            this.locations.splice(index, 1);
        },

        selectVariant(id, name, price, originalPrice, discount, stock, image, quantity = 1) {
            this.selectedVariantId = id;
            this.selectedVariantName = name;
            this.basePrice = price;
            this.selectedOriginalPrice = originalPrice;
            this.selectedDiscount = discount;
            this.selectedStock = stock;
            this.mainImage = image;
            this.variantPackQty = quantity || 1;
            this.updateFinalPrice();
            this.enforceLocationLimit();
            // Sync gallery active thumbnail
            for (let i = 0; i < this.galleryImages.length; i++) {
                if (this.galleryImages[i].src === image) {
                    this.activeGalleryIdx = i;
                    break;
                }
            }
        },

        addToCart() {
            if (!this.selectedVariantId) return;

            if (this.customLogoUploading) {
                alert('Please wait for your logo to finish uploading.');
                return;
            }
            if (this.hasCustomLogo && !this.customLogoPath) {
                this.customLogoError = 'Please upload your logo to continue, or uncheck the custom logo add-on.';
                alert('Please upload your custom logo, or uncheck the "Custom Company Logo & Design" add-on.');
                return;
            }

            if (this.skipForNow) {
                this.locations = [{
                    id: 'skip-setup-later',
                    name: 'Skip for now (Link later)'
                }];
            } else {
                // If locations list is empty but search box has a value, auto-confirm it (if within max limit)
                if (this.locations.length < this.maxAllowedLocations()) {
                    if (this.linkMode === 'direct' && (this.tempDirectInput || '').trim()) {
                        this.addLocation();
                    } else if (this.linkMode === 'search') {
                        const input = document.getElementById('google-places-input');
                        if (this.tempPlaceName || (input && input.value.trim())) {
                            this.addLocation();
                        }
                    }
                }

                if (this.locations.length === 0) {
                    alert('Please search your business location, enter a direct review link, or select "Skip for now".');
                    return;
                }
            }

            const cart = JSON.parse(localStorage.getItem('rb_cart') || '[]');
            const finalVariantName = this.hasCustomLogo ? (this.selectedVariantName + ' + Custom Logo') : this.selectedVariantName;
            
            const itemQty = parseInt(this.selectedQty) || 1;

            const newItem = {
                variantId: this.selectedVariantId,
                name: finalVariantName,
                productName: '{{ $product->name }}',
                price: parseFloat(this.selectedPrice),
                hasCustomLogo: this.hasCustomLogo,
                customLogoFee: this.hasCustomLogo ? 2.99 : 0.00,  // Custom Logo Price
                customLogoPath: this.hasCustomLogo ? this.customLogoPath : '',
                customLogoName: this.hasCustomLogo ? this.customLogoName : '',
                image: this.mainImage,
                placeId: this.locations[0].id, // Fallback for existing checkout
                placeName: this.locations[0].name, // Fallback for existing checkout
                locationList: this.locations, // Full array for enhanced features
                locationText: this.locations.map(l => l.name).join(', '),
                qty: itemQty
            };

            const existingIdx = cart.findIndex(i => 
                i.variantId === newItem.variantId && 
                JSON.stringify(i.locationList || []) === JSON.stringify(newItem.locationList)
            );
            
            if (existingIdx > -1) {
                cart[existingIdx].qty += newItem.qty;
            } else {
                cart.push(newItem);
            }

            localStorage.setItem('rb_cart', JSON.stringify(cart));
            window.dispatchEvent(new CustomEvent('cart-updated'));
            window.dispatchEvent(new CustomEvent('open-cart'));

            // Analytics: add-to-cart beacon (fire and forget)
            try {
                fetch('{{ route('track.event') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        type: 'add_to_cart',
                        variant_id: newItem.variantId,
                        quantity: newItem.qty,
                        value: (parseFloat(newItem.price) || 0) * (newItem.qty || 1)
                    }),
                    keepalive: true
                }).catch(function () {});
            } catch (e) {}
        }
    };
}

function initAutocomplete() {
    const input = document.getElementById('google-places-input');
    if (!input || input.dataset.autocompleteBound) return;
    input.dataset.autocompleteBound = "true";

    const autocomplete = new google.maps.places.Autocomplete(input, {
        types: ['establishment'],
        componentRestrictions: { country: 'gb' },
    });

    autocomplete.addListener('place_changed', function () {
        const place = autocomplete.getPlace();
        if (place) {
            const pId = place.place_id || ('loc-' + Date.now());
            
            let pName = input.value;
            if (place.name && place.formatted_address) {
                if (place.formatted_address.toLowerCase().includes(place.name.toLowerCase())) {
                    pName = place.formatted_address;
                } else {
                    pName = place.name + ' - ' + place.formatted_address;
                }
            } else if (place.name) {
                pName = place.name;
            } else if (place.formatted_address) {
                pName = place.formatted_address;
            }

            window.dispatchEvent(new CustomEvent('place-selected', {
                detail: {
                    id: pId,
                    name: pName
                }
            }));
        }
    });
}
</script>

@endsection
