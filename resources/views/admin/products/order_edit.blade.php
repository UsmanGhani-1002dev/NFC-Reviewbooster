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
            adminEditMode: 'search',
            manualReviewUrl: '',
            customNfcUrl: '',
            placeId: '{{ $order->google_place_id }}',
            placeName: '{{ $order->google_place_name }}',
            places: @json($order->google_places ?? []),
            showNfcModal: false,
            
            showEmailModal: false,
            emailSubject: 'Update regarding your Order #{{ $order->order_number }}',
            emailMessage: '',
            includeSummary: true,
            isSendingEmail: false,
            emailSuccessMessage: '',
            emailErrorMessage: '',

            applyEmailTemplate(type) {
                if (type === 'shipping') {
                    this.emailSubject = 'Tracking update for your Order #{{ $order->order_number }}';
                    this.emailMessage = 'We wanted to let you know that your order #{{ $order->order_number }} is on its way!\n\nTracking Number: {{ $order->tracking_number ?? "[Insert Tracking Number]" }}\nCarrier: {{ $order->carrier ?? "Royal Mail" }}\n\nThank you for choosing Tap Review Cards!';
                } else if (type === 'location') {
                    this.emailSubject = 'Action Required: Google Review Link for Order #{{ $order->order_number }}';
                    this.emailMessage = 'Thank you for your order! To program your NFC cards/stands, please reply to this email with your Google Business review link or business name.\n\nOnce received, we will dispatch your order immediately.\n\nBest regards,\nTap Review Cards Team';
                } else if (type === 'general') {
                    this.emailSubject = 'Update regarding your Order #{{ $order->order_number }}';
                    this.emailMessage = 'Here are some updates regarding your order #{{ $order->order_number }}:\n\n[Type your custom details here]\n\nBest regards,\nTap Review Cards Team';
                }
            },

            async sendCustomerEmailSubmit() {
                if (!this.emailSubject.trim() || !this.emailMessage.trim()) {
                    alert('Please fill in both Subject and Message.');
                    return;
                }

                this.isSendingEmail = true;
                this.emailSuccessMessage = '';
                this.emailErrorMessage = '';

                try {
                    const response = await fetch('{{ route("admin.orders.send-email", $order) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            subject: this.emailSubject,
                            message: this.emailMessage,
                            include_summary: this.includeSummary ? 1 : 0
                        })
                    });

                    const data = await response.json();
                    if (data.success) {
                        this.emailSuccessMessage = data.message || 'Email sent successfully!';
                        setTimeout(() => {
                            this.showEmailModal = false;
                            this.emailSuccessMessage = '';
                        }, 1800);
                    } else {
                        this.emailErrorMessage = data.message || 'Failed to send email.';
                    }
                } catch (e) {
                    console.error('Email error:', e);
                    this.emailErrorMessage = 'Network error. Please try again.';
                } finally {
                    this.isSendingEmail = false;
                }
            },
            
            get reviewLink() {
                if (this.customNfcUrl) return this.customNfcUrl;
                if (!this.placeId && !this.placeName) return '';
                if (this.placeId && (this.placeId.startsWith('http://') || this.placeId.startsWith('https://'))) return this.placeId;
                if (this.placeName && (this.placeName.startsWith('http://') || this.placeName.startsWith('https://'))) return this.placeName;
                if (this.placeId && (this.placeId.startsWith('loc-') || this.placeId.startsWith('skip-') || this.placeId.startsWith('direct-'))) {
                    return '';
                }
                return this.placeId ? `https://search.google.com/local/writereview?placeid=${this.placeId}` : '';
            },
            
            get mapsLink() {
                if (!this.placeId && !this.placeName) return '';
                if (this.placeId && (this.placeId.startsWith('http://') || this.placeId.startsWith('https://'))) return this.placeId;
                if (this.placeName && (this.placeName.startsWith('http://') || this.placeName.startsWith('https://'))) return this.placeName;
                if (this.placeId && this.placeId.startsWith('skip-')) return '';
                const query = encodeURIComponent(this.placeName);
                if (this.placeId && !this.placeId.startsWith('loc-') && !this.placeId.startsWith('direct-')) {
                    return `https://www.google.com/maps/search/?api=1&query=${query}&query_place_id=${this.placeId}`;
                }
                return `https://www.google.com/maps/search/?api=1&query=${query}`;
            },

            resolveLocLink(loc) {
                if (!loc) return '';
                if (loc.id && (loc.id.startsWith('http://') || loc.id.startsWith('https://'))) return loc.id;
                if (loc.name && (loc.name.startsWith('http://') || loc.name.startsWith('https://'))) return loc.name;
                if (!loc.id || loc.id.startsWith('loc-') || loc.id.startsWith('skip-') || loc.id.startsWith('direct-')) {
                    return loc.name ? `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(loc.name)}` : '';
                }
                return `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(loc.name)}&query_place_id=${loc.id}`;
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
                        this.customNfcUrl = (pId && (pId.startsWith('http://') || pId.startsWith('https://'))) ? pId : '';
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

            saveManualUrl() {
                const url = this.manualReviewUrl.trim();
                if (!url) return;
                this.saveLocation(url, url);
            },

            openNfcModal() {
                console.log('Opening NFC Modal...');
                this.customNfcUrl = this.reviewLink;
                this.showNfcModal = true;
            },

            // Open the NFC modal pre-loaded with a specific ORDER ITEM's review
            // link, so each card/keyring can be programmed with its own location.
            openNfcModalFor(url, name) {
                this.customNfcUrl = url || '';
                this.placeName = name || this.placeName;
                this.placeId = url || this.placeId;
                this.showNfcModal = true;
            },

            copyReviewLink() {
                const target = this.customNfcUrl || this.reviewLink;
                if (target) {
                    navigator.clipboard.writeText(target);
                    if (window.toast) window.toast('Review link copied!', 'info');
                }
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
                <div class="flex flex-wrap items-center gap-2 mt-2">
                    @if($order->partner_role_label || !empty($order->billing_details))
                        <span class="inline-flex items-center gap-1 px-3 py-0.5 rounded-full text-xs font-bold bg-purple-100 text-purple-800 border border-purple-200">
                            👑 Partner Account{{ $order->partner_role_label ? ' · ' . $order->partner_role_label : '' }}
                        </span>
                    @endif
                    @if($order->is_dropship)
                        <span class="inline-flex items-center gap-1 px-3 py-0.5 rounded-full text-xs font-bold bg-indigo-100 text-indigo-800 border border-indigo-200">
                            📦 Dropship
                        </span>
                    @endif
                    @if(!$order->partner_role_label && empty($order->billing_details))
                        <span class="inline-flex items-center gap-1 px-3 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-600 border border-gray-200">
                            Standard Customer
                        </span>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="flex flex-wrap items-center gap-3">
            <button type="button" @click="showEmailModal = true"
               class="inline-flex items-center bg-purple-600 text-white px-5 py-3.5 rounded-2xl hover:bg-purple-700 transition-all text-sm font-bold shadow-md cursor-pointer">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Email Customer
            </button>
            <a href="{{ route('admin.orders.print', $order) }}" target="_blank"
               class="inline-flex items-center bg-blue-600 text-white px-5 py-3.5 rounded-2xl hover:bg-blue-700 transition-all text-sm font-bold">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Print Order Details
            </a>
            <a href="{{ route('admin.orders.label', $order) }}" target="_blank"
               class="inline-flex items-center bg-gray-900 text-white px-5 py-3.5 rounded-2xl hover:bg-black transition-all text-sm font-bold">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5a1.99 1.99 0 011.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.99 1.99 0 013 12V7a4 4 0 014-4z"/></svg>
                Print Label
            </a>
            <a href="{{ route('admin.orders.index') }}"
               class="inline-flex items-center bg-gray-100 text-gray-700 px-6 py-3.5 rounded-2xl hover:bg-gray-200 transition-all text-sm font-bold">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-xl flex items-center gap-3">
        <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
    @endif

    @if($order->is_dropship)
        <div class="mb-8 p-5 bg-gradient-to-r from-purple-900 to-indigo-900 text-white rounded-2xl shadow-lg border border-purple-700 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center text-2xl shrink-0">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="text-lg font-extrabold text-white">DROPSHIPPING FULFILLMENT DIRECTIVE</h3>
                        @if($order->partner_role_label)
                            <span class="px-3 py-0.5 rounded-full text-xs font-bold bg-purple-500/30 text-purple-200 border border-purple-400/30">
                                {{ $order->partner_role_label }} Order
                            </span>
                        @endif
                    </div>
                    <p class="text-purple-200 text-sm mt-1">
                        <strong>PACKAGING REQUIREMENT: UNBRANDED / BLANK INVOICE REQUIRED.</strong> Ship directly to end-customer delivery address below. Do not include ReviewBooster brand invoices.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left: Customer & Status -->
        <div class="lg:col-span-1 space-y-8">
            <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center justify-between">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        @if($order->is_dropship) End-Customer (Receiver) @else Customer Overview @endif
                    </span>
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Name</label>
                        <div class="text-sm font-semibold text-gray-800">{{ $order->customer_name }}</div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Email Address</label>
                            {{-- <button type="button" @click="showEmailModal = true" class="text-xs font-bold text-purple-600 hover:text-purple-800 hover:underline inline-flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                Send Email
                            </button> --}}
                        </div>
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

                    @if(!empty($order->billing_details))
                        <div class="pt-4 border-t border-gray-200">
                            <label class="text-[10px] font-extrabold text-purple-600 uppercase tracking-widest block mb-2">
                                {{ $order->is_dropship ? 'Reseller (Purchasing Partner)' : 'Purchasing Partner Billing Info' }}
                            </label>
                            <div class="bg-purple-50 p-3.5 rounded-xl border border-purple-100 text-xs space-y-1 text-purple-900">
                                <div><strong>Partner:</strong> {{ $order->billing_details['partner_name'] ?? 'N/A' }} ({{ $order->billing_details['partner_type'] ?? '' }})</div>
                                @if(!empty($order->billing_details['partner_company']))
                                    <div><strong>Company:</strong> {{ $order->billing_details['partner_company'] }}</div>
                                @endif
                                <div><strong>Billing Name:</strong> {{ $order->billing_details['billing_name'] ?? ($order->billing_details['partner_name'] ?? 'N/A') }}</div>
                                <div><strong>Billing Email:</strong> {{ $order->billing_details['billing_email'] ?? ($order->billing_details['partner_email'] ?? 'N/A') }}</div>
                                @if(!empty($order->billing_details['vat_number']))
                                    <div><strong>VAT:</strong> {{ $order->billing_details['vat_number'] }}</div>
                                @endif
                                @if(!empty($order->billing_details['reseller_reference']))
                                    <div class="pt-1 mt-1 border-t border-purple-200/60">
                                        <strong>Reseller Ref / PO:</strong>
                                        <span class="font-mono">{{ $order->billing_details['reseller_reference'] }}</span>
                                    </div>
                                @endif
                            </div>
                            @if($order->user_id)
                                <a href="{{ route('admin.users.edit', $order->user_id) }}"
                                   class="inline-flex items-center gap-1 mt-2 text-[11px] font-bold text-purple-600 hover:text-purple-800 hover:underline">
                                    View partner account
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Order Fulfillment</span>
                    </h3>
                    @if($order->has_tracking)
                        <span class="px-2.5 py-1 rounded-full text-[12px] font-bold bg-green-100 text-green-700 border border-green-200 whitespace-nowrap">
                            ✓ Tracked
                        </span>
                    @endif
                </div>

                <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="space-y-4"
                      x-data="{ 
                          selectedStatus: '{{ old('status', $order->status) }}',
                          hasTracking: {{ $order->has_tracking ? 'true' : 'false' }}
                      }">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1.5">Order Status</label>
                        <select name="status" x-model="selectedStatus" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm font-semibold focus:ring-2 focus:ring-indigo-400">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ $order->status == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <!-- Show Courier & Tracking inputs ONLY when status is Shipped / Completed or tracking already exists -->
                    <div x-show="selectedStatus === 'shipped' || selectedStatus === 'completed' || hasTracking" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="space-y-4 border-t border-gray-200/80 pt-4">

                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1.5">Shipping Courier</label>
                            <select name="carrier" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-indigo-400">
                                <option value="Royal Mail" {{ ($order->carrier ?? 'Royal Mail') == 'Royal Mail' ? 'selected' : '' }}>Royal Mail</option>
                                <option value="DPD" {{ ($order->carrier ?? '') == 'DPD' ? 'selected' : '' }}>DPD UK</option>
                                <option value="DHL" {{ ($order->carrier ?? '') == 'DHL' ? 'selected' : '' }}>DHL Express</option>
                                <option value="EVRi" {{ ($order->carrier ?? '') == 'EVRi' ? 'selected' : '' }}>EVRi / Hermes</option>
                                <option value="Other" {{ !in_array(($order->carrier ?? ''), ['Royal Mail', 'DPD', 'DHL', 'EVRi']) && $order->carrier ? 'selected' : '' }}>Other / Custom</option>
                            </select>
                        </div>

                        <div>
                            <div class="flex justify-between items-center mb-1.5">
                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Tracking Number / Code</label>
                                @if($order->has_tracking)
                                    <a href="{{ $order->tracking_url }}" target="_blank" class="text-[11px] font-bold text-indigo-600 hover:text-indigo-800 hover:underline flex items-center gap-1">
                                        <span>Track on Royal Mail</span>
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    </a>
                                @endif
                            </div>
                            <input type="text" name="tracking_number" value="{{ old('tracking_number', $order->tracking_number) }}"
                                   placeholder="e.g. GB123456789GB"
                                   class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-mono focus:ring-2 focus:ring-indigo-400">
                        </div>

                        <div class="pt-1">
                            <label class="flex items-start gap-2.5 cursor-pointer select-none">
                                <input type="checkbox" name="send_notification" value="1" checked class="mt-0.5 w-4 h-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                                <span class="text-xs text-gray-600 font-medium">
                                    Send Royal Mail tracking email to customer (<strong class="text-gray-800">{{ $order->customer_email }}</strong>)
                                </span>
                            </label>
                        </div>

                        @if($order->tracking_notified_at)
                            <div class="text-[11px] text-gray-500 bg-white p-2.5 rounded-lg border border-gray-200 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Email sent on {{ $order->tracking_notified_at->format('d M Y, h:i A') }}</span>
                            </div>
                        @endif
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-3 rounded-xl hover:bg-indigo-700 transition-all shadow-md shadow-indigo-100 flex items-center justify-center gap-2 mt-4">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Update Order Status</span>
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

                <!-- Desktop Table (hidden on mobile) -->
                <div class="hidden md:block overflow-x-auto">
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
                            @php
                                $variant = $item->variant;
                                $product = $variant?->product;
                                if (!$product && $item->product_name) {
                                    $product = \App\Models\Product::where('name', $item->product_name)->first();
                                }
                                $imagePath = $variant?->image ?: $product?->image;
                                $productShopUrl = $product ? route('shop.show', $product->slug) : null;
                                $productAdminUrl = $product ? route('admin.products.edit', $product) : null;
                            @endphp
                            <tr>
                                <td class="px-6 py-4 align-top">
                                    <div class="flex items-start gap-4">
                                        <div class="w-14 h-14 bg-gray-50 rounded-xl border border-gray-100 flex items-center justify-center overflow-hidden shrink-0 shadow-sm">
                                            @if($imagePath)
                                                <img src="{{ asset('storage/' . $imagePath) }}" alt="{{ $item->product_name }}" class="w-full h-full object-contain">
                                            @else
                                                <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-400">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <div class="flex flex-wrap items-center gap-2">
                                                @if($productShopUrl)
                                                    <a href="{{ $productShopUrl }}" target="_blank" class="font-bold text-gray-900 hover:text-blue-600 transition-colors inline-flex items-center gap-1 group">
                                                        <span>{{ $item->product_name }}</span>
                                                        <svg class="w-3.5 h-3.5 text-gray-400 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                        </svg>
                                                    </a>
                                                @else
                                                    <div class="font-bold text-gray-800">{{ $item->product_name }}</div>
                                                @endif

                                                @if($productAdminUrl)
                                                    <a href="{{ $productAdminUrl }}" target="_blank" title="Edit product in admin panel" class="text-[10px] font-bold text-gray-500 hover:text-blue-600 bg-gray-100 hover:bg-blue-50 border border-gray-200 hover:border-blue-200 px-2 py-0.5 rounded-md transition-all inline-flex items-center gap-1">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                        Edit Product
                                                    </a>
                                                @endif
                                            </div>

                                            <div class="mt-1">
                                                <span class="inline-flex items-center rounded-md bg-gray-100 px-2 py-0.5 text-xs font-medium text-blue-600">
                                                    Variant: {{ $item->variant_name }}
                                                </span>
                                            </div>

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

                                            @php
                                                $itemLocations = is_array($item->locations) ? array_values($item->locations) : [];
                                                $isValidReview = function ($loc) {
                                                    $id = trim($loc['id'] ?? '');
                                                    $nm = trim($loc['name'] ?? '');
                                                    return (str_starts_with($id, 'http') || str_starts_with($nm, 'http'))
                                                        || (strlen($id) >= 20 && !str_starts_with($id, 'skip-') && !str_starts_with($id, 'loc-') && !str_starts_with($id, 'direct-'));
                                                };
                                            @endphp

                                            @if(count($itemLocations))
                                            <div class="mt-3 space-y-2.5">
                                                <div class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Linked location(s) — each has its own review link / QR</div>
                                                @foreach($itemLocations as $li => $loc)
                                                    @php
                                                        $isDirectLink = str_starts_with($loc['name'] ?? '', 'http') || str_starts_with($loc['id'] ?? '', 'http');
                                                        $linkUrl = $isDirectLink
                                                            ? ($loc['name'] ?? $loc['id'])
                                                            : 'https://www.google.com/maps/search/?api=1&query=' . urlencode($loc['name']) . '&query_place_id=' . urlencode($loc['id']);
                                                        $locValid = $isValidReview($loc);
                                                        $prefill = $isDirectLink ? ($loc['name'] ?? $loc['id']) : '';
                                                    @endphp
                                                    <div x-data="orderItemLocation('{{ route('admin.orders.item-location', [$order->id, $item->id]) }}', {{ $li }}, @js($prefill))"
                                                         class="border-l-2 {{ $locValid ? 'border-green-200' : 'border-amber-300' }} pl-2">
                                                        <div class="flex items-start gap-1.5">
                                                            <svg class="w-4 h-4 mt-0.5 {{ $locValid ? 'text-green-500' : 'text-amber-500' }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                            <div class="min-w-0">
                                                                <span class="text-[11px] text-gray-600 block" style="word-wrap:anywhere">{{ $loc['name'] }}</span>
                                                                <div class="flex items-center gap-4 mt-0.5">
                                                                    <a href="{{ $linkUrl }}" target="_blank" class="inline-flex items-center gap-1 text-[10px] font-bold text-blue-500 hover:underline">
                                                                        <i data-lucide="external-link" class="h-4 w-4 flex-shrink-0"></i>Open
                                                                    </a>
                                                                    <button type="button" @click="toggle()" class="text-[10px] font-bold {{ $locValid ? 'text-blue-600' : 'text-amber-600' }} hover:underline">
                                                                        <span class="inline-flex items-center gap-1 hover:underline">
                                                                            <i data-lucide="{{ $locValid ? 'pencil' : 'triangle-alert' }}" class="h-4 w-4 flex-shrink-0"></i>
                                                                            {{ $locValid ? 'Change review link' : 'Add review link' }}
                                                                        </span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @include('admin.products._review_editor_body')
                                                    </div>
                                                @endforeach
                                            </div>
                                            @else
                                            <div class="mt-3" x-data="orderItemLocation('{{ route('admin.orders.item-location', [$order->id, $item->id]) }}', null, '')">
                                                <button type="button" @click="toggle()" class="text-[10px] font-bold text-amber-600 hover:underline">⚠ Add review link (needed for QR)</button>
                                                @include('admin.products._review_editor_body')
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-600">{{ $item->quantity }}</td>
                                <td class="px-6 py-4 text-right text-gray-600">£{{ number_format($item->unit_price, 2) }}</td>
                                <td class="px-6 py-4 text-right font-bold text-gray-900">£{{ number_format($item->total, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards (hidden on desktop) -->
                <div class="md:hidden divide-y divide-gray-100">
                    @foreach($order->items as $item)
                    @php
                        $variant = $item->variant;
                        $product = $variant?->product;
                        if (!$product && $item->product_name) {
                            $product = \App\Models\Product::where('name', $item->product_name)->first();
                        }
                        $imagePath = $variant?->image ?: $product?->image;
                        $productShopUrl = $product ? route('shop.show', $product->slug) : null;
                        $productAdminUrl = $product ? route('admin.products.edit', $product) : null;
                    @endphp
                    <div class="p-4">
                        <div class="flex items-start gap-3">
                            <div class="w-14 h-14 bg-gray-50 rounded-xl border border-gray-100 flex items-center justify-center overflow-hidden shrink-0 shadow-sm">
                                @if($imagePath)
                                    <img src="{{ asset('storage/' . $imagePath) }}" alt="{{ $item->product_name }}" class="w-full h-full object-contain">
                                @else
                                    <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                @if($productShopUrl)
                                    <a href="{{ $productShopUrl }}" target="_blank" class="font-bold text-gray-900 hover:text-blue-600 transition-colors inline-flex items-center gap-1 group break-words">
                                        <span>{{ $item->product_name }}</span>
                                        <svg class="w-3.5 h-3.5 text-gray-400 group-hover:text-blue-600 transition-colors shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                    </a>
                                @else
                                    <div class="font-bold text-gray-800 break-words">{{ $item->product_name }}</div>
                                @endif

                                <div class="mt-1">
                                    <span class="inline-flex items-center rounded-md bg-gray-100 px-2 py-0.5 text-xs font-medium text-blue-600">
                                        {{ $item->variant_name }}
                                    </span>
                                </div>

                                @if($productAdminUrl)
                                    <a href="{{ $productAdminUrl }}" target="_blank" class="mt-1.5 text-[10px] font-bold text-gray-500 hover:text-blue-600 bg-gray-100 hover:bg-blue-50 border border-gray-200 hover:border-blue-200 px-2 py-0.5 rounded-md transition-all inline-flex items-center gap-1 w-fit">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        Edit Product
                                    </a>
                                @endif
                            </div>
                        </div>

                        @if($item->custom_logo_path)
                        @php
                            $logoUrlM = asset('storage/' . $item->custom_logo_path);
                            $logoIsImageM = \Illuminate\Support\Str::endsWith(strtolower($item->custom_logo_path), ['.png', '.jpg', '.jpeg', '.svg', '.webp']);
                            $logoExtM = pathinfo($item->custom_logo_path, PATHINFO_EXTENSION);
                            $logoDownloadNameM = trim($order->order_number . ' - ' . preg_replace('/[\/\\\\:*?"<>|]/', '', $item->variant_name)) . ($logoExtM ? '.' . $logoExtM : '');
                        @endphp
                        <div class="mt-3 pl-[68px]">
                            <div class="flex items-center gap-3 bg-blue-50 border border-blue-100 rounded-xl p-2.5 w-fit">
                                <a href="{{ $logoUrlM }}" target="_blank" class="w-11 h-11 rounded-lg bg-white border border-blue-100 flex items-center justify-center overflow-hidden flex-shrink-0">
                                    @if($logoIsImageM)
                                        <img src="{{ $logoUrlM }}" alt="Custom logo" class="w-full h-full object-contain">
                                    @else
                                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    @endif
                                </a>
                                <div>
                                    <div class="text-[10px] font-bold text-blue-700 uppercase tracking-widest">🎨 Custom Logo</div>
                                    <a href="{{ $logoUrlM }}" target="_blank" download="{{ $logoDownloadNameM }}" class="text-[11px] font-bold text-blue-600 hover:underline">View / Download</a>
                                </div>
                            </div>
                        </div>
                        @endif

                        @php
                            $itemLocationsM = is_array($item->locations) ? array_values($item->locations) : [];
                            $isValidReviewM = function ($loc) {
                                $id = trim($loc['id'] ?? '');
                                $nm = trim($loc['name'] ?? '');
                                return (str_starts_with($id, 'http') || str_starts_with($nm, 'http'))
                                    || (strlen($id) >= 20 && !str_starts_with($id, 'skip-') && !str_starts_with($id, 'loc-') && !str_starts_with($id, 'direct-'));
                            };
                        @endphp

                        @if(count($itemLocationsM))
                        <div class="mt-3 pl-[68px] space-y-2.5">
                            <div class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Linked location(s) — each has its own review link / QR</div>
                            @foreach($itemLocationsM as $li => $loc)
                                @php
                                    $isDirectLink = str_starts_with($loc['name'] ?? '', 'http') || str_starts_with($loc['id'] ?? '', 'http');
                                    $linkUrl = $isDirectLink
                                        ? ($loc['name'] ?? $loc['id'])
                                        : 'https://www.google.com/maps/search/?api=1&query=' . urlencode($loc['name']) . '&query_place_id=' . urlencode($loc['id']);
                                    $locValid = $isValidReviewM($loc);
                                    $prefill = $isDirectLink ? ($loc['name'] ?? $loc['id']) : '';
                                @endphp
                                <div x-data="orderItemLocation('{{ route('admin.orders.item-location', [$order->id, $item->id]) }}', {{ $li }}, @js($prefill))" class="border-l-2 {{ $locValid ? 'border-green-200' : 'border-amber-300' }} pl-2">
                                    <div class="flex items-start gap-1.5">
                                        <svg class="w-4 h-4 mt-0.5 {{ $locValid ? 'text-green-500' : 'text-amber-500' }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        <div class="min-w-0">
                                            <span class="text-[11px] text-gray-600 block" style="word-wrap:anywhere">{{ $loc['name'] }}</span>
                                            <div class="flex items-center gap-2 mt-0.5">
                                                <a href="{{ $linkUrl }}" target="_blank" class="inline-flex items-center gap-1 text-[10px] font-bold text-blue-500 hover:underline">
                                                    <i data-lucide="external-link" class="h-4 w-4 flex-shrink-0"></i>Open
                                                </a>
                                                <button type="button" @click="toggle()" class="text-[10px] font-bold {{ $locValid ? 'text-blue-600' : 'text-amber-600' }} hover:underline">
                                                    {{ $locValid ? '✎ Change review link' : '⚠ Add review link' }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @include('admin.products._review_editor_body')
                                </div>
                            @endforeach
                        </div>
                        @else
                        <div class="mt-3 pl-[68px]" x-data="orderItemLocation('{{ route('admin.orders.item-location', [$order->id, $item->id]) }}', null, '')">
                            <button type="button" @click="toggle()" class="text-[10px] font-bold text-amber-600 hover:underline">⚠ Add review link (needed for QR)</button>
                            @include('admin.products._review_editor_body')
                        </div>
                        @endif

                        <div class="mt-3 pl-[68px] flex items-center justify-between text-sm border-t border-gray-50 pt-2">
                            <span class="text-gray-500">Qty: <span class="font-semibold text-gray-700">{{ $item->quantity }}</span></span>
                            <span class="text-gray-500">£{{ number_format($item->unit_price, 2) }} <span class="text-gray-300">×</span> {{ $item->quantity }} = <span class="font-bold text-gray-900">£{{ number_format($item->total, 2) }}</span></span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Shipping & Location -->
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm">
                <div class="px-6 py-4 bg-gray-50 border-bottom border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-900">Shipping & Location</h3>
                    <div class="flex items-center gap-2">
                        <button x-show="placeId" @click="openNfcModal()" class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-blue-700 transition-all shadow-sm">
                            <i data-lucide="nfc" class="h-4 w-4"></i>
                            Configure NFC Card
                        </button>
                    </div>
                </div>

                @if(str_starts_with($order->google_place_id ?? '', 'skip-'))
                <div class="mx-6 mt-4 p-4 bg-amber-50 border border-amber-200 rounded-xl text-amber-900 flex items-start gap-3">
                    <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-amber-800">Customer Selected "Skip For Now"</p>
                        <p class="text-xs text-amber-700 mt-0.5">The customer opted to link their Google Business Profile after ordering. Please obtain their Google review link (or GBP link), then paste and save it below before encoding cards.</p>
                    </div>
                </div>
                @endif

                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="order-2 md:order-1">
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

                        @php
                            $shipMethod = $order->shipping_method ?: 'standard';
                            $isExpress = strtolower($shipMethod) === 'express';
                            $shipLabel = $isExpress ? 'Express Delivery' : 'Standard Delivery';
                            $shipFee = (float) $order->shipping_fee;
                        @endphp
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mt-5 mb-3">Delivery Method</label>
                        <div class="flex items-center justify-between gap-3 bg-gray-50 rounded-xl p-4 border border-gray-100">
                            <div class="flex items-center gap-3">
                                <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg {{ $isExpress ? 'bg-amber-100 text-amber-600' : 'bg-blue-100 text-blue-600' }} flex-shrink-0">
                                    <i data-lucide="{{ $isExpress ? 'zap' : 'truck' }}" class="w-4 h-4"></i>
                                </span>
                                <div>
                                    <div class="text-sm font-bold text-gray-800">{{ $shipLabel }}</div>
                                    <div class="text-[11px] text-gray-500">Chosen by customer at checkout</div>
                                </div>
                            </div>
                            <span class="text-sm font-bold {{ $shipFee > 0 ? 'text-gray-800' : 'text-green-600' }} whitespace-nowrap">
                                {{ $shipFee > 0 ? '£' . number_format($shipFee, 2) : 'FREE' }}
                            </span>
                        </div>
                    </div>
                    <div class="order-1 md:order-2">
                        <div class="flex justify-between items-center mb-3">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block">Business Location / Review Link</label>
                        </div>

                        <!-- Search / Direct Link Input -->
                        <div x-show="isEditing" x-transition class="mb-4 space-y-3">
                            <div class="flex gap-2">
                                <button type="button" @click="adminEditMode = 'search'" 
                                        :class="adminEditMode === 'search' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all">Google Search</button>
                                <button type="button" @click="adminEditMode = 'custom'" 
                                        :class="adminEditMode === 'custom' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all">Paste Direct Link / URL</button>
                            </div>

                            <!-- Mode A: Google Search -->
                            <div x-show="adminEditMode === 'search'" class="relative group">
                                <input type="text" id="order-business-search" 
                                       placeholder="Search for a business on Google..." 
                                       class="w-full px-12 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none text-sm font-medium">
                                <div class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </div>
                                <div x-show="isLoading" class="absolute right-5 top-1/2 -translate-y-1/2">
                                    <svg class="animate-spin h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                </div>
                            </div>

                            <!-- Mode B: Direct Link -->
                            <div x-show="adminEditMode === 'custom'" class="flex gap-2">
                                <input type="text" x-model="manualReviewUrl" 
                                       placeholder="Paste review URL e.g. https://g.page/r/... or Place ID"
                                       class="flex-1 px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-blue-500 text-xs font-mono outline-none">
                                <button type="button" @click="saveManualUrl()" 
                                        :disabled="!manualReviewUrl.trim() || isLoading"
                                        class="bg-blue-600 text-white px-5 py-3 rounded-xl text-xs font-bold hover:bg-blue-700 transition disabled:opacity-50 flex items-center gap-1">
                                    <span x-text="isLoading ? 'Saving...' : 'Save Link'"></span>
                                </button>
                            </div>
                        </div>

                        <div x-show="!isEditing" class="bg-gray-50 rounded-xl p-4 border border-gray-100 min-h-[120px] flex flex-col justify-center space-y-4">
                            <template x-if="places && places.length > 0">
                                <div class="space-y-3">
                                    <template x-for="(loc, idx) in places" :key="idx">
                                        <div x-show="loc.id && loc.name" 
                                             :class="placeId === loc.id ? 'border-blue-500 bg-blue-50/30' : 'border-gray-200 bg-white'"
                                             class="p-3 rounded-xl border transition-all flex justify-between items-center group" style="word-wrap:anywhere">
                                            <div class="flex-1">
                                                <div class="text-sm font-bold text-gray-800" x-text="loc.name"></div>
                                                <div class="text-[10px] text-gray-400 font-medium" x-text="'ID: ' + loc.id"></div>
                                            </div>
                                            
                                            <div class="flex items-center gap-2">
                                                <a :href="resolveLocLink(loc)" 
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

            <!-- Review QR Code(s) -->
            @php
                // Only build a QR when there is a REAL review link. Skip-for-now,
                // placeholder ids (loc-/skip-/direct-) and dummy/short values
                // return null so no QR is generated until the admin sets a proper
                // link/business location.
                $reviewLinkFor = function ($id, $name) {
                    $id = trim((string) $id);
                    $name = trim((string) $name);
                    // 1) A full URL saved directly (pasted review link) — use it.
                    foreach ([$id, $name] as $val) {
                        if ($val !== '' && (str_starts_with($val, 'http://') || str_starts_with($val, 'https://'))) {
                            return $val;
                        }
                    }
                    // 2) Reject empty / placeholder / skip-for-now values.
                    if ($id === '' || str_starts_with($id, 'loc-') || str_starts_with($id, 'skip-') || str_starts_with($id, 'direct-')) {
                        return null;
                    }
                    // 3) Only accept a genuine Google Place ID (they are long).
                    //    Dummy/junk values like "google" are ignored.
                    if (strlen($id) < 20) {
                        return null;
                    }
                    return 'https://search.google.com/local/writereview?placeid=' . urlencode($id);
                };

                $reviewTargets = [];
                foreach ($order->items as $it) {
                    if (!empty($it->locations) && is_array($it->locations)) {
                        foreach ($it->locations as $loc) {
                            $url = $reviewLinkFor($loc['id'] ?? '', $loc['name'] ?? '');
                            if (!$url) continue;
                            if (!isset($reviewTargets[$url])) {
                                $reviewTargets[$url] = ['name' => $loc['name'] ?? 'Business', 'variants' => []];
                            }
                            if (!empty($it->variant_name) && !in_array($it->variant_name, $reviewTargets[$url]['variants'])) {
                                $reviewTargets[$url]['variants'][] = $it->variant_name;
                            }
                        }
                    }
                }
                if (empty($reviewTargets)) {
                    $url = $reviewLinkFor($order->google_place_id, $order->google_place_name);
                    if ($url) $reviewTargets[$url] = ['name' => $order->google_place_name ?: 'Business', 'variants' => []];
                }

                // Was any location captured at all (even a skip-for-now placeholder)?
                $hasAnyLocation = !empty($order->google_place_id) || !empty($order->google_place_name);
                foreach ($order->items as $it) {
                    if (!empty($it->locations)) { $hasAnyLocation = true; break; }
                }

                // Does this order contain a printable Google Card / Stand?
                $hasDesignItem = $order->items->contains(function ($it) {
                    $h = strtolower(trim(($it->product_name ?? '') . ' ' . ($it->variant_name ?? '')));
                    return str_contains($h, 'stand') || str_contains($h, 'card');
                });
            @endphp

            @if($hasDesignItem)
            <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-2xl border border-emerald-100 overflow-hidden shadow-sm mt-8">
                <div class="px-6 py-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-start gap-3">
                        <span class="inline-flex items-center justify-center w-11 h-11 rounded-xl bg-emerald-600 text-white flex-shrink-0">
                            <i data-lucide="layout-template" class="h-5 w-5" stroke-width="2.5"></i>
                        </span>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Printable Card / Stand Design</h3>
                            <p class="text-xs text-gray-600 mt-0.5">Ready-to-print artwork with the business name on top{{ collect($order->items)->contains(fn($it) => str_contains(strtolower(($it->product_name ?? '').' '.($it->variant_name ?? '')), 'stand')) ? ' and a scannable QR code on the stand' : '' }}. Open to download a PNG or print/save as PDF.</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.orders.design', $order) }}" target="_blank"
                       class="inline-flex items-center justify-center gap-2 bg-emerald-600 text-white px-5 py-3 rounded-xl text-sm font-bold hover:bg-emerald-700 transition-all shadow-sm whitespace-nowrap flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Open &amp; Download Design
                    </a>
                </div>
            </div>
            @endif

            @if(!empty($reviewTargets))
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm mt-8">
                <div class="px-6 py-4 bg-gray-50 border-bottom border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i data-lucide="qr-code" class="h-4 w-4 flex-shrink-0" stroke-width="2.5"></i>
                        Review QR Code{{ count($reviewTargets) > 1 ? 's' : '' }}
                    </h3>
                    <p class="text-xs text-gray-500 mt-1">Download &amp; print these so customers can scan to leave a Google review if they can't tap the NFC card.</p>
                </div>
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @foreach($reviewTargets as $url => $target)
                        @php
                            $bizName = $target['name'];
                            $variantLabel = implode(', ', $target['variants']);
                            $qid = 'qr-' . md5($url);
                            $slug = \Illuminate\Support\Str::slug(trim($bizName . ' ' . $variantLabel)) ?: 'business';
                        @endphp
                        <div class="flex flex-col items-center text-center bg-gray-50 border border-gray-100 rounded-xl p-5">
                            <div id="{{ $qid }}" data-qr-url="{{ $url }}" class="bg-white p-3 rounded-xl border border-gray-200 inline-flex items-center justify-center min-h-[180px] min-w-[180px]"></div>
                            <div class="mt-3 w-full">
                                @if($variantLabel)
                                    <div class="inline-flex items-center rounded-md bg-blue-50 border border-blue-100 px-2 py-0.5 text-xs font-bold text-blue-700 mb-1" title="{{ $variantLabel }}">{{ $variantLabel }}</div>
                                @endif
                                <div class="text-sm font-bold text-gray-800 truncate" title="{{ $bizName }}">{{ $bizName }}</div>
                                <a href="{{ $url }}" target="_blank" class="text-[11px] text-blue-600 hover:underline break-all">{{ \Illuminate\Support\Str::limit($url, 48) }}</a>
                            </div>
                            <button type="button" onclick="downloadQr('{{ $qid }}', '{{ $order->order_number }}', '{{ $slug }}')"
                                    class="mt-3 inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg text-xs font-bold hover:bg-blue-700 transition-all shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                Download QR
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
            @elseif($hasAnyLocation)
            <div class="bg-amber-50 rounded-2xl border border-amber-200 overflow-hidden shadow-sm mt-8">
                <div class="px-6 py-5 flex items-start gap-3">
                    <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <div>
                        <h4 class="text-sm font-bold text-amber-900">Review QR code not available yet</h4>
                        <p class="text-xs text-amber-700 mt-0.5">This order has no valid Google review link yet (e.g. the customer chose “Skip for now”). Add the business location / review link above (use <strong>Change</strong> or <strong>Paste Direct Link</strong>), then a scannable QR code will appear here automatically.</p>
                    </div>
                </div>
            </div>
            @endif

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
                <div class="flex items-center justify-between">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">NFC Review Link (Direct URL or Place Review Link)</label>
                    <span class="text-[10px] text-gray-400">Editable before writing</span>
                </div>
                <div class="flex items-center gap-2">
                    <input type="text" x-model="customNfcUrl" placeholder="https://g.page/r/... or review link"
                           class="flex-1 bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-xs font-mono text-gray-800 focus:bg-white focus:border-blue-500 outline-none">
                    <button @click="copyReviewLink()" class="p-3 bg-blue-50 text-blue-600 rounded-xl border border-blue-100 hover:bg-blue-100 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    </button>
                </div>
                <div x-show="!customNfcUrl && !reviewLink" class="text-xs text-amber-700 bg-amber-50 p-2.5 rounded-xl border border-amber-200">
                    ⚠️ No review link found. Please paste the customer's direct Google Review URL into the box above before writing to the card.
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="grid grid-cols-2 gap-4">
                <button @click="writeNfc(customNfcUrl || reviewLink)" 
                        :disabled="!(customNfcUrl || reviewLink)"
                        :class="!(customNfcUrl || reviewLink) ? 'opacity-50 cursor-not-allowed bg-gray-400' : 'bg-gray-900 hover:bg-black hover:-translate-y-1 cursor-pointer'"
                        class="flex flex-col items-center justify-center p-6 text-white rounded-2xl transition-all shadow-lg">
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

    <!-- Email Customer Modal -->
    <template x-teleport="body">
        <div x-show="showEmailModal" x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm overflow-y-auto">
            
            <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full p-6 sm:p-8 border border-gray-100 relative overflow-hidden my-auto" @click.away="showEmailModal = false">
                <div class="flex items-center justify-between pb-4 border-b border-gray-100 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center font-bold">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Email Customer</h3>
                            <p class="text-xs text-gray-500">Send custom order update or request details from {{ $order->customer_name }}</p>
                        </div>
                    </div>
                    <button type="button" @click="showEmailModal = false" class="text-gray-400 hover:text-gray-600 p-2 rounded-xl hover:bg-gray-100 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Recipient Badge -->
                <div class="mb-5 p-3.5 bg-blue-50 border border-blue-100 rounded-2xl flex items-center justify-between">
                    <div class="flex items-center gap-2 text-xs text-blue-900 font-semibold">
                        <span>To:</span>
                        <strong class="text-blue-950 font-bold">{{ $order->customer_name }}</strong>
                        <span class="text-blue-600">({{ $order->customer_email }})</span>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-200 text-blue-800">
                        Order #{{ $order->order_number }}
                    </span>
                </div>

                <!-- Quick Template Shortcuts -->
                <div class="mb-5">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Quick Message Templates:</label>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" @click="applyEmailTemplate('shipping')" class="flex items-center gap-2 px-3 py-1.5 bg-gray-100 hover:bg-purple-100 hover:text-blue-700 text-gray-700 text-xs font-semibold rounded-xl transition-all border border-gray-200">
                            <i data-lucide="package" class="w-4 h-4"></i> Shipping Update
                        </button>
                        <button type="button" @click="applyEmailTemplate('location')" class="flex items-center gap-2 px-3 py-1.5 bg-gray-100 hover:bg-purple-100 hover:text-blue-700 text-gray-700 text-xs font-semibold rounded-xl transition-all border border-gray-200">
                            <i data-lucide="map-pin" class="w-4 h-4"></i> Google Link Required
                        </button>
                        <button type="button" @click="applyEmailTemplate('general')" class="flex items-center gap-2 px-3 py-1.5 bg-gray-100 hover:bg-purple-100 hover:text-blue-700 text-gray-700 text-xs font-semibold rounded-xl transition-all border border-gray-200">
                            <i data-lucide="square-text" class="w-4 h-4"></i> General Note
                        </button>
                    </div>
                </div>

                <!-- Messages Status -->
                <div x-show="emailSuccessMessage" class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 text-xs font-bold rounded-xl flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span x-text="emailSuccessMessage"></span>
                </div>

                <div x-show="emailErrorMessage" class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 text-xs font-bold rounded-xl flex items-center gap-2">
                    <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span x-text="emailErrorMessage"></span>
                </div>

                <!-- Subject Input -->
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Email Subject <span class="text-red-500">*</span></label>
                    <input type="text" x-model="emailSubject" placeholder="Enter email subject"
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm text-gray-800 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-all font-medium">
                </div>

                <!-- Message Body Textarea -->
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Message / Order Details <span class="text-red-500">*</span></label>
                    <textarea x-model="emailMessage" rows="5" placeholder="Write your message or order details here..."
                              class="w-full border border-gray-300 rounded-xl p-4 text-sm text-gray-800 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-all font-medium leading-relaxed"></textarea>
                </div>

                <!-- Include Summary Checkbox -->
                <div class="mb-6 flex items-center gap-2 select-none">
                    <input type="checkbox" id="include_summary_check" x-model="includeSummary" class="w-4 h-4 rounded text-blue-600 focus:ring-blue-500 cursor-pointer">
                    <label for="include_summary_check" class="text-xs font-bold text-gray-700 cursor-pointer">
                        Include Order Summary Box (Items, Total, & Shipping Address) in email
                    </label>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" @click="showEmailModal = false" class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-700 text-sm font-bold hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="button" @click="sendCustomerEmailSubmit()" :disabled="isSendingEmail"
                            class="px-6 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-bold hover:bg-blue-700 transition-all shadow-md flex items-center gap-2 cursor-pointer disabled:opacity-50">
                        <template x-if="isSendingEmail">
                            <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </template>
                        <span x-text="isSendingEmail ? 'Sending Email...' : 'Send Email to Customer'"></span>
                    </button>
                </div>
            </div>
        </div>
    </template>

</div>

<!-- Load Google Maps -->
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&libraries=places"></script>

<!-- Review QR Code generation -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-qr-url]').forEach(function (el) {
            if (!el.dataset.qrUrl || el.dataset.qrRendered) return;
            try {
                new QRCode(el, {
                    text: el.dataset.qrUrl,
                    width: 180,
                    height: 180,
                    correctLevel: QRCode.CorrectLevel.M
                });
                el.dataset.qrRendered = '1';
            } catch (e) {
                console.error('QR render failed', e);
            }
        });
    });

    function downloadQr(containerId, orderNumber, slug) {
        var container = document.getElementById(containerId);
        if (!container) return;
        var canvas = container.querySelector('canvas');
        var img = container.querySelector('img');
        var dataUrl = null;
        if (canvas) {
            dataUrl = canvas.toDataURL('image/png');
        } else if (img && img.src) {
            dataUrl = img.src;
        }
        if (!dataUrl) {
            alert('QR code is still generating, please try again in a moment.');
            return;
        }
        var a = document.createElement('a');
        a.href = dataUrl;
        a.download = 'review-qr-' + orderNumber + (slug ? '-' + slug : '') + '.png';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    }
