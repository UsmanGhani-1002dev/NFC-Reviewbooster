@extends('layouts.app')

@section('content')

    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://js.stripe.com/v3/"></script>
    <section x-data="subscriptionForm()" x-init="init()" class="w-full mx-auto py-4 sm:py-8">
        {{-- Alert Messages --}}
        @if (auth()->check() &&
            isset($showSubscriptionWarning) &&
            $showSubscriptionWarning &&
            !session('subscription_notification_dismissed'))
            @if (isset($subscriptionExpired) && $subscriptionExpired)
                <!-- EXPIRED SUBSCRIPTION WARNING -->
                <div id="subscription-warning" class="mb-6 rounded-2xl overflow-hidden shadow-lg border border-red-200 animate-fadeIn">
                    <div class="bg-gradient-to-r from-red-500 via-rose-500 to-red-600 px-5 py-4 sm:px-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div class="flex items-start gap-4">
                                {{-- Animated icon --}}
                                <div class="relative flex-shrink-0 mt-0.5">
                                    <div class="w-11 h-11 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                                    </div>
                                    <span class="absolute -top-1 -right-1 flex h-3.5 w-3.5">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-white"></span>
                                    </span>
                                </div>
                                <div>
                                    <h3 class="text-white font-bold text-base sm:text-lg leading-tight">Subscription Expired</h3>
                                    <p class="text-red-100 text-sm mt-0.5 leading-relaxed">Your plan has expired. Renew now to restore full access to all features and keep your business running smoothly.</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 sm:flex-shrink-0 pl-15 sm:pl-0">
                                <button @click="showModal = true"
                                   class="inline-flex items-center gap-2 bg-white text-red-600 hover:bg-red-50 font-bold px-5 py-2.5 rounded-xl text-sm shadow-md hover:shadow-lg transition-all duration-300 hover:-translate-y-0.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182"/></svg>
                                    Renew Now
                                </button>
                                <button onclick="document.getElementById('subscription-warning').style.display='none'"
                                        class="p-2 text-white/60 hover:text-white hover:bg-white/10 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif (isset($subscriptionExpiresIn2Days) && $subscriptionExpiresIn2Days)
                <!-- EXPIRING SOON WARNING -->
                <div id="subscription-warning" class="mb-6 rounded-2xl overflow-hidden shadow-lg border border-amber-200 animate-fadeIn">
                    <div class="bg-gradient-to-r from-amber-400 via-yellow-400 to-orange-400 px-5 py-4 sm:px-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div class="flex items-start gap-4">
                                {{-- Animated icon --}}
                                <div class="relative flex-shrink-0 mt-0.5">
                                    <div class="w-11 h-11 bg-white/25 backdrop-blur-sm rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-amber-900" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <span class="absolute -top-1 -right-1 flex h-3.5 w-3.5">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-800 opacity-60"></span>
                                        <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-amber-800"></span>
                                    </span>
                                </div>
                                <div>
                                    <h3 class="text-amber-900 font-bold text-base sm:text-lg leading-tight">Subscription Expiring Soon</h3>
                                    <p class="text-amber-800/80 text-sm mt-0.5 leading-relaxed">Your plan will expire <strong>{{ $timeLeft ?? 'soon' }}</strong>. Renew today to avoid any interruption to your services.</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 sm:flex-shrink-0 pl-15 sm:pl-0">
                                <button @click="showModal = true"
                                   class="inline-flex items-center gap-2 bg-amber-900 text-white hover:bg-amber-800 font-bold px-5 py-2.5 rounded-xl text-sm shadow-md hover:shadow-lg transition-all duration-300 hover:-translate-y-0.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182"/></svg>
                                    Renew Now
                                </button>
                                <button onclick="document.getElementById('subscription-warning').style.display='none'"
                                        class="p-2 text-amber-700/50 hover:text-amber-900 hover:bg-white/20 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endif

        {{-- Toast popup --}}
        <div x-show="showToast" x-cloak x-transition
            class="fixed top-20 right-6 bg-green-500 text-white px-6 py-3 rounded shadow-lg z-50">
            Subscription Updated successfully.
        </div>

       <header class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-6 md:mb-10">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">{{ __('Subscription Plans') }}</h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-1 sm:mt-2 font-medium">Manage your active plans and billing history</p>
            </div>

            @if ($subscriptions->count())
                @php
                    $hasActiveSub = $subscriptions->contains(function ($sub) {
                        return $sub->status === 'active' && (!$sub->ends_at || !\Carbon\Carbon::parse($sub->ends_at)->isPast());
                    });
                @endphp
                <button @click="showModal = true"
                    class="inline-flex items-center justify-center px-6 py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl shadow-lg shadow-blue-100 transition-all duration-300 transform hover:-translate-y-1">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    {{ $hasActiveSub ? __('Change Plan') : __('Renew Subscription') }}
                </button>
            @endif
        </header>

        @if ($subscriptions->count())
        {{-- Desktop Table --}}
        <div class="hidden md:block bg-white rounded-2xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-indigo-600 text-white uppercase tracking-wider">
                        <th class="px-8 py-5 text-[14px] uppercase tracking-widest">Plan</th>
                        <th class="px-8 py-5 text-[14px] uppercase tracking-widest">Description</th>
                        <th class="px-8 py-5 text-[14px] uppercase tracking-widest text-center">Price</th>
                        <th class="px-8 py-5 text-[14px] uppercase tracking-widest text-center">Started At</th>
                        <th class="px-8 py-5 text-[14px] uppercase tracking-widest text-center">Ends At</th>
                        <th class="px-8 py-5 text-[14px] uppercase tracking-widest text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach ($subscriptions as $subscription)
                    <tr class="group hover:bg-blue-50/30 transition-colors">
                        <td class="px-8 py-6 text-base font-bold text-gray-900 capitalize">{{ $subscription->plan->name ?? 'N/A' }}</td>
                        <td class="px-8 py-6 max-w-xs">
                            <div class="text-sm text-gray-600 truncate" title="{{ strip_tags($subscription->plan->description) }}">
                                {!! $subscription->plan->description ?? 'N/A' !!}
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center font-extrabold text-blue-600">€{{ number_format($subscription->plan->price ?? 0, 2) }}</td>
                        <td class="px-8 py-6 text-center text-sm font-medium text-gray-500">{{ \Carbon\Carbon::parse($subscription->started_at)->format('d M Y') }}</td>
                        <td class="px-8 py-6 text-center text-sm font-medium text-gray-500">
                            {{ $subscription->ends_at ? \Carbon\Carbon::parse($subscription->ends_at)->format('d M Y') : 'N/A' }}
                        </td>
                        <td class="px-8 py-6 text-center">
                            @if($subscription->status === 'active')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 uppercase tracking-tight">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-2 animate-pulse"></span>    
                                    Active
                                </span>
                            @elseif($subscription->status === 'expired')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-rose-50 text-rose-600 border border-rose-100 uppercase tracking-tight">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-2"></span>    
                                    Expired
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-600 border border-amber-100 uppercase tracking-tight">
                                    {{ $subscription->status }}
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards --}}
        <div class="block md:hidden space-y-4">
            @foreach ($subscriptions as $subscription)
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 space-y-4">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 capitalize">{{ $subscription->plan->name ?? 'N/A' }}</h3>
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mt-1">Current Plan</p>
                    </div>
                    <div class="text-right">
                        <p class="text-lg font-black text-blue-600">€{{ number_format($subscription->plan->price ?? 0, 2) }}</p>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-xl p-4 space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 font-medium">Started At</span>
                        <span class="text-gray-900 font-bold">{{ \Carbon\Carbon::parse($subscription->started_at)->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 font-medium">Ends At</span>
                        <span class="text-gray-900 font-bold">{{ $subscription->ends_at ? \Carbon\Carbon::parse($subscription->ends_at)->format('d M Y') : 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between items-center pt-2 border-t border-gray-100">
                        <span class="text-gray-500 font-medium">Status</span>
                        @if($subscription->status === 'active')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-600 border border-emerald-100 uppercase">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-2 animate-pulse"></span> Active
                            </span>
                        @elseif($subscription->status === 'expired')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-extrabold bg-rose-50 text-rose-600 border border-rose-100 uppercase">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-2"></span> Expired
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-extrabold bg-amber-50 text-amber-600 border border-amber-100 uppercase">
                                {{ $subscription->status }}
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="text-sm text-gray-600 leading-relaxed italic border-l-4 border-blue-500 pl-3">
                    {!! $subscription->plan->description ?? 'N/A' !!}
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 p-12 text-center">
            <div class="w-20 h-20 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-300 mx-auto mb-6">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                </svg>
            </div>
            <h3 class="text-xl font-extrabold text-gray-900">No active plans found</h3>
            <p class="text-gray-500 mt-2 max-w-xs mx-auto">You haven't subscribed to any review plans yet.</p>
            <button @click="showModal = true" class="mt-8 px-8 py-3 bg-blue-600 text-white font-bold rounded-xl shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all">
                Browse Plans
            </button>
        </div>
        @endif

        <style>
            [x-cloak] {
                display: none !important;
            }
            @keyframes celebration {
                0% { transform: scale(0.9); opacity: 0; }
                50% { transform: scale(1.02); }
                100% { transform: scale(1); opacity: 1; }
            }
            .animate-celebration {
                animation: celebration 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
            }
        </style>

        {{-- Modal --}}
        <div x-show="showModal" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm transition-all duration-300 p-4">
            <div @click.away="showModal = false"
                class="bg-white w-full max-w-xl rounded-3xl shadow-2xl transform transition-all duration-300 overflow-hidden border border-white/20">
                
                {{-- Modal Header --}}
                <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center bg-white sticky top-0 z-10">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900" x-text="step === 1 ? 'Select a Payment Plan' : 'Payment Details'"></h2>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-0.5" x-text="step === 1 ? 'Step 1 of 2' : 'Final Step'"></p>
                    </div>
                    <button @click="showModal = false" class="text-gray-400 hover:text-gray-600 transition p-2 hover:bg-gray-50 rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="submitSubscription" class="flex flex-col">
                    {{-- Step 1: Plan Selection --}}
                    <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" class="p-8 space-y-4 max-h-[60vh] overflow-y-auto">
                        @foreach ($plans as $plan)
                            <label class="flex flex-col sm:flex-row justify-between items-start sm:items-center p-5 border border-gray-100 rounded-2xl shadow-sm cursor-pointer hover:border-blue-200 hover:bg-blue-50/10 transition-all duration-300 group relative"
                                :class="selectedPlanId == {{ $plan->id }} ? 'border-blue-600 ring-1 ring-blue-600 bg-blue-50/30' : ''">
                                
                                <div class="flex items-start gap-4">
                                    <div class="mt-1 relative flex items-center justify-center">
                                        <input type="radio" name="payment_plan" value="{{ $plan->id }}" x-model="selectedPlanId"
                                            class="w-5 h-5 border-2 border-gray-300 rounded-full checked:border-blue-600 checked:bg-blue-600 appearance-none cursor-pointer transition-all">
                                        <div x-show="selectedPlanId == {{ $plan->id }}" class="absolute w-2 h-2 bg-white rounded-full"></div>
                                    </div>
                    
                                    <div>
                                        <div class="text-sm sm:text-lg font-bold text-gray-900 capitalize">{{ $plan->name }}</div>
                                        <div class="text-xs sm:text-sm text-gray-500 leading-relaxed mt-1 sm:mt-2 space-y-1">
                                            {!! $plan->description !!}
                                        </div>
                                    </div>
                                </div>
                    
                                <div class="text-left sm:text-right whitespace-nowrap ml-9 sm:ml-4 mt-3 sm:mt-0">
                                    <div class="text-xl sm:text-2xl font-bold text-[#142D63]">€{{ number_format($plan->price, 2) }}</div>
                                    <div class="text-xs sm:text-sm text-gray-500 m-1 sm:m-2">/ {{ $plan->duration_days }} days</div>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    {{-- Step 2: Payment Details (Image 2 Design) --}}
                    <div x-show="step === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" class="p-8 space-y-6">
                        
                        {{-- Selected Plan Summary --}}
                        <div class="bg-blue-50/50 border border-blue-100 rounded-2xl p-5 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-100">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <div>
                                    <p class="text-[10px] font-extrabold text-blue-400 uppercase tracking-widest leading-none mb-1">Applying Plan</p>
                                    <template x-if="selectedPlanId">
                                        <p class="text-base font-bold text-gray-900 capitalize" x-text="plans.find(p => p.id == selectedPlanId)?.name"></p>
                                    </template>
                                </div>
                            </div>
                            <div class="text-right">
                                <template x-if="selectedPlanId">
                                    <p class="text-xl font-black text-[#142D63]" x-text="'€' + number_format(plans.find(p => p.id == selectedPlanId)?.price, 2)"></p>
                                </template>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6">
                            {{-- Card Number --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Card number</label>
                                <div class="relative group">
                                    <div id="card-number" class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus-within:border-blue-600 focus-within:ring-4 focus-within:ring-blue-50 transition-all bg-white shadow-sm"></div>
                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 flex items-center gap-3 pointer-events-none transition-all">
                                        {{-- Brand Icons --}}
                                        <div class="flex items-center gap-3">
                                            {{-- Visa --}}
                                            <svg class="h-8 w-auto opacity-20 transition-all duration-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" id="visa" :class="cardBrand === 'visa' ? '!opacity-100 scale-125' : 'saturate-50'" viewBox="0 0 100 62">
                                                <path fill="#191E6E" d="M13.967 13.837c-.766 0-1.186-.105-1.831-.37l-.239-.109-.271 1.575c.466.192 1.306.357 2.175.37 2.041 0 3.375-.947 3.391-2.404.016-.801-.51-1.409-1.621-1.91-.674-.325-1.094-.543-1.094-.873 0-.292.359-.603 1.109-.603a3.602 3.602 0 0 1 1.455.269l.18.08.271-1.522-.047.01a5.053 5.053 0 0 0-1.74-.297c-1.92 0-3.275.954-3.285 2.321-.012 1.005.964 1.571 1.701 1.908.757.345 1.01.562 1.008.872-.005.471-.605.683-1.162.683zm8.461-5.655h-1.5c-.467 0-.816.125-1.021.583l-2.885 6.44h2.041l.408-1.054 2.49.002c.061.246.24 1.052.24 1.052H24l-1.572-7.023zM20.03 12.71l.774-1.963c-.01.02.16-.406.258-.67l.133.606.449 2.027H20.03zM8.444 15.149h1.944l1.215-7.026H9.66v-.002zM4.923 12.971l-.202-.976v.003l-.682-3.226c-.117-.447-.459-.579-.883-.595H.025L0 8.325c.705.165 1.34.404 1.908.697a.392.392 0 0 1 .18.234l1.68 5.939h2.054l3.061-7.013H6.824l-1.901 4.789z"></path>
                                            </svg>
                                            {{-- Mastercard --}}
                                            <svg class="h-6 w-auto opacity-20 transition-all duration-300" :class="cardBrand === 'mastercard' ? '!opacity-100 scale-125' : 'saturate-50'" viewBox="0 0 100 62">
                                                <circle cx="31" cy="31" r="31" fill="#EB001B"/>
                                                <circle cx="69" cy="31" r="31" fill="#F79E1B"/>
                                                <path d="M50 8.01c-6.23 6.08-10.12 14.56-10.12 23.99 0 9.43 3.89 17.91 10.12 23.99 6.23-6.08 10.12-14.56 10.12-23.99 0-9.43-3.89-17.91-10.12-23.99z" fill="#FF5F00"/>
                                            </svg>
                                            {{-- Amex --}}
                                            <svg class="h-6 w-auto opacity-20 transition-all duration-300" :class="cardBrand === 'amex' ? '!opacity-100 scale-125' : 'saturate-50'" viewBox="0 0 100 60">
                                                <rect width="100" height="60" rx="8" fill="#007BC1" />
                                                <text x="50" y="42" font-family="Arial, sans-serif" font-weight="900" font-size="28" fill="white" text-anchor="middle">AMEX</text>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                                {{-- Expiration --}}
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Expiration date</label>
                                    <div id="card-expiry" class="px-4 py-3.5 border border-gray-200 rounded-xl focus-within:border-blue-600 focus-within:ring-4 focus-within:ring-blue-50 transition-all bg-white shadow-sm"></div>
                                </div>
                                {{-- CVC --}}
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Security code</label>
                                    <div class="relative group">
                                        <div id="card-cvc" class="px-4 py-3.5 border border-gray-200 rounded-xl focus-within:border-blue-600 focus-within:ring-4 focus-within:ring-blue-50 transition-all bg-white shadow-sm"></div>
                                        <div class="absolute right-4 top-1/2 -translate-y-1/2 opacity-20 group-focus-within:opacity-40 transition-opacity">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                                {{-- Country --}}
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Country</label>
                                    <div class="relative group">
                                        <select class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:border-blue-600 focus:ring-4 focus:ring-blue-50 transition-all bg-white shadow-sm outline-none appearance-none font-medium text-gray-700">
                                            <option value="UK">United Kingdom</option>
                                            <option value="US">United States</option>
                                            <option value="DE">Germany</option>
                                            <option value="FR">France</option>
                                        </select>
                                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400 group-focus-within:text-blue-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                                        </div>
                                    </div>
                                </div>
                                {{-- Postal Code --}}
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Postal code</label>
                                    <input type="text" placeholder="WS11 1DB" class="w-full px-4 py-3.5 border border-gray-200 rounded-xl focus:border-blue-600 focus:ring-4 focus:ring-blue-50 transition-all bg-white shadow-sm outline-none font-medium text-gray-700 placeholder-gray-300">
                                </div>
                            </div>
                        </div>
                        <div id="card-errors" class="text-rose-600 text-[11px] font-extrabold uppercase tracking-tight"></div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="px-8 py-6 bg-gray-50 flex items-center justify-between border-t border-gray-100">
                        <button type="button" @click="step === 1 ? showModal = false : step = 1"
                            class="px-6 py-3 text-gray-500 font-bold hover:text-gray-900 transition-all rounded-xl hover:bg-gray-100">
                            <span x-text="step === 1 ? 'Cancel' : 'Back'"></span>
                        </button>
                        
                        <div class="flex gap-3">
                            <button type="button" x-show="step === 1" @click="if(selectedPlanId) step = 2"
                                class="px-10 py-3 bg-blue-600 text-white font-extrabold rounded-xl shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all transform hover:-translate-y-0.5 disabled:opacity-50 disabled:transform-none disabled:shadow-none"
                                :disabled="!selectedPlanId">
                                Continue
                            </button>
                            <button type="submit" x-show="step === 2"
                                class="px-10 py-3 bg-blue-600 text-white font-extrabold rounded-xl shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all flex items-center justify-center transform hover:-translate-y-0.5"
                                :disabled="loading">
                                <template x-if="!loading">
                                    <span>Confirm & Pay</span>
                                </template>
                                <template x-if="loading">
                                    <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg>
                                </template>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Success Modal --}}
        <div x-show="showSuccessModal" x-cloak x-transition.opacity
            class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/40 backdrop-blur-sm p-4" aria-modal="true"
            role="dialog">
            <div class="bg-white rounded-3xl shadow-2xl max-w-sm w-full p-10 text-center relative animate-celebration">
                <div class="w-20 h-20 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-500 mx-auto mb-6">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 class="text-2xl font-extrabold text-gray-900 mb-2">Subscription Updated!</h3>
                <p class="text-gray-500 font-medium mb-8">Your subscription has been updated successfully.</p>
                <button @click="showSuccessModal = false; window.location.reload();"
                    class="w-full py-4 bg-blue-600 text-white font-extrabold rounded-2xl shadow-xl shadow-blue-100 hover:bg-blue-700 transition-all transform hover:-translate-y-1">
                    Awesome!
                </button>
            </div>
        </div>

    </section>

    <script>
        function number_format(number, decimals) {
            return parseFloat(number).toLocaleString('en-US', {
                minimumFractionDigits: decimals,
                maximumFractionDigits: decimals
            });
        }

        function subscriptionForm() {
            return {
                stripe: null,
                elements: null,
                cardNumber: null,
                cardExpiry: null,
                cardCvc: null,
                loading: false,
                showToast: false,
                showModal: false,
                showSuccessModal: false,
                errorToast: '',
                step: 1,
                selectedPlanId: null,
                cardBrand: null,
                plans: @json($plans),

                init() {
                    this.stripe = Stripe("{{ config('services.stripe.key') }}");
                    this.elements = this.stripe.elements();
                    
                    const style = {
                        base: {
                            color: '#111827',
                            fontFamily: 'Inter, system-ui, sans-serif',
                            fontSmoothing: 'antialiased',
                            fontSize: '16px',
                            '::placeholder': { color: '#9ca3af' }
                        },
                        invalid: { color: '#ef4444', iconColor: '#ef4444' }
                    };

                    this.cardNumber = this.elements.create('cardNumber', { style });
                    this.cardExpiry = this.elements.create('cardExpiry', { style });
                    this.cardCvc = this.elements.create('cardCvc', { style });

                    this.cardNumber.mount('#card-number');
                    this.cardExpiry.mount('#card-expiry');
                    this.cardCvc.mount('#card-cvc');

                    this.cardNumber.on('change', event => {
                        this.cardBrand = event.brand;
                        document.getElementById('card-errors').textContent = event.error ? event.error.message : '';
                    });

                    [this.cardExpiry, this.cardCvc].forEach(element => {
                        element.on('change', event => {
                            document.getElementById('card-errors').textContent = event.error ? event.error.message : '';
                        });
                    });
                },

                async submitSubscription() {
                    this.loading = true;

                    if (!this.selectedPlanId) {
                        this.errorToast = 'Please select a plan.';
                        this.loading = false;
                        return;
                    }

                    try {
                        // Step 1: Create Payment Intent
                        const res = await fetch("{{ route('user.subscription.create-intent') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({
                                plan_id: this.selectedPlanId
                            })
                        });

                        if (!res.ok) {
                            this.errorToast = "Server error while creating payment intent.";
                            this.loading = false;
                            return;
                        }

                        const data = await res.json();

                        if (!data.clientSecret) {
                            this.errorToast = "Failed to create payment intent.";
                            this.loading = false;
                            return;
                        }

                        // Step 2: Confirm Payment
                        const result = await this.stripe.confirmCardPayment(data.clientSecret, {
                            payment_method: {
                                card: this.cardNumber,
                                billing_details: {
                                    name: "{{ auth()->user()->name }}",
                                    email: "{{ auth()->user()->email }}"
                                }
                            }
                        });

                        if (result.error) {
                            document.getElementById('card-errors').textContent = result.error.message;
                            this.loading = false;
                            return;
                        }

                        if (result.paymentIntent.status === 'succeeded') {
                            // Step 3: Save subscription
                            const saveRes = await fetch("{{ route('user.subscription.update') }}", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                },
                                body: JSON.stringify({
                                    payment_plan: this.selectedPlanId,
                                    payment_intent_id: result.paymentIntent.id
                                })
                            });

                            const saveData = await saveRes.json();

                            if (saveData.success) {
                                this.showModal = false;
                                this.showSuccessModal = true;
                            } else {
                                this.errorToast = "Subscription update failed.";
                                this.loading = false;
                            }
                        }

                    } catch (err) {
                        this.errorToast = "Unexpected error.";
                        this.loading = false;
                        console.error(err);
                    }
                }
            }
        }
    </script>

    <!-- Subscription Expiry Notification -->

    <script>
        function dismissNotification() {
            fetch('/subscription/dismiss-notification', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }).then(() => {
                document.getElementById('subscription-warning').style.display = 'none';
            }).catch(error => {
                console.error('Error dismissing notification:', error);
            });
        }

        // Auto-refresh notification every 5 minutes (optional)
        setInterval(function() {
            // Only refresh if user is authenticated
            if (document.querySelector('[data-user-authenticated]')) {
                location.reload();
            }
        }, 300000); // 5 minutes
    </script>
@endsection