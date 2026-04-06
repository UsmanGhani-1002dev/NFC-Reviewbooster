@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

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
                                    <i data-lucide="triangle-alert" class="w-6 h-6 text-white"></i>
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
                            <a href="{{ route('user.subscription.index') }}"
                               class="inline-flex items-center gap-2 bg-white text-red-600 hover:bg-red-50 font-bold px-5 py-2.5 rounded-xl text-sm shadow-md hover:shadow-lg transition-all duration-300 hover:-translate-y-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182"/></svg>
                                Renew Now
                            </a>
                            <button onclick="dismissSubscriptionWarning('subscription-warning')"
                                    class="p-2 text-white/60 hover:text-white hover:bg-white/10 rounded-lg transition-colors">
                                <i data-lucide="x" class="w-5 h-5"></i>
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
                                    <i data-lucide="clock" class="w-6 h-6 text-amber-900"></i>
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
                            <a href="{{ route('user.subscription.index') }}"
                               class="inline-flex items-center gap-2 bg-amber-900 text-white hover:bg-amber-800 font-bold px-5 py-2.5 rounded-xl text-sm shadow-md hover:shadow-lg transition-all duration-300 hover:-translate-y-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182"/></svg>
                                Renew Now
                            </a>
                            <button onclick="dismissSubscriptionWarning('subscription-warning')"
                                    class="p-2 text-amber-700/50 hover:text-amber-900 hover:bg-white/20 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif

    <div x-data="{ loading: true }" x-init="setTimeout(() => loading = false, 800)" class="bg-white shadow-2xl rounded-3xl md:p-10 p-6 border border-gray-100">
        <h1 class="text-2xl md:text-5xl font-extrabold text-gray-900 mb-4">Welcome to Your Dashboard 🎉</h1>
        <p class="text-lg text-gray-500 mb-10">Manage everything from one place with ease and style.</p>

        @if (Auth::check() && Auth::user()->role === 'admin')
            {{-- ═══════════════════ ADMIN DASHBOARD ═══════════════════ --}}

            {{-- ── KPI Stat Cards ── --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6 mb-10">
                {{-- Total Users --}}
                <a href="{{ route('admin.users.index') }}" class="admin-stat-card group relative block overflow-hidden bg-gradient-to-br from-blue-500 to-blue-700 text-white rounded-2xl p-5 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                    <div x-show="loading" class="absolute inset-0 bg-white/10 backdrop-blur-sm skeleton z-10"></div>
                    <div class="absolute -top-4 -right-4 w-20 h-20 bg-white/10 rounded-full blur-md group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="flex items-center gap-3 mb-3">
                        <div class="bg-white/20 p-2.5 rounded-xl backdrop-blur-sm">
                            <i data-lucide="users-2" class="w-6 h-6"></i>
                        </div>
                        <span class="text-sm font-medium text-blue-100 opacity-90">Total Users</span>
                    </div>
                    <div class="text-3xl md:text-4xl font-extrabold counter" data-target="{{ $adminStats['totalUsers'] ?? 0 }}">0</div>
                    <p class="text-xs text-blue-200 mt-1">+{{ $adminStats['newUsersThisWeek'] ?? 0 }} this week</p>
                </a>

                {{-- Business Owners --}}
                <a href="{{ route('admin.manage_business.index') }}" class="admin-stat-card group relative block overflow-hidden bg-gradient-to-br from-violet-500 to-purple-700 text-white rounded-2xl p-5 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                    <div x-show="loading" class="absolute inset-0 bg-white/10 backdrop-blur-sm skeleton z-10"></div>
                    <div class="absolute -top-4 -right-4 w-20 h-20 bg-white/10 rounded-full blur-md group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="flex items-center gap-3 mb-3">
                        <div class="bg-white/20 p-2.5 rounded-xl backdrop-blur-sm">
                            <i data-lucide="building-2" class="w-6 h-6"></i>
                        </div>
                        <span class="text-sm font-medium text-purple-100 opacity-90">Businesses</span>
                    </div>
                    <div class="text-3xl md:text-4xl font-extrabold counter" data-target="{{ $adminStats['totalBusinessOwners'] ?? 0 }}">0</div>
                    <p class="text-xs text-purple-200 mt-1">Registered owners</p>
                </a>

                {{-- Total Reviews --}}
                <a href="{{ route('admin.reviews') }}" class="admin-stat-card group relative block overflow-hidden bg-gradient-to-br from-emerald-500 to-teal-700 text-white rounded-2xl p-5 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                    <div x-show="loading" class="absolute inset-0 bg-white/10 backdrop-blur-sm skeleton z-10"></div>
                    <div class="absolute -top-4 -right-4 w-20 h-20 bg-white/10 rounded-full blur-md group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="flex items-center gap-3 mb-3">
                        <div class="bg-white/20 p-2.5 rounded-xl backdrop-blur-sm">
                            <i data-lucide="star" class="w-6 h-6"></i>
                        </div>
                        <span class="text-sm font-medium text-emerald-100 opacity-90">Total Reviews</span>
                    </div>
                    <div class="text-3xl md:text-4xl font-extrabold counter" data-target="{{ $adminStats['totalReviews'] ?? 0 }}">0</div>
                    <p class="text-xs text-emerald-200 mt-1">Platform-wide feedback</p>
                </a>

                {{-- Review Cards (Now Total Orders) --}}
                <a href="{{ route('admin.orders.index') }}" class="admin-stat-card group relative block overflow-hidden bg-gradient-to-br from-amber-500 to-orange-600 text-white rounded-2xl p-5 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                    <div x-show="loading" class="absolute inset-0 bg-white/10 backdrop-blur-sm skeleton z-10"></div>
                    <div class="absolute -top-4 -right-4 w-20 h-20 bg-white/10 rounded-full blur-md group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="flex items-center gap-3 mb-3">
                        <div class="bg-white/20 p-2.5 rounded-xl backdrop-blur-sm">
                            <i data-lucide="shopping-bag" class="w-6 h-6"></i>
                        </div>
                        <span class="text-sm font-medium text-amber-100 opacity-90">Total Orders</span>
                    </div>
                    <div class="text-3xl md:text-4xl font-extrabold counter" data-target="{{ $adminStats['totalOrders'] ?? 0 }}">0</div>
                    <p class="text-xs text-amber-200 mt-1">Platform-wide orders</p>
                </a>
            </div>

            {{-- ── Secondary Stats Row ── --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-6 mb-10">
                <a href="{{ route('admin.users.index') }}" class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow flex items-center gap-4 group">
                    <div class="bg-blue-50 p-3 rounded-xl group-hover:scale-110 transition-transform">
                        <i data-lucide="clock" class="w-6 h-6 text-blue-600"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">New Today</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $adminStats['newUsersToday'] ?? 0 }}</p>
                    </div>
                </a>
                <a href="{{ route('admin.manage-subscription.index') }}" class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow flex items-center gap-4 group">
                    <div class="bg-emerald-50 p-3 rounded-xl group-hover:scale-110 transition-transform">
                        <i data-lucide="check-circle" class="w-6 h-6 text-emerald-600"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Active Subscription</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $adminStats['activeSubscriptions'] ?? 0 }}</p>
                    </div>
                </a>
                <a href="{{ route('admin.contact-submissions.index') }}" class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow flex items-center gap-4 group">
                    <div class="bg-amber-50 p-3 rounded-xl group-hover:scale-110 transition-transform">
                        <i data-lucide="mail" class="w-6 h-6 text-amber-600"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Contact</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $adminStats['totalContacts'] ?? 0 }}</p>
                    </div>
                </a>
            </div>

            {{-- ── Quick Actions ── --}}
            <div class="mb-10">
                <h2 class="text-lg font-bold text-gray-700 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>
                    Quick Actions
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="{{ route('admin.reviews') }}" class="group flex items-center gap-4 bg-white border border-gray-100 rounded-2xl p-4 shadow-sm hover:shadow-lg hover:border-blue-200 transition-all duration-300 hover:-translate-y-0.5">
                        <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-3 rounded-xl text-white group-hover:scale-110 transition-transform duration-300">
                            <i data-lucide="message-square" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <span class="font-bold text-gray-800 text-sm">Manage Reviews</span>
                            <p class="text-xs text-gray-400">Moderate feedback</p>
                        </div>
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="group flex items-center gap-4 bg-white border border-gray-100 rounded-2xl p-4 shadow-sm hover:shadow-lg hover:border-purple-200 transition-all duration-300 hover:-translate-y-0.5">
                        <div class="bg-gradient-to-br from-purple-500 to-purple-600 p-3 rounded-xl text-white group-hover:scale-110 transition-transform duration-300">
                            <i data-lucide="users" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <span class="font-bold text-gray-800 text-sm">Manage Users</span>
                            <p class="text-xs text-gray-400">View & edit accounts</p>
                        </div>
                    </a>
                    <a href="{{ route('admin.manage-subscription.index') }}" class="group flex items-center gap-4 bg-white border border-gray-100 rounded-2xl p-4 shadow-sm hover:shadow-lg hover:border-indigo-200 transition-all duration-300 hover:-translate-y-0.5">
                        <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 p-3 rounded-xl text-white group-hover:scale-110 transition-transform duration-300">
                            <i data-lucide="credit-card" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <span class="font-bold text-gray-800 text-sm">Subscriptions</span>
                            <p class="text-xs text-gray-400">Plans & billing</p>
                        </div>
                    </a>
                    <a href="{{ route('admin.manage_business.index') }}" class="group flex items-center gap-4 bg-white border border-gray-100 rounded-2xl p-4 shadow-sm hover:shadow-lg hover:border-teal-200 transition-all duration-300 hover:-translate-y-0.5">
                        <div class="bg-gradient-to-br from-teal-500 to-teal-600 p-3 rounded-xl text-white group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.15c0 .415.336.75.75.75z"/></svg>
                        </div>
                        <div>
                            <span class="font-bold text-gray-800 text-sm">Businesses</span>
                            <p class="text-xs text-gray-400">Manage businesses</p>
                        </div>
                    </a>
                </div>
            </div>

            {{-- ── Main Content: 3 Panels ── --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Panel 1: Latest Registered Users --}}
                <div class="lg:col-span-1 bg-white shadow-lg rounded-2xl border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="px-5 py-4 border-b border-gray-50 bg-gray-50/50 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="bg-blue-100 p-1.5 rounded-lg">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                            </div>
                            <h3 class="font-bold text-gray-800 text-sm">Latest Users</h3>
                        </div>
                        <a href="{{ route('admin.users.index') }}" class="text-xs text-blue-600 font-semibold hover:text-blue-700">View All →</a>
                    </div>
                    <div class="p-4 space-y-2">
                        @forelse ($latestUsers as $user)
                            <a href="{{ route('admin.users.index', ['search' => $user->email]) }}" class="flex items-center gap-3 p-2.5 rounded-xl bg-gray-50/80 hover:bg-blue-50/50 transition-colors duration-200 group block">
                                <div class="w-9 h-9 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-xs shrink-0">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="font-semibold text-gray-800 text-sm truncate">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-400 truncate">{{ $user->email }}</div>
                                </div>
                                <div class="text-right shrink-0">
                                    @php
                                        $planName = $user->subscription && $user->subscription->plan ? $user->subscription->plan->name : 'No Plan';
                                    @endphp
                                    <span class="inline-block px-2 py-0.5 rounded-full text-white text-[10px] font-bold capitalize
                                        {{ strtolower($planName) == 'premium' ? 'bg-yellow-500' : (strtolower($planName) == 'standard' ? 'bg-blue-500' : (strtolower($planName) == 'basic' ? 'bg-green-500' : 'bg-gray-400')) }}">
                                        {{ $planName }}
                                    </span>
                                    <div class="text-[10px] text-gray-400 mt-0.5">{{ $user->created_at->format('M d') }}</div>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-10 text-gray-400">
                                <svg class="w-10 h-10 mx-auto mb-2 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                                <p class="text-sm">No users registered yet</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Panel 2: Top Business Owners (uses $usersWithCardStats) --}}
                <div class="lg:col-span-1 bg-white shadow-lg rounded-2xl border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="px-5 py-4 border-b border-gray-50 bg-gray-50/50 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="bg-purple-100 p-1.5 rounded-lg">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M18.75 4.236c.982.143 1.954.317 2.916.52A6.003 6.003 0 0016.27 9.728M18.75 4.236V4.5c0 2.108-.966 3.99-2.48 5.228m0 0a6.023 6.023 0 01-3.52 1.397m0 0a6.023 6.023 0 01-3.52-1.397"/></svg>
                            </div>
                            <h3 class="font-bold text-gray-800 text-sm">Top Business Owners</h3>
                        </div>
                        <a href="{{ route('admin.manage_business.index') }}" class="text-xs text-purple-600 font-semibold hover:text-purple-700">View All →</a>
                    </div>
                    <div class="p-4 space-y-2">
                        @forelse ($usersWithCardStats->sortByDesc(fn($u) => $u->cards->count())->take(5) as $bizUser)
                            <a href="{{ route('admin.users.index', ['search' => $bizUser->email]) }}" class="flex items-center gap-3 p-2.5 rounded-xl bg-gray-50/80 hover:bg-purple-50/50 transition-colors duration-200 group block">
                                <div class="w-9 h-9 bg-gradient-to-br from-violet-500 to-fuchsia-600 rounded-full flex items-center justify-center text-white font-bold text-xs shrink-0">
                                    {{ strtoupper(substr($bizUser->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="font-semibold text-gray-800 text-sm truncate">{{ $bizUser->name }}</div>
                                    <div class="text-xs text-gray-400 truncate">{{ $bizUser->email }}</div>
                                </div>
                                <div class="shrink-0 flex items-center gap-1.5 bg-purple-50 px-2.5 py-1 rounded-full group-hover:bg-purple-100 transition-colors">
                                    <svg class="w-3.5 h-3.5 text-purple-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                                    <span class="text-xs font-bold text-purple-700">{{ $bizUser->cards->count() }}</span>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-10 text-gray-400">
                                <svg class="w-10 h-10 mx-auto mb-2 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75"/></svg>
                                <p class="text-sm">No businesses with cards yet</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Panel 3: Recent Contact Requests --}}
                <div class="lg:col-span-1 bg-white shadow-lg rounded-2xl border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="px-5 py-4 border-b border-gray-50 bg-gray-50/50 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="bg-amber-100 p-1.5 rounded-lg">
                                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                            </div>
                            <h3 class="font-bold text-gray-800 text-sm">Recent Contacts</h3>
                        </div>
                        <a href="{{ route('admin.contact-submissions.index') }}" class="text-xs text-amber-600 font-semibold hover:text-amber-700">View All →</a>
                    </div>
                    <div class="p-4 space-y-2">
                        @forelse ($recentContacts as $contact)
                            <a href="{{ route('admin.contact-submissions.view', ['id' => $contact->id]) }}"
                               class="flex items-center gap-3 p-2.5 rounded-xl bg-gray-50/80 hover:bg-amber-50/50 transition-colors duration-200 group block">
                                <div class="w-9 h-9 bg-gradient-to-br from-amber-400 to-orange-500 rounded-full flex items-center justify-center text-white font-bold text-xs shrink-0">
                                    {{ strtoupper(substr($contact->first_name, 0, 1)) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="font-semibold text-gray-800 text-sm truncate">{{ $contact->first_name }} {{ $contact->last_name }}</div>
                                    <div class="text-xs text-gray-400 truncate">{{ $contact->email }}</div>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700 capitalize">{{ $contact->inquiry_type }}</span>
                                    <div class="text-[10px] text-gray-400 mt-0.5">{{ $contact->created_at->diffForHumans(null, true, true) }}</div>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-10 text-gray-400">
                                <svg class="w-10 h-10 mx-auto mb-2 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                                <p class="text-sm">No contact messages yet</p>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        @else
            {{-- ═══════════════════ BUSINESS OWNER DASHBOARD ═══════════════════ --}}

            {{-- ── KPI Stat Cards ── --}}
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-10">
                {{-- Total Reviews --}}
                <a href="{{ route('business.reviews') }}" class="admin-stat-card group relative block overflow-hidden bg-gradient-to-br from-blue-500 to-blue-700 text-white rounded-2xl p-5 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                    <div x-show="loading" class="absolute inset-0 bg-white/10 backdrop-blur-sm skeleton z-10"></div>
                    <div class="absolute -top-4 -right-4 w-20 h-20 bg-white/10 rounded-full blur-md group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="flex items-center gap-3 mb-3">
                        <div class="bg-white/20 p-2.5 rounded-xl backdrop-blur-sm">
                            <i data-lucide="star" class="w-6 h-6"></i>
                        </div>
                        <span class="text-sm font-medium text-blue-100 opacity-90">Total Reviews</span>
                    </div>
                    <div class="text-3xl md:text-4xl font-extrabold counter" data-target="{{ $totalReviewsAllTime }}">0</div>
                    <p class="text-xs text-blue-200 mt-1">All time feedback</p>
                </a>

                {{-- Positive Reviews --}}
                <a href="{{ route('business.reviews') }}" class="admin-stat-card group relative block overflow-hidden bg-gradient-to-br from-emerald-500 to-teal-700 text-white rounded-2xl p-5 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                    <div x-show="loading" class="absolute inset-0 bg-white/10 backdrop-blur-sm skeleton z-10"></div>
                    <div class="absolute -top-4 -right-4 w-20 h-20 bg-white/10 rounded-full blur-md group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="flex items-center gap-3 mb-3">
                        <div class="bg-white/20 p-2.5 rounded-xl backdrop-blur-sm">
                            <i data-lucide="smile" class="w-6 h-6"></i>
                        </div>
                        <span class="text-sm font-medium text-emerald-100 opacity-90">Positive</span>
                    </div>
                    <div class="text-3xl md:text-4xl font-extrabold counter" data-target="{{ $positiveReviews }}">0</div>
                    <p class="text-xs text-emerald-100 mt-1">This month</p>
                </a>

                {{-- Negative Reviews --}}
                <a href="{{ route('business.reviews.feedback') }}" class="admin-stat-card group relative block overflow-hidden bg-gradient-to-br from-rose-500 to-red-700 text-white rounded-2xl p-5 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                    <div x-show="loading" class="absolute inset-0 bg-white/10 backdrop-blur-sm skeleton z-10"></div>
                    <div class="absolute -top-4 -right-4 w-20 h-20 bg-white/10 rounded-full blur-md group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="flex items-center gap-3 mb-3">
                        <div class="bg-white/20 p-2.5 rounded-xl backdrop-blur-sm">
                            <i data-lucide="frown" class="w-6 h-6"></i>
                        </div>
                        <span class="text-sm font-medium text-rose-100 opacity-90">Negative</span>
                    </div>
                    <div class="text-3xl md:text-4xl font-extrabold counter" data-target="{{ $negativeReviews }}">0</div>
                    <p class="text-xs text-rose-100 mt-1">This month</p>
                </a>

                {{-- Total Cards --}}
                <a href="{{ route('cards.index') }}" class="admin-stat-card group relative block overflow-hidden bg-gradient-to-br from-violet-500 to-purple-700 text-white rounded-2xl p-5 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                    <div x-show="loading" class="absolute inset-0 bg-white/10 backdrop-blur-sm skeleton z-10"></div>
                    <div class="absolute -top-4 -right-4 w-20 h-20 bg-white/10 rounded-full blur-md group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="flex items-center gap-3 mb-3">
                        <div class="bg-white/20 p-2.5 rounded-xl backdrop-blur-sm">
                            <i data-lucide="credit-card" class="w-6 h-6"></i>
                        </div>
                        <span class="text-sm font-medium text-violet-100 opacity-90">Review Cards</span>
                    </div>
                    <div class="text-3xl md:text-4xl font-extrabold counter" data-target="{{ $totalCards }}">0</div>
                    <p class="text-xs text-violet-100 mt-1">NFC & QR cards</p>
                </a>
            </div>

            {{-- ── Quick Actions ── --}}
            <div class="mb-10">
                <h2 class="text-lg font-bold text-gray-700 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>
                    Quick Actions
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="{{ route('cards.create') }}" class="group flex items-center gap-4 bg-white border border-gray-100 rounded-2xl p-4 shadow-sm hover:shadow-lg hover:border-blue-200 transition-all duration-300 hover:-translate-y-0.5">
                        <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-3 rounded-xl text-white group-hover:scale-110 transition-transform duration-300">
                            <i data-lucide="plus" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <span class="font-bold text-gray-800 text-sm">Create Card</span>
                            <p class="text-xs text-gray-400">New NFC / QR card</p>
                        </div>
                    </a>
                    <a href="{{ route('cards.index') }}" class="group flex items-center gap-4 bg-white border border-gray-100 rounded-2xl p-4 shadow-sm hover:shadow-lg hover:border-purple-200 transition-all duration-300 hover:-translate-y-0.5">
                        <div class="bg-gradient-to-br from-purple-500 to-purple-600 p-3 rounded-xl text-white group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                        </div>
                        <div>
                            <span class="font-bold text-gray-800 text-sm">View Cards</span>
                            <p class="text-xs text-gray-400">Manage your cards</p>
                        </div>
                    </a>
                    <a href="{{ route('business.reviews') }}" class="group flex items-center gap-4 bg-white border border-gray-100 rounded-2xl p-4 shadow-sm hover:shadow-lg hover:border-indigo-200 transition-all duration-300 hover:-translate-y-0.5">
                        <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 p-3 rounded-xl text-white group-hover:scale-110 transition-transform duration-300">
                            <i data-lucide="message-square" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <span class="font-bold text-gray-800 text-sm">Manage Reviews</span>
                            <p class="text-xs text-gray-400">Track customer feedback</p>
                        </div>
                    </a>
                    <a href="{{ route('businesses.index') }}" class="group flex items-center gap-4 bg-white border border-gray-100 rounded-2xl p-4 shadow-sm hover:shadow-lg hover:border-teal-200 transition-all duration-300 hover:-translate-y-0.5">
                        <div class="bg-gradient-to-br from-teal-500 to-teal-600 p-3 rounded-xl text-white group-hover:scale-110 transition-transform duration-300">
                            <i data-lucide="building" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <span class="font-bold text-gray-800 text-sm">My Business</span>
                            <p class="text-xs text-gray-400">Manage business info</p>
                        </div>
                    </a>
                    <a href="{{ route('dashboard.report') }}" class="group flex items-center gap-4 bg-white border border-gray-100 rounded-2xl p-4 shadow-sm hover:shadow-lg hover:border-rose-200 transition-all duration-300 hover:-translate-y-0.5">
                        <div class="bg-gradient-to-br from-rose-500 to-rose-600 p-3 rounded-xl text-white group-hover:scale-110 transition-transform duration-300">
                            <i data-lucide="file-text" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <span class="font-bold text-gray-800 text-sm">Analytics Report</span>
                            <p class="text-xs text-gray-400">Download PDF report</p>
                        </div>
                    </a>
                </div>
            </div>

            {{-- ── Charts Row ── --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                {{-- Review Growth Chart --}}
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="px-5 py-4 border-b border-gray-50 bg-gray-50/50 flex items-center gap-2">
                        <div class="bg-blue-100 p-1.5 rounded-lg">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941"/></svg>
                        </div>
                        <h3 class="font-bold text-gray-800 text-sm">Review Growth</h3>
                    </div>
                    <div class="p-5">
                        <div class="flex items-center justify-center min-h-[280px]">
                            @if(empty($chartData) || array_sum($chartData) == 0)
                                <div class="text-center space-y-4">
                                    <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mx-auto">
                                        <svg class="w-8 h-8 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                                    </div>
                                    <p class="text-gray-400 font-medium text-sm">No review data yet</p>
                                </div>
                            @else
                                <canvas id="growthChart"></canvas>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Review Distribution Chart --}}
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="px-5 py-4 border-b border-gray-50 bg-gray-50/50 flex items-center gap-2">
                        <div class="bg-teal-100 p-1.5 rounded-lg">
                            <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z"/><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z"/></svg>
                        </div>
                        <h3 class="font-bold text-gray-800 text-sm">Review Distribution</h3>
                    </div>
                    <div class="p-5">
                        <div class="flex items-center justify-center min-h-[280px]">
                            @if(empty($reviewStats) || array_sum($reviewStats) == 0)
                                <div class="text-center space-y-4">
                                    <div class="w-16 h-16 bg-teal-50 rounded-full flex items-center justify-center mx-auto">
                                        <svg class="w-8 h-8 text-teal-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <p class="text-gray-400 font-medium text-sm">Waiting for feedback...</p>
                                </div>
                            @else
                                <div class="w-full max-w-[280px]">
                                    <canvas id="typeChart"></canvas>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Bottom Panels: Active Cards, Latest Feedback, Top Cards ── --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 pb-6">

                {{-- Panel 1: Active Cards --}}
                <div class="lg:col-span-1 bg-white shadow-lg rounded-2xl border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="px-5 py-4 border-b border-gray-50 bg-gray-50/50 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="bg-blue-100 p-1.5 rounded-lg">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                            </div>
                            <h3 class="font-bold text-gray-800 text-sm">Active Cards</h3>
                        </div>
                        <a href="{{ route('cards.index') }}" class="text-xs text-blue-600 font-semibold hover:text-blue-700">View All →</a>
                    </div>
                    <div class="p-4 space-y-2">
                        @forelse($userCards->take(5) as $card)
                            <div class="flex items-center gap-3 p-2.5 rounded-xl bg-gray-50/80 hover:bg-blue-50/50 transition-colors duration-200 group">
                                <div class="w-9 h-9 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold text-xs shrink-0 group-hover:scale-110 transition-transform">
                                    {{ substr($card->name, 0, 1) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="font-semibold text-gray-800 text-sm truncate">{{ $card->name }}</div>
                                    <div class="text-xs text-gray-400 capitalize">{{ str_replace('_', ' ', $card->type) }}</div>
                                </div>
                                <div class="flex items-center gap-1.5 shrink-0">
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-green-50 text-green-700 border border-green-100">Active</span>
                                    <a href="{{ route('cards.edit', $card) }}" class="p-1.5 text-gray-400 hover:text-blue-600 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10 text-gray-400">
                                <div class="w-14 h-14 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 border-2 border-dashed border-gray-200">
                                    <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                </div>
                                <p class="text-sm font-medium">No cards yet</p>
                                <a href="{{ route('cards.create') }}" class="inline-block mt-2 text-xs font-bold text-blue-600 hover:text-blue-700">Create your first card →</a>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Panel 2: Latest Feedback --}}
                <div class="lg:col-span-1 bg-white shadow-lg rounded-2xl border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="px-5 py-4 border-b border-gray-50 bg-gray-50/50 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="bg-indigo-100 p-1.5 rounded-lg">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.076-4.076a1.526 1.526 0 011.037-.443 48.282 48.282 0 005.68-.494c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"/></svg>
                            </div>
                            <h3 class="font-bold text-gray-800 text-sm">Latest Feedback</h3>
                        </div>
                        <a href="{{ route('business.reviews') }}" class="text-xs text-indigo-600 font-semibold hover:text-indigo-700">Explore →</a>
                    </div>
                    <div class="p-4 space-y-2">
                        @forelse($reviews->take(5) as $review)
                            <div class="p-2.5 rounded-xl bg-gray-50/80 hover:bg-indigo-50/50 transition-colors duration-200">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="font-semibold text-sm text-gray-800 truncate">{{ $review->name ?? 'Anonymous' }}</span>
                                    <div class="flex text-yellow-400 text-xs shrink-0 ml-2">
                                        {!! str_repeat('★', $review->rating) !!}{!! str_repeat('☆', 5 - $review->rating) !!}
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 line-clamp-2">{{ $review->review_content }}</p>
                                <p class="text-[10px] text-gray-400 mt-1">{{ $review->created_at->diffForHumans() }}</p>
                            </div>
                        @empty
                            <div class="text-center py-10 text-gray-400">
                                <div class="w-14 h-14 bg-indigo-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-7 h-7 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                                </div>
                                <p class="text-sm font-medium">No reviews yet</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Panel 3: Staff Performance Leaderboard --}}
                <div class="lg:col-span-1 bg-white shadow-lg rounded-2xl border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="px-5 py-4 border-b border-gray-50 bg-gray-50/50 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="bg-amber-100 p-1.5 rounded-lg">
                                <i data-lucide="trophy" class="w-4 h-4 text-amber-600"></i>
                            </div>
                            <h3 class="font-bold text-gray-800 text-sm">Staff Leaderboard</h3>
                        </div>
                    </div>
                    <div class="p-4 space-y-3">
                        @forelse($reviewsGroupedByCard->sortByDesc('avg_rating') as $index => $cardGroup)
                            <div class="relative flex items-center gap-4 p-3 rounded-2xl bg-gradient-to-r {{ $index === 0 ? 'from-amber-50 to-orange-50 border border-amber-100' : 'from-gray-50 to-white border border-gray-100' }} transition-all duration-300 ">
                                {{-- Medal/Rank --}}
                                <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-xl {{ $index === 0 ? 'bg-amber-400' : ($index === 1 ? 'bg-gray-300' : ($index === 2 ? 'bg-orange-300' : 'bg-blue-100')) }} text-white font-black shadow-sm">
                                    @if($index < 3)
                                        <i data-lucide="{{ $index === 0 ? 'crown' : 'medal' }}" class="w-6 h-6"></i>
                                    @else
                                        {{ $index + 1 }}
                                    @endif
                                </div>

                                <div class="min-w-0 flex-1">
                                    <div class="font-bold text-gray-900 text-sm truncate">{{ $cardGroup['name'] }}</div>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <div class="flex text-amber-400">
                                            <i data-lucide="star" class="w-3 h-3 fill-current"></i>
                                        </div>
                                        <span class="text-xs font-bold text-gray-700">{{ $cardGroup['avg_rating'] }}</span>
                                        <span class="text-[10px] text-gray-400 capitalize">• {{ $cardGroup['review_count'] }} Reviews</span>
                                    </div>
                                </div>
                                
                                {{-- XP Bar integration --}}
                                <div class="hidden sm:block w-12 text-right">
                                    <div class="text-[10px] font-black text-amber-600 uppercase tracking-tighter">Top Pro</div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10 text-gray-400">
                                <i data-lucide="award" class="w-12 h-12 mx-auto mb-2 opacity-20"></i>
                                <p class="text-sm font-medium">No performance data yet</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        @endif

        {{-- ── Real Time Live Activity Feed Row (Global) ── --}}
        <div class="mt-8 border-t border-gray-50 pt-10">
            <h2 class="text-lg font-bold text-gray-700 mb-5 flex items-center gap-2">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                </span>
                Live Activity Feed
            </h2>
            <div class="bg-gray-50/50 rounded-3xl border border-gray-100 overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 divide-y md:divide-y-0 md:divide-x divide-gray-100/50">
                    @forelse($liveFeed as $activity)
                        <div class="p-6 transition-all duration-300 hover:bg-white group">
                            <div class="flex items-start gap-4">
                                <div class="bg-{{ $activity['color'] }}-100 p-2.5 rounded-2xl group-hover:scale-110 transition-transform duration-300 shrink-0">
                                    <i data-lucide="{{ $activity['icon'] }}" class="w-5 h-5 text-{{ $activity['color'] }}-600"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center justify-between mb-1 gap-2">
                                        <h4 class="font-bold text-gray-900 text-sm truncate">{{ $activity['title'] }}</h4>
                                        <span class="text-[10px] font-medium text-gray-400 whitespace-nowrap shrink-0">{{ $activity['time'] }}</span>
                                    </div>
                                    <p class="text-xs text-gray-500 leading-relaxed line-clamp-2">
                                        {{ $activity['description'] }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-4 p-8 text-center text-gray-400 italic">
                            No recent activity found.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script>
        function dismissSubscriptionWarning(elementId) {
            document.getElementById(elementId).style.display = 'none';
            // Send AJAX request to store dismissal in session
            fetch("{{ route('dashboard.dismiss-warning') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).catch(error => console.error('Error dismissing warning:', error));
        }

        document.addEventListener('DOMContentLoaded', function() {
            // ── Animated Counters for Admin KPIs ──
            document.querySelectorAll('.counter').forEach(counter => {
                const target = +counter.getAttribute('data-target');
                if (target === 0) return;
                const duration = 1200;
                const start = performance.now();
                const step = (now) => {
                    const progress = Math.min((now - start) / duration, 1);
                    const eased = 1 - Math.pow(1 - progress, 3); // easeOutCubic
                    counter.textContent = Math.floor(eased * target).toLocaleString();
                    if (progress < 1) requestAnimationFrame(step);
                    else counter.textContent = target.toLocaleString();
                };
                requestAnimationFrame(step);
            });

            // ── Stagger-in animation for stat cards ──
            document.querySelectorAll('.admin-stat-card').forEach((card, i) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 100 + i * 120);
            });

            // Growth Chart
            const growthChartEl = document.getElementById('growthChart');
            if (growthChartEl) {
                const growthCtx = growthChartEl.getContext('2d');
                new Chart(growthCtx, {
                    type: 'line',
                    data: {
                        labels: @json($chartLabels),
                        datasets: [{
                            label: 'Reviews',
                            data: @json($chartData),
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.05)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#3b82f6',
                            pointBorderColor: '#fff',
                            pointHoverRadius: 6,
                            pointRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(17, 24, 39, 0.9)',
                                padding: 12,
                                cornerRadius: 8,
                                titleFont: { size: 13, weight: 'bold' },
                                bodyFont: { size: 12 }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { stepSize: 1, color: '#9ca3af' },
                                grid: { color: 'rgba(243, 244, 246, 0.6)', drawBorder: false }
                            },
                            x: {
                                ticks: { color: '#9ca3af' },
                                grid: { display: false }
                            }
                        }
                    }
                });
            }

            // Distribution Chart
            const typeChartEl = document.getElementById('typeChart');
            if (typeChartEl) {
                const typeCtx = typeChartEl.getContext('2d');
                new Chart(typeCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Positive', 'Negative'],
                        datasets: [{
                            data: @json($reviewStats),
                            backgroundColor: ['#10b981', '#ef4444'],
                            hoverOffset: 4,
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '75%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 20,
                                    usePointStyle: true,
                                    font: { size: 12, weight: 'bold' }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
  

    <style>
        .pulse-alert-red {
            animation: ambulanceRed 1s steps(1, start) infinite;
        }

        .pulse-alert-yellow {
            animation: ambulanceYellow 1s steps(1, start) infinite;
        }

        .celebration-animation {
            animation: celebrate 0.6s ease-in-out;
        }

        @keyframes ambulanceRed {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.2;
            }
        }

        @keyframes ambulanceYellow {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.2;
            }
        }

        @keyframes celebrate {

            0%,
            100% {
                transform: scale(1);
            }

            25% {
                transform: scale(1.05) rotate(1deg);
            }

            75% {
                transform: scale(1.05) rotate(-1deg);
            }
        }

        .slide-in-right {
            animation: slideInRight 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        @keyframes slideInRight {
            0% {
                transform: translateX(100%);
                opacity: 0;
            }

            100% {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>
@endsection