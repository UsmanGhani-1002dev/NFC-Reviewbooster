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
                    <h2 class="text-lg font-bold text-[#142D63] mb-4 font-ubuntu">Order Summary</h2>
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-gray-100 rounded-xl flex items-center justify-center">
                            @if($variant->product->image)
                                <img src="{{ asset('storage/' . $variant->product->image) }}" class="w-12 h-12 object-contain" alt="{{ $variant->name }}">
                            @else
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-[#142D63] font-ubuntu">{{ $variant->product->name }}</h3>
                            <p class="text-gray-500 text-sm font-mulish">{{ $variant->name }}</p>
                        </div>
                        <div class="text-right">
                            <span class="text-xl font-bold text-[#142D63]">£{{ number_format($variant->price, 2) }}</span>
                            @if($variant->original_price > $variant->price)
                            <span class="block text-sm text-gray-400 line-through">£{{ number_format($variant->original_price, 2) }}</span>
                            @endif
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

                <!-- Shipping Address -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <h2 class="text-lg font-bold text-[#142D63] mb-4 font-ubuntu">Shipping Address</h2>
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

                <!-- Payment -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <h2 class="text-lg font-bold text-[#142D63] mb-4 font-ubuntu">Payment Details</h2>
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
                    <span x-text="processing ? 'Processing...' : 'Pay £{{ number_format($variant->price, 2) }}'"></span>
                </button>
            </div>

            <!-- Right: Summary Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 sticky top-24">
                    <h3 class="text-lg font-bold text-[#142D63] mb-4 font-ubuntu">Your Order</h3>

                    <div class="border-b border-gray-100 pb-4 mb-4">
                        <div class="flex justify-between text-sm text-[#142D63] font-bold font-ubuntu mb-1">
                            <span>{{ $variant->name }}</span>
                            <span>£{{ number_format($variant->price, 2) }}</span>
                        </div>
                        
                        <!-- Selected Locations List -->
                        <div class="mt-2 mb-3 bg-gray-50 rounded-xl p-3 border border-gray-100" 
                            x-data="{ item: (JSON.parse(localStorage.getItem('rb_cart') || '[]')[0] || {}) }"
                            x-show="item.locationList && item.locationList.length > 0">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">Linked Locations:</p>
                            <div class="space-y-1.5">
                                <template x-for="(loc, lIdx) in item.locationList" :key="lIdx">
                                    <div class="flex items-start gap-2">
                                        <div class="w-1 h-1 bg-green-500 rounded-full mt-1.5 flex-shrink-0"></div>
                                        <span class="text-[11px] text-gray-600 font-mulish leading-tight" x-text="loc.name"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                        
                        <div class="flex justify-between text-xs text-gray-500 font-mulish" x-data="{ item: (JSON.parse(localStorage.getItem('rb_cart') || '[]')[0] || {}) }" x-show="item.placeName && (!item.locationList || item.locationList.length === 0)">
                             <span x-text="'Location: ' + item.placeName"></span>
                        </div>
                        @if($variant->discount_percent > 0)
                        <div class="flex justify-between text-sm font-mulish">
                            <span class="text-green-600">Discount ({{ $variant->discount_percent }}%)</span>
                            <span class="text-green-600">-£{{ number_format($variant->original_price - $variant->price, 2) }}</span>
                        </div>
                        @endif
                    </div>

                    <div class="border-b border-gray-100 pb-4 mb-4">
                        <div class="flex justify-between text-sm text-gray-600 font-mulish">
                            <span>Shipping</span>
                            <span class="text-green-600 font-semibold">FREE</span>
                        </div>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-lg font-bold text-[#142D63] font-ubuntu">Total</span>
                        <span class="text-2xl font-bold text-[#142D63] font-ubuntu">£{{ number_format($variant->price, 2) }}</span>
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
        customerName: '',
        customerEmail: '',
        customerPhone: '',
        addressLine1: '',
        addressLine2: '',
        city: '',
        county: '',
        postcode: '',
        country: 'United Kingdom',
        processing: false,
        stripe: null,
        elements: null,
        paymentElement: null,
        clientSecret: null,

        async init() {
            if (typeof Stripe === 'undefined') {
                setTimeout(() => this.init(), 200);
                return;
            }
            
            const stripeKey = '{{ $stripeKey }}';
            
            this.autoFillCart();

            try {
                this.stripe = Stripe(stripeKey);
                
                // 1. We must fetch the PaymentIntent client_secret FIRST for the new Payment Element
                const intentResponse = await fetch('{{ route("shop.payment-intent") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ variant_id: '{{ $variant->id }}' })
                });

                const intentData = await intentResponse.json();
                if (!intentData.client_secret) throw new Error(intentData.message || 'Could not create payment intent');
                
                this.clientSecret = intentData.client_secret;

                // 2. Initialize elements with the client_secret
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

                this.elements = this.stripe.elements({ clientSecret: this.clientSecret, appearance });

                // 3. Create and mount the Payment Element
                this.paymentElement = this.elements.create('payment', {
                    layout: 'tabs',
                    defaultValues: {
                        billingDetails: {
                            address: { country: 'GB' }
                        }
                    }
                });
                
                this.paymentElement.mount('#card-element');

                this.paymentElement.on('change', (event) => {
                    const errorDiv = document.getElementById('card-errors');
                    errorDiv.textContent = event.error ? event.error.message : '';
                });

            } catch (err) {
                const errorDiv = document.getElementById('card-errors');
                if (errorDiv) errorDiv.textContent = 'Failed to load payment form. Please try reloading the page.';
            }
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

            try {
                // Confirm the payment using elements
                const { error, paymentIntent } = await this.stripe.confirmPayment({
                    elements: this.elements,
                    confirmParams: {
                        payment_method_data: {
                            billing_details: {
                                name: this.customerName,
                                email: this.customerEmail,
                                phone: this.customerPhone,
                                address: {
                                    line1: this.addressLine1,
                                    line2: this.addressLine2,
                                    city: this.city,
                                    postal_code: this.postcode,
                                    country: this.country === 'United Kingdom' ? 'GB' : 'IE'
                                }
                            }
                        }
                    },
                    redirect: 'if_required' // Prevent automatic redirect so we can save order first
                });

                if (error) {
                    const errorDiv = document.getElementById('card-errors');
                    if (errorDiv) errorDiv.textContent = error.message;
                    // Also alert for visibility if scrolled away
                    if (error.type === 'validation_error' || error.type === 'card_error') {
                        // Let Stripe Elements handle showing the error in the UI
                    } else {
                        alert(error.message);
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
