@extends('layouts.guest')
@section('meta_robots', 'noindex, follow')
@section('content')

<div class="bg-gray-50 min-h-screen py-12 -mt-[100px] pt-[140px]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8" x-data="checkoutForm()" x-init="init()">

        <h1 class="text-3xl font-bold text-[#142D63] mb-8 font-ubuntu">Checkout</h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left: Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Order Summary Card -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center gap-3">
                            <h2 class="text-lg font-bold text-[#142D63] font-ubuntu">Order Summary</h2>
                        </div>
                        @if(auth()->check() && auth()->user()->isApprovedPartner())
                            <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-purple-100 text-purple-800 border border-purple-200">
                                👑 {{ auth()->user()->partner_type_label }} ({{ auth()->user()->getPartnerDiscountPercent() }}% OFF)
                            </span>
                        @endif
                    </div>
                    <div class="space-y-4">
                        <template x-for="(line, idx) in displayLines" :key="idx">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 bg-gray-100 rounded-xl flex items-center justify-center overflow-hidden">
                                    <img :src="line.image" x-show="line.image" class="w-12 h-12 object-contain" :alt="line.name">
                                    <svg x-show="!line.image" class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-[#142D63] font-ubuntu" x-text="line.productName"></h3>
                                    <p class="text-gray-500 text-sm font-mulish" x-text="(line.cleanName || line.name) + ' × ' + line.qty"></p>
                                    <template x-if="line.hasCustomLogo">
                                        <div class="mt-1.5 inline-flex items-center gap-1 bg-blue-50 text-blue-700 border border-blue-200 text-xs font-bold px-2.5 py-0.5 rounded-full">
                                            <span>🎨 Custom Logo & Design (+£2.99)</span>
                                        </div>
                                    </template>
                                </div>
                                <div class="text-right flex items-center gap-3">
                                    <div>
                                        <span class="text-xl font-bold text-[#142D63]" x-text="'£' + line.lineTotal.toFixed(2)"></span>
                                        <span x-show="line.discounted" class="block text-sm text-gray-400 line-through" x-text="'£' + (line.basePrice * line.qty).toFixed(2)"></span>
                                    </div>
                                    <button type="button" @click="removeItem(idx)" title="Remove item from order" class="text-gray-400 hover:text-red-500 transition-colors p-1.5 rounded-lg hover:bg-red-50 cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                        <div x-show="displayLines.length === 0" class="text-center py-6">
                            <p class="text-sm text-gray-500 font-mulish mb-3">Your cart is currently empty.</p>
                            <a href="{{ route('shop.index') }}" class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-xl bg-[#142D63] text-white text-xs font-bold hover:bg-[#00A0FF] transition-all">
                                <span>Return to Shop</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Customer Details -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <h2 class="text-lg font-bold text-[#142D63] mb-4 font-ubuntu">Customer Details</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 font-mulish">Full Name *</label>
                            <input type="text" x-model="customerName" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#00A0FF] focus:border-[#00A0FF] transition-all font-mulish" placeholder="John Smith" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 font-mulish">Email Address *</label>
                            <input type="email" x-model="customerEmail" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#00A0FF] focus:border-[#00A0FF] transition-all font-mulish" placeholder="john@example.com" required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1 font-mulish">Phone Number</label>
                            <input type="tel" x-model="customerPhone" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#00A0FF] focus:border-[#00A0FF] transition-all font-mulish" placeholder="+44 7123 456789">
                        </div>
                    </div>
                </div>

                @if(auth()->check() && auth()->user()->isApprovedPartner())
                <!-- Partner / Dropshipping Options -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-bold text-[#142D63] font-ubuntu">Partner Order Options</h2>
                        <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-purple-100 text-purple-800 border border-purple-200">
                            👑 {{ auth()->user()->partner_type_label }}
                        </span>
                    </div>

                    <label class="flex items-start gap-3 cursor-pointer select-none p-3 rounded-xl border border-gray-200 hover:border-purple-300 transition-all">
                        <input type="checkbox" x-model="isDropship" class="mt-1 w-5 h-5 rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                        <span>
                            <span class="block text-sm font-bold text-[#142D63] font-ubuntu">This is a dropshipping order</span>
                            <span class="block text-xs text-gray-500 font-mulish mt-0.5">Ship directly to my customer in unbranded packaging. Enter your customer's address in the shipping section below.</span>
                        </span>
                    </label>

                    <div x-show="isDropship" x-transition class="mt-4 space-y-4 border-t border-gray-100 pt-4">
                        <p class="text-xs text-gray-500 font-mulish">Your (reseller) billing details — used for your invoice/records. These stay on your account and are not shared with the end customer.</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1 font-mulish">Your Billing Name</label>
                                <input type="text" x-model="billingName" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all font-mulish" placeholder="{{ auth()->user()->name }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1 font-mulish">Your Billing Email</label>
                                <input type="email" x-model="billingEmail" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all font-mulish" placeholder="{{ auth()->user()->email }}">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 font-mulish">Your Order Reference / PO Number</label>
                            <input type="text" x-model="resellerReference" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all font-mulish" placeholder="e.g. your own order number for this sale">
                        </div>
                    </div>
                </div>
                @endif

                <!-- Shipping Address -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-2">
                        <h2 class="text-lg font-bold text-[#142D63] font-ubuntu">
                            @if(auth()->check() && auth()->user()->isApprovedPartner())
                                <span x-show="isDropship">Shipping Address (End Customer / Receiver)</span>
                                <span x-show="!isDropship">Shipping Address</span>
                            @else
                                Shipping Address
                            @endif
                        </h2>
                        @if(auth()->check() && auth()->user()->isApprovedPartner())
                            <span x-show="isDropship" class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                📦 Dropshipping Order
                            </span>
                        @endif
                    </div>
                    @if(auth()->check() && auth()->user()->isApprovedPartner())
                        <p x-show="isDropship" class="text-xs text-gray-500 mb-4 bg-gray-50 p-2.5 rounded-lg border border-gray-200">
                            ℹ️ <strong>Unbranded Fulfillment:</strong> Enter your customer's shipping address. We will dispatch this item directly to your buyer in unbranded, plain packaging.
                        </p>
                    @endif
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 font-mulish">Address Line 1 *</label>
                            <input type="text" x-model="addressLine1" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#00A0FF] focus:border-[#00A0FF] transition-all font-mulish" placeholder="123 High Street" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 font-mulish">Address Line 2</label>
                            <input type="text" x-model="addressLine2" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#00A0FF] focus:border-[#00A0FF] transition-all font-mulish" placeholder="Apartment, suite, etc.">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1 font-mulish">City *</label>
                                <input type="text" x-model="city" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#00A0FF] focus:border-[#00A0FF] transition-all font-mulish" placeholder="London" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1 font-mulish">County</label>
                                <input type="text" x-model="county" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#00A0FF] focus:border-[#00A0FF] transition-all font-mulish" placeholder="Greater London">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1 font-mulish">Postcode *</label>
                                <input type="text" x-model="postcode" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#00A0FF] focus:border-[#00A0FF] transition-all font-mulish" placeholder="SW1A 1AA" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1 font-mulish">Country *</label>
                                <select x-model="country" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#00A0FF] focus:border-[#00A0FF] transition-all font-mulish">
                                    <option value="United Kingdom">United Kingdom</option>
                                    <option value="Ireland">Ireland</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                 <!-- Delivery Method -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <h2 class="text-lg font-bold text-[#142D63] mb-4 font-ubuntu">Delivery Method</h2>
                    <div class="space-y-3">
                        <label @click="changeShipping('standard')"
                               :class="shippingMethod === 'standard' ? 'border-[#00A0FF] ring-2 ring-[#00A0FF]/20 bg-blue-50/40' : 'border-gray-200 hover:border-gray-300'"
                               class="flex items-center justify-between gap-3 p-4 rounded-xl border cursor-pointer transition-all select-none">
                            <div class="flex items-center gap-3">
                                <span :class="shippingMethod === 'standard' ? 'border-[#00A0FF]' : 'border-gray-300'" class="w-5 h-5 rounded-full border-2 flex items-center justify-center flex-shrink-0">
                                    <span x-show="shippingMethod === 'standard'" class="block rounded-full" style="width:11px;height:11px;background:#00A0FF;"></span>
                                </span>
                                <div>
                                    <p class="font-bold text-sm text-[#142D63] font-ubuntu">Standard Delivery</p>
                                    <p class="text-xs text-gray-500 font-mulish">Delivered within ~48 hours</p>
                                </div>
                            </div>
                            <span class="font-bold font-ubuntu">
                                <span x-show="!standardIsFree" class="text-[#142D63]">£{{ number_format($deliveryStandard ?? 3.90, 2) }}</span>
                                <span x-show="standardIsFree" class="text-green-600">FREE</span>
                            </span>
                        </label>

                        <label @click="changeShipping('express')"
                               :class="shippingMethod === 'express' ? 'border-[#00A0FF] ring-2 ring-[#00A0FF]/20 bg-blue-50/40' : 'border-gray-200 hover:border-gray-300'"
                               class="flex items-center justify-between gap-3 p-4 rounded-xl border cursor-pointer transition-all select-none">
                            <div class="flex items-center gap-3">
                                <span :class="shippingMethod === 'express' ? 'border-[#00A0FF]' : 'border-gray-300'" class="w-5 h-5 rounded-full border-2 flex items-center justify-center flex-shrink-0">
                                    <span x-show="shippingMethod === 'express'" class="block rounded-full" style="width:11px;height:11px;background:#00A0FF;"></span>
                                </span>
                                <div>
                                    <p class="font-bold text-sm text-[#142D63] font-ubuntu">Express Delivery</p>
                                    <p class="text-xs text-gray-500 font-mulish">Delivered within ~24 hours</p>
                                </div>
                            </div>
                            <span class="font-bold text-[#142D63] font-ubuntu">£{{ number_format($deliveryExpress ?? 6.90, 2) }}</span>
                        </label>
                        
                        <p x-show="freeThreshold > 0 && !standardIsFree" class="text-xs text-gray-500 font-mulish flex items-center gap-1.5 pt-1">
                            <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Spend <strong x-text="'£' + freeThreshold.toFixed(2)"></strong>+ (items) to unlock <strong class="text-green-600">FREE standard delivery</strong>.</span>
                        </p>
                        <p x-show="standardIsFree" x-cloak class="text-xs text-green-600 font-bold font-mulish flex items-center gap-1.5 pt-1">
                            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span>You’ve unlocked FREE standard delivery!</span>
                        </p>
                    </div>
                </div>

                <!-- Payment -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <h2 class="text-lg font-bold text-[#142D63] mb-4 font-ubuntu">Payment Details</h2>
                    
                    <!-- Statement note FIRST, before card input -->
                    <div class="mb-4 p-3 bg-blue-50/70 border border-blue-200 rounded-xl flex items-start gap-2.5">
                        <svg class="w-5 h-5 text-[#00A0FF] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm text-gray-600 font-mulish leading-relaxed">
                            <strong class="text-[#142D63] font-ubuntu">Bank Statement Note:</strong><br> Charges on your bank/card statement will appear as <strong class="text-[#142D63]">Enovtec Ltd</strong>.
                        </p>
                    </div>
                    
                    <div id="card-element" class="border border-gray-300 rounded-xl px-4 py-4 bg-gray-50 mb-2" style="min-height: 50px; display: block !important;">
                        <!-- Stripe Element will be inserted here -->
                    </div>
                    <div id="card-errors" class="text-red-500 text-sm mt-2" role="alert"></div>
                </div>

                <!-- Submit -->
                <button @click="submitOrder()" :disabled="processing"
                        :class="processing ? 'bg-gray-400 cursor-not-allowed' : 'bg-green-500 hover:bg-green-600'"
                        class="w-full text-white py-4 rounded-xl font-bold text-lg transition-all duration-300 shadow-lg hover:shadow-xl font-ubuntu flex items-center justify-center gap-2">
                    <svg x-show="processing" class="animate-spin w-5 h-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <span x-text="processing ? 'Processing...' : (serverTotal !== null ? ('Pay £' + serverTotal.toFixed(2)) : 'Pay')"></span>
                </button>
            </div>

            <!-- Right: Summary Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 sticky top-2">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-[#142D63] font-ubuntu">Your Order</h3>
                        <button type="button" @click="clearCartAndRedirect()" class="text-xs text-red-500 hover:text-red-700 font-semibold hover:underline cursor-pointer">
                            Clear Cart
                        </button>
                    </div>

                    <div class="border-b border-gray-100 pb-4 mb-4 space-y-3">
                        <template x-for="(line, idx) in displayLines" :key="idx">
                            <div class="border-b border-gray-50 pb-3 last:border-0 last:pb-0">
                                <div class="flex justify-between items-center text-sm text-[#142D63] font-bold font-ubuntu mb-1">
                                    <span x-text="(line.cleanName || line.name) + ' × ' + line.qty"></span>
                                    <div class="flex items-center gap-2">
                                        <span x-text="'£' + line.lineTotal.toFixed(2)"></span>
                                        <button type="button" @click="removeItem(idx)" title="Remove item" class="text-gray-400 hover:text-red-500 transition-colors p-0.5 cursor-pointer">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                </div>
                                <template x-if="line.hasCustomLogo">
                                    <div class="mt-0.5 mb-1.5 inline-flex items-center gap-1 bg-blue-50 text-blue-700 border border-blue-200 text-xs font-bold px-2 py-0.5 rounded-md">
                                        <span>Custom Logo & Design (+£2.99)</span>
                                    </div>
                                </template>
                                <template x-if="line.locationList && line.locationList.length > 0">
                                    <div class="mt-1 mb-1 bg-gray-50 rounded-lg p-2.5 border border-gray-100">
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Linked Locations:</p>
                                        <div class="space-y-1.5">
                                            <template x-for="(loc, lIdx) in line.locationList" :key="lIdx">
                                                <div class="flex items-start gap-2">
                                                    <div class="w-1 h-1 bg-green-500 rounded-full mt-1.5 flex-shrink-0"></div>
                                                    <span class="text-[11px] text-gray-600 font-mulish leading-tight" x-text="loc.name" style="word-wrap: anywhere;"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                                <template x-if="(!line.locationList || line.locationList.length === 0) && line.placeName">
                                    <div class="flex justify-between text-xs text-gray-500 font-mulish">
                                        <span x-text="'Location: ' + line.placeName"></span>
                                    </div>
                                </template>
                            </div>
                        </template>

                        @if(auth()->check() && auth()->user()->isApprovedPartner())
                        <div class="flex justify-between text-sm font-mulish mt-2" x-show="partnerSavings > 0">
                            <span class="text-purple-700 font-bold">👑 Partner Discount ({{ auth()->user()->getPartnerDiscountPercent() }}%)</span>
                            <span class="text-purple-700 font-bold" x-text="'-£' + partnerSavings.toFixed(2)"></span>
                        </div>
                        @endif
                    </div>

                    <div class="border-b border-gray-100 pb-4 mb-4 space-y-2">
                        <div class="flex justify-between text-sm text-gray-600 font-mulish">
                            <span>Subtotal</span>
                            <span x-text="'£' + itemsTotal.toFixed(2)"></span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600 font-mulish">
                            <span x-text="'Shipping (' + (shippingMethod === 'express' ? 'Express · 24h' : 'Standard · 48h') + ')'"></span>
                            <span class="font-semibold" :class="shippingFee > 0 ? 'text-[#142D63]' : 'text-green-600'"
                                  x-text="shippingFee > 0 ? ('£' + shippingFee.toFixed(2)) : 'FREE'"></span>
                        </div>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-lg font-bold text-[#142D63] font-ubuntu">Total</span>
                        <span class="text-2xl font-bold text-[#142D63] font-ubuntu" x-text="serverTotal !== null ? ('£' + serverTotal.toFixed(2)) : 'Calculating…'"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
function checkoutForm() {
    return {
        customerName: @json(auth()->check() ? auth()->user()->name : ''),
        customerEmail: @json(auth()->check() ? auth()->user()->email : ''),
        customerPhone: @json(auth()->check() ? (auth()->user()->phone ?? '') : ''),
        addressLine1: '',
        addressLine2: '',
        city: '',
        county: '',
        postcode: '',
        country: 'United Kingdom',
        isDropship: false,
        billingName: @json(auth()->check() ? auth()->user()->name : ''),
        billingEmail: @json(auth()->check() ? auth()->user()->email : ''),
        resellerReference: '',
        processing: false,
        stripe: null,
        elements: null,
        paymentElement: null,
        clientSecret: null,
        cart: [],
        displayLines: [],
        serverTotal: null,
        partnerSavings: 0,
        shippingMethod: 'standard',
        shippingFee: {{ $deliveryStandard ?? 3.90 }},
        itemsTotal: 0,
        deliveryFees: { standard: {{ $deliveryStandard ?? 3.90 }}, express: {{ $deliveryExpress ?? 6.90 }} },
        freeThreshold: {{ $freeDeliveryThreshold ?? 25 }},
        get standardIsFree() {
            // Free delivery applies to STANDARD only.
            return this.freeThreshold > 0 && this.itemsTotal >= this.freeThreshold;
        },

        async init() {
            if (typeof Stripe === 'undefined') {
                setTimeout(() => this.init(), 200);
                return;
            }
            
            this.autoFillCart();
            this.cart = JSON.parse(localStorage.getItem('rb_cart') || '[]');

            if (this.cart.length === 0) {
                const urlParams = new URLSearchParams(window.location.search);
                if (!urlParams.get('variant_id')) {
                    // Empty cart with no direct item URL param -> redirect to shop
                    window.location.href = "{{ route('shop.index') }}";
                    return;
                }
            }

            const stripeKey = '{{ $stripeKey }}';

            try {
                this.stripe = Stripe(stripeKey);
                await this.setupPayment();
            } catch (err) {
                const errorDiv = document.getElementById('card-errors');
                if (errorDiv) errorDiv.textContent = 'Failed to load payment form. Please try reloading the page.';
            }
        },

        async setupPayment() {
            // The whole cart + chosen delivery method is priced server-side
            // (authoritative). Changing the delivery method re-runs this.
            const intentResponse = await fetch('{{ route("shop.payment-intent") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ items: this.cart, shipping_method: this.shippingMethod })
            });

            const intentData = await intentResponse.json();
            if (!intentData.client_secret) throw new Error(intentData.message || 'Could not create payment intent');

            this.clientSecret = intentData.client_secret;
            this.applyQuote(intentData);

            const appearance = {
                theme: 'stripe',
                variables: {
                    colorPrimary: '#00A0FF',
                    colorBackground: '#ffffff',
                    colorText: '#142D63',
                    fontFamily: 'Mulish, sans-serif',
                    borderRadius: '12px',
                }
            };

            // Rebuild the Payment Element against the new client secret.
            if (this.paymentElement) {
                try { this.paymentElement.unmount(); } catch (e) {}
                this.paymentElement = null;
            }

            this.elements = this.stripe.elements({ clientSecret: this.clientSecret, appearance });
            this.paymentElement = this.elements.create('payment', {
                layout: 'tabs',
                defaultValues: { billingDetails: { address: { country: 'GB' } } }
            });
            this.paymentElement.mount('#card-element');
            this.paymentElement.on('change', (event) => {
                const errorDiv = document.getElementById('card-errors');
                if (errorDiv) errorDiv.textContent = event.error ? event.error.message : '';
            });
        },

        async changeShipping(method) {
            if (this.shippingMethod === method) return;
            this.shippingMethod = method;
            await this.setupPayment();
        },
              

        removeItem(idx) {
            if (!confirm('Are you sure you want to remove this item from your order?')) return;

            if (idx >= 0 && idx < this.cart.length) {
                this.cart.splice(idx, 1);
            } else {
                this.cart.shift();
            }

            localStorage.setItem('rb_cart', JSON.stringify(this.cart));
            window.dispatchEvent(new CustomEvent('cart-updated'));

            if (this.cart.length === 0) {
                window.location.href = "{{ route('shop.index') }}";
            } else {
                this.init();
            }
        },

        clearCartAndRedirect() {
            if (!confirm('Are you sure you want to clear your cart and return to the shop?')) return;
            this.cart = [];
            localStorage.removeItem('rb_cart');
            window.dispatchEvent(new CustomEvent('cart-updated'));
            window.location.href = "{{ route('shop.index') }}";
        },

        autoFillCart() {
            const cart = JSON.parse(localStorage.getItem('rb_cart') || '[]');
            const urlParams = new URLSearchParams(window.location.search);
            const vId = urlParams.get('variant_id');
            const pId = urlParams.get('place_id');
            const pName = urlParams.get('place_name');

            if (vId && cart.length === 0) {
                const newItem = {
                    variantId: parseInt(vId),
                    name: '{{ $variant->name }}',
                    productName: '{{ $variant->product->name }}',
                    price: {{ (float)$variant->price }},
                    image: '{{ $variant->image ? asset('storage/' . $variant->image) : asset('storage/' . $variant->product->image) }}',
                    placeId: pId || '',
                    placeName: pName || '',
                    qty: 1
                };
                cart.push(newItem);
                localStorage.setItem('rb_cart', JSON.stringify(cart));
                window.dispatchEvent(new CustomEvent('cart-updated'));
            }
        },

        applyQuote(quote) {
            this.serverTotal = (typeof quote.amount === 'number') ? quote.amount : null;
            if (typeof quote.shipping_fee === 'number') this.shippingFee = quote.shipping_fee;
            if (typeof quote.shipping_method === 'string') this.shippingMethod = quote.shipping_method;
            const breakdown = quote.items || [];

            // Merge authoritative server prices with local cart metadata
            // (image, locations, base price) matched by variant id.
            this.displayLines = breakdown.map(bi => {
                const local = this.cart.find(c => 
                    String(c.variantId) === String(bi.variant_id) && 
                    (c.name === bi.name || c.productName === bi.product_name)
                ) || this.cart.find(c => String(c.variantId) === String(bi.variant_id)) || {};
                const basePrice = (typeof local.price === 'number') ? local.price : bi.unit_price;
                const nameCandidate = bi.name || local.name || '';
                const hasCustomLogo = Boolean(
                    local.hasCustomLogo || 
                    (nameCandidate && /custom\s*logo/i.test(nameCandidate))
                );
                const cleanName = nameCandidate.replace(/\s*\+\s*Custom\s*Logo/gi, '').trim();

                return {
                    variantId: bi.variant_id,
                    productName: bi.product_name || local.productName || '',
                    name: nameCandidate,
                    cleanName: cleanName || nameCandidate,
                    qty: bi.quantity,
                    unitPrice: bi.unit_price,
                    lineTotal: bi.line_total,
                    basePrice: basePrice,
                    discounted: bi.unit_price < (basePrice - 0.001),
                    hasCustomLogo: hasCustomLogo,
                    customLogoFee: local.customLogoFee || 2.99,
                    image: local.image || '',
                    placeName: local.placeName || '',
                    locationList: local.locationList || []
                };
            });

            // Items subtotal (server total minus the delivery fee).
            this.itemsTotal = (typeof quote.items_total === 'number')
                ? quote.items_total
                : (this.serverTotal !== null ? Math.round((this.serverTotal - this.shippingFee) * 100) / 100 : 0);

            // Partner saving vs. the base (undiscounted) item prices.
            const baseSum = this.displayLines.reduce((s, l) => s + (l.basePrice * l.qty), 0);
            this.partnerSavings = Math.max(0, Math.round((baseSum - this.itemsTotal) * 100) / 100);
        },

        async submitOrder() {
            if (this.processing) return;

            if (!this.customerName || !this.customerEmail || !this.addressLine1 || !this.city || !this.postcode) {
                alert('Please fill in all required customer and shipping fields.');
                return;
            }

            if (!this.stripe || !this.elements) {
                alert('Payment system is not ready yet. Please wait a moment.');
                return;
            }

            this.processing = true;
            const errorDiv = document.getElementById('card-errors');
            if (errorDiv) errorDiv.textContent = '';

            try {
                const billingDetails = {
                    name: this.customerName,
                    email: this.customerEmail,
                    address: {
                        line1: this.addressLine1,
                        city: this.city,
                        postal_code: this.postcode,
                        country: this.country === 'United Kingdom' ? 'GB' : 'IE'
                    }
                };

                if (this.addressLine2 && this.addressLine2.trim()) {
                    billingDetails.address.line2 = this.addressLine2.trim();
                }

                if (this.customerPhone && this.customerPhone.trim()) {
                    billingDetails.phone = this.customerPhone.trim();
                }

                // Confirm the payment using Stripe Elements
                const { error, paymentIntent } = await this.stripe.confirmPayment({
                    elements: this.elements,
                    confirmParams: {
                        return_url: window.location.href,
                        payment_method_data: {
                            billing_details: billingDetails
                        }
                    },
                    redirect: 'if_required' // Prevent automatic redirect so we can save order first
                });

                if (error) {
                    if (errorDiv) errorDiv.textContent = error.message;
                    if (error.type !== 'validation_error' && error.type !== 'card_error') {
                        alert(error.message || 'A processing error occurred.');
                    }
                    this.processing = false;
                    return;
                }

                if (paymentIntent && paymentIntent.status === 'succeeded') {
                    console.log('v11: Payment Succeeded, creating order on backend...');
                    
                    const orderResponse = await fetch('{{ route("shop.process-checkout") }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({
                            variant_id: '{{ $variant->id }}',
                            payment_intent_id: paymentIntent.id,
                            customer_name: this.customerName,
                            customer_email: this.customerEmail,
                            customer_phone: this.customerPhone,
                            address_line1: this.addressLine1,
                            address_line2: this.addressLine2,
                            city: this.city,
                            county: this.county,
                            postcode: this.postcode,
                            country: this.country,
                            google_place_id: (JSON.parse(localStorage.getItem('rb_cart') || '[]')[0] || {}).placeId || '',
                            google_place_name: (JSON.parse(localStorage.getItem('rb_cart') || '[]')[0] || {}).placeName || '',
                            is_dropship: this.isDropship,
                            billing_name: this.billingName,
                            billing_email: this.billingEmail,
                            reseller_reference: this.resellerReference,
                            shipping_method: this.shippingMethod,
                            items: JSON.parse(localStorage.getItem('rb_cart') || '[]')
                        })
                    });

                    const orderData = await orderResponse.json();
                    if (orderData.success) {
                        localStorage.removeItem('rb_cart');
                        window.dispatchEvent(new CustomEvent('cart-updated'));
                        window.location.href = orderData.redirect;
                    } else {
                        alert(orderData.message || 'Error saving order to database.');
                        this.processing = false;
                    }
                } else {
                    alert('Payment could not be completed at this time.');
                    this.processing = false;
                }
            } catch (err) {
                console.error('v11: SUBMIT ERROR:', err);
                alert('An unexpected error occurred. Please try again.');
                this.processing = false;
            }
        }
    };
}
</script>

@endsection
