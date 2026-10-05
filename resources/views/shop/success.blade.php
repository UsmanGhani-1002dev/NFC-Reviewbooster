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
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 pb-6 border-b border-gray-100">
                <div>
                    <span class="text-xs text-gray-400 uppercase tracking-wider font-mulish font-bold">Order Number</span>
                    <p class="text-xl font-bold text-[#142D63] font-ubuntu">{{ $order->order_number }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $order->status_badge }}">
                        {{ $order->status === 'completed' ? 'Dispatched' : ucfirst($order->status) }}
                    </span>
                    <a href="{{ route('orders.track', ['q' => $order->order_number]) }}" target="_blank"
                       class="inline-flex items-center gap-1.5 bg-blue-50 hover:bg-blue-100 text-[#00A0FF] px-3 py-1.5 rounded-xl font-bold text-xs transition-colors font-ubuntu">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <span>Track Delivery</span>
                    </a>
                </div>
            </div>

            <!-- Delivery Progress Stepper -->
            <div class="mb-8 bg-gray-50/80 p-5 rounded-2xl border border-gray-100">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider font-mulish mb-4">Delivery Progress</h3>
                @php
                    $isPaid = in_array($order->status, ['paid', 'shipped', 'completed']);
                    $isDispatched = in_array($order->status, ['shipped', 'completed']);
                    $isProcessing = $isPaid && !$isDispatched;
                    $isCancelled = $order->status === 'cancelled';
                    $progressPercent = $isDispatched ? 66 : ($isPaid ? 33 : 0);

                    $checkIcon = <<<'SVG'
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    SVG;
                @endphp

                @if($isCancelled)
                    <div class="p-3 bg-red-50 text-red-700 rounded-xl font-bold text-xs text-center border border-red-200">
                        ❌ This order has been cancelled. If you have any questions, please contact our support team.
                    </div>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 relative">
                        <!-- Progress line (desktop only) -->
                        <div class="hidden sm:block absolute top-4 left-[12.5%] right-[12.5%] h-1 bg-gray-200 rounded-full z-0">
                            <div class="h-full bg-green-600 rounded-full transition-all duration-500" style="width: {{ $progressPercent }}%"></div>
                        </div>

                        <!-- Step 1: Received -->
                        <div class="flex items-center sm:flex-col sm:items-center text-left sm:text-center gap-2 relative z-10">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center bg-green-600 text-white font-bold text-xs shadow-sm">
                                {!! $checkIcon !!}
                            </div>
                            <div>
                                <p class="font-bold text-xs text-[#142D63] font-ubuntu">Received</p>
                                <p class="text-[10px] text-gray-400 font-mulish">{{ $order->created_at->format('M d, H:i') }}</p>
                            </div>
                        </div>

                        <!-- Step 2: Paid -->
                        <div class="flex items-center sm:flex-col sm:items-center text-left sm:text-center gap-2 relative z-10">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs {{ $isPaid ? 'bg-green-600 text-white shadow-sm' : 'bg-gray-200 text-gray-400' }}">
                                @if($isPaid) {!! $checkIcon !!} @else 2 @endif
                            </div>
                            <div>
                                <p class="font-bold text-xs text-[#142D63] font-ubuntu">Paid</p>
                                <p class="text-[10px] text-gray-400 font-mulish">{{ $isPaid ? 'Paid via Stripe' : 'Pending' }}</p>
                            </div>
                        </div>

                        <!-- Step 3: Dispatched -->
                        <div class="flex items-center sm:flex-col sm:items-center text-left sm:text-center gap-2 relative z-10">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs shadow-sm transition-all {{ $isDispatched ? 'bg-green-600 text-white' : ($isProcessing ? 'bg-amber-400 text-white ring-4 ring-amber-100' : 'bg-gray-200 text-gray-400') }}">
                                @if($isDispatched) {!! $checkIcon !!} @elseif($isProcessing) <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> @else 3 @endif
                            </div>
                            <div>
                                <p class="font-bold text-xs {{ $isDispatched ? 'text-[#142D63]' : ($isProcessing ? 'text-amber-700' : 'text-gray-400') }} font-ubuntu">
                                    {{ $isDispatched ? 'Dispatched' : 'Processing' }}
                                </p>
                                <p class="text-[10px] text-gray-400 font-mulish">
                                    {{ $isDispatched ? ($order->shipped_at ? $order->shipped_at->format('M d, Y') : 'On its way') : ($isProcessing ? 'Preparing cards' : 'Pending') }}
                                </p>
                            </div>
                        </div>

                        <!-- Step 4: Delivered -->
                        <div class="flex items-center sm:flex-col sm:items-center text-left sm:text-center gap-2 relative z-10">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center bg-gray-200 text-gray-400 font-bold text-xs">
                                4
                            </div>
                            <div>
                                <p class="font-bold text-xs text-gray-400 font-ubuntu">Delivered</p>
                                <p class="text-[10px] text-gray-300 font-mulish">Royal Mail</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Royal Mail / Courier Parcel Tracking Box (If shipped & tracking number added) -->
            @if($order->has_tracking)
                <div class="mb-8 p-4 bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-700 text-white rounded-2xl shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center text-white flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h4 class="font-bold text-sm font-ubuntu">{{ $order->carrier ?: 'Royal Mail' }} Tracked Delivery</h4>
                                <span class="bg-emerald-400 text-gray-900 text-[9px] font-extrabold px-2 py-0.5 rounded-full uppercase">Dispatched</span>
                            </div>
                            <p class="text-xs text-blue-100 font-mulish mt-0.5">
                                Tracking #: <strong class="text-white font-mono select-all">{{ $order->tracking_number }}</strong>
                            </p>
                        </div>
                    </div>
                    @if($order->tracking_url)
                        <a href="{{ $order->tracking_url }}" target="_blank" rel="noopener noreferrer" 
                           class="inline-flex items-center gap-1.5 bg-white text-blue-900 hover:bg-blue-50 px-4 py-2 rounded-xl font-bold text-xs shadow transition-colors font-ubuntu flex-shrink-0">
                            <span>Track Package</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    @endif
                </div>
            @endif

            <!-- Items -->
            @php $hasItemLocations = $order->items->contains(fn($i) => !empty($i->locations)); @endphp
            <h3 class="text-sm font-bold uppercase tracking-wider text-gray-400 mb-3 font-mulish">Items Ordered</h3>
            @foreach($order->items as $item)
            <div class="py-3 border-b border-gray-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-start gap-4">
                        <div class="w-16 h-16 bg-[#d8e4ef] rounded-xl flex items-center justify-center overflow-hidden flex-shrink-0">
                            @if($item->variant && $item->variant->image)
                                <img src="{{ asset('storage/' . $item->variant->image) }}" class="w-full h-full object-contain" alt="{{ $item->product_name }}">
                            @elseif($item->variant && $item->variant->product && $item->variant->product->image)
                                <img src="{{ asset('storage/' . $item->variant->product->image) }}" class="w-full h-full object-contain" alt="{{ $item->product_name }}">
                            @else
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            @endif
                        </div>

                        <div>
                            <p class="font-semibold text-[#142D63] font-ubuntu">{{ $item->product_name }}</p>
                            @php
                                $hasLogo = str_contains(strtolower($item->variant_name), 'custom logo');
                                $cleanVariantName = trim(str_ireplace('+ Custom Logo', '', $item->variant_name));
                            @endphp
                            <p class="text-sm text-gray-500 font-mulish">{{ $cleanVariantName }} × {{ $item->quantity }}</p>
                            @if($hasLogo)
                            <div class="mt-1 inline-flex items-center gap-1 bg-blue-50 text-blue-700 border border-blue-200 text-xs font-bold px-2 py-0.5 rounded-md">
                                <span>🎨 Custom Logo & Design (+£2.99)</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    <span class="font-bold text-[#142D63]">£{{ number_format($item->total, 2) }}</span>
                </div>
                @if(!empty($item->locations))
                <div class="mt-2.5 bg-gray-50 rounded-lg p-3 border border-gray-100">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5 font-mulish">Linked Location(s) for this pack</p>
                    <div class="space-y-1.5">
                        @foreach($item->locations as $loc)
                        <div class="flex items-start gap-2">
                            <svg class="w-3.5 h-3.5 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="text-xs text-gray-700 font-mulish leading-tight flex-1">{{ $loc['name'] }}</span>
                            @if(!str_starts_with($loc['id'] ?? '', 'skip-'))
                                @if(filter_var($loc['id'] ?? '', FILTER_VALIDATE_URL) || filter_var($loc['name'] ?? '', FILTER_VALIDATE_URL))
                                    @php $directLink = filter_var($loc['id'] ?? '', FILTER_VALIDATE_URL) ? $loc['id'] : $loc['name']; @endphp
                                    <a href="{{ $directLink }}" target="_blank" class="text-[10px] font-bold text-[#00A0FF] hover:underline uppercase tracking-wider whitespace-nowrap">Link ↗</a>
                                @elseif(!str_starts_with($loc['id'] ?? '', 'loc-') && !str_starts_with($loc['id'] ?? '', 'direct-'))
                                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($loc['name']) }}&query_place_id={{ $loc['id'] }}" target="_blank" class="text-[10px] font-bold text-[#00A0FF] hover:underline uppercase tracking-wider whitespace-nowrap">Map</a>
                                @else
                                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($loc['name']) }}" target="_blank" class="text-[10px] font-bold text-[#00A0FF] hover:underline uppercase tracking-wider whitespace-nowrap">Map</a>
                                @endif
                            @else
                                <span class="text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">Will link later</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            @endforeach

            <!-- Total Breakdown -->
            <div class="pt-4 mt-2 space-y-2 text-sm font-mulish">
                <div class="flex justify-between text-gray-600">
                    <span>Items Subtotal</span>
                    <span>£{{ number_format($order->total - ($order->shipping_fee ?? 0), 2) }}</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>UK Shipping & Delivery</span>
                    @if(($order->shipping_fee ?? 0) > 0)
                        <span class="font-bold text-[#142D63]">£{{ number_format($order->shipping_fee, 2) }}</span>
                    @else
                        <span class="text-green-600 font-bold">FREE</span>
                    @endif
                </div>
                <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                    <span class="text-lg font-bold text-[#142D63] font-ubuntu">Total Paid</span>
                    <span class="text-2xl font-bold text-green-600 font-ubuntu">£{{ number_format($order->total, 2) }}</span>
                </div>
            </div>

            <!-- Shipping / Delivery Address -->
            @if($order->shipping_address)
            <div class="mt-6 pt-6 border-t border-gray-100">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-400 mb-3 font-mulish flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#00A0FF]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>Delivery Address</span>
                </h3>
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 text-sm font-mulish text-gray-700 leading-relaxed">
                    <p class="font-bold text-gray-900 font-ubuntu text-base mb-1">{{ $order->customer_name }}</p>
                    <p>{{ $order->shipping_address['line1'] ?? '' }}</p>
                    @if(!empty($order->shipping_address['line2']))<p>{{ $order->shipping_address['line2'] }}</p>@endif
                    <p>{{ $order->shipping_address['city'] ?? '' }}{{ !empty($order->shipping_address['county']) ? ', ' . $order->shipping_address['county'] : '' }}, {{ $order->shipping_address['postcode'] ?? '' }}</p>
                    <p class="font-bold text-gray-800 uppercase mt-0.5">{{ $order->shipping_address['country'] ?? 'United Kingdom' }}</p>
                </div>
            </div>
            @endif

            <!-- Business Details (aggregated fallback for orders without per-item locations) -->
            @if(!$hasItemLocations && !empty($order->google_places))
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
                                @if(str_starts_with($loc['id'] ?? '', 'skip-'))
                                    <p class="text-xs text-amber-600 font-mulish mt-1 font-semibold">⚡ Linking skipped — our team will contact you to link your cards before dispatch.</p>
                                @elseif(filter_var($loc['id'] ?? '', FILTER_VALIDATE_URL) || filter_var($loc['name'] ?? '', FILTER_VALIDATE_URL))
                                    @php $directLink = filter_var($loc['id'] ?? '', FILTER_VALIDATE_URL) ? $loc['id'] : $loc['name']; @endphp
                                    <a href="{{ $directLink }}" target="_blank" class="inline-flex items-center mt-2 text-[10px] font-bold text-[#00A0FF] hover:underline uppercase tracking-wider">
                                        Open Review Link ↗
                                    </a>
                                @else
                                    <p class="text-[10px] text-gray-400 font-mulish mt-1">Place ID: {{ $loc['id'] }}</p>
                                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($loc['name']) }}{{ (!str_starts_with($loc['id'] ?? '', 'loc-') && !str_starts_with($loc['id'] ?? '', 'direct-')) ? '&query_place_id=' . $loc['id'] : '' }}" 
                                       target="_blank" 
                                       class="inline-flex items-center mt-2 text-[10px] font-bold text-[#00A0FF] hover:underline uppercase tracking-wider">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        View on Maps
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @elseif(!$hasItemLocations && $order->google_place_name)
            <div class="mt-6 pt-6 border-t border-gray-100">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-400 mb-2 font-mulish">Business Details</h3>
                <p class="text-[#142D63] font-bold font-ubuntu text-sm">{{ $order->google_place_name }}</p>
                @if(str_starts_with($order->google_place_id ?? '', 'skip-'))
                    <p class="text-xs text-amber-600 font-mulish mt-1 font-semibold">⚡ Linking skipped — our team will contact you before dispatch.</p>
                @elseif($order->google_maps_link)
                    <a href="{{ $order->google_maps_link }}" target="_blank" class="inline-flex items-center mt-2 text-xs font-bold text-[#00A0FF] hover:underline uppercase">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ filter_var($order->google_maps_link, FILTER_VALIDATE_URL) && !str_contains($order->google_maps_link, 'maps/search') ? 'Open Review Link' : 'Verify Location' }}
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
