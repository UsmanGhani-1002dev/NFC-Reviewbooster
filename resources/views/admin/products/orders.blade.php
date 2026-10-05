@extends('layouts.app')

@section('content')
<div class="p-4 sm:p-6 bg-white rounded-xl shadow-xl">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-blue-50 rounded-2xl text-blue-600 shadow-sm border border-blue-100/50">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div>
                <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Orders</h2>
                <p class="text-sm font-medium text-gray-400 mt-1">Manage customer orders</p>
            </div>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-4 w-full md:w-auto">
            <form action="{{ route('admin.orders.index') }}" method="GET" class="relative flex-1 sm:min-w-[300px]">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Search by Order #, Name or Email..." 
                       class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition-all text-sm">
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                @if(request('search'))
                    <a href="{{ route('admin.orders.index') }}" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </a>
                @endif
            </form>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-xl flex items-center gap-3">
        <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
    @endif

    <div class="overflow-hidden rounded-xl border border-gray-200 shadow-sm">
        <div class="hidden md:block">
            <table class="w-full text-sm text-left text-gray-700 bg-white">
                <thead class="bg-blue-600 text-white text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Order #</th>
                        <th class="px-6 py-4">Customer</th>
                        <th class="px-6 py-4">Items</th>
                        <th class="px-6 py-4">Total</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4 text-center">Action</th>
                    </tr>
                </thead>
                 <tbody class="divide-y divide-gray-100">
                    @forelse ($orders as $order)
                        <tr class="hover:bg-blue-50 transition duration-150">
                            <td class="px-6 py-4 font-semibold text-gray-800">
                                <div class="flex flex-col gap-1">
                                    <span>{{ $order->order_number }}</span>
                                    @if($order->is_dropship)
                                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-extrabold bg-purple-100 text-purple-800 border border-purple-300 tracking-wider">
                                            DROPSHIP
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium flex items-center gap-2">
                                    {{ $order->customer_name }}
                                    @if($order->partner_role_label)
                                        <span class="px-2 py-0.5 bg-blue-100 text-blue-800 rounded text-[10px] font-bold">
                                            {{ $order->partner_role_label }}
                                        </span>
                                    @endif
                                </div>
                                <div class="text-xs text-gray-400">{{ $order->customer_email }}</div>
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-600">
                                @foreach($order->items as $item)
                                    <div class="font-medium text-gray-800">{{ $item->variant_name }}</div>
                                @endforeach
                            </td>
                            <td class="px-4 py-4 font-bold">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M16 8.5a4 4 0 00-8 0v3H6v2h2v2c0 1.1-.9 2-2 2v2h12v-2H10.83c.11-.32.17-.65.17-1v-3h4v-2h-4v-3a2 2 0 014 0h2a4 4 0 00-4-4z"/>
                                    </svg>
                                    <span>{{ number_format($order->total, 2) }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $order->status_badge }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-500 whitespace-nowrap">{{ $order->created_at->format('d M Y') }}</td>
                            <td class="px-4 py-4 text-center">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="{{ route('admin.orders.edit', $order) }}"
                                       class="inline-flex items-center gap-2 bg-blue-50 text-blue-600 px-4 py-2 rounded-lg font-bold text-xs hover:bg-blue-100 transition-all border border-blue-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        View
                                    </a>
                                    <a href="{{ route('admin.orders.print', $order) }}" target="_blank" title="Print order details"
                                       class="inline-flex items-center justify-center w-9 h-9 bg-gray-50 text-gray-500 rounded-lg hover:bg-gray-100 hover:text-blue-600 transition-all border border-gray-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                    </a>
                                    <a href="{{ route('admin.orders.label', $order) }}" target="_blank" title="Print shipping label"
                                       class="inline-flex items-center justify-center w-9 h-9 bg-gray-50 text-gray-500 rounded-lg hover:bg-gray-100 hover:text-gray-900 transition-all border border-gray-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5a1.99 1.99 0 011.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.99 1.99 0 013 12V7a4 4 0 014-4z"/></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500 italic">No orders yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile -->
        <div class="block md:hidden divide-y divide-gray-100">
            @forelse ($orders as $order)
                <div class="p-5 space-y-3">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="font-bold text-gray-800">{{ $order->order_number }}</div>
                            <div class="text-sm text-gray-500 line-clamp-1">{{ $order->customer_name }}</div>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $order->status_badge }}">{{ ucfirst($order->status) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500">{{ $order->created_at->format('d M Y') }}</span>
                        <span class="font-bold text-gray-800">£{{ number_format($order->total, 2) }}</span>
                    </div>
                    <div class="flex gap-2 pt-4">
                        <a href="{{ route('admin.orders.edit', $order) }}"
                           class="flex-1 text-center bg-blue-600 text-white font-bold py-2.5 rounded-xl text-xs shadow-md shadow-blue-100">
                            View Order
                        </a>
                        <a href="{{ route('admin.orders.print', $order) }}" target="_blank" title="Print order details"
                           class="inline-flex items-center justify-center w-9 h-9 bg-gray-50 text-gray-500 rounded-lg hover:bg-gray-100 hover:text-blue-600 transition-all border border-gray-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        </a>
                        <a href="{{ route('admin.orders.label', $order) }}" target="_blank" title="Print shipping label"
                            class="inline-flex items-center justify-center w-9 h-9 bg-gray-50 text-gray-500 rounded-lg hover:bg-gray-100 hover:text-gray-900 transition-all border border-gray-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5a1.99 1.99 0 011.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.99 1.99 0 013 12V7a4 4 0 014-4z"/></svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="p-10 text-center text-gray-500 italic">No orders yet.</div>
            @endforelse
        </div>
    </div>

    @if($orders->hasPages())
    <div class="mt-6">
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endsection
