@extends('layouts.guest')

@section('title', 'NFC Google Review Cards UK — Tap to Review Instantly')
@section('meta_description', 'Get the #1 NFC Google review cards in the UK. One tap to collect verified customer reviews effortlessly. No app needed. Works on iPhone & Android. Free UK shipping.')
@section('meta_keywords', 'google review cards, NFC review card UK, tap review card, contactless review card, buy google review card, NFC tap cards')

@section('content')
<div class="bg-white">
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-br from-[#1800ad] to-[#142D63] py-20 px-4 sm:px-6 lg:px-8 overflow-hidden">
        <div class="max-w-6xl mx-auto text-center relative z-10">
            <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-6 font-ubuntu">
                The Smartest Way to Get <br class="hidden md:block"> <span class="text-[#0CC0DF]">More Google Reviews</span>
            </h1>
            <p class="text-xl text-white/90 mb-10 max-w-3xl mx-auto font-mulish leading-relaxed">
                NFC Google Review Cards are the #1 tool for UK businesses to skyrocket their online reputation. One tap, one second, one review.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="#products" class="bg-white text-[#1800ad] px-8 py-4 rounded-2xl font-bold text-lg hover:bg-gray-100 transition-all shadow-xl hover:-translate-y-1">Get Your Cards Now</a>
                <a href="{{ route('howitswork') }}" class="bg-white/10 text-white border border-white/30 px-8 py-4 rounded-2xl font-bold text-lg hover:bg-white/20 transition-all">How It Works</a>
            </div>
        </div>
    </div>

    <!-- Product Grid -->
    <div id="products" class="bg-gray-50 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-[#1800ad] mb-4 font-ubuntu">Shop Our NFC Review Cards</h2>
                <p class="text-gray-600 font-mulish max-w-2xl mx-auto">Choose the perfect card for your business. All cards come pre-programmed and ready to use.</p>
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

    <!-- Benefits Section -->
    <div class="bg-gray-50 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
                <div>
                    <h2 class="text-3xl md:text-4xl font-bold text-[#1800ad] mb-8 font-ubuntu leading-tight">Why Every UK Business Needs <br> <span class="text-[#0cc0df]">NFC Review Cards</span></h2>
                    <div class="space-y-6">
                        <div class="flex gap-4">
                            <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center flex-shrink-0 text-[#0cc0df]">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-[#1800ad] mb-1">Instant Redirection</h4>
                                <p class="text-gray-600 text-sm font-mulish">One tap opens your Google profile. No more "I'll do it later" — get the review right there on the spot.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center flex-shrink-0 text-[#0cc0df]">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-[#1800ad] mb-1">Works Worldwide</h4>
                                <p class="text-gray-600 text-sm font-mulish">100% compatible with iPhone and Android. Includes a QR code for older devices so everyone can review you.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <div class="bg-gradient-to-br from-white to-gray-100 rounded-[3rem] p-4 shadow-2xl overflow-hidden">
                        <img src="{{ asset('images/cardsimages.jpg') }}" alt="NFC Google Review Card usage" class="rounded-[2.5rem] w-[300px]">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