</script>

<!-- Per-item review link editor -->
<script>
    function orderItemLocation(saveUrl, index, prefill) {
        return {
            editing: false,
            saving: false,
            mode: (prefill ? 'direct' : 'search'),   // 'search' (Google autocomplete) | 'direct' (paste URL)
            link: prefill || '',
            name: '',
            placeId: '',
            locationIndex: (index === undefined ? null : index),

            toggle() {
                this.editing = !this.editing;
                if (this.editing) this.$nextTick(() => this.bindAutocomplete());
            },

            setMode(m) {
                this.mode = m;
                if (m === 'search') this.$nextTick(() => this.bindAutocomplete());
            },

            bindAutocomplete() {
                if (this.mode !== 'search') return;
                const input = this.$refs.search;
                if (!input || input.dataset.acBound) return;
                if (typeof google === 'undefined' || !google.maps || !google.maps.places) {
                    setTimeout(() => this.bindAutocomplete(), 400);
                    return;
                }
                const ac = new google.maps.places.Autocomplete(input, { types: ['establishment'] });
                input.dataset.acBound = '1';
                ac.addListener('place_changed', () => {
                    const place = ac.getPlace();
                    if (!place || !place.place_id) return;
                    this.placeId = place.place_id;
                    this.name = place.name || input.value;
                });
            },

            async save() {
                // Work out what to store: a Google place id (search) or a URL (direct).
                let reviewLink = '';
                let placeName = this.name;
                if (this.mode === 'search') {
                    if (!this.placeId) { alert('Please pick a business from the dropdown, or use “Paste Direct Link”.'); return; }
                    reviewLink = this.placeId;
                    placeName = this.name || this.placeId;
                } else {
                    reviewLink = (this.link || '').trim();
                    if (!reviewLink) { alert('Please paste the review link first.'); return; }
                }

                this.saving = true;
                try {
                    const res = await fetch(saveUrl, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ review_link: reviewLink, place_name: placeName, index: this.locationIndex })
                    });
                    const data = await res.json();
                    if (data.success) {
                        if (window.toast) window.toast('Review link saved!', 'success');
                        window.location.reload();
                    } else {
                        alert(data.message || 'Failed to save the link.');
                        this.saving = false;
                    }
                } catch (e) {
                    console.error(e);
                    alert('Failed to save. Please try again.');
                    this.saving = false;
                }
            }
        };
    }
</script>
@endsection
