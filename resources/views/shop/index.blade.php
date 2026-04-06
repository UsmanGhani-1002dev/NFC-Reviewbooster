@extends('layouts.guest')

@section('title', 'Shop Google Review Cards & NFC Stands UK | Tap Review Cards')
@section('meta_description', 'Browse our range of NFC Google review cards, review stands, and keyrings. Trusted by 1,000+ UK businesses. Pre-programmed, ready to use, free UK shipping. From £16.99.')
@section('meta_keywords', 'google review card, NFC google review card, google review stand, review stand for counter, NFC review keyring, google review tap card UK, buy review cards')

@section('schema')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "CollectionPage",
    "name": "Shop NFC Google Review Cards & Stands",
    "description": "Browse our full range of NFC-enabled Google review cards, counter stands, and keyrings for UK businesses.",
    "url": "https://tapreviewcards.co.uk/shop",
    "mainEntity": {
        "@type": "ItemList",
        "itemListElement": [
            @foreach($products as $pIndex => $product)
                @foreach($product->variants as $vIndex => $variant)
                {
                    "@type": "ListItem",
                    "position": {{ $loop->parent->iteration * 10 + $loop->iteration }},
                    "item": {
                        "@type": "Product",
                        "name": "{{ $variant->name }}",
                        "url": "{{ route('shop.show', ['product' => $product->slug, 'variant' => $variant->id]) }}",
                        "image": "{{ $variant->image ? asset('storage/' . $variant->image) : ($product->image ? asset('storage/' . $product->image) : '') }}",
                        "offers": {
                            "@type": "Offer",
                            "price": "{{ $variant->price }}",
                            "priceCurrency": "GBP",
                            "availability": "https://schema.org/InStock",
                            "seller": {
                                "@type": "Organization",
                                "name": "Tap Review Cards"
                            }
                        }
                    }
                }@if(!($loop->parent->last && $loop->last)),@endif
                @endforeach
            @endforeach
        ]
    }
}
</script>
@endsection

@section('content')

<!-- Hero Banner -->
<section class="relative py-20 -mt-[100px] pt-[140px] bg-cover bg-center bg-no-repeat text-[#1800ad] font-mulish" style="background-image: url('https://d1yei2z3i6k35z.cloudfront.net/161/609bb9ff8ffc9_Groupedemasques1.jpg');">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-[#1800ad] mb-4 font-ubuntu">Our NFC Review Cards</h1>
        <p class="text-xl text-[#142D63] max-w-2xl mx-auto font-mulish">
            Get more Google reviews with our NFC-enabled tap cards and tags. Works on all modern smartphones.
        </p>
        <div class="flex justify-center gap-6 mt-8 flex-wrap">
            <div class="flex items-center gap-2 text-[#142D63]">
                <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                <span class="font-mulish">Free Shipping</span>
            </div>
            <div class="flex items-center gap-2 text-[#142D63]">
                <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                <span class="font-mulish">30-Day Money Back</span>
            </div>
            <div class="flex items-center gap-2 text-[#142D63]">
                <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                <span class="font-mulish">100% Ready to Use</span>
            </div>
        </div>
    </div>
</div>

<!-- Products Grid -->
<div class="bg-gray-50 py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @foreach($products as $product)
        <div class="mb-20">
            <!-- Product Category Title -->
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-[#1800ad] font-ubuntu">{{ $product->name }}</h2>
                @if($product->subtitle)
                    <p class="text-gray-600 mt-2 text-lg font-mulish">{{ $product->subtitle }}</p>
                @endif
            </div>

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

<!-- Trust Badges Section -->
<div class="bg-gray-50 pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div class="space-y-3 bg-white rounded-3xl p-6 md:p-8 flex flex-col items-center text-center">
                <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto">
                    <svg class="w-8 h-8 text-[#00A0FF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div class="text-2xl font-bold text-[#1800ad] font-ubuntu">1,000+</div>
                <div class="text-gray-500 font-mulish text-sm">Businesses Trust Us</div>
            </div>
            <div class="space-y-3 bg-white rounded-3xl p-6 md:p-8 flex flex-col items-center text-center">
                <div class="w-16 h-16 bg-green-50 rounded-2xl flex items-center justify-center mx-auto">
                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="text-2xl font-bold text-[#1800ad] font-ubuntu">100%</div>
                <div class="text-gray-500 font-mulish text-sm">Ready to Use</div>
            </div>
            <div class="space-y-3 bg-white rounded-3xl p-6 md:p-8 flex flex-col items-center text-center">
                <div class="w-16 h-16 bg-purple-50 rounded-2xl flex items-center justify-center mx-auto">
                    <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                    </svg>
                </div>
                <div class="text-2xl font-bold text-[#1800ad] font-ubuntu">UK</div>
                <div class="text-gray-500 font-mulish text-sm">Based Company</div>
            </div>
            <div class="space-y-3 bg-white rounded-3xl p-6 md:p-8 flex flex-col items-center text-center">
                <div class="w-16 h-16 bg-yellow-50 rounded-2xl flex items-center justify-center mx-auto">
                    <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"/>
                    </svg>
                </div>
                <div class="text-2xl font-bold text-[#1800ad] font-ubuntu">Free</div>
                <div class="text-gray-500 font-mulish text-sm">Returns & Exchanges</div>
            </div>
        </div>
    </div>
</div>

<!-- Money Back Guarantee -->
   <section class="py-8 sm:px-6 lg:px-8 font-mulish">
        <div class="max-w-7xl mx-auto rounded-2xl bg-cover bg-center bg-no-repeat text-[#1800ad] text-center px-8 md:px-20 py-20"
            style="background-image: url('https://d1yei2z3i6k35z.cloudfront.net/161/609bb9ff8ffc9_Groupedemasques1.jpg');">
            <div class="flex justify-center gap-3 mb-4">
                <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5C18.515 5.613 19 6.632 19 7.999v2c0 5.591-3.824 10.29-9 11.622-5.176-1.332-9-6.03-9-11.622v-2c0-1.367.485-2.386 1.166-3H2.166z" clip-rule="evenodd"/></svg>
                <h2 class="text-3xl sm:text-4xl font-bold mb-4 leading-tight font-ubuntu">
                    30-Day Money Back Guarantee
                </h2>
            </div>
            <p class="text-lg sm:text-md mb-8 leading-relaxed font-mulish text-[#142D63]">
                If you're not completely satisfied with your purchase, return it within 30 days for a full refund. No questions asked.
            </p>
        </div>
    </section>

@endsection
