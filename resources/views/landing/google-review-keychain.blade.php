@extends('layouts.guest')

@section('title', 'Portable Google Review Keychains UK — Collect Reviews Anywhere')
@section('meta_description', 'Take your review collection on the road with an NFC Google Review Keychain. Flexible plans, tracking dashboards, and fast setup.')
@section('meta_keywords', 'google review keychain, NFC review tag, portable review card, NFC keychain for business, review collector')

@section('content')
<div class="bg-white" x-data="keyringPlans()" x-init="init()">
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-br from-[#1800ad] via-[#120084] to-[#142D63] py-20 px-4 sm:px-6 lg:px-8 overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"></div>
        <div class="max-w-6xl mx-auto text-center relative z-10">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 text-[#0CC0DF] border border-white/20 text-sm font-semibold mb-6 backdrop-blur-md">
                ✨ Portable NFC Review Collector
            </span>
            <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-6 font-ubuntu leading-tight">
                Reviews <br class="hidden md:block"> <span class="text-[#0CC0DF]">On The Go</span>
            </h1>
            <p class="text-xl text-white/90 mb-10 max-w-3xl mx-auto font-mulish leading-relaxed">
                NFC Google Review Keychains are designed for businesses that don't have a fixed location. Perfect for tradespeople, delivery drivers, and mobile services.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="#pricing" class="bg-white text-[#1800ad] px-8 py-4 rounded-2xl font-bold text-lg hover:bg-gray-100 transition-all shadow-lg hover:shadow-cyan-500/25 transform hover:-translate-y-0.5">View Plans & Pricing</a>
                <a href="#products" class="bg-white/10 text-white border border-white/30 px-8 py-4 rounded-2xl font-bold text-lg hover:bg-white/20 transition-all backdrop-blur-sm">Browse Products</a>
            </div>
        </div>
    </div>

    <!-- Plans & Pricing Section -->
    <div id="pricing" class="py-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="text-center mb-12">
            <span class="text-[#1800ad] font-bold text-sm uppercase tracking-wider bg-indigo-50 px-4 py-1.5 rounded-full">Transparent Pricing</span>
            <h2 class="text-3xl md:text-5xl font-extrabold text-gray-900 mt-4 mb-4 font-ubuntu">Choose the Right Plan for Your Business</h2>
            <p class="text-gray-600 font-mulish max-w-2xl mx-auto text-lg">Whether you need a single keyring or complete tracking for your team, select the option that fits your needs.</p>

            <!-- Monthly / Annual Toggle -->
            <div class="mt-10 inline-flex items-center p-1.5 rounded-2xl bg-gray-100 border border-gray-200 shadow-inner">
                <button type="button" @click="billingCycle = 'monthly'" 
                        :class="billingCycle === 'monthly' ? 'bg-white text-gray-900 shadow-md font-bold' : 'text-gray-600 hover:text-gray-900 font-medium'"
                        class="px-6 py-3 rounded-xl text-sm transition-all duration-300">
                    Monthly Billing
                </button>
                <button type="button" @click="billingCycle = 'annual'" 
                        :class="billingCycle === 'annual' ? 'bg-[#1800ad] text-white shadow-md font-bold' : 'text-gray-700 hover:text-gray-900 font-medium'"
                        class="px-6 py-3 rounded-xl text-sm transition-all duration-300 flex items-center gap-2">
                    <span>Annual Billing</span>
                    <span :class="billingCycle === 'annual' ? 'bg-[#0cc0df] text-white' : 'bg-[#1800ad] text-white'"
                          class="text-[11px] font-extrabold px-2.5 py-1 rounded-full uppercase tracking-wider whitespace-nowrap shadow-sm transition-colors duration-300">
                        1 Month Free
                    </span>
                </button>
            </div>
        </div>

        @php
            // Plan prices come from the product variants (admin-managed), with
            // sensible fallbacks so the page never breaks if a variant is missing.
            $LOGO_FEE = 2.99;              // per keyring
            $LOGO_FEE_5  = $LOGO_FEE * 5;  // 5 Keyrings Plan  -> 14.95
            $LOGO_FEE_10 = $LOGO_FEE * 10; // 10 Keyrings Plan -> 29.90
            $p_no_sub   = isset($planVariants['no_subscription']) ? (float) $planVariants['no_subscription']->price : 9.99;
            $p_5_month  = isset($planVariants['5_monthly'])       ? (float) $planVariants['5_monthly']->price       : 14.99;
            $p_5_annual = isset($planVariants['5_annual'])        ? (float) $planVariants['5_annual']->price        : 164.99;
            $p_10_month = isset($planVariants['10_monthly'])      ? (float) $planVariants['10_monthly']->price      : 19.99;
            $p_10_annual= isset($planVariants['10_annual'])       ? (float) $planVariants['10_annual']->price       : 219.99;
        @endphp

        <!-- 3 Pricing Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch mb-16">
            
            <!-- Card 1: Keyrings without subscription -->
            <div class="bg-white rounded-3xl p-8 border border-gray-200 shadow-lg hover:shadow-xl transition-all duration-300 flex flex-col justify-between relative group">
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs font-bold rounded-full uppercase tracking-wider">Pay As You Go</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 font-ubuntu mb-2">No Subscription</h3>
                    <p class="text-gray-500 text-sm mb-6">Standard keyring purchase without recurring monthly subscription or dashboard tracking.</p>
                    
                    <div class="mb-4 p-4 bg-gray-50 rounded-2xl border border-gray-100">
                        <div class="flex items-baseline gap-1">
                            <span class="text-4xl font-extrabold text-gray-900" x-text="card1CustomLogo ? '£{{ number_format($p_no_sub + $LOGO_FEE, 2) }}' : '£{{ number_format($p_no_sub, 2) }}'"></span>
                            <span class="text-gray-500 text-sm font-medium">/ generic keyring</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">One-time payment • No recurring fees</p>
                    </div>

                    <!-- Custom Logo Swatch Checkbox -->
                    <label class="flex items-center gap-2.5 cursor-pointer mb-6 p-3 bg-gray-50 rounded-xl border border-gray-200 hover:border-blue-400 transition-all select-none">
                        <input type="checkbox" x-model="card1CustomLogo" class="w-4 h-4 rounded text-[#142D63] focus:ring-[#00A0FF]">
                        <span class="text-xs font-bold text-gray-700 font-ubuntu">🎨 Add Custom Logo (+£2.99)</span>
                    </label>

                    <ul class="space-y-3 text-sm text-gray-600 mb-8">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span><strong>Generic Keyring:</strong> £{{ number_format($p_no_sub, 2) }} each</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span><strong>Custom Logo/Design:</strong> +£{{ number_format($LOGO_FEE, 2) }} (£{{ number_format($p_no_sub + $LOGO_FEE, 2) }} total)</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span><strong>Setup Charge:</strong> £0 (No setup fee)</span>
                        </li>
                        <li class="flex items-start gap-3 opacity-60">
                            <svg class="w-5 h-5 text-gray-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            <span>Dashboard & Analytics: Not Included</span>
                        </li>
                        <li class="flex items-start gap-3 opacity-60">
                            <svg class="w-5 h-5 text-gray-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            <span>Free Monthly Replacements: Not Included</span>
                        </li>
                    </ul>
                </div>

                <button type="button" @click="openModal('no-subscription')" class="w-full text-center py-4 px-6 rounded-2xl bg-gray-900 text-white font-bold hover:bg-gray-800 transition-all shadow-md cursor-pointer">
                    Order Keyrings
                </button>
            </div>

            <!-- Card 2: Up to 5 Keyrings Plan (Most Popular) -->
            <div class="bg-gradient-to-b from-white to-blue-50/40 rounded-3xl p-8 border-2 border-[#1800ad] shadow-2xl transition-all duration-300 flex flex-col justify-between relative transform md:-translate-y-2">
                <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-[#1800ad] text-white text-xs font-bold uppercase tracking-wider px-5 py-2 rounded-full shadow-lg flex items-center gap-1.5 whitespace-nowrap z-20">
                    <svg class="w-3.5 h-3.5 text-yellow-300 fill-current shrink-0" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <span>Most Popular Plan</span>
                </div>

                <div>
                    <div class="flex justify-between items-center mb-4 mt-2">
                        <span class="px-3 py-1 bg-indigo-100 text-[#1800ad] text-xs font-bold rounded-full uppercase tracking-wider">Up to 5 Keyrings</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 font-ubuntu mb-2">5 Keyrings Plan</h3>
                    <p class="text-gray-600 text-sm mb-6">Perfect for small mobile teams & trades requiring tracking and dashboard access.</p>
                    
                    <div class="mb-4 p-4 bg-white rounded-2xl border border-indigo-100 shadow-sm">
                        <div x-show="billingCycle === 'monthly'" class="transition-all duration-300">
                            <div class="flex items-baseline gap-1">
                                <span class="text-4xl font-extrabold text-[#1800ad]" x-text="card2CustomLogo ? '£{{ number_format($p_5_month + $LOGO_FEE_5, 2) }}' : '£{{ number_format($p_5_month, 2) }}'"></span>
                                <span class="text-gray-500 text-sm font-medium">/ month</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Billed monthly • Min. 3 months commitment</p>
                        </div>
                        <div x-show="billingCycle === 'annual'" x-cloak class="transition-all duration-300">
                            <div class="flex items-baseline gap-1">
                                <span class="text-4xl font-extrabold text-[#1800ad]" x-text="card2CustomLogo ? '£{{ number_format($p_5_annual + $LOGO_FEE_5, 2) }}' : '£{{ number_format($p_5_annual, 2) }}'"></span>
                                <span class="text-gray-500 text-sm font-medium">/ year</span>
                            </div>
                            <p class="text-xs text-green-600 font-semibold mt-1">Equivalent to <span x-text="card2CustomLogo ? '£{{ number_format(($p_5_annual + $LOGO_FEE_5) / 12, 2) }}/mo' : '£{{ number_format($p_5_annual / 12, 2) }}/mo'"></span> (1 Month FREE)</p>
                        </div>
                    </div>

                    <!-- Custom Logo Swatch Checkbox -->
                    <label class="flex items-center gap-2.5 cursor-pointer mb-6 p-3 bg-indigo-50/70 rounded-xl border border-indigo-200 hover:border-indigo-400 transition-all select-none">
                        <input type="checkbox" x-model="card2CustomLogo" class="w-4 h-4 rounded text-[#1800ad] focus:ring-[#00A0FF]">
                        <span class="text-xs font-bold text-[#1800ad] font-ubuntu">🎨 Add Custom Logo (+£{{ number_format($LOGO_FEE_5, 2) }})</span>
                    </label>

                    <ul class="space-y-3 text-sm text-gray-700 mb-8">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span><strong>Supports up to 5 Keyrings</strong> with dashboard</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span><strong>Discounted Keyring:</strong> £4.50 each (vs £9.99)</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span><strong>Custom Logo:</strong> +£2.99 per keyring (£{{ number_format($LOGO_FEE_5, 2) }} total)</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span><strong>Free Replacement:</strong> 1 free per month</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span><strong>Setup Charge:</strong> £0 (No setup fee)</span>
                        </li>
                    </ul>
                </div>

                <button type="button" @click="openModal('5-keyrings')" class="w-full text-center py-4 px-6 rounded-2xl bg-[#1800ad] text-white font-bold hover:bg-[#120084] transition-all shadow-lg shadow-indigo-600/30 cursor-pointer">
                    Choose 5 Keyrings Plan
                </button>
            </div>

            <!-- Card 3: Up to 10 Keyrings Plan (Best Value) -->
            <div class="bg-white rounded-3xl p-8 border border-gray-200 shadow-lg hover:shadow-xl transition-all duration-300 flex flex-col justify-between relative group">
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <span class="px-3 py-1 bg-cyan-50 text-[#0CC0DF] text-xs font-bold rounded-full uppercase tracking-wider">Best Value for Teams</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 font-ubuntu mb-2">10 Keyrings Plan</h3>
                    <p class="text-gray-500 text-sm mb-6">Designed for growing fleets, multi-staff businesses, and larger mobile teams.</p>
                    
                    <div class="mb-4 p-4 bg-gray-50 rounded-2xl border border-gray-100">
                        <div x-show="billingCycle === 'monthly'" class="transition-all duration-300">
                            <div class="flex items-baseline gap-1">
                                <span class="text-4xl font-extrabold text-gray-900" x-text="card3CustomLogo ? '£{{ number_format($p_10_month + $LOGO_FEE_10, 2) }}' : '£{{ number_format($p_10_month, 2) }}'"></span>
                                <span class="text-gray-500 text-sm font-medium">/ month</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Billed monthly • Min. 3 months commitment</p>
                        </div>
                        <div x-show="billingCycle === 'annual'" x-cloak class="transition-all duration-300">
                            <div class="flex items-baseline gap-1">
                                <span class="text-4xl font-extrabold text-gray-900" x-text="card3CustomLogo ? '£{{ number_format($p_10_annual + $LOGO_FEE_10, 2) }}' : '£{{ number_format($p_10_annual, 2) }}'"></span>
                                <span class="text-gray-500 text-sm font-medium">/ year</span>
                            </div>
                            <p class="text-xs text-green-600 font-semibold mt-1">Equivalent to <span x-text="card3CustomLogo ? '£{{ number_format(($p_10_annual + $LOGO_FEE_10) / 12, 2) }}/mo' : '£{{ number_format($p_10_annual / 12, 2) }}/mo'"></span> (1 Month FREE)</p>
                        </div>
                    </div>

                    <!-- Custom Logo Swatch Checkbox -->
                    <label class="flex items-center gap-2.5 cursor-pointer mb-6 p-3 bg-gray-50 rounded-xl border border-gray-200 hover:border-cyan-400 transition-all select-none">
                        <input type="checkbox" x-model="card3CustomLogo" class="w-4 h-4 rounded text-[#142D63] focus:ring-[#00A0FF]">
                        <span class="text-xs font-bold text-gray-700 font-ubuntu">🎨 Add Custom Logo (+£{{ number_format($LOGO_FEE_10, 2) }})</span>
                    </label>

                    <ul class="space-y-3 text-sm text-gray-600 mb-8">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span><strong>Supports up to 10 Keyrings</strong> with dashboard</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span><strong>Discounted Keyring:</strong> £4.50 each (vs £9.99)</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span><strong>Custom Logo:</strong> +£2.99 per keyring (£{{ number_format($LOGO_FEE_10, 2) }} total)</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span><strong>Free Replacement:</strong> 1 free per month</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span><strong>Setup Charge:</strong> £0 (No setup fee)</span>
                        </li>
                    </ul>
                </div>

                <button type="button" @click="openModal('10-keyrings')" class="w-full text-center py-4 px-6 rounded-2xl bg-gray-900 text-white font-bold hover:bg-gray-800 transition-all shadow-md cursor-pointer">
                    Choose 10 Keyrings Plan
                </button>
            </div>

        </div>

        <!-- Important Pricing Rules Callout -->
        <div class="bg-gradient-to-r from-gray-900 via-[#120084] to-[#1800ad] rounded-3xl p-8 md:p-10 text-white shadow-xl border border-white/10">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-[#0CC0DF]/20 flex items-center justify-center text-[#0CC0DF] shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-xl md:text-2xl font-bold font-ubuntu">Important Pricing Rules & Policies</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-gray-200">
                <div class="flex items-start gap-3 bg-white/5 p-4 rounded-2xl border border-white/10">
                    <span class="text-xl shrink-0">🏷️</span>
                    <div>
                        <strong class="text-white block font-ubuntu text-base mb-1">Discounted Keyrings Offer</strong>
                        <span>The £4.50 keyring price is available strictly with an active monthly or annual subscription.</span>
                    </div>
                </div>
                <div class="flex items-start gap-3 bg-white/5 p-4 rounded-2xl border border-white/10">
                    <span class="text-xl shrink-0">🎨</span>
                    <div>
                        <strong class="text-white block font-ubuntu text-base mb-1">Custom Business Branding</strong>
                        <span>Custom company logo & design adds £2.99 per keyring across all subscription and non-subscription options.</span>
                    </div>
                </div>
                <div class="flex items-start gap-3 bg-white/5 p-4 rounded-2xl border border-white/10">
                    <span class="text-xl shrink-0">⏱️</span>
                    <div>
                        <strong class="text-white block font-ubuntu text-base mb-1">Minimum Commitment</strong>
                        <span>Monthly subscription plans require a minimum commitment of 3 months.</span>
                    </div>
                </div>
                <div class="flex items-start gap-3 bg-white/5 p-4 rounded-2xl border border-white/10">
                    <span class="text-xl shrink-0">🔄</span>
                    <div>
                        <strong class="text-white block font-ubuntu text-base mb-1">Free Monthly Replacements</strong>
                        <span>Each active subscription includes 1 free replacement keyring per month if damaged or lost.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Configure Order Modal (Custom Logo & Location Details) -->
    <div x-show="showModal" x-cloak 
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm transition-all duration-300"
         @keydown.escape.window="showModal = false">
        
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-gray-100 relative transform transition-all animate-fade-in"
             @click.away="showModal = false">
            
            <!-- Close Button -->
            <button type="button" @click="showModal = false" class="absolute top-5 right-5 text-gray-400 hover:text-gray-600 transition-colors p-1 rounded-full hover:bg-gray-100 cursor-pointer">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <!-- Modal Header -->
            <div class="mb-6">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-indigo-50 text-[#1800ad] text-xs font-bold uppercase tracking-wider mb-2 font-ubuntu">
                    Configure Your Order
                </span>
                <h3 class="text-2xl font-bold text-[#142D63] font-ubuntu" x-text="modalPlanTitle"></h3>
                <p class="text-gray-500 text-sm font-mulish mt-1" x-text="modalBillingSubtitle"></p>
            </div>

            <!-- Custom Logo Add-on Swatch Checkbox -->
            <div class="mb-6 bg-gradient-to-r from-blue-50/80 via-indigo-50/60 to-cyan-50/80 p-4 rounded-2xl border border-blue-200 shadow-sm transition-all duration-300 hover:border-blue-400">
                <label class="flex items-start gap-3.5 cursor-pointer select-none">
                    <input type="checkbox" x-model="modalCustomLogo" @change="if(!modalCustomLogo) removeLogo()"
                           class="mt-1 w-5 h-5 rounded border-gray-300 text-[#142D63] focus:ring-[#00A0FF] transition-all cursor-pointer">
                    <div class="flex-1">
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-[#142D63] font-ubuntu text-sm sm:text-base flex items-center gap-2">
                                <span>🎨 Custom Company Logo & Design</span>
                            </span>
                            <div class="flex items-center gap-2">
                                <span class="bg-[#00A0FF] font-extrabold text-white px-3 py-1 rounded-full text-sm sm:text-base" x-text="'+£' + modalLogoFee.toFixed(2)"></span>
                            </div>
                        </div>
                        <p class="text-xs text-gray-600 font-mulish mt-1">
                            Print your custom business logo, branding colors, and unique artwork onto your review products.
                        </p>
                    </div>
                </label>

                <!-- Logo File Uploader (shown when add-on selected) -->
                <div x-show="modalCustomLogo" x-transition x-cloak class="mt-4 pt-4 border-t border-blue-200/70">
                    <label class="block text-xs font-bold text-[#142D63] font-ubuntu mb-2">
                        Upload your logo / artwork <span class="text-red-500">*</span>
                    </label>

                    <!-- Empty state: choose file -->
                    <label x-show="!customLogoPath && !customLogoUploading"
                           class="flex flex-col items-center justify-center gap-2 w-full border-2 border-dashed border-blue-300 rounded-xl p-5 cursor-pointer bg-white/60 hover:bg-white hover:border-[#00A0FF] transition-all text-center">
                        <svg class="w-8 h-8 text-[#00A0FF]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.9A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                        <span class="text-xs font-semibold text-gray-600 font-mulish">Click to upload your logo</span>
                        <span class="text-[10px] text-gray-400 font-mulish">PNG, JPG, SVG, WEBP or PDF · max 5MB</span>
                        <input type="file" class="hidden" accept=".jpg,.jpeg,.png,.svg,.webp,.pdf,image/*,application/pdf" @change="uploadLogo($event)">
                    </label>

                    <!-- Uploading state -->
                    <div x-show="customLogoUploading" class="flex items-center gap-3 w-full border border-blue-200 rounded-xl p-4 bg-white">
                        <svg class="animate-spin h-5 w-5 text-[#00A0FF]" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        <span class="text-xs font-semibold text-gray-600 font-mulish">Uploading your logo…</span>
                    </div>

                    <!-- Uploaded state -->
                    <div x-show="customLogoPath && !customLogoUploading" class="flex items-center justify-between gap-3 w-full border border-green-200 rounded-xl p-3 bg-green-50">
                        <div class="flex items-center gap-3 overflow-hidden">
                            <div class="w-10 h-10 rounded-lg bg-white border border-green-100 flex items-center justify-center overflow-hidden flex-shrink-0">
                                <template x-if="customLogoIsImage">
                                    <img :src="customLogoUrl" class="w-full h-full object-contain" alt="Logo preview">
                                </template>
                                <template x-if="!customLogoIsImage">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </template>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-green-800 font-mulish truncate" x-text="customLogoName"></p>
                                <p class="text-[10px] text-green-600 font-mulish">Logo attached ✓</p>
                            </div>
                        </div>
                        <button type="button" @click="removeLogo()" class="text-gray-400 hover:text-red-500 transition-colors p-1 flex-shrink-0" title="Remove logo">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <p x-show="customLogoError" x-text="customLogoError" class="text-xs text-red-600 font-mulish mt-2"></p>
                </div>
            </div>

            <!-- Business Location Search Section -->
            <div class="mb-4">
                <p class="text-gray-700 font-bold mb-3 font-ubuntu text-lg">Where should we link your cards?</p>
                
                <!-- Added Locations List -->
                <div class="space-y-2.5 mb-3" x-show="!skipForNow && locations.length > 0">
                    <template x-for="(loc, index) in locations" :key="index">
                        <div class="flex items-center justify-between bg-white border border-gray-200 p-3 rounded-xl shadow-xs animate-fade-in">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-green-50 text-green-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <span class="text-sm font-semibold text-[#142D63] font-mulish" x-text="loc.name"></span>
                            </div>
                            <button type="button" @click="removeLocation(index)" class="text-gray-300 hover:text-red-500 transition-colors p-1 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </template>
                </div>

                <!-- Skip for now active notice -->
                <div x-show="skipForNow" class="p-3.5 bg-blue-50/80 border border-blue-200 rounded-xl mb-4 animate-fade-in flex items-start gap-3">
                    <div class="w-7 h-7 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="flex-1 text-xs font-mulish text-[#142D63]">
                        <p class="font-bold text-sm text-[#142D63] mb-0.5">Linking skipped for now</p>
                        <p class="text-gray-600">No problem! You can order now and our team will contact you for your Google review link before dispatch.</p>
                    </div>
                    <button type="button" @click="skipForNow = false" class="text-xs font-bold text-blue-600 hover:underline">
                        Change
                    </button>
                </div>

                <div x-show="!skipForNow">
                    <p class="text-gray-600 font-mulish mb-2 text-sm font-bold" x-text="locations.length > 0 ? 'Add another location' : 'Search your business location'"></p>
                    <div class="relative flex gap-2">
                        <div class="relative flex-1">
                            <input type="text" id="google-places-input-modal" x-model="tempPlaceName"
                                   :placeholder="locations.length > 0 ? 'e.g. 2nd branch address' : 'Please search your business here'"
                                   class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm text-gray-700 focus:ring-2 focus:ring-green-400 focus:border-green-400 transition-all font-mulish pr-10">
                            <div class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400" x-show="!tempPlaceName">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                        </div>
                        <button type="button" @click="addLocation()" 
                                :disabled="!tempPlaceName"
                                :class="!tempPlaceName ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-[#142D63] text-white hover:bg-[#00A0FF] cursor-pointer'"
                                class="px-5 py-3 rounded-xl text-xs font-bold transition-all duration-300 flex items-center justify-center shadow-md">
                            Confirm
                        </button>
                    </div>
                    <p class="text-xs text-gray-400 mt-2 font-mulish">Example: 38 Mayfair Row, London 1BX456</p>
                    <input type="hidden" id="google-place-id-modal" x-model="tempPlaceId">
                </div>
            </div>

            <!-- Skip for now button/toggle -->
            <div class="mb-4 flex items-center justify-between pt-2 border-t border-gray-100">
                <button type="button" @click="toggleSkip()" 
                        class="inline-flex items-center gap-1.5 text-xs font-semibold transition-colors cursor-pointer"
                        :class="skipForNow ? 'text-gray-500 hover:text-gray-700' : 'text-blue-600 hover:text-blue-800 hover:underline'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/></svg>
                    <span x-text="skipForNow ? '← Add a location instead' : 'Don\'t have your link ready? Skip for now and set up later'"></span>
                </button>
            </div>

            <!-- Modal Action Button -->
            <button type="button" @click="confirmAndProceedToCheckout()" 
                    class="w-full text-center text-white py-4 rounded-2xl font-bold text-lg bg-[#0cc0df] hover:bg-[#0bb0cd] transition-all shadow-lg hover:shadow-cyan-500/25 transform hover:-translate-y-0.5 font-ubuntu flex items-center justify-center gap-2 cursor-pointer">
                <span>BUY NOW</span>
                <span class="text-sm font-normal opacity-90" x-text="'(' + getModalDisplayPrice() + ')'"></span>
            </button>
        </div>
    </div>

    <!-- Product Grid -->
    <div id="products" class="bg-gray-50 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-[#1800ad] mb-4 font-ubuntu">Shop Our NFC Review Cards</h2>
                <p class="text-gray-600 font-mulish max-w-2xl mx-auto">Choose the perfect card for your business. All cards come pre-programmed and ready to use.</p>
            </div>

            @foreach($products as $product)
            <div class="mb-20">

                <!-- Variants Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($product->variants as $variant)
                    <a href="{{ route('shop.show', ['product' => $product->slug, 'variant' => $variant->id]) }}" class="group mt-4 block">
                        <div class="relative bg-white rounded-2xl shadow-md hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border border-gray-100">
                            <!-- Discount Badge -->
                            @if($variant->discount_percent > 0)
                            <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 z-10">
                                <span class="bg-[#1800ad] text-white text-sm font-bold px-5 py-2 rounded-full shadow-lg whitespace-nowrap">
                                    save {{ $variant->discount_percent }}%
                                </span>
                            </div>
                            @endif

                            <!-- Best Value Badge -->
                            @if($variant->is_best_value)
                            <div class="absolute bottom-[140px] right-0 z-10">
                                <div class="bg-[#4285F4] text-white px-5 py-2 rounded-l-lg font-bold text-sm flex items-center gap-1.5 shadow-lg">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    Best value
                                </div>
                            </div>
                            @endif

                            <!-- Product Image (variant image first, fallback to product image) -->
                            <div class="bg-[#d8e4ef] p-8 flex items-center justify-center md:h-[380px] h-[280px]  transition-all duration-500 overflow-hidden rounded-t-2xl">
                                @if($variant->image)
                                    <img src="{{ asset('storage/' . $variant->image) }}" alt="{{ $variant->name }}" class="max-h-72 w-auto object-contain group-hover:scale-110 transition-transform duration-500" loading="lazy">
                                @elseif($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $variant->name }}" class="max-h-72 w-auto object-contain group-hover:scale-110 transition-transform duration-500" loading="lazy">
                                @else
                                    <div class="w-40 h-40 bg-white rounded-2xl shadow-inner flex items-center justify-center">
                                        <svg class="w-20 h-20 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Product Info -->
                            <div class="p-6 text-center">
                                <!-- Star Rating -->
                                <div class="flex justify-center mb-3">
                                    @for($i = 0; $i < 5; $i++)
                                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    @endfor
                                </div>

                                <!-- Variant Name -->
                                <h3 class="text-lg font-semibold text-[#1800ad] mb-2 font-ubuntu">{{ $variant->name }}</h3>

                                <!-- Pricing -->
                                @php
                                    $userPartnerDiscount = (auth()->check() && auth()->user()->isApprovedPartner()) ? auth()->user()->getPartnerDiscountPercent() : 0;
                                    $displayPrice = $variant->price;
                                    if ($userPartnerDiscount > 0) {
                                        $displayPrice = round($variant->price * (1 - ($userPartnerDiscount / 100)), 2);
                                    }
                                @endphp
                                <div class="flex flex-col items-center gap-1">
                                    @if($userPartnerDiscount > 0)
                                        <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-purple-100 text-purple-800 border border-purple-200">
                                            {{ auth()->user()->partner_type_label }} ({{ $userPartnerDiscount }}% OFF)
                                        </span>
                                    @endif
                                    <div class="flex items-center justify-center gap-3">
                                        <span class="text-2xl font-bold text-[#1800ad]">£{{ number_format($displayPrice, 2) }}</span>
                                        @if($userPartnerDiscount > 0)
                                            <span class="text-lg text-gray-400 line-through">£{{ number_format($variant->price, 2) }}</span>
                                        @elseif($variant->original_price > $variant->price)
                                            <span class="text-lg text-gray-400 line-through">£{{ number_format($variant->original_price, 2) }}</span>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endforeach

            @if($products->isEmpty())
            <div class="text-center py-20">
                <svg class="w-24 h-24 text-gray-300 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <h3 class="text-2xl font-bold text-gray-400 font-ubuntu">No products available yet</h3>
                <p class="text-gray-400 mt-2 font-mulish">Check back soon for our amazing products!</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Products Overview -->
    <div id="products" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-extrabold text-gray-900 mb-8 font-ubuntu text-center">Featured Keyring Collector Packs</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="w-12 h-12 bg-indigo-50 text-[#1800ad] rounded-xl flex items-center justify-center mb-4 font-bold text-xl">1</div>
                    <h4 class="text-xl font-bold text-gray-900 mb-2 font-ubuntu">Single Trade Keyring</h4>
                    <p class="text-gray-600 text-sm font-mulish">Perfect for solo tradespeople wanting an easy way to get Google reviews right after finishing a job.</p>
                </div>
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="w-12 h-12 bg-indigo-50 text-[#1800ad] rounded-xl flex items-center justify-center mb-4 font-bold text-xl">5</div>
                    <h4 class="text-xl font-bold text-gray-900 mb-2 font-ubuntu">Small Team Pack (5 Tags)</h4>
                    <p class="text-gray-600 text-sm font-mulish">Equip up to 5 technicians or drivers. Track individual performance on your dashboard.</p>
                </div>
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="w-12 h-12 bg-indigo-50 text-[#1800ad] rounded-xl flex items-center justify-center mb-4 font-bold text-xl">10</div>
                    <h4 class="text-xl font-bold text-gray-900 mb-2 font-ubuntu">Fleet Pack (10 Tags)</h4>
                    <p class="text-gray-600 text-sm font-mulish">Ideal for larger mobile workforces, delivery teams, and multi-van service companies.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Target Audience Callout -->
    <div class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-r from-[#1800ad] to-[#120084] rounded-3xl p-8 md:p-16 text-white text-center relative overflow-hidden shadow-2xl">
                <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:20px_20px]"></div>
                <h2 class="text-3xl md:text-5xl font-bold mb-12 font-ubuntu relative z-10">Who is it for?</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 relative z-10">
                    <div class="bg-white/10 p-6 rounded-2xl text-center backdrop-blur-md border border-white/10">
                        <div class="text-4xl mb-3 text-[#0cc0df]">🔧</div>
                        <h5 class="font-bold text-lg">Electricians</h5>
                    </div>
                    <div class="bg-white/10 p-6 rounded-2xl text-center backdrop-blur-md border border-white/10">
                        <div class="text-4xl mb-3 text-[#0cc0df]">🚿</div>
                        <h5 class="font-bold text-lg">Plumbers</h5>
                    </div>
                    <div class="bg-white/10 p-6 rounded-2xl text-center backdrop-blur-md border border-white/10">
                        <div class="text-4xl mb-3 text-[#0cc0df]">🚗</div>
                        <h5 class="font-bold text-lg">Delivery</h5>
                    </div>
                    <div class="bg-white/10 p-6 rounded-2xl text-center backdrop-blur-md border border-white/10">
                        <div class="text-4xl mb-3 text-[#0cc0df]">💅</div>
                        <h5 class="font-bold text-lg">Mobile Beauty</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
[x-cloak] { display: none !important; }
@keyframes float {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
    100% { transform: translateY(0px); }
}
.animate-float {
    animation: float 6s ease-in-out infinite;
}
</style>

<!-- Google Places API -->
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_places.api_key', env('GOOGLE_PLACES_API_KEY')) }}&libraries=places&callback=initAutocomplete" async defer></script>

<script>
function keyringPlans() {
    const variantsMap = @json($planVariants ?? []);
    const primaryProd = @json($primaryProduct ?? []);

    return {
        billingCycle: 'monthly',
        card1CustomLogo: false,
        card2CustomLogo: false,
        card3CustomLogo: false,
        variants: variantsMap,

        // Modal configuration
        showModal: false,
        activePlanKey: '',
        modalPlanTitle: '',
        modalBillingSubtitle: '',
        modalBasePrice: 0,
        modalLogoFee: 0, // per-plan custom-logo fee (£2.99 x keyrings)
        modalCustomLogo: false,
        modalVariant: null,
        customLogoPath: '',
        customLogoUrl: '',
        customLogoName: '',
        customLogoUploading: false,
        customLogoError: '',
        get customLogoIsImage() {
            return /\.(png|jpe?g|svg|webp)$/i.test((this.customLogoName || this.customLogoUrl || '').toLowerCase());
        },
        locations: [],
        tempPlaceName: '',
        tempPlaceId: '',
        skipForNow: false,

        toggleSkip() {
            this.skipForNow = !this.skipForNow;
            if (this.skipForNow) {
                this.tempPlaceName = '';
                this.tempPlaceId = '';
            }
        },

        init() {
            window.addEventListener('place-selected', (e) => {
                this.tempPlaceId = e.detail.id;
                this.tempPlaceName = e.detail.name;
                const input = document.getElementById('google-places-input-modal');
                if (input) input.value = e.detail.name;
            });
        },

        openModal(planKey) {
            this.activePlanKey = planKey;
            this.removeLogo();
            const isAnnual = this.billingCycle === 'annual';

            if (planKey === 'no-subscription') {
                this.modalVariant = this.variants['no_subscription'] || @json($defaultVariant);
                this.modalPlanTitle = 'Generic Keyring (No Subscription)';
                this.modalBasePrice = this.modalVariant ? parseFloat(this.modalVariant.price) : {{ $p_no_sub }};
                this.modalBillingSubtitle = 'One-time payment • No recurring fees';
                this.modalLogoFee = {{ $LOGO_FEE }};   // 1 keyring
                this.modalCustomLogo = this.card1CustomLogo;
            } else if (planKey === '5-keyrings') {
                this.modalVariant = isAnnual ? this.variants['5_annual'] : this.variants['5_monthly'];
                this.modalPlanTitle = '5 Keyrings Plan';
                this.modalBasePrice = this.modalVariant ? parseFloat(this.modalVariant.price) : (isAnnual ? {{ $p_5_annual }} : {{ $p_5_month }});
                this.modalBillingSubtitle = isAnnual ? ('Billed annually (£' + this.modalBasePrice.toFixed(2) + '/yr • 1 Month FREE)') : ('Billed monthly (£' + this.modalBasePrice.toFixed(2) + '/mo • Min. 3 months)');
                this.modalLogoFee = {{ $LOGO_FEE_5 }};  // 5 keyrings
                this.modalCustomLogo = this.card2CustomLogo;
            } else if (planKey === '10-keyrings') {
                this.modalVariant = isAnnual ? this.variants['10_annual'] : this.variants['10_monthly'];
                this.modalPlanTitle = '10 Keyrings Plan';
                this.modalBasePrice = this.modalVariant ? parseFloat(this.modalVariant.price) : (isAnnual ? {{ $p_10_annual }} : {{ $p_10_month }});
                this.modalBillingSubtitle = isAnnual ? ('Billed annually (£' + this.modalBasePrice.toFixed(2) + '/yr • 1 Month FREE)') : ('Billed monthly (£' + this.modalBasePrice.toFixed(2) + '/mo • Min. 3 months)');
                this.modalLogoFee = {{ $LOGO_FEE_10 }};  // 10 keyrings
                this.modalCustomLogo = this.card3CustomLogo;
            }

            this.showModal = true;

            this.$nextTick(() => {
                if (typeof initAutocomplete === 'function') {
                    initAutocomplete();
                }
            });
        },

        getModalDisplayPrice() {
            const finalPrice = this.modalCustomLogo ? (this.modalBasePrice + this.modalLogoFee) : this.modalBasePrice;
            const period = (this.activePlanKey === 'no-subscription') ? '' : (this.billingCycle === 'annual' ? '/yr' : '/mo');
            return '£' + finalPrice.toFixed(2) + period;
        },

        addLocation() {
            const input = document.getElementById('google-places-input-modal');
            const placeName = (this.tempPlaceName || (input ? input.value : '')).trim();
            if (!placeName) return;

            const placeId = this.tempPlaceId || ('loc-' + Date.now());
            
            const exists = this.locations.some(loc => loc.id === placeId || loc.name === placeName);
            
            this.tempPlaceId = '';
            this.tempPlaceName = '';
            if (input) input.value = '';

            if (exists) {
                // Location is already added to the list, clear inputs silently
                return;
            }

            this.locations.push({
                id: placeId,
                name: placeName
            });
            this.skipForNow = false;
        },

        removeLocation(index) {
            this.locations.splice(index, 1);
        },

        async uploadLogo(event) {
            const file = event.target.files && event.target.files[0];
            if (!file) return;

            this.customLogoError = '';
            this.customLogoUploading = true;

            const formData = new FormData();
            formData.append('logo', file);

            try {
                const res = await fetch('{{ route('shop.upload-logo') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });
                const data = await res.json();
                if (data.success) {
                    this.customLogoPath = data.path;
                    this.customLogoUrl = data.url;
                    this.customLogoName = data.name || 'logo';
                } else {
                    this.customLogoError = data.message || 'Upload failed. Please try again.';
                }
            } catch (e) {
                console.error('Logo upload failed:', e);
                this.customLogoError = 'Upload failed. Please check your connection and try again.';
            } finally {
                this.customLogoUploading = false;
                if (event.target) event.target.value = '';
            }
        },

        removeLogo() {
            this.customLogoPath = '';
            this.customLogoUrl = '';
            this.customLogoName = '';
            this.customLogoError = '';
        },

        confirmAndProceedToCheckout() {
            if (this.customLogoUploading) {
                alert('Please wait for your logo to finish uploading.');
                return;
            }
            if (this.modalCustomLogo && !this.customLogoPath) {
                this.customLogoError = 'Please upload your logo to continue, or uncheck the custom logo add-on.';
                alert('Please upload your custom logo, or uncheck the "Custom Company Logo & Design" add-on.');
                return;
            }

            if (this.skipForNow) {
                this.locations = [{
                    id: 'skip-setup-later',
                    name: 'Skip for now (Link later)'
                }];
            } else {
                // Auto-confirm typed location if input is filled but confirm button wasn't clicked
                if (this.locations.length === 0) {
                    const input = document.getElementById('google-places-input-modal');
                    if (this.tempPlaceName || (input && input.value.trim())) {
                        this.addLocation();
                    }
                }

                if (this.locations.length === 0) {
                    alert('Please search your business location or select "Skip for now".');
                    return;
                }
            }

            const v = this.modalVariant;
            const isAnnual = this.billingCycle === 'annual';
            const variantId = v ? v.id : 1;
            const finalPrice = this.modalCustomLogo ? (this.modalBasePrice + this.modalLogoFee) : this.modalBasePrice;
            const defaultName = (this.activePlanKey === 'no-subscription')
                ? 'Generic Keyring (No Subscription)'
                : ((this.activePlanKey === '5-keyrings' ? '5 Keyrings Plan' : '10 Keyrings Plan') + (isAnnual ? ' (Annual Billing)' : ' (Monthly Billing)'));
            const variantName = (v ? v.name : defaultName) + (this.modalCustomLogo ? ' + Custom Logo' : '');

            this.appendCartItem({
                variantId: variantId,
                name: variantName,
                productName: primaryProd.name || 'Google Review Keyrings Plan',
                price: finalPrice,
                hasCustomLogo: this.modalCustomLogo,
                customLogoFee: this.modalCustomLogo ? this.modalLogoFee : 0.00,  // Custom Logo Design (£2.99 x keyrings)
                customLogoPath: this.modalCustomLogo ? this.customLogoPath : '',
                customLogoName: this.modalCustomLogo ? this.customLogoName : '',
                image: (v && v.image) ? ('/storage/' + v.image) : (primaryProd.image ? ('/storage/' + primaryProd.image) : ''),
                placeId: this.locations[0].id,
                placeName: this.locations[0].name,
                locationList: this.locations,
                locationText: this.locations.map(l => l.name).join(', '),
                qty: 1
            });
        },

        appendCartItem(newItem) {
            const cart = JSON.parse(localStorage.getItem('rb_cart') || '[]');
            
            const existingIdx = cart.findIndex(i => 
                String(i.variantId) === String(newItem.variantId) && 
                Boolean(i.hasCustomLogo) === Boolean(newItem.hasCustomLogo) &&
                JSON.stringify(i.locationList || []) === JSON.stringify(newItem.locationList)
            );

            if (existingIdx > -1) {
                cart[existingIdx].qty++;
            } else {
                cart.push(newItem);
            }

            localStorage.setItem('rb_cart', JSON.stringify(cart));
            window.dispatchEvent(new CustomEvent('cart-updated'));

            // Analytics: add-to-cart beacon (fire and forget)
            try {
                fetch('{{ route('track.event') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        type: 'add_to_cart',
                        variant_id: newItem.variantId,
                        quantity: newItem.qty || 1,
                        value: (parseFloat(newItem.price) || 0) * (newItem.qty || 1)
                    }),
                    keepalive: true
                }).catch(function () {});
            } catch (e) {}

            window.location.href = '{{ route("shop.checkout") }}?variant_id=' + newItem.variantId;
        }
    };
}

function initAutocomplete() {
    const input = document.getElementById('google-places-input-modal');
    if (!input || input.dataset.autocompleteBound) return;
    input.dataset.autocompleteBound = "true";

    const autocomplete = new google.maps.places.Autocomplete(input, {
        types: ['establishment'],
        componentRestrictions: { country: 'gb' },
    });

    autocomplete.addListener('place_changed', function () {
        const place = autocomplete.getPlace();
        if (place) {
            const pId = place.place_id || ('loc-' + Date.now());
            
            let pName = input.value;
            if (place.name && place.formatted_address) {
                if (place.formatted_address.toLowerCase().includes(place.name.toLowerCase())) {
                    pName = place.formatted_address;
                } else {
                    pName = place.name + ' - ' + place.formatted_address;
                }
            } else if (place.name) {
                pName = place.name;
            } else if (place.formatted_address) {
                pName = place.formatted_address;
            }

            window.dispatchEvent(new CustomEvent('place-selected', {
                detail: {
                    id: pId,
                    name: pName
                }
            }));
        }
    });
}
</script>
@endsection
