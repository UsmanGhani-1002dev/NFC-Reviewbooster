@extends('layouts.guest')
@section('meta_robots', 'noindex, follow')
@section('content')

<div class="bg-gray-50 min-h-screen py-12 -mt-[100px] pt-[140px]">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <!-- Success Animation -->
        <div class="mb-8">
            <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6 animate-bounce">
                <svg class="w-12 h-12 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
            </div>
            <h1 class="text-3xl md:text-4xl font-bold text-[#142D63] mb-3 font-ubuntu">Order Confirmed!</h1>
            <p class="text-lg text-gray-600 font-mulish">Thank you for your purchase. Your order has been placed successfully.</p>
        </div>

        <!-- Order Details -->
        <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 text-left mb-8">
            <div class="flex items-center justify-between mb-6 pb-6 border-b border-gray-100">
                <div>
                    <span class="text-sm text-gray-500 font-mulish">Order Number</span>
                    <p class="text-xl font-bold text-[#142D63] font-ubuntu">{{ $order->order_number }}</p>
                </div>
                <div class="text-right">
                    <span class="text-sm text-gray-500 font-mulish">Date</span>
                    <p class="text-sm font-semibold text-gray-700 font-mulish">{{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>
            </div>

            <!-- Items -->
            <h3 class="text-sm font-bold uppercase tracking-wider text-gray-400 mb-3 font-mulish">Items Ordered</h3>
            @foreach($order->items as $item)
            <div class="flex items-center justify-between py-3 border-b border-gray-50">
                <div>
                    <p class="font-semibold text-[#142D63] font-ubuntu">{{ $item->product_name }}</p>
                    <p class="text-sm text-gray-500 font-mulish">{{ $item->variant_name }} × {{ $item->quantity }}</p>
                </div>
                <span class="font-bold text-[#142D63]">£{{ number_format($item->total, 2) }}</span>
            </div>
            @endforeach

            <!-- Total -->
            <div class="flex items-center justify-between pt-4 mt-2">
                <span class="text-lg font-bold text-[#142D63] font-ubuntu">Total Paid</span>
                <span class="text-2xl font-bold text-green-600 font-ubuntu">£{{ number_format($order->total, 2) }}</span>
            </div>

            <!-- Shipping Address -->
            @if($order->shipping_address)
            <div class="mt-6 pt-6 border-t border-gray-100">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-400 mb-2 font-mulish">Shipping To</h3>
                <p class="text-gray-700 font-mulish">
                    {{ $order->customer_name }}<br>
                    {{ $order->shipping_address['line1'] ?? '' }}<br>
                    @if(!empty($order->shipping_address['line2'])){{ $order->shipping_address['line2'] }}<br>@endif
                    {{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['postcode'] ?? '' }}<br>
                    {{ $order->shipping_address['country'] ?? '' }}
                </p>
            </div>
            @endif

            <!-- Business Details -->
            @if(!empty($order->google_places))
            <div class="mt-6 pt-6 border-t border-gray-100">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-400 mb-4 font-mulish">Linked Locations</h3>
                <div class="space-y-4">
                    @foreach($order->google_places as $loc)
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 relative group transition-all hover:border-green-400">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-green-100 text-green-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-[#142D63] font-bold font-ubuntu text-sm">{{ $loc['name'] }}</p>
                                <p class="text-[10px] text-gray-400 font-mulish mt-1">Place ID: {{ $loc['id'] }}</p>
                                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($loc['name']) }}&query_place_id={{ $loc['id'] }}" 
                                   target="_blank" 
                                   class="inline-flex items-center mt-2 text-[10px] font-bold text-[#00A0FF] hover:underline uppercase tracking-wider">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    View on Maps
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @elseif($order->google_place_name)
            <div class="mt-6 pt-6 border-t border-gray-100">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-400 mb-2 font-mulish">Business Details</h3>
                <p class="text-[#142D63] font-bold font-ubuntu text-sm">{{ $order->google_place_name }}</p>
                <p class="text-xs text-gray-400 font-mulish">Place ID: {{ $order->google_place_id }}</p>
                @if($order->google_maps_link)
                <a href="{{ $order->google_maps_link }}" target="_blank" class="inline-flex items-center mt-2 text-xs font-bold text-[#00A0FF] hover:underline uppercase">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Verify Location
                </a>
                @endif
            </div>
            @endif
        </div>

        <!-- What's Next -->
        <div class="bg-blue-50 rounded-2xl p-6 border border-blue-100 mb-8 text-left">
            <h3 class="font-bold text-[#142D63] mb-3 font-ubuntu">What happens next?</h3>
            <div class="space-y-3 text-sm text-gray-600 font-mulish">
                <div class="flex gap-3">
                    <div class="w-6 h-6 bg-[#00A0FF] text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">1</div>
                    <p>You'll receive a confirmation email at <strong>{{ $order->customer_email }}</strong></p>
                </div>
                <div class="flex gap-3">
                    <div class="w-6 h-6 bg-[#00A0FF] text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">2</div>
                    <p>We'll prepare your NFC cards with your business details</p>
                </div>
                <div class="flex gap-3">
                    <div class="w-6 h-6 bg-[#00A0FF] text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">3</div>
                    <p>Your cards will be shipped within 48 hours</p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('shop.index') }}" class="bg-[#142D63] hover:bg-blue-900 text-white px-8 py-3 rounded-xl font-semibold transition-all duration-300 font-mulish">
                Continue Shopping
            </a>
            <a href="{{ route('home') }}" class="border-2 border-[#142D63] text-[#142D63] hover:bg-[#142D63] hover:text-white px-8 py-3 rounded-xl font-semibold transition-all duration-300 font-mulish">
                Back to Home
            </a>
        </div>
    </div>
</div>

@endsection
