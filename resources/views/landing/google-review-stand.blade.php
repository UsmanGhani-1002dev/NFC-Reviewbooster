@extends('layouts.guest')

@section('title', 'NFC Google Review Stands for UK Businesses — Professional & Effective')
@section('meta_description', 'Boost your reviews with a professional Google Review Stand. Perfect for reception desks, restaurant tables, and checkout counters. NFC-enabled & QR-ready.')
@section('meta_keywords', 'google review stand, NFC review stand, review plaque for desk, restaurant google review stand, google reviews for business')

@section('content')
<div class="bg-white">
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-br from-[#1800ad] to-[#142D63] py-20 px-4 sm:px-6 lg:px-8 overflow-hidden">
        <div class="max-w-6xl mx-auto text-center relative z-10">
            <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-6 font-ubuntu">
                Turn Every Desk into a <br class="hidden md:block"> <span class="text-[#0CC0DF]">Review Collector</span>
            </h1>
            <p class="text-xl text-white/90 mb-10 max-w-3xl mx-auto font-mulish leading-relaxed">
                NFC Google Review Stands are the perfect choice for physical locations. Place them where customers spend time and watch your reviews grow.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="#products" class="bg-white text-[#1800ad] px-8 py-4 rounded-2xl font-bold text-lg hover:bg-gray-100 transition-all shadow-xl hover:-translate-y-1">View Review Stands</a>
                <a href="{{ route('shop.index') }}" class="bg-white/10 text-white border border-white/30 px-8 py-4 rounded-2xl font-bold text-lg hover:bg-white/20 transition-all">Browse all Products</a>
            </div>
        </div>
    </div>

    <!-- Product Grid -->
    <div id="products" class="bg-gray-50 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-[#1800ad] mb-4 font-ubuntu">Professional Google Review Stands</h2>
                <p class="text-gray-600 font-mulish max-w-2xl mx-auto">High-quality, eye-catching stands designed to grab attention and invite feedback.</p>
            </div>

            @foreach($products as $product)
            <div class="mb-20">

                <!-- Variants Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($product->variants as $variant)
                    <a href="{{ route('shop.show', ['product' => $product->slug, 'variant' => $variant->id]) }}" class="group mt-4 block">
                        <div class="relative bg-white rounded-2xl shadow-md hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border border-gray-100">
                            <!-- Discount Badge -->
                            @if($variant->discount_percent > 0)
                            <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 z-10">
                                <span class="bg-[#1800ad] text-white text-sm font-bold px-5 py-2 rounded-full shadow-lg whitespace-nowrap">
                                    save {{ $variant->discount_percent }}%
                                </span>
                            </div>
                            @endif

                            <!-- Best Value Badge -->
                            @if($variant->is_best_value)
                            <div class="absolute bottom-[140px] right-0 z-10">
                                <div class="bg-[#4285F4] text-white px-5 py-2 rounded-l-lg font-bold text-sm flex items-center gap-1.5 shadow-lg">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    Best value
                                </div>
                            </div>
                            @endif

                            <!-- Product Image (variant image first, fallback to product image) -->
                            <div class="bg-[#d8e4ef] p-8 flex items-center justify-center md:h-[380px] h-[280px]  transition-all duration-500 overflow-hidden rounded-t-2xl">
                                @if($variant->image)
                                    <img src="{{ asset('storage/' . $variant->image) }}" alt="{{ $variant->name }}" class="max-h-48 w-auto object-contain group-hover:scale-110 transition-transform duration-500" loading="lazy">
                                @elseif($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $variant->name }}" class="max-h-48 w-auto object-contain group-hover:scale-110 transition-transform duration-500" loading="lazy">
                                @else
                                    <div class="w-40 h-40 bg-white rounded-2xl shadow-inner flex items-center justify-center">
                                        <svg class="w-20 h-20 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Product Info -->
                            <div class="p-6 text-center">
                                <!-- Star Rating -->
                                <div class="flex justify-center mb-3">
                                    @for($i = 0; $i < 5; $i++)
                                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    @endfor
                                </div>

                                <!-- Variant Name -->
                                <h3 class="text-lg font-semibold text-[#1800ad] mb-2 font-ubuntu">{{ $variant->name }}</h3>

                                <!-- Pricing -->
                                <div class="flex items-center justify-center gap-3">
                                    <span class="text-2xl font-bold text-[#1800ad]">£{{ number_format($variant->price, 2) }}</span>
                                    @if($variant->original_price > $variant->price)
                                    <span class="text-lg text-gray-400 line-through">£{{ number_format($variant->original_price, 2) }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endforeach

            @if($products->isEmpty())
            <div class="text-center py-20">
                <svg class="w-24 h-24 text-gray-300 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <h3 class="text-2xl font-bold text-gray-400 font-ubuntu">No products available yet</h3>
                <p class="text-gray-400 mt-2 font-mulish">Check back soon for our amazing products!</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Features Section -->
    <div class="bg-[#1800ad] py-20 text-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl md:text-5xl font-bold mb-16 text-center font-ubuntu">Why Choose <span class="text-green-300">NFC Review Stands?</span></h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center mx-auto mb-6 text-green-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </div>
                    <h4 class="font-bold mb-2">High Visibility</h4>
                    <p class="text-white/70 text-sm">Large format ensures customers see it immediately at your checkout or desk.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center mx-auto mb-6 text-green-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <h4 class="font-bold mb-2">Professional Finish</h4>
                    <p class="text-white/70 text-sm">Durable, high-quality material that looks great in any professional environment.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center mx-auto mb-6 text-green-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </div>
                    <h4 class="font-bold mb-2">Dual Technology</h4>
                    <p class="text-white/70 text-sm">Full NFC chip embedded inside with high-resolution QR output on the exterior.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center mx-auto mb-6 text-green-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <h4 class="font-bold mb-2">Free Delivery</h4>
                    <p class="text-white/70 text-sm">Dispatched within 48 hours with free tracked delivery across the UK.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
