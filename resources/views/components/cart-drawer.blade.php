<!-- resources/views/components/cart-drawer.blade.php -->
<div
    x-data="cartDrawer()"
    @open-cart.window="cartOpen = true"
    @cart-updated.window="loadCart()"
    @keydown.escape.window="cartOpen = false"
    x-init="loadCart()"
    class="relative z-[100]"
>
    <!-- Overlay -->
    <div
        x-show="cartOpen"
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="cartOpen = false"
        class="fixed inset-0 bg-black bg-opacity-50"
        style="display: none;"
    ></div>

    <!-- Drawer -->
    <div
        x-show="cartOpen"
        x-transition:enter="transition ease-in-out duration-300 transform"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in-out duration-300 transform"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="fixed inset-y-0 right-0 max-w-md w-full bg-white shadow-2xl flex flex-col z-[101]"
        style="display: none;"
    >
        <!-- Header -->
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-xl font-bold text-[#142D63] tracking-widest uppercase font-ubuntu">Cart</h2>
            <button @click="cartOpen = false" class="text-gray-400 hover:text-gray-600 transition-colors p-2 rounded-full border border-gray-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Free Delivery Progress -->
        <template x-if="items.length > 0 && freeThreshold > 0">
            <div class="px-6 py-3 border-b border-gray-100 bg-gray-50/60">
                <p class="text-xs font-mulish mb-2 flex items-center gap-1.5" :class="qualifiesFree ? 'text-green-600 font-bold' : 'text-gray-600'">
                    <span x-show="qualifiesFree">🎉 You’ve unlocked <strong>FREE standard delivery!</strong></span>
                    <span x-show="!qualifiesFree">Add <strong x-text="'£' + remainingForFree.toFixed(2)"></strong>&nbsp;more for <strong>FREE standard delivery</strong></span>
                </p>
                <div class="w-full bg-gray-200 rounded-full h-1.5 overflow-hidden">
                    <div class="h-1.5 rounded-full transition-all duration-500" :class="qualifiesFree ? 'bg-green-500' : 'bg-[#00A0FF]'" :style="'width: ' + freeProgress + '%'"></div>
                </div>
            </div>
        </template>

        <!-- Cart Items -->
        <div class="flex-1 overflow-y-auto">
            <!-- Empty State -->
            <template x-if="items.length === 0">
                <div class="flex flex-col items-center justify-center h-full p-8 text-center">
                    <div class="mb-8">
                        <svg class="w-24 h-24 text-gray-100 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#142D63] mb-4 font-ubuntu">Your cart is currently empty</h3>
                    <a href="{{ route('shop.index') }}" @click="cartOpen = false" class="inline-block bg-[#00A0FF] hover:bg-[#1800ad] text-white px-10 py-4 rounded-full font-bold uppercase tracking-wider transition-all duration-300 transform hover:scale-105 shadow-lg">
                        Return to Shop
                    </a>
                </div>
            </template>

            <!-- Items List -->
            <template x-if="items.length > 0">
                <div class="p-6 space-y-6">
                    <template x-for="(item, index) in items" :key="index">
                        <div class="flex gap-4 relative">
                            <!-- Remove Button -->
                            <button @click="removeItem(index)" class="absolute top-0 right-0 text-gray-300 hover:text-red-500 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>

                            <!-- Item Image -->
                            <div class="w-20 h-20 bg-[#d8e4ef] rounded-xl flex items-center justify-center flex-shrink-0">
                                <img :src="item.image" :alt="item.name" class="w-16 h-16 object-contain" x-show="item.image">
                            </div>

                            <!-- Item Details -->
                            <div class="flex-1 pr-6">
                                <h4 class="font-bold text-[#142D63] text-sm font-ubuntu" x-text="item.productName"></h4>
                                <p class="text-xs text-gray-500 font-mulish" x-text="'Cards: ' + item.name"></p>
                                <div class="mt-1">
                                    <template x-if="item.locationList && item.locationList.length > 0">
                                        <div class="space-y-1">
                                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Locations:</p>
                                            <template x-for="(loc, lIdx) in item.locationList" :key="lIdx">
                                                <p class="text-[10px] text-gray-500 font-mulish flex items-start gap-1 leading-tight">
                                                    <span class="text-green-500 mt-0.5">•</span>
                                                    <span class="break-anywhere" x-text="loc.name"></span>
                                                </p>
                                            </template>
                                        </div>
                                    </template>
                                    <template x-if="!item.locationList || item.locationList.length === 0">
                                        <p class="text-xs text-gray-500 font-mulish" x-show="item.placeName" x-text="'Address: ' + item.placeName"></p>
                                    </template>
                                </div>

                                <!-- Quantity Controls -->
                                <div class="flex items-center justify-between mt-2">
                                    <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden">
                                        <button @click="updateQty(index, -1)" class="px-2 py-1 text-gray-500 hover:bg-gray-100 transition-colors text-sm">−</button>
                                        <span class="px-3 py-1 text-sm font-semibold text-[#142D63] min-w-[30px] text-center" x-text="item.qty"></span>
                                        <button @click="updateQty(index, 1)" class="px-2 py-1 text-gray-500 hover:bg-gray-100 transition-colors text-sm">+</button>
                                    </div>
                                    <span class="font-bold text-[#142D63] font-ubuntu" x-text="'£' + (item.price * item.qty).toFixed(2)"></span>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </template>
        </div>

        <!-- Footer -->
        <template x-if="items.length > 0">
            <div class="border-t border-gray-100">
                <div class="px-6 py-3 text-xs text-gray-500 text-center font-mulish">
                    Tax included and shipping calculated at checkout
                </div>
                <div class="px-6 pb-6">
                    <a :href="checkoutUrl()" class="block w-full bg-[#28A745] hover:bg-[#218838] text-white text-center py-4 rounded-full font-bold uppercase tracking-wider transition-all duration-300 shadow-lg">
                        Check Out — <span x-text="'£' + cartTotal().toFixed(2) + ' GBP'"></span>
                    </a>
                </div>
            </div>
        </template>
    </div>
</div>

<script>
function cartDrawer() {
    return {
        cartOpen: false,
        items: [],
        freeThreshold: {{ (float) \App\Models\Setting::get('free_delivery_threshold', 25) }},

        get qualifiesFree() {
            return this.freeThreshold > 0 && this.cartTotal() >= this.freeThreshold;
        },
        get remainingForFree() {
            return Math.max(0, this.freeThreshold - this.cartTotal());
        },
        get freeProgress() {
            if (this.freeThreshold <= 0) return 0;
            return Math.min(100, (this.cartTotal() / this.freeThreshold) * 100);
        },

        loadCart() {
            try {
                this.items = JSON.parse(localStorage.getItem('rb_cart') || '[]');
            } catch (e) {
                this.items = [];
            }
            this.updateBadge();
        },

        saveCart() {
            localStorage.setItem('rb_cart', JSON.stringify(this.items));
            this.updateBadge();
        },

        updateBadge() {
            const totalQty = this.items.reduce((sum, item) => sum + item.qty, 0);
            document.querySelectorAll('.cart-count-badge').forEach(el => {
                el.textContent = totalQty;
                el.style.display = totalQty > 0 ? 'flex' : 'none';
            });
        },

        removeItem(index) {
            this.items.splice(index, 1);
            this.saveCart();
        },

        updateQty(index, delta) {
            this.items[index].qty = Math.max(1, this.items[index].qty + delta);
            this.saveCart();
        },

        cartTotal() {
            return this.items.reduce((sum, item) => sum + (item.price * item.qty), 0);
        },

        checkoutUrl() {
            if (this.items.length === 0) return '#';
            const item = this.items[0];
            return '/shop/checkout?variant_id=' + item.variantId
                + '&place_id=' + encodeURIComponent(item.placeId || '')
                + '&place_name=' + encodeURIComponent(item.placeName || '');
        }
    };
}
</script>
