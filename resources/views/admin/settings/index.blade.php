@extends('layouts.app')

@section('content')
<style>
    @keyframes floatUp {
        0% { opacity: 0; transform: translateY(20px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .animate-float-up {
        animation: floatUp 0.5s ease-out forwards;
    }
    .animate-float-up-delay-1 {
        animation: floatUp 0.5s ease-out 0.1s forwards;
        opacity: 0;
    }
    .animate-float-up-delay-2 {
        animation: floatUp 0.5s ease-out 0.2s forwards;
        opacity: 0;
    }
    .form-input-premium {
        transition: all 0.3s ease;
    }
    .form-input-premium:focus {
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
        border-color: #6366f1;
    }
</style>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    {{-- Header --}}
    <div class="mb-10 animate-float-up">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-gray-900 to-gray-600 tracking-tight">System Settings</h1>
        <p class="text-gray-500 mt-2 text-lg">Manage API keys, integrations, and core service configurations.</p>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="mb-8 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center shadow-sm animate-float-up">
            <div class="flex-shrink-0 w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center mr-4">
                 <i data-lucide="check-circle" class="h-6 w-6 flex-shrink-0 text-emerald-600"></i>
            </div>
            <div>
                <h4 class="text-sm font-bold text-emerald-800">Changes Saved Successfully</h4>
                <p class="text-xs text-emerald-600 mt-0.5">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')

        {{-- Stripe Settings Card --}}
        <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/40 border border-gray-100 overflow-hidden animate-float-up-delay-1">
            <div class="bg-gradient-to-r from-indigo-50 to-white px-8 py-6 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200 text-white">
                        <i data-lucide="credit-card-plus" class="h-6 w-6 flex-shrink-0"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Stripe Configuration</h2>
                        <p class="text-sm text-gray-500">Provide keys to enable subscription payments.</p>
                    </div>
                </div>
                <div class="hidden sm:block">
                    <span class="inline-flex items-center px-3 py-1 bg-indigo-100 text-indigo-700 text-xs font-bold rounded-full">Billing</span>
                </div>
            </div>
            
            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label for="stripe_key" class="block text-sm font-bold text-gray-700 mb-2">Publishable Key</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-gray-400">
                                <i data-lucide="key-round" class="h-5 w-5"></i>
                            </span>
                        </div>
                        <input type="text" name="stripe_key" id="stripe_key" value="{{ $settings['stripe_key'] ?? '' }}" 
                            class="form-input-premium w-full pl-11 pr-4 py-3.5 bg-gray-50/50 hover:bg-white rounded-xl border border-gray-200 text-gray-900 text-sm font-mono outline-none"
                            placeholder="pk_test_...">
                    </div>
                </div>
                
                <div>
                    <label for="stripe_secret" class="block text-sm font-bold text-gray-700 mb-2">Secret Key</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-gray-400">
                               <i data-lucide="lock-keyhole" class="h-5 w-5"></i>
                            </span>
                        </div>
                        <input type="password" name="stripe_secret" id="stripe_secret" value="{{ $settings['stripe_secret'] ?? '' }}" 
                            class="form-input-premium w-full pl-11 pr-4 py-3.5 bg-gray-50/50 hover:bg-white rounded-xl border border-gray-200 text-gray-900 text-sm font-mono outline-none"
                            placeholder="sk_test_...">
                    </div>
                </div>
            </div>
        </div>

        {{-- Google Places API Card --}}
        <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/40 border border-gray-100 overflow-hidden animate-float-up-delay-2">
            <div class="bg-gradient-to-r from-blue-50 to-white px-8 py-6 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-200 text-white">
                        <i data-lucide="map-pin" class="h-6 w-6 flex-shrink-0"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Google Places API</h2>
                        <p class="text-sm text-gray-500">Configure connection to Google Maps services.</p>
                    </div>
                </div>
                <div class="hidden sm:block">
                    <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full">Integrations</span>
                </div>
            </div>
            
            <div class="p-8">
                <div>
                    <label for="google_places_api_key" class="block text-sm font-bold text-gray-700 mb-2">API Key</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-gray-400">
                                <i data-lucide="code-xml" class="h-5 w-5"></i>
                            </span>
                        </div>
                        <input type="text" name="google_places_api_key" id="google_places_api_key" value="{{ $settings['google_places_api_key'] ?? '' }}" 
                            class="form-input-premium w-full pl-11 pr-4 py-3.5 bg-gray-50/50 hover:bg-white rounded-xl border border-gray-200 text-gray-900 text-sm font-mono outline-none"
                            placeholder="AIza...">
                    </div>
                    <p class="mt-3 text-sm text-gray-500 flex items-start gap-2 bg-gray-50 p-3 rounded-lg border border-gray-100">
                        <svg class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Used for business location lookups, matching review places, and fetching photos via the Google Places API.</span>
                    </p>
                </div>
            </div>
        </div>

        {{-- B2B Partner Tier Pricing & Discounts Card --}}
        <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/40 border border-gray-100 overflow-hidden animate-float-up-delay-2">
            <div class="bg-gradient-to-r from-purple-50 to-white px-8 py-6 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-purple-200 text-white">
                        <i data-lucide="circle-pound-sterling" class="h-6 w-6 flex-shrink-0"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">B2B Partner Store Discounts</h2>
                        <p class="text-sm text-gray-500">Configure global percentage (%) discounts for approved partner roles across all shop products.</p>
                    </div>
                </div>
                <div class="hidden sm:block">
                    <span class="inline-flex items-center px-3 py-1 bg-purple-100 text-purple-700 text-xs font-bold rounded-full">B2B Pricing</span>
                </div>
            </div>
            
            <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Wholesaler Discount --}}
                <div>
                    <label for="wholesaler_discount_percent" class="block text-sm font-bold text-gray-700 mb-2">Wholesaler Discount (%)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-gray-400">
                                <i data-lucide="tag" class="h-5 w-5"></i>
                            </span>
                        </div>
                        <input type="number" step="0.1" name="wholesaler_discount_percent" id="wholesaler_discount_percent" value="{{ $settings['wholesaler_discount_percent'] ?? '20' }}" 
                            class="form-input-premium w-full pl-11 pr-10 py-3.5 bg-gray-50/50 hover:bg-white rounded-xl border border-gray-200 text-gray-900 text-sm font-mono font-bold outline-none"
                            placeholder="20">
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                            <span class="text-gray-400 font-bold text-sm">%</span>
                        </div>
                    </div>
                    <p class="mt-2 text-xs text-gray-500">Applied automatically for approved Wholesalers.</p>
                </div>

                {{-- Retailer Discount --}}
                <div>
                    <label for="retailer_discount_percent" class="block text-sm font-bold text-gray-700 mb-2">Retailer Discount (%)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-gray-400">
                                <i data-lucide="shopping-bag" class="h-5 w-5"></i>
                            </span>
                        </div>
                        <input type="number" step="0.1" name="retailer_discount_percent" id="retailer_discount_percent" value="{{ $settings['retailer_discount_percent'] ?? '15' }}" 
                            class="form-input-premium w-full pl-11 pr-10 py-3.5 bg-gray-50/50 hover:bg-white rounded-xl border border-gray-200 text-gray-900 text-sm font-mono font-bold outline-none"
                            placeholder="15">
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                            <span class="text-gray-400 font-bold text-sm">%</span>
                        </div>
                    </div>
                    <p class="mt-2 text-xs text-gray-500">Applied automatically for approved Retailers.</p>
                </div>

                {{-- Corporate Discount --}}
                <div>
                    <label for="corporate_discount_percent" class="block text-sm font-bold text-gray-700 mb-2">Corporate Discount (%)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-gray-400">
                                <i data-lucide="diamond-percent" class="h-5 w-5"></i>
                            </span>
                        </div>
                        <input type="number" step="0.1" name="corporate_discount_percent" id="corporate_discount_percent" value="{{ $settings['corporate_discount_percent'] ?? '10' }}" 
                            class="form-input-premium w-full pl-11 pr-10 py-3.5 bg-gray-50/50 hover:bg-white rounded-xl border border-gray-200 text-gray-900 text-sm font-mono font-bold outline-none"
                            placeholder="10">
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                            <span class="text-gray-400 font-bold text-sm">%</span>
                        </div>
                    </div>
                    <p class="mt-2 text-xs text-gray-500">Applied automatically for approved Corporate accounts.</p>
                </div>
            </div>
        </div>
        
        {{-- Delivery / Shipping Fees --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden animate-float-up-delay-2">
            <div class="bg-gradient-to-r from-emerald-50 to-white px-8 py-6 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-200 text-white">
                        <i data-lucide="truck" class="h-6 w-6 flex-shrink-0"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Delivery / Shipping Fees</h2>
                        <p class="text-sm text-gray-500">Set the delivery charges shown at checkout. Customers pick one of these at checkout.</p>
                    </div>
                </div>
                <div class="hidden sm:block">
                    <span class="inline-flex items-center px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full">Checkout</span>
                </div>
            </div>

            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Standard Delivery --}}
                <div>
                    <label for="delivery_fee_standard" class="block text-sm font-bold text-gray-700 mb-2">Standard Delivery — 48 hours (£)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-gray-500 font-bold text-sm">
                                <i data-lucide="pound-sterling" class="h-4 w-4 flex-shrink-0"></i>
                            </span>
                        </div>
                        <input type="number" step="0.01" min="0" name="delivery_fee_standard" id="delivery_fee_standard" value="{{ $settings['delivery_fee_standard'] ?? '3.90' }}"
                            class="form-input-premium w-full pl-9 pr-4 py-3.5 bg-gray-50/50 hover:bg-white rounded-xl border border-gray-200 text-gray-900 text-sm font-mono font-bold outline-none"
                            placeholder="3.90">
                    </div>
                    <p class="mt-2 text-xs text-gray-500">Default standard shipping fee (delivered within ~48 hours).</p>
                </div>

                {{-- Express Delivery --}}
                <div>
                    <label for="delivery_fee_express" class="block text-sm font-bold text-gray-700 mb-2">Express Delivery — 24 hours (£)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-gray-500 font-bold text-sm">
                                <i data-lucide="pound-sterling" class="h-4 w-4 flex-shrink-0"></i>
                            </span>
                        </div>
                        <input type="number" step="0.01" min="0" name="delivery_fee_express" id="delivery_fee_express" value="{{ $settings['delivery_fee_express'] ?? '6.90' }}"
                            class="form-input-premium w-full pl-9 pr-4 py-3.5 bg-gray-50/50 hover:bg-white rounded-xl border border-gray-200 text-gray-900 text-sm font-mono font-bold outline-none"
                            placeholder="6.90">
                    </div>
                    <p class="mt-2 text-xs text-gray-500">Faster express shipping fee (delivered within ~24 hours).</p>
                </div>
                
                {{-- Free Delivery Threshold --}}
                <div class="md:col-span-2">
                    <label for="free_delivery_threshold" class="block text-sm font-bold text-gray-700 mb-2">Free Delivery Threshold (£)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-gray-500 font-bold text-sm">
                                <i data-lucide="pound-sterling" class="h-4 w-4 flex-shrink-0"></i>
                            </span>
                        </div>
                        <input type="number" step="0.01" min="0" name="free_delivery_threshold" id="free_delivery_threshold" value="{{ $settings['free_delivery_threshold'] ?? '25' }}"
                            class="form-input-premium w-full pl-9 pr-4 py-3.5 bg-gray-50/50 hover:bg-white rounded-xl border border-gray-200 text-gray-900 text-sm font-mono font-bold outline-none"
                            placeholder="25">
                    </div>
                    <p class="mt-2 text-xs text-gray-500">Orders with an items subtotal of this amount or more get <strong>free delivery</strong> (both Standard &amp; Express). Set to <strong>0</strong> to disable free delivery.</p>
                </div>
            </div>
        </div>
        
        {{-- AI Chatbot (Google Gemini) --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden animate-float-up-delay-2">
            <div class="flex items-center justify-between gap-3 px-8 py-5 border-b border-gray-100 bg-gradient-to-r from-blue-50/60 to-indigo-50/40">
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-[#00A0FF] text-white">
                        <i data-lucide="message-circle" class="h-5 w-5"></i>
                    </span>
                    <div>
                        <h3 class="text-base font-bold text-gray-900">AI Support Chatbot</h3>
                        <p class="text-xs text-gray-500">A customer-support assistant on your public website, powered by Google Gemini (free).</p>
                    </div>
                </div>
                <span class="hidden sm:inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full">Website</span>
            </div>

            <div class="p-8 space-y-6">
                {{-- Enable toggle --}}
                <label class="flex items-start gap-3 cursor-pointer select-none">
                    <input type="checkbox" name="chatbot_enabled" value="1" {{ !empty($settings['chatbot_enabled']) ? 'checked' : '' }}
                           class="mt-0.5 w-5 h-5 rounded border-gray-300 text-[#00A0FF] focus:ring-[#00A0FF]">
                    <span>
                        <span class="block text-sm font-bold text-gray-800">Enable chatbot on the website</span>
                        <span class="block text-xs text-gray-500">Shows a floating chat bubble on public pages. Needs a valid API key below.</span>
                    </span>
                </label>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- API key --}}
                    <div>
                        <label for="gemini_api_key" class="block text-sm font-bold text-gray-700 mb-2">Google Gemini API Key</label>
                        <input type="text" name="gemini_api_key" id="gemini_api_key" value="{{ $settings['gemini_api_key'] ?? '' }}"
                               class="form-input-premium w-full px-4 py-3.5 bg-gray-50/50 hover:bg-white rounded-xl border border-gray-200 text-gray-900 text-sm font-mono outline-none"
                               placeholder="AIza..." autocomplete="off">
                        <p class="mt-2 text-xs text-gray-500">Get a free key at <a href="https://aistudio.google.com/app/apikey" target="_blank" class="text-blue-600 font-semibold hover:underline">aistudio.google.com/app/apikey</a>. Kept private on the server.</p>
                    </div>

                    {{-- Model --}}
                    <div>
                        <label for="chatbot_model" class="block text-sm font-bold text-gray-700 mb-2">Model</label>
                        <input type="text" name="chatbot_model" id="chatbot_model" value="{{ $settings['chatbot_model'] ?? 'gemini-2.0-flash' }}"
                               class="form-input-premium w-full px-4 py-3.5 bg-gray-50/50 hover:bg-white rounded-xl border border-gray-200 text-gray-900 text-sm font-mono outline-none"
                               placeholder="gemini-2.0-flash">
                        <p class="mt-2 text-xs text-gray-500">Default <strong>gemini-2.0-flash</strong> (fast &amp; free). You can also use <strong>gemini-1.5-flash</strong>.</p>
                    </div>
                </div>

                {{-- Greeting --}}
                <div>
                    <label for="chatbot_greeting" class="block text-sm font-bold text-gray-700 mb-2">Greeting Message</label>
                    <input type="text" name="chatbot_greeting" id="chatbot_greeting" value="{{ $settings['chatbot_greeting'] ?? '' }}"
                           class="form-input-premium w-full px-4 py-3.5 bg-gray-50/50 hover:bg-white rounded-xl border border-gray-200 text-gray-900 text-sm outline-none"
                           placeholder="Hi! I'm Tappy, your Tap Review Cards assistant…">
                    <p class="mt-2 text-xs text-gray-500">First message shown when the chat opens. Leave blank for the default.</p>
                </div>

                {{-- Extra context --}}
                <div>
                    <label for="chatbot_extra_context" class="block text-sm font-bold text-gray-700 mb-2">Extra Knowledge (optional)</label>
                    <textarea name="chatbot_extra_context" id="chatbot_extra_context" rows="4"
                              class="form-input-premium w-full px-4 py-3.5 bg-gray-50/50 hover:bg-white rounded-xl border border-gray-200 text-gray-900 text-sm outline-none"
                              placeholder="Add any extra facts the bot should know — opening hours, return policy, warranty, FAQs…">{{ $settings['chatbot_extra_context'] ?? '' }}</textarea>
                    <p class="mt-2 text-xs text-gray-500">The bot already knows your products and delivery fees. Add anything else here.</p>
                </div>
            </div>
        </div>

        {{-- Submit Action --}}
        <div class="pt-6 flex justify-end animate-float-up-delay-2">
            <button type="submit" 
                class="group flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-gray-900 to-gray-800 hover:from-black hover:to-gray-900 text-white font-bold rounded-xl focus:ring-4 focus:ring-gray-200 transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-1">
                <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                </svg>
                <span>Save Configuration</span>
            </button>
        </div>
    </form>
</div>
@endsection
