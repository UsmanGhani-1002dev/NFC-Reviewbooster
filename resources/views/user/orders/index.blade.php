@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="max-w-6xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8 bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl sm:text-3xl font-bold text-[#142D63] font-ubuntu">My Orders</h1>
                <span class="bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1 rounded-full font-mulish">
                    {{ $orders->total() }} {{ Str::plural('Order', $orders->total()) }}
                </span>
            </div>
            <p class="text-gray-500 font-mulish text-sm mt-1">Track your purchases, view delivery status, and access order receipts.</p>
        </div>

        <a href="{{ route('shop.index') }}" 
           class="inline-flex items-center gap-2 bg-[#00A0FF] hover:bg-[#1800ad] text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-md hover:shadow-lg transition-all duration-300 font-ubuntu">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            <span>Browse Shop</span>
        </a>
    </div>

    @if($orders->isEmpty())
        <!-- Empty State -->
        <div class="bg-white rounded-3xl p-12 text-center shadow-sm border border-gray-100 my-8">
            <div class="w-20 h-20 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
            <h3 class="text-xl font-bold text-[#142D63] mb-2 font-ubuntu">No Orders Found</h3>
            <p class="text-gray-500 font-mulish text-sm max-w-md mx-auto mb-6">
                You haven't placed any physical card orders yet. Browse our store to order premium NFC review cards, stands, and keyrings!
            </p>
            <a href="{{ route('shop.index') }}" class="inline-block bg-[#142D63] hover:bg-[#00A0FF] text-white px-8 py-3 rounded-xl font-bold shadow-md transition-colors font-ubuntu text-sm">
                Explore Products
            </a>
        </div>
    @else
        <!-- Orders List -->
        <div class="space-y-6">
            @foreach($orders as $order)
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                    <!-- Order Card Header -->
                    <div class="bg-gray-50/80 px-6 py-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-4">
                        <div class="flex flex-wrap items-center gap-4">
                            <div>
                                <span class="text-xs text-gray-400 font-mulish block">Order Number</span>
                                <span class="font-bold text-[#142D63] font-ubuntu text-base">{{ $order->order_number }}</span>
                            </div>
                            <div class="h-8 w-px bg-gray-200 hidden sm:block"></div>
                            <div>
                                <span class="text-xs text-gray-400 font-mulish block">Date Placed</span>
                                <span class="text-sm font-semibold text-gray-700 font-mulish">{{ $order->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="h-8 w-px bg-gray-200 hidden sm:block"></div>
                            <div>
                                <span class="text-xs text-gray-400 font-mulish block">Total Amount</span>
                                <span class="text-sm font-extrabold text-[#142D63] font-ubuntu">£{{ number_format($order->total, 2) }} GBP</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $order->status_badge }}">
                                {{ ucfirst($order->status) }}
                            </span>
                            <a href="{{ route('user.orders.show', $order->order_number) }}" 
                               class="text-xs font-bold text-[#00A0FF] hover:text-[#1800ad] hover:underline flex items-center gap-1 font-mulish">
                                <span>Details</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>

                    <!-- Items & Tracking Body -->
                    <div class="p-6">
                        <!-- Items list -->
                        <div class="divide-y divide-gray-100">
                            @foreach($order->items as $item)
                                <div class="py-4 first:pt-0 last:pb-0 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-16 h-16 bg-[#d8e4ef] rounded-xl flex items-center justify-center flex-shrink-0">
                                            @if($item->variant && $item->variant->image)
                                                <img src="{{ asset('storage/' . $item->variant->image) }}" class="w-12 h-12 object-contain" alt="{{ $item->product_name }}">
                                            @elseif($item->variant && $item->variant->product && $item->variant->product->image)
                                                <img src="{{ asset('storage/' . $item->variant->product->image) }}" class="w-12 h-12 object-contain" alt="{{ $item->product_name }}">
                                            @else
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-[#142D63] font-ubuntu text-base">{{ $item->product_name }}</h4>
                                            <p class="text-xs text-gray-500 font-mulish mt-0.5">{{ $item->variant_name }}</p>

                                            <!-- Locations info if present -->
                                            @if(!empty($item->locations) && is_array($item->locations))
                                                <div class="mt-1 flex flex-wrap items-center gap-1.5">
                                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Locations:</span>
                                                    @foreach($item->locations as $loc)
                                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-green-50 text-green-700 border border-green-200 rounded-md text-[11px] font-semibold font-mulish">
                                                            <svg class="w-3 h-3 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                            </svg>
                                                            <span>{{ $loc['name'] ?? 'Location' }}</span>
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="text-right sm:text-right w-full sm:w-auto flex justify-between sm:block">
                                        <span class="text-xs text-gray-400 font-mulish block">Qty: {{ $item->quantity }}</span>
                                        <span class="font-bold text-[#142D63] font-ubuntu text-sm">£{{ number_format($item->total, 2) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Royal Mail / Shipping Tracking Alert -->
                        {{-- @if($order->has_tracking)
                            <div class="mt-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-2xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-600 text-white rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-[#142D63] font-ubuntu text-sm">{{ $order->carrier ?: 'Royal Mail' }} Tracked</span>
                                            <span class="bg-blue-600 text-white text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-full">Dispatched</span>
                                        </div>
                                        <p class="text-xs text-gray-600 font-mulish mt-0.5">
                                            Tracking Number: <strong class="text-[#142D63] font-mono select-all">{{ $order->tracking_number }}</strong>
                                        </p>
                                    </div>
                                </div>

                                <a href="{{ $order->tracking_url }}" target="_blank" rel="noopener noreferrer" 
                                   class="inline-flex items-center gap-2 bg-[#142D63] hover:bg-[#00A0FF] text-white px-4 py-2 rounded-xl text-xs font-bold shadow transition-colors font-ubuntu flex-shrink-0">
                                    <span>Track Shipment</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                            </div>
                        @endif --}}
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
