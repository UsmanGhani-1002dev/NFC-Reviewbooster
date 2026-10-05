@extends('layouts.app')

@section('title', 'Order ' . $order->order_number)

@section('content')
<div class="max-w-5xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Breadcrumbs & Header -->
    <div class="mb-6">
        <a href="{{ route('user.orders.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-gray-500 hover:text-[#00A0FF] font-mulish transition-colors mb-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            <span>Back to My Orders</span>
        </a>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl sm:text-3xl font-bold text-[#142D63] font-ubuntu">Order {{ $order->order_number }}</h1>
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $order->status_badge }}">
                        {{ $order->status === 'completed' ? 'Dispatched' : ucfirst($order->status) }}
                    </span>
                </div>
                <p class="text-xs text-gray-500 font-mulish mt-1">Placed on {{ $order->created_at->format('F d, Y \a\t g:i A') }}</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('orders.track', ['q' => $order->order_number]) }}" target="_blank"
                   class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-xl font-bold text-xs transition-colors font-ubuntu">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <span>Public Status</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Order Timeline / Status Steps -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 mb-6">
        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider font-mulish mb-6">Order Status Timeline</h3>

        @php
            $isPaid = in_array($order->status, ['paid', 'shipped', 'completed']);
            // The 3rd step is dynamic: "Processing" (waiting) while paid-but-not-completed,
            // and it becomes "Dispatched" (van) once the order is completed.
            // Delivered stays inactive for now (future task).
            $isDispatched = in_array($order->status, ['shipped', 'completed']);
            $isProcessing = $isPaid && !$isDispatched;
            $isCancelled = $order->status === 'cancelled';

            $progressPercent = $isDispatched ? 66 : ($isPaid ? 33 : 0);

            $checkIcon = <<<'SVG'
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            SVG;
        @endphp

        @if($isCancelled)
            <div class="p-4 bg-red-50 text-red-700 rounded-xl font-bold text-sm text-center border border-red-200">
                ❌ This order has been cancelled. If you have any questions, please contact our support team.
            </div>
        @else
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-6 sm:gap-4 relative">
            <!-- Progress connector line (desktop only) -->
            <div class="hidden sm:block absolute top-5 left-[12.5%] right-[12.5%] h-1 bg-gray-200 rounded-full z-0">
                <div class="h-full bg-green-600 rounded-full transition-all duration-500" style="width: {{ $progressPercent }}%"></div>
            </div>

            <!-- Step 1: Received -->
            <div class="flex sm:flex-col items-center sm:items-center text-left sm:text-center gap-3 sm:gap-2 relative z-10">
                <div class="w-10 h-10 rounded-full flex items-center justify-center bg-green-600 text-white shadow-md">
                    {!! $checkIcon !!}
                </div>
                <div>
                    <p class="font-bold text-xs text-[#142D63] font-ubuntu">Received</p>
                    <p class="text-[11px] text-gray-400 font-mulish">{{ $order->created_at->format('M d, H:i') }}</p>
                </div>
            </div>

            <!-- Step 2: Paid -->
            <div class="flex sm:flex-col items-center sm:items-center text-left sm:text-center gap-3 sm:gap-2 relative z-10">
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm {{ $isPaid ? 'bg-green-600 text-white shadow-md' : 'bg-gray-200 text-gray-400' }}">
                    @if($isPaid)
                        {!! $checkIcon !!}
                    @else
                        2
                    @endif
                </div>
                <div>
                    <p class="font-bold text-xs text-[#142D63] font-ubuntu">Paid</p>
                    <p class="text-[11px] text-gray-400 font-mulish">{{ $isPaid ? 'Paid via Stripe' : 'Pending' }}</p>
                </div>
            </div>

            <!-- Step 3: Processing (waiting) -> Dispatched (van) once completed -->
            <div class="flex sm:flex-col items-center sm:items-center text-left sm:text-center gap-3 sm:gap-2 relative z-10">
                <div class="w-10 h-10 rounded-full flex items-center justify-center shadow-md transition-all {{ $isDispatched ? 'bg-green-600 text-white' : ($isProcessing ? 'bg-amber-400 text-white ring-4 ring-amber-100 animate-pulse' : 'bg-gray-200 text-gray-400') }}">
                    @if($isDispatched)
                        {!! $checkIcon !!}
                    @elseif($isProcessing)
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @else
                        3
                    @endif
                </div>
                <div>
                    <p class="font-bold text-xs {{ $isDispatched ? 'text-[#142D63]' : ($isProcessing ? 'text-amber-700' : 'text-gray-400') }} font-ubuntu">
                        {{ $isDispatched ? 'Dispatched' : 'Processing' }}
                    </p>
                    <p class="text-[11px] text-gray-400 font-mulish">
                        {{ $isDispatched ? ($order->shipped_at ? $order->shipped_at->format('M d, Y') : 'On its way') : ($isProcessing ? 'Preparing your order' : 'Pending') }}
                    </p>
                </div>
            </div>

            <!-- Step 4: Delivered (future stage — inactive for now) -->
            <div class="flex sm:flex-col items-center sm:items-center text-left sm:text-center gap-3 sm:gap-2 relative z-10">
                <div class="w-10 h-10 rounded-full flex items-center justify-center bg-gray-200 text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </div>
                <div>
                    <p class="font-bold text-xs text-gray-400 font-ubuntu">Delivered</p>
                    <p class="text-[11px] text-gray-300 font-mulish">—</p>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Tracking Card (If shipped) -->
    @if($order->has_tracking)
        <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-700 text-white rounded-3xl p-6 shadow-md mb-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center text-white flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-bold text-lg font-ubuntu">{{ $order->carrier ?: 'Royal Mail' }} Parcel Tracking</h3>
                            <span class="bg-emerald-500 text-white text-[10px] font-extrabold px-2 py-0.5 rounded-full uppercase">Active</span>
                        </div>
                        <p class="text-xs text-blue-100 font-mulish mt-1">
                            Tracking Number: <strong class="text-white font-mono text-sm select-all">{{ $order->tracking_number }}</strong>
                        </p>
                        @if($order->shipped_at)
                            <p class="text-[11px] text-blue-200 font-mulish mt-0.5">Dispatched on {{ $order->shipped_at->format('F d, Y \a\t g:i A') }}</p>
                        @endif
                    </div>
                </div>

                @if($order->tracking_url)
                    <a href="{{ $order->tracking_url }}" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-2 bg-white hover:bg-blue-50 text-blue-900 px-6 py-3 rounded-2xl font-bold text-sm shadow-lg transition-all duration-300 font-ubuntu flex-shrink-0">
                        <span>Track on {{ $order->carrier ?: 'Royal Mail' }}</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>
                @endif
            </div>
        </div>
    @endif

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left: Items & Locations (2 cols) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
                <h3 class="font-bold text-[#142D63] font-ubuntu text-lg mb-4">Order Items</h3>

                <div class="divide-y divide-gray-100">
                    @forelse($order->items as $item)
                        @php
                            $itemProduct = $item->variant?->product;
                        @endphp
                        <div class="py-4 first:pt-0 last:pb-0 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 sm:gap-4">
                            <div class="flex items-start gap-4">
                                <div class="w-16 h-16 bg-[#d8e4ef] rounded-2xl flex items-center justify-center flex-shrink-0">
                                    @if($item->variant && $item->variant->image)
                                        <img src="{{ asset('storage/' . $item->variant->image) }}" class="w-12 h-12 object-contain" alt="{{ $item->product_name }}">
                                    @elseif($itemProduct && $itemProduct->image)
                                        <img src="{{ asset('storage/' . $itemProduct->image) }}" class="w-12 h-12 object-contain" alt="{{ $item->product_name }}">
                                    @else
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    @endif
                                </div>

                                <div class="min-w-0">
                                    <h4 class="font-bold text-[#142D63] font-ubuntu text-base break-words">
                                        @if($itemProduct)
                                            <a href="{{ route('shop.show', $itemProduct->slug) }}" class="hover:text-blue-600 transition-colors">
                                                {{ $item->product_name }}
                                            </a>
                                        @else
                                            {{ $item->product_name }}
                                        @endif
                                    </h4>
                                    <p class="text-xs text-gray-500 font-mulish">{{ $item->variant_name }}</p>

                                    <p class="text-xs text-gray-400 font-mulish mt-1">
                                        Qty: <strong>{{ $item->quantity }}</strong> × £{{ number_format($item->unit_price, 2) }}
                                    </p>

                                    @if($item->custom_logo_path)
                                    @php
                                        $logoUrl = asset('storage/' . $item->custom_logo_path);
                                        $logoIsImage = \Illuminate\Support\Str::endsWith(strtolower($item->custom_logo_path), ['.png', '.jpg', '.jpeg', '.svg', '.webp']);
                                        $logoExt = pathinfo($item->custom_logo_path, PATHINFO_EXTENSION);
                                        $logoDownloadName = trim($order->order_number . ' - ' . preg_replace('/[\/\\\\:*?"<>|]/', '', $item->variant_name)) . ($logoExt ? '.' . $logoExt : '');
                                    @endphp
                                    <div class="mt-3 flex items-center gap-3 bg-blue-50 border border-blue-100 rounded-xl p-2.5 w-fit">
                                        <a href="{{ $logoUrl }}" target="_blank" class="w-12 h-12 rounded-lg bg-white border border-blue-100 flex items-center justify-center overflow-hidden flex-shrink-0">
                                            @if($logoIsImage)
                                                <img src="{{ $logoUrl }}" alt="Custom logo" class="w-full h-full object-contain">
                                            @else
                                                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            @endif
                                        </a>
                                        <div>
                                            <a href="{{ $logoUrl }}" target="_blank" download="{{ $logoDownloadName }}" class="text-[11px] font-bold text-blue-600 hover:underline inline-flex items-center gap-1">
                                                View / Download
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                            </a>
                                        </div>
                                    </div>
                                    @endif

                                    <!-- Linked Locations -->
                                    @if(!empty($item->locations) && is_array($item->locations))
                                        <div class="mt-3 bg-gray-50 p-3 rounded-xl border border-gray-100">
                                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Programmed Google Review Locations:</p>
                                            <div class="divide-y divide-gray-200">
                                                @foreach($item->locations as $loc)
                                                    <div class="flex items-start gap-2 text-xs font-semibold text-gray-700 font-mulish py-2 first:pt-0 last:pb-0">
                                                        <svg class="w-3.5 h-3.5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                        <span style="word-wrap:anywhere">{{ $loc['name'] ?? 'Location' }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="text-left sm:text-right pl-20 sm:pl-0 flex-shrink-0">
                                <span class="font-extrabold text-[#142D63] font-ubuntu text-base">£{{ number_format($item->total, 2) }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400 italic py-4 font-mulish">No items found for this order.</p>
                    @endforelse
                </div>
            </div>

            <!-- Customer Notes / Instructions -->
            @if($order->notes)
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
                    <h3 class="font-bold text-[#142D63] font-ubuntu text-base mb-2">Order Notes</h3>
                    <p class="text-sm text-gray-600 font-mulish">{!! nl2br(e($order->notes)) !!}</p>
                </div>
            @endif
        </div>

        <!-- Right: Address & Summary (1 col) -->
        <div class="space-y-6">
            <!-- Shipping Address Card -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
                <h3 class="font-bold text-[#142D63] font-ubuntu text-base mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#00A0FF]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>Shipping Address</span>
                </h3>

                <div class="text-sm font-mulish text-gray-600 space-y-1">
                    <p class="font-bold text-gray-900 text-base mb-1">{{ $order->customer_name }}</p>
                    <p>{{ $order->shipping_address['line1'] ?? '' }}</p>
                    @if(!empty($order->shipping_address['line2']))
                        <p>{{ $order->shipping_address['line2'] }}</p>
                    @endif
                    <p>{{ $order->shipping_address['city'] ?? '' }}{{ !empty($order->shipping_address['county']) ? ', ' . $order->shipping_address['county'] : '' }}</p>
                    <p class="font-bold text-gray-800 uppercase">{{ $order->shipping_address['postcode'] ?? '' }}</p>
                    <p>{{ $order->shipping_address['country'] ?? 'United Kingdom' }}</p>
                </div>

                <div class="mt-4 pt-4 border-t border-gray-100 text-xs font-mulish space-y-1">
                    <p class="text-gray-500">Email: <strong class="text-gray-800">{{ $order->customer_email }}</strong></p>
                    @if($order->customer_phone)
                        <p class="text-gray-500">Phone: <strong class="text-gray-800">{{ $order->customer_phone }}</strong></p>
                    @endif
                </div>
            </div>

            <!-- Financial Summary Card -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
                <h3 class="font-bold text-[#142D63] font-ubuntu text-base mb-4">Payment Summary</h3>

                <div class="space-y-2.5 text-sm font-mulish">
                    <div class="flex justify-between text-gray-600">
                        <span>Items Subtotal</span>
                        <span>£{{ number_format($order->total - $order->shipping_fee, 2) }}</span>
                    </div>

                    @if(!empty($order->discount_amount) && $order->discount_amount > 0)
                        <div class="flex justify-between text-purple-700 font-bold">
                            <span>👑 Partner Discount</span>
                            <span>-£{{ number_format($order->discount_amount, 2) }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between text-gray-600">
                        @if($order->shipping_fee > 0)
                            <span>{{ $order->shipping_method === 'express' ? 'Express Delivery (24h)' : 'Standard Delivery (48h)' }}</span>
                            <span class="font-bold text-[#142D63]">£{{ number_format($order->shipping_fee, 2) }}</span>
                        @else
                            <span>UK Shipping</span>
                            <span class="text-green-600 font-bold">FREE</span>
                        @endif
                    </div>
                    <div class="pt-3 border-t border-gray-100 flex justify-between items-center">
                        <span class="font-bold text-[#142D63] text-base font-ubuntu">Total Paid</span>
                        <span class="font-extrabold text-[#142D63] text-xl font-ubuntu">£{{ number_format($order->total, 2) }}</span>
                    </div>
                </div>

                <div class="mt-4 p-3 bg-green-50 border border-green-200 rounded-xl text-xs text-green-800 font-mulish flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span>Payment verified via Stripe</span>
                </div>
            </div>

            <!-- Support Box -->
            <div class="bg-gray-50 rounded-3xl p-6 border border-gray-200/80 text-center">
                <h4 class="font-bold text-[#142D63] font-ubuntu text-sm mb-1">Need help with your order?</h4>
                <p class="text-xs text-gray-500 font-mulish mb-4">Our UK support team is here to assist with address changes, custom graphics, or programming details.</p>
                <a href="{{ route('contact') }}" class="inline-block bg-white hover:bg-gray-100 text-[#142D63] border border-gray-300 font-bold px-4 py-2.5 rounded-xl text-xs font-ubuntu transition-colors shadow-sm">
                    Contact Support
                </a>
            </div>
        </div>
    </div>
</div>
@endsection