@extends('layouts.app')

@section('content')
<script>
    /**
     * Highly robust initializer for order location management.
     * Defined globally to avoid timing issues with Alpine.js modules.
     */
    window.setupOrderLocationManager = function() {
        return {
            isEditing: false,
            isLoading: false,
            placeId: '{{ $order->google_place_id }}',
            placeName: '{{ $order->google_place_name }}',
            places: @json($order->google_places ?? []),
            showNfcModal: false,
            
            get reviewLink() {
                return this.placeId ? `https://search.google.com/local/writereview?placeid=${this.placeId}` : '';
            },
            
            get mapsLink() {
                if (!this.placeId && !this.placeName) return '';
                const query = encodeURIComponent(this.placeName);
                return `https://www.google.com/maps/search/?api=1&query=${query}&query_place_id=${this.placeId}`;
            },

            init() {
                console.log('NFC Manager Initializing...');
                
                // Retry mechanism for Google Maps
                const initIfReady = () => {
                    if (typeof google !== 'undefined' && google.maps && google.maps.places) {
                        console.log('Google Maps ready, initializing autocomplete...');
                        this.initAutocomplete();
                    } else {
                        console.warn('Google Maps not ready yet, retrying in 500ms...');
                        setTimeout(initIfReady, 500);
                    }
                };
                
                initIfReady();
            },

            initAutocomplete() {
                const input = document.getElementById('order-business-search');
                if (!input) {
                    console.error('Search input not found!');
                    return;
                }

                const autocomplete = new google.maps.places.Autocomplete(input, {
                    types: ['establishment'],
                });

                autocomplete.addListener('place_changed', () => {
                    const place = autocomplete.getPlace();
                    if (!place.place_id) return;
                    
                    this.saveLocation(place.place_id, place.name);
                });
            },

            async saveLocation(pId, pName) {
                this.isLoading = true;
                try {
                    const response = await fetch('{{ route("admin.orders.update-location", $order) }}', {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            google_place_id: pId,
                            google_place_name: pName
                        })
                    });

                    const data = await response.json();
                    if (data.success) {
                        this.placeId = pId;
                        this.placeName = pName;
                        this.places = [{id: pId, name: pName}]; // Update the list too
                        this.isEditing = false;
                        if (window.toast) window.toast('Location updated successfully!', 'success');
                        setTimeout(() => window.location.reload(), 500);
                    }
                } catch (error) {
                    console.error('Update failed:', error);
                    alert('Failed to update location. Please try again.');
                } finally {
                    this.isLoading = false;
                }
            },

            openNfcModal() {
                console.log('Opening NFC Modal...');
                this.showNfcModal = true;
            },

            copyReviewLink() {
                navigator.clipboard.writeText(this.reviewLink);
                if (window.toast) window.toast('Review link copied!', 'info');
            },

            // Native NFC Logic
            async writeNfc(url) {
                try {
                    if ("NDEFReader" in window) {
                        const ndef = new NDEFReader();
                        window.toast('📲 Ready to write! Tap your NFC card.', 'info');
                        const controller = new AbortController();
                        setTimeout(() => controller.abort(), 30000);
                        await ndef.write(url, { signal: controller.signal });
                        window.toast('✅ NFC Tag written successfully!', 'success');
                    } else {
                        this.showFallback(url, "Web NFC is not supported", "Your device or browser does not support native NFC writing.");
                    }
                } catch (error) {
                    console.error(error);
                    this.showFallback(url, "NFC Error", error.message);
                }
            },

            async readNfc() {
                try {
                    if ("NDEFReader" in window) {
                        const ndef = new NDEFReader();
                        window.toast('📲 Ready to read! Tap an NFC card.', 'info');
                        await ndef.scan();
                        ndef.onreading = event => {
                            const decoder = new TextDecoder();
                            for (const record of event.message.records) {
                                const data = decoder.decode(record.data);
                                window.toast('📡 Tag Contains: ' + data, 'success');
                            }
                        };
                    } else {
                        window.toast('Reading not supported on this device.', 'error');
                    }
                } catch (error) {
                    console.error(error);
                }
            },

            showFallback(url, title, desc) {
                document.getElementById('fallback-url-input').value = url;
                document.getElementById('nfc-modal-title').textContent = title;
                document.getElementById('nfc-modal-desc').textContent = desc;
                document.getElementById('nfc-fallback-modal').classList.remove('hidden');
            }
        };
    };

    function copyFallbackUrl() {
        const input = document.getElementById('fallback-url-input');
        input.select();
        document.execCommand('copy');
        if (window.toast) window.toast('Link copied!', 'success');
    }
