@extends('layouts.guest')

@section('title', 'Track Your Order | ' . config('app.name', 'Tap Review Cards'))
@section('meta_description', 'Track your ReviewBooster order status and Royal Mail package delivery in real time.')

@section('content')
<div class="bg-gray-50 py-12 -mt-[100px] pt-[140px] min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Search Header -->
        <div class="text-center max-w-2xl mx-auto mb-10">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100 text-xs font-bold uppercase tracking-wider mb-4">
                Royal Mail Order Tracking
            </span>
            <h1 class="text-3xl sm:text-5xl font-extrabold text-[#142D63] font-ubuntu mb-4">
                Track Your Order Status
            </h1>
            <p class="text-gray-600 font-mulish text-base">
                Enter your Order Number (e.g. <span class="font-mono text-indigo-600 font-bold">RB-12345678</span>), Email Address, or Royal Mail Tracking Code below.
            </p>

            <!-- Search Form -->
            <form action="{{ route('orders.track') }}" method="GET" class="mt-8">
                <div class="flex flex-col sm:flex-row gap-3 bg-white p-2 rounded-2xl shadow-lg border border-gray-200">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text" name="q" value="{{ $query }}" 
                               placeholder="Order # (e.g. RB-12345678) or Email..." 
                               required
                               class="w-full pl-11 pr-4 py-3.5 rounded-xl border-none text-gray-900 font-ubuntu focus:ring-0 text-sm sm:text-base">
                    </div>
                    <button type="submit" class="bg-[#1800ad] hover:bg-indigo-700 text-white font-bold px-8 py-3.5 rounded-xl transition-all shadow-md font-ubuntu text-sm sm:text-base flex items-center justify-center gap-2">
                        <span>Search Order</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>
            </form>
        </div>

        @if($searched && $order)
            <!-- Order Tracking Details Card -->
            <div class="bg-white rounded-3xl p-6 sm:p-10 border border-gray-200 shadow-xl space-y-8">
                
                <!-- Order Top Info -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-100 pb-6">
                    <div>
                        <div class="flex items-center gap-3 mb-1">
                            <h2 class="text-2xl font-black text-[#142D63] font-ubuntu">Order {{ $order->order_number }}</h2>
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $order->status_badge }}">
                                {{ $order->status === 'completed' ? 'Dispatched' : ucfirst($order->status) }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 font-mulish">
                            Placed on {{ $order->created_at->format('M d, Y \a\t h:i A') }}
                        </p>
                    </div>

                    @if($order->has_tracking)
                        <a href="{{ $order->tracking_url }}" target="_blank" 
                           class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-red-500 text-white font-bold text-sm hover:bg-red-600 transition-all shadow-md hover:shadow-lg">
                            <span>Track on Royal Mail</span>    
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    @endif
                </div>

                <!-- Current Status + Progress Stepper -->
                @php
                    $isCancelled = $order->status === 'cancelled';
                    $isPaid = in_array($order->status, ['paid', 'shipped', 'completed']);
                    $isDispatched = in_array($order->status, ['shipped', 'completed']);
                    $isProcessing = $isPaid && !$isDispatched;
                    $fillWidth = $isDispatched ? 50 : ($isPaid ? 25 : 0);
                @endphp

                @if($isCancelled)
                    <div class="p-6 bg-red-50 text-red-700 rounded-2xl font-bold text-sm text-center border border-red-200">
                        ❌ This order has been cancelled. If you have questions, please contact support.
                    </div>
                @else
                    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-100 shadow-sm">
                        <!-- CURRENT STATUS hero -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-5 mb-10">
                            <div class="flex items-center gap-4">
                                <div class="w-20 h-20 rounded-full flex items-center justify-center shadow-lg flex-shrink-0 {{ $isProcessing ? 'bg-amber-400 text-white' : 'bg-green-600 text-white' }}">
                                    @if($isDispatched)
                                        <i class="fa-solid fa-truck-fast text-4xl"></i>
                                    @elseif($isProcessing)
                                        <i class="fa-solid fa-clock text-4xl"></i>
                                    @else
                                        <i class="fa-solid fa-bag-shopping text-4xl"></i>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-[11px] font-extrabold uppercase tracking-widest {{ $isProcessing ? 'text-amber-600' : 'text-green-600' }}">Current Status</p>
                                    <h3 class="text-2xl sm:text-3xl font-black font-ubuntu text-gray-800">
                                        {{ $isDispatched ? 'Dispatched' : ($isProcessing ? 'Processing' : 'Received') }}
                                    </h3>
                                    <p class="text-sm text-gray-500 font-mulish">
                                        {{ $isDispatched ? 'Your order is on its way' : ($isProcessing ? 'Preparing your order' : 'We have received your order') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Progress bar stepper (edge to edge) -->
                        <div class="relative pb-12 pl-4 pr-4">
                            <!-- Track line: spans from the centre of node 1 to the centre of node 4 -->
                            <div class="absolute top-5 left-5 right-5 h-1 bg-gray-200 rounded-full z-0">
                                <div class="h-full bg-green-600 rounded-full transition-all duration-500" style="width: {{ $isDispatched ? '66.66' : ($isPaid ? '33.33' : '0') }}%"></div>

                                <!-- Van on the segment before Delivered (only once dispatched) -->
                                @if($isDispatched)
                                    <div class="absolute z-20 flex items-center gap-1 text-green-600" style="left: 83.33%; bottom: -50%; transform: translateX(-90%);">
                                        <img src="/images/express-delivery.png" class="w-8 h-8 flex-shrink-0">
                                    </div>
                                @endif
                            </div>

                            <!-- Nodes spread edge to edge -->
                            <div class="flex justify-between relative z-10">
                                <!-- Node 1: Received -->
                                <div class="relative w-10 flex flex-col items-center">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center bg-green-600 text-white shadow-md ring-4 ring-white">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                    <span class="absolute top-12 left-1/2 -translate-x-1/2 whitespace-nowrap text-xs font-bold text-green-700">Received</span>
                                </div>

                                <!-- Node 2: Paid -->
                                <div class="relative w-10 flex flex-col items-center">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center ring-4 ring-white {{ $isPaid ? 'bg-green-600 text-white shadow-md' : 'bg-white border-2 border-gray-300 text-gray-400' }}">
                                        @if($isPaid)
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        @endif
                                    </div>
                                    <span class="absolute top-12 left-1/2 -translate-x-1/2 whitespace-nowrap text-xs font-bold {{ $isPaid ? 'text-green-700' : 'text-gray-400' }}">Paid</span>
                                </div>

                                <!-- Node 3: Processing (waiting) -> Dispatched (solid green) once completed -->
                                <div class="relative w-10 flex flex-col items-center">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center ring-4 ring-white transition-all {{ $isDispatched ? 'bg-green-600 text-white shadow-md' : ($isProcessing ? 'bg-amber-400 text-white shadow-md' : 'bg-white border-2 border-gray-300 text-gray-400') }}">
                                        @if($isProcessing)
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @endif
                                    </div>
                                    <span class="absolute top-12 left-1/2 -translate-x-1/2 whitespace-nowrap text-center">
                                        <span class="block text-xs font-bold {{ $isDispatched ? 'text-green-700' : ($isProcessing ? 'text-amber-600' : 'text-gray-400') }}">{{ $isDispatched ? 'Dispatched' : 'Processing' }}</span>
                                        @if(!$isDispatched)
                                            <span class="block text-[10px] text-gray-400">{{ $isProcessing ? 'Preparing your order' : 'Pending' }}</span>
                                        @endif
                                    </span>
                                </div>

                                <!-- Node 4: Delivered (future stage — inactive for now) -->
                                <div class="relative w-10 flex flex-col items-center">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center bg-white border-2 border-gray-300 text-gray-300 ring-4 ring-white"></div>
                                    <span class="absolute top-12 left-1/2 -translate-x-1/2 whitespace-nowrap text-xs font-bold text-gray-400">Delivered</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Royal Mail Tracking Information Banner -->
                <div class="p-6 rounded-2xl bg-gradient-to-r from-red-50 via-indigo-50/50 to-blue-50 border border-red-100">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div>
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-red-600 bg-red-100 px-2.5 py-0.5 rounded-full">Courier Information</span>
                            <h4 class="text-lg font-bold text-gray-900 font-ubuntu mt-1">
                                Shipping Carrier: {{ $order->carrier ?? 'Royal Mail' }}
                            </h4>
                            @if($order->has_tracking)
                                <p class="text-xs text-gray-600 font-mulish mt-1">
                                    Tracking Code: <strong class="font-mono text-indigo-700 text-base">{{ $order->clean_tracking_number }}</strong>
                                </p>
                            @else
                                <p class="text-xs text-gray-500 font-mulish mt-1">
                                    Tracking code will be assigned once your parcel is handed over to Royal Mail.
                                </p>
                            @endif
                        </div>
                        {{-- @if($order->has_tracking)
                            <a href="{{ $order->tracking_url }}" target="_blank" 
                               class="bg- text-white font-bold px-5 py-2.5 rounded-xl text-xs hover:bg-[#b81c15] transition-all shadow-sm flex items-center gap-1.5 shrink-0">
                                <span>Track on Royal Mail</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        @endif --}}
                    </div>
                </div>

                <!-- Shipping Address & Items Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Delivery Address -->
                    <div class="bg-gray-50 p-5 rounded-2xl border border-gray-100">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Delivery Address</h4>
                        <div class="text-sm font-semibold text-gray-800">{{ $order->customer_name }}</div>
                        <div class="text-xs text-gray-600 font-mulish mt-1 leading-relaxed">
                            {{ $order->formatted_address }}
                        </div>
                    </div>

                    <!-- Items Summary -->
                    <div class="bg-gray-50 p-5 rounded-2xl border border-gray-100">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Order Items</h4>
                        <div class="space-y-2">
                            @foreach($order->items as $item)
                                <div class="flex justify-between items-center text-xs font-mulish border-b border-gray-200/60 pb-2 last:border-0 last:pb-0">
                                    <span class="font-semibold text-gray-800">{{ $item->product_name }} ({{ $item->variant_name }}) &times; {{ $item->quantity }}</span>
                                    <span class="font-bold text-gray-900">&pound;{{ number_format($item->total, 2) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
        @elseif($searched && !$order)
            <!-- Not Found State -->
            <div class="bg-white rounded-3xl p-10 text-center border border-gray-200 shadow-lg max-w-xl mx-auto">
                <div class="w-16 h-16 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 font-ubuntu">Order Not Found</h3>
                <p class="text-gray-500 text-sm font-mulish mt-2">
                    We couldn't find any order matching "<strong class="text-gray-700">{{ $query }}</strong>". Please double check your order number or email address.
                </p>
            </div>
        @endif

    </div>
</div>
@endsection