</script>

<div x-data="setupOrderLocationManager()" class="p-4 sm:p-6 bg-white rounded-xl shadow-xl">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-blue-50 rounded-2xl text-blue-600 shadow-sm border border-blue-100/50">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Order Details</h2>
                <p class="text-sm font-medium text-gray-400 mt-1">Order #{{ $order->order_number }}</p>
            </div>
        </div>
        
        <a href="{{ route('admin.orders.index') }}"
           class="inline-flex items-center bg-gray-100 text-gray-700 px-6 py-3.5 rounded-2xl hover:bg-gray-200 transition-all text-sm font-bold">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Orders
        </a>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-xl flex items-center gap-3">
        <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left: Customer & Status -->
        <div class="lg:col-span-1 space-y-8">
            <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Customer Overview
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Customer Name</label>
                        <div class="text-sm font-semibold text-gray-800">{{ $order->customer_name }}</div>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Email Address</label>
                        <div class="text-sm font-semibold text-gray-800">{{ $order->customer_email }}</div>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Phone</label>
                        <div class="text-sm font-semibold text-gray-800">{{ $order->customer_phone ?: 'N/A' }}</div>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Order Date</label>
                        <div class="text-sm font-semibold text-gray-800">{{ $order->created_at->format('d M Y, h:i A') }}</div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Order Status
                </h3>
                <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-2">Current Status</label>
                        <select name="status" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-400">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ $order->status == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3.5 rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-200">
                        Update Status
                    </button>
                </form>
            </div>
        </div>

        <!-- Middle & Right: Items & Shipping -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Order Items -->
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm">
                <div class="px-6 py-4 bg-gray-50 border-bottom border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-900">Order Items</h3>
                    <span class="text-sm font-bold text-blue-600">Total: £{{ number_format($order->total, 2) }}</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50/50 text-gray-400 text-[10px] uppercase font-bold tracking-widest border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4">Product</th>
                                <th class="px-6 py-4">Qty</th>
                                <th class="px-6 py-4 text-right">Price</th>
                                <th class="px-6 py-4 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($order->items as $item)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-800">{{ $item->product_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $item->variant_name }}</div>
                                </td>
                                <td class="px-6 py-4 text-gray-600">{{ $item->quantity }}</td>
                                <td class="px-6 py-4 text-right text-gray-600">£{{ number_format($item->unit_price, 2) }}</td>
                                <td class="px-6 py-4 text-right font-bold text-gray-900">£{{ number_format($item->total, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Shipping & Location -->
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm">
                <div class="px-6 py-4 bg-gray-50 border-bottom border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-900">Shipping & Location</h3>
                    <div class="flex items-center gap-2">
                        <button x-show="placeId" @click="openNfcModal()" class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-blue-700 transition-all shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h2M12 8V7m0 1v4m4 4h.01m-4.01 0h-2M8 16h.01M6 20h4M4 12h4m12 0h2M12 8V7m0 1v4M4 20h4"/></svg>
                            Configure NFC Card
                        </button>
                    </div>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-3">Shipping Address</label>
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 text-sm leading-relaxed text-gray-700">
                            {{ $order->customer_name }}<br>
                            {{ $order->shipping_address['line1'] }}<br>
                            @if(!empty($order->shipping_address['line2']))
                                {{ $order->shipping_address['line2'] }}<br>
                            @endif
                            {{ $order->shipping_address['city'] }}, {{ $order->shipping_address['postcode'] }}<br>
                            {{ $order->shipping_address['country'] }}
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between items-center mb-3">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">Business Location (Google Search)</label>
                            <button @click="isEditing = !isEditing" class="text-[10px] font-bold text-blue-600 uppercase hover:underline">
                                <span x-text="isEditing ? 'Cancel' : (placeId ? 'Change' : 'Assign')"></span>
                            </button>
                        </div>

                        <!-- Search Input -->
                        <div x-show="isEditing" x-transition class="mb-4">
                            <div class="relative group">
                                <input type="text" id="order-business-search" 
                                       placeholder="Search for a business..." 
                                       class="w-full px-12 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none text-sm font-medium">
                                <div class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </div>
                                <div x-show="isLoading" class="absolute right-5 top-1/2 -translate-y-1/2">
                                    <svg class="animate-spin h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                </div>
                            </div>
                        </div>

                        <div x-show="!isEditing" class="bg-gray-50 rounded-xl p-4 border border-gray-100 min-h-[120px] flex flex-col justify-center space-y-4">
                            <template x-if="places && places.length > 0">
                                <div class="space-y-3">
                                    <template x-for="(loc, idx) in places" :key="idx">
                                        <div x-show="loc.id && loc.name" 
                                             :class="placeId === loc.id ? 'border-blue-500 bg-blue-50/30' : 'border-gray-200 bg-white'"
                                             class="p-3 rounded-xl border transition-all flex justify-between items-center group">
                                            <div class="flex-1">
                                                <div class="text-sm font-bold text-gray-800" x-text="loc.name"></div>
                                                <div class="text-[10px] text-gray-400 font-medium" x-text="'ID: ' + loc.id"></div>
                                            </div>
                                            
                                            <div class="flex items-center gap-2">
                                                <a :href="'https://www.google.com/maps/search/?api=1&query=' + encodeURIComponent(loc.name) + '&query_place_id=' + loc.id" 
                                                   target="_blank" 
                                                   title="View on Maps"
                                                   class="p-2 bg-gray-50 text-gray-400 hover:text-blue-600 rounded-lg border border-gray-100 transition-all">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                </a>
                                                
                                                <button @click="placeId = loc.id; placeName = loc.name; if(window.toast) window.toast('Selected for programming', 'success')"
                                                        :class="placeId === loc.id ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-blue-600 border-blue-100 hover:bg-blue-50'"
                                                        class="px-3 py-1.5 rounded-lg border font-bold text-[10px] transition-all flex items-center gap-1.5 shadow-sm">
                                                    <svg x-show="placeId === loc.id" class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                                    <span x-text="placeId === loc.id ? 'Selected' : 'Select for NFC'"></span>
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                            
                            <template x-if="(!places || places.length === 0) && placeName">
                                <div class="bg-white p-4 rounded-xl border border-blue-100">
                                    <div class="text-sm font-bold text-gray-800 mb-1" x-text="placeName"></div>
                                    <div class="text-[10px] text-blue-500 font-medium mb-3" x-text="'Place ID: ' + placeId"></div>
                                    
                                    <a :href="mapsLink" target="_blank" 
                                       class="inline-flex items-center gap-2 bg-gray-50 text-blue-600 px-4 py-2 rounded-lg border border-blue-100 font-bold text-[10px] hover:bg-blue-100 transition-all w-fit mt-auto">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        Open in Maps
                                    </a>
                                </div>
                            </template>
                            
                            <template x-if="!placeName && (!places || places.length === 0)">
                                <div class="text-sm text-gray-400 italic">No business location selected during checkout.</div>
                            </template>
                        </div>
                    </div>
                </div>
                @if($order->notes)
                <div class="px-6 pb-6 pt-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-3">Additional Notes</label>
                    <div class="bg-yellow-50/50 p-4 rounded-xl border border-yellow-100 text-sm text-gray-700 italic">
                        "{{ $order->notes }}"
                    </div>
                </div>
                @endif
            </div>

            <!-- Danger Zone -->
            <div class="bg-red-50 rounded-2xl border border-red-100 overflow-hidden shadow-sm mt-8">
                <div class="px-6 py-4 bg-red-100/50 border-bottom border-red-100">
                    <h3 class="text-lg font-bold text-red-900">Danger Zone</h3>
                </div>
                <div class="p-6 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div>
                        <h4 class="text-sm font-bold text-gray-900 mb-1">Delete this Order</h4>
                        <p class="text-xs text-gray-500">Once deleted, an order cannot be recovered. Please be certain.</p>
                    </div>
                    <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" 
                          onsubmit="return confirm('Are you absolutely sure you want to delete this order? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 text-white font-bold px-6 py-3 rounded-xl hover:bg-red-700 transition-all shadow-lg shadow-red-200 text-sm">
                            Delete Order Permanently
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>


<!-- NFC Programming Modal -->
<div x-show="showNfcModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/40 backdrop-blur-sm p-4" style="display: none;">
    <div @click.away="showNfcModal = false" class="bg-white rounded-3xl shadow-2xl max-w-lg w-full overflow-hidden transition-all duration-300">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-6">
            <h3 class="text-xl font-bold text-white flex items-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v3m0 0v3m0-3h3m-3 0H9m11 0a8 8 0 11-16 0 8 8 0 0116 0z"/>
                </svg>
                Program NFC Card
            </h3>
            <p class="text-blue-100 text-sm mt-1 opacity-90">Encode the business review link onto your physical hardware.</p>
        </div>

        <div class="p-8 space-y-8">
            <!-- Business Info -->
            <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100">
                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Target Business</div>
                <div class="text-lg font-bold text-gray-900 mb-1" x-text="placeName"></div>
                <div class="text-xs text-blue-600 font-mono" x-text="'Place ID: ' + placeId"></div>
            </div>

            <!-- Review Link -->
            <div class="space-y-3">
                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Generated Review Link</label>
                <div class="flex items-center gap-2">
                    <input type="text" :value="reviewLink" readonly 
                           class="flex-1 bg-gray-50 border border-gray-100 rounded-xl px-4 py-3 text-xs font-mono text-gray-600 outline-none">
                    <button @click="copyReviewLink()" class="p-3 bg-blue-50 text-blue-600 rounded-xl border border-blue-100 hover:bg-blue-100 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    </button>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="grid grid-cols-2 gap-4">
                <button @click="writeNfc(reviewLink)" 
                        class="flex flex-col items-center justify-center p-6 bg-gray-900 hover:bg-black text-white rounded-2xl transition-all shadow-lg hover:-translate-y-1">
                    <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    <span class="text-xs font-bold uppercase tracking-wider">Write to Tag</span>
                </button>
                <button @click="readNfc()" 
                        class="flex flex-col items-center justify-center p-6 bg-white border-2 border-gray-100 hover:border-blue-500 text-gray-900 rounded-2xl transition-all hover:-translate-y-1">
                    <svg class="w-8 h-8 mb-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Read Tag</span>
                </button>
            </div>
        </div>

        <div class="bg-gray-50 px-8 py-4 flex justify-end">
            <button @click="showNfcModal = false" class="text-sm font-bold text-gray-500 hover:text-gray-700">Close</button>
        </div>
    </div>
</div>

<!-- NFC Fallback Modal -->
<div id="nfc-fallback-modal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-[110] hidden px-4 sm:px-6">
    <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-lg overflow-hidden relative border border-gray-100">
        <div class="bg-gray-50 px-8 py-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-xl font-bold text-gray-900 flex items-center gap-3">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                NFC Compatibility
            </h3>
            <button onclick="document.getElementById('nfc-fallback-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="p-8 space-y-6">
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 flex gap-4">
                <svg class="w-6 h-6 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <div>
                    <p class="font-bold text-amber-900 text-sm mb-1" id="nfc-modal-title">Web NFC is not active</p>
                    <p class="text-amber-800 text-xs leading-relaxed" id="nfc-modal-desc">This usually happens on iPhones, desktop computers, or if NFC is disabled in settings.</p>
                </div>
            </div>

            <div class="space-y-4">
                <h4 class="font-bold text-gray-900 text-xs uppercase tracking-widest">Manual Setup Steps:</h4>
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-8 h-8 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600 font-bold">1</div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-600 font-medium mb-3">Copy the short link:</p>
                        <div class="flex">
                            <input type="text" id="fallback-url-input" class="text-xs bg-gray-50 border border-gray-200 border-r-0 rounded-l-xl py-3 px-4 w-full text-gray-500 outline-none" readonly>
                            <button onclick="copyFallbackUrl()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-r-xl text-xs font-bold transition">Copy</button>
                        </div>
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-8 h-8 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600 font-bold">2</div>
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Use the <strong>"NFC Tools"</strong> app on your smartphone to write this URL to the card.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-gray-50 px-8 py-5 border-t border-gray-100 flex justify-end">
            <button onclick="document.getElementById('nfc-fallback-modal').classList.add('hidden')" class="text-sm font-bold text-gray-500">Got it</button>
        </div>
    </div>
</div>

</div>

<!-- Load Google Maps -->
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&libraries=places"></script>
@endsection
