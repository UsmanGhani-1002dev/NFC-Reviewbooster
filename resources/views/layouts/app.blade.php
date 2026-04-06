<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- SEO: Robots (Dashboard) -->
    <meta name="robots" content="noindex, nofollow">

    <link rel="icon" href="{{ asset('images/tap fevicon.png') }}" type="image/x-icon">
    
    @laravelPWA

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <!-- <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script> -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    

      <style>
        .pulse-notification {
            animation: pulseNotification 2s infinite;
        }

        @keyframes pulseNotification {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }

        .notification-item-enter {
            animation: slideInNotification 0.3s ease-out;
        }

        @keyframes slideInNotification {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
    <style>
        .pulse-alert-red {
            animation: pulseRed 1s infinite;
            color: #ef4444;
            /* Tailwind red-500 */
        }

        .pulse-alert-yellow {
            animation: pulseYellow 1s infinite;
            color: #facc15;
            /* Tailwind yellow-400 */
        }

        @keyframes pulseRed {

            0%,
            100% {
                transform: scale(1);
                opacity: 1;
                filter: drop-shadow(0 0 5px #ef4444);
            }

            50% {
                transform: scale(1.3);
                opacity: 0.7;
                filter: drop-shadow(0 0 15px #ef4444);
            }
        }

        @keyframes pulseYellow {

            0%,
            100% {
                transform: scale(1);
                opacity: 1;
                filter: drop-shadow(0 0 5px #facc15);
            }

            50% {
                transform: scale(1.3);
                opacity: 0.7;
                filter: drop-shadow(0 0 15px #facc15);
            }
        }

        @media (max-width: 640px) {
            #notification-dropdown {
                position: fixed !important;
                top: 64px !important;
                left: 1rem !important;
                right: 1rem !important;
                width: auto !important;
                max-width: none !important;
            }
        }
    </style>

</head>

<body class="font-sans antialiased bg-gray-50" x-data="{ }">
    <x-cart-drawer />
    <div id="notification-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <div class="flex min-h-screen">
        
        <!-- Sidebar: Only for Admin -->
        @auth
            @if (Auth::user()->role === 'admin')
                <x-sidebar />
            @elseif(Auth::user()->role === 'bussiness_owner')
                <x-usersidebar />
            @endif
        @endauth

        <!-- Main Content Wrapper -->
        <div class="flex flex-col flex-1 lg:ml-64">

            <!-- Top Bar -->
            <nav class="bg-white border-b border-gray-200 shadow-sm">
                <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-end">
                    @auth
                        <div class="flex items-center space-x-4">
                            <!-- Notification Bell -->
                            @auth
                                <div class="flex items-center space-x-2">
                                    <!-- Cart Icon (Triggers Drawer) -->
                                    <button @click="$dispatch('open-cart')" class="p-2 text-gray-400 hover:text-blue-600 transition-colors rounded-full hover:bg-gray-100 relative" title="Open Cart">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <span class="cart-count-badge absolute top-1 right-1 bg-[#007cb5] text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center shadow-sm" style="display: none;">0</span>
                                    </button>

                                    <div class="relative">
                                        <!-- Bell Icon -->
                                        <button onclick="toggleNotificationDropdown()" class="relative p-2 text-gray-400 hover:text-blue-600 transition-colors focus:outline-none rounded-full hover:bg-gray-100">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 7.165 6 9.388 6 12v2.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                        </svg>
                                        <span id="notification-badge"
                                            class="absolute top-1 right-1 w-4 h-4 text-[10px] font-bold text-white bg-red-500 rounded-full flex items-center justify-center hidden border-2 border-white">
                                        </span>
                                    </button>

                                    <!-- Dropdown -->
                                    <div id="notification-dropdown"
                                         class="absolute right-0 mt-2 w-80 sm:w-96 bg-white shadow-lg rounded-lg border border-gray-200 hidden z-50 max-h-[80vh] flex flex-col" style="max-width: calc(100vw - 2rem);">
                                        <div class="flex items-center justify-between px-4 py-2 border-b">
                                            <span class="text-sm font-semibold text-gray-700">Notifications</span>
                                            <button onclick="clearAllNotifications()"
                                                class="text-xs text-red-500 hover:text-red-700">Clear All</button>
                                        </div>
                                        <div id="notifications-list" class="overflow-y-auto flex-1" style="max-height: 60vh;">
                                            <!-- JS will inject items here -->
                                        </div>
                                    </div>
                                </div>
                            @endauth
                            <!-- Business Switcher -->
                            @if (Auth::user()->role === 'bussiness_owner')
                                @php
                                    $allBusinesses = Auth::user()->businesses;
                                    $activeBusinessId = session('active_business_id');
                                    $activeBusiness = $activeBusinessId ? $allBusinesses->where('id', $activeBusinessId)->first() : null;
                                @endphp
                                
                                @if($allBusinesses->count() > 0)
                                    <div class="relative mr-2">
                                        <button onclick="document.getElementById('business-dropdown').classList.toggle('hidden')"
                                            class="flex items-center space-x-2 px-3 py-1.5 bg-gray-50 hover:bg-gray-100 rounded-xl transition-all border border-gray-100 focus:outline-none group">
                                            <div class="w-8 h-8 {{ $activeBusiness ? 'bg-indigo-50 text-indigo-600' : 'bg-indigo-600 text-white' }} rounded-lg flex items-center justify-center font-bold text-xs uppercase shadow-sm transition-colors">
                                                @if($activeBusiness)
                                                    {{ substr($activeBusiness->business_name, 0, 1) }}
                                                @else
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                                                @endif
                                            </div>
                                            <div class="text-left hidden sm:block">
                                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider leading-none">View Mode</p>
                                                <p class="text-xs font-bold text-gray-700 truncate max-w-[120px]">
                                                    {{ $activeBusiness->business_name ?? 'All Businesses' }}
                                                </p>
                                            </div>
                                            <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4 4 4-4" />
                                            </svg>
                                        </button>

                                        <!-- Dropdown -->
                                        <div id="business-dropdown" class="hidden absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-xl border border-gray-100 z-50 overflow-hidden">
                                            <div class="p-3 border-b border-gray-50 flex items-center justify-between bg-gray-50/50">
                                                <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Your Businesses</span>
                                                <a href="{{ route('businesses.create') }}" class="text-[10px] font-bold text-blue-600 hover:text-blue-700 transition-colors">+ NEW</a>
                                            </div>
                                            <div class="max-h-60 overflow-y-auto custom-scrollbar">
                                                <!-- All Businesses Option -->
                                                <form action="{{ route('businesses.switch') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="business_id" value="all">
                                                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-all text-left group border-b border-gray-50">
                                                        <div class="w-8 h-8 {{ !$activeBusinessId ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-500' }} rounded-lg flex items-center justify-center font-bold text-xs uppercase group-hover:bg-indigo-600 group-hover:text-white transition-all">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <p class="text-xs font-bold {{ !$activeBusinessId ? 'text-gray-900' : 'text-gray-600' }} truncate">All Businesses</p>
                                                            <p class="text-[9px] text-gray-400 font-medium">Show combined data</p>
                                                        </div>
                                                        @if(!$activeBusinessId)
                                                            <div class="w-1.5 h-1.5 bg-indigo-600 rounded-full"></div>
                                                        @endif
                                                    </button>
                                                </form>

                                                @foreach($allBusinesses as $business)
                                                    <form action="{{ route('businesses.switch') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="business_id" value="{{ $business->id }}">
                                                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 hover:bg-blue-50 transition-all text-left group">
                                                            <div class="w-8 h-8 {{ $activeBusinessId == $business->id ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-500' }} rounded-lg flex items-center justify-center font-bold text-xs uppercase group-hover:bg-blue-600 group-hover:text-white transition-all">
                                                                {{ substr($business->business_name, 0, 1) }}
                                                            </div>
                                                            <div class="flex-1 min-w-0">
                                                                <p class="text-xs font-bold {{ $activeBusinessId == $business->id ? 'text-gray-900' : 'text-gray-600' }} truncate">{{ $business->business_name }}</p>
                                                                <p class="text-[9px] text-gray-400 font-medium">{{ $business->cards_count ?? $business->cards()->count() }} Cards Linked</p>
                                                            </div>
                                                            @if($activeBusinessId == $business->id)
                                                                <div class="w-1.5 h-1.5 bg-blue-600 rounded-full"></div>
                                                            @endif
                                                        </button>
                                                    </form>
                                                @endforeach
                                            </div>
                                            <div class="p-2 bg-gray-50 border-t border-gray-100">
                                                <a href="{{ route('businesses.index') }}" class="block w-full py-2 text-center text-[11px] font-bold text-gray-500 hover:text-blue-600 transition-colors uppercase tracking-widest">Manage All</a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                            
                            <!-- User Dropdown -->
                            <div class="relative">
                                <button onclick="document.getElementById('user-dropdown').classList.toggle('hidden')"
                                    class="flex items-center space-x-2 focus:outline-none">
                                    <div
                                        class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center uppercase text-sm">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                    <span class="text-gray-700 text-sm hidden sm:block">
                                        {{ Auth::user()->name }}
                                    </span>
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <!-- Dropdown -->
                                <div id="user-dropdown"
                                    class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg border z-50">
                                    <a href="{{ route('profile.edit') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endauth
                </div>
            </nav>

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <div class="flex-grow pt-10 bg-gray-50 page-wrapper">
                <main class="w-full">
                    @if (View::hasSection('full_content'))
                        {{-- Full-width content without centering --}}
                        <div class="w-full md:px-12 md:px-4 px-2">
                            @yield('full_content')
                        </div>
                    @else
                        {{-- Centered content with padding --}}
                        <div class="flex items-center justify-center">
                            <div class="w-full max-w-7xl px-4 pb-20">
                                @yield('content')
                            </div>
                        </div>
                    @endif
                </main>
            </div>

            <!-- Footer -->
            <footer class="bg-gray-800 text-white py-4 mt-10">
                <div class="max-w-7xl mx-auto px-4 text-center">
                    <p>&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.</p>
                    <p>
                        <a href="#" class="text-gray-400 hover:text-white">Privacy Policy</a> |
                        <a href="#" class="text-gray-400 hover:text-white">Terms of Service</a>
                    </p>
                </div>
            </footer>
        </div>
    </div>

    <!-- Dropdown Toggle Script -->
    <script>
        window.addEventListener('click', function(e) {
            const dropdown = document.getElementById('user-dropdown');
            const button = dropdown?.previousElementSibling;
            if (dropdown && !button.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>

    <script>
        // Initialize notifications storage
        window.bellNotifications = [];

        // Load notifications from server on page load
        async function loadBellNotifications() {
            try {
                // First load from localStorage for instant display
                const stored = localStorage.getItem('bellNotifications');
                if (stored) {
                    window.bellNotifications = JSON.parse(stored);
                    updateBellNotificationUI();
                    if (window.bellNotifications.length > 0) showBellBadge();
                }

                // Then fetch fresh data from server
                const response = await fetch("{{ route('dashboard.notifications') }}");
                if (response.ok) {
                    let freshData = await response.json();
                    
                    // Filter based on "Clear All" checkpoint
                    const clearedAt = localStorage.getItem('notificationsClearedAt');
                    if (clearedAt) {
                        const clearedDate = new Date(clearedAt);
                        freshData = freshData.filter(notif => {
                            // Only show if it's newer than the clearing time
                            return new Date(notif.timestamp) > clearedDate;
                        });
                    }

                    window.bellNotifications = freshData;
                    saveBellNotifications();
                    updateBellNotificationUI();
                    
                    if (window.bellNotifications.length > 0) {
                        showBellBadge();
                    } else {
                        hideBellBadge();
                    }
                }
            } catch (error) {
                console.error('Error loading notifications:', error);
            }
        }

        // Save notifications to localStorage
        function saveBellNotifications() {
            try {
                localStorage.setItem('bellNotifications', JSON.stringify(window.bellNotifications));
            } catch (error) {
                console.error('Error saving notifications:', error);
            }
        }

        // Toggle notification dropdown
        function toggleNotificationDropdown() {
            const dropdown = document.getElementById('notification-dropdown');
            if (dropdown) {
                dropdown.classList.toggle('hidden');

                // Mark all as read when dropdown opens
                if (!dropdown.classList.contains('hidden')) {
                    markAllAsRead();
                }
            }
        }

        // Add notification to bell icon
        function addToBellNotifications(notification) {
            // Add timestamp if not present
            if (!notification.timestamp) {
                notification.timestamp = new Date().toISOString();
            }

            // Add unique ID if not present
            if (!notification.id) {
                notification.id = 'bell-notif-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);
            }

            // Add to beginning of array (newest first)
            window.bellNotifications.unshift(notification);

            // Keep only last 10 notifications
            if (window.bellNotifications.length > 10) {
                window.bellNotifications = window.bellNotifications.slice(0, 10);
            }

            // Save to localStorage
            saveBellNotifications();

            // Update UI
            updateBellNotificationUI();
            showBellBadge();
        }

        // Update bell notification UI
        function updateBellNotificationUI() {
            const notificationsList = document.getElementById('notifications-list');
            if (!notificationsList) return;

            if (window.bellNotifications.length === 0) {
                notificationsList.innerHTML = `
            <div id="empty-notifications" class="p-6 text-center text-gray-500">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 7.165 6 9.388 6 12v2.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <p class="text-sm">No notifications yet</p>
            </div>
        `;
                return;
            }

            let notificationsHTML = '';
            window.bellNotifications.forEach(notification => {
                const timeAgo = getTimeAgo(new Date(notification.timestamp));

                if (notification.type === 'registration') {
                    notificationsHTML += createRegistrationNotificationHTML(notification, timeAgo);
                } else if (notification.type === 'review') {
                    notificationsHTML += createReviewNotificationHTML(notification, timeAgo);
                }
            });

            notificationsList.innerHTML = notificationsHTML;
            
            // Initialize Lucide icons for new items
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }

        // Create registration notification HTML
        function createRegistrationNotificationHTML(notification, timeAgo) {
            return `
        <div class="notification-item p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors">
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center text-purple-600">
                        <i data-lucide="user-plus" class="w-5 h-5"></i>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between mb-0.5">
                        <p class="text-xs font-bold text-gray-900">${notification.title || 'New User'}</p>
                        <span class="text-[10px] font-medium text-gray-400">${timeAgo}</span>
                    </div>
                    <p class="text-[11px] text-gray-500 line-clamp-2">${notification.description}</p>
                </div>
            </div>
        </div>
    `;
        }

        // Create review notification HTML
        function createReviewNotificationHTML(notification, timeAgo) {
            const stars = notification.rating ? '★'.repeat(notification.rating) + '☆'.repeat(5 - notification.rating) : '';
            const color = notification.color || 'blue';

            return `
        <div class="notification-item p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors">
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-${color}-100 rounded-xl flex items-center justify-center text-${color}-600">
                        <i data-lucide="star" class="w-5 h-5"></i>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between mb-0.5">
                        <p class="text-xs font-bold text-gray-900">${notification.title || 'New Feedback'}</p>
                        <span class="text-[10px] font-medium text-gray-400">${timeAgo}</span>
                    </div>
                    <p class="text-[11px] text-gray-500 line-clamp-2">${notification.description}</p>
                    ${stars ? `<div class="text-yellow-400 text-[10px] mt-1">${stars}</div>` : ''}
                </div>
            </div>
        </div>
    `;
        }

        // Show bell badge
        function showBellBadge() {
            const badge = document.getElementById('notification-badge');
            if (badge) {
                badge.textContent = window.bellNotifications.length;
                badge.classList.remove('hidden');
            }
        }

        // Hide bell badge
        function hideBellBadge() {
            const badge = document.getElementById('notification-badge');
            if (badge) {
                badge.classList.add('hidden');
            }
        }

        // Remove single notification
        function removeNotification(notificationId) {
            window.bellNotifications = window.bellNotifications.filter(notif => notif.id !== notificationId);
            saveBellNotifications();
            updateBellNotificationUI();

            if (window.bellNotifications.length === 0) {
                hideBellBadge();
            } else {
                showBellBadge();
            }
        }

        // Clear all notifications
        function clearAllNotifications() {
            window.bellNotifications = [];
            localStorage.removeItem('bellNotifications');
            // Store current time as a checkpoint to filter out existing feeds on refresh
            localStorage.setItem('notificationsClearedAt', new Date().toISOString());
            updateBellNotificationUI();
            hideBellBadge();
        }

        // Mark all as read
        function markAllAsRead() {
            hideBellBadge();
            window.bellNotifications.forEach(notif => notif.read = true);
            saveBellNotifications();
        }

        // Get time ago string
        function getTimeAgo(date) {
            const now = new Date();
            const diffInSeconds = Math.floor((now - date) / 1000);

            if (diffInSeconds < 60) return 'Just now';
            if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)}m ago`;
            if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)}h ago`;
            return `${Math.floor(diffInSeconds / 86400)}d ago`;
        }

        // Show Registration Success Notification (Toast)
        function showRegistrationNotification(data) {
            // Add to bell notifications
            addToBellNotifications({
                type: 'registration',
                id: 'reg-' + Date.now(),
                user_name: data.user_name,
                name: data.name,
                price: data.price,
                duration_days: data.duration_days,
                success_message: data.success_message,
                timestamp: data.timestamp || new Date().toISOString()
            });

            // Show toast notification
            const container = document.getElementById('notification-container');
            if (!container) return;

            const notificationId = 'registration-notification-' + Date.now();
            const notification = document.createElement('div');
            notification.id = notificationId;
            notification.className = 'transform transition-all duration-700 ease-out translate-x-full opacity-0';

            const duration = data.duration_days > 1 ? `${data.duration_days} days` : `${data.duration_days} day`;
            const price = data.price ? `$${data.price}` : '';

            notification.innerHTML = `
        <div class="bg-gradient-to-r from-green-400 to-blue-500 rounded-lg shadow-xl p-4 max-w-sm border-l-4 border-green-400 celebration-animation relative">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-md">
                        <svg class="w-7 h-7 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-bold text-white">🎉 Welcome to ReviewBooster!</p>
                    <p class="text-sm text-white opacity-90 mt-1 leading-relaxed">${data.success_message}</p>
                    <div class="mt-3 bg-white bg-opacity-20 rounded-lg p-2">
                        <div class="flex items-center justify-between text-xs text-white">
                            <div>
                                <p class="font-semibold">Plan: ${data.name}</p>
                                <p class="opacity-80">Duration: ${duration}</p>
                            </div>
                            ${price ? `<div class="text-right">
                                            <p class="font-bold text-lg">${price}</p>
                                        </div>` : ''}
                        </div>
                    </div>
                </div>
            </div>
            <button onclick="closeNotification('${notificationId}')" 
                    class="absolute top-2 right-2 text-white hover:text-gray-200 transition-colors opacity-70 hover:opacity-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    `;

            container.appendChild(notification);

            // Show with slide animation
            setTimeout(() => {
                notification.className =
                    'slide-in-right transform transition-all duration-700 ease-out translate-x-0 opacity-100';
            }, 100);

            // Auto close after 10 seconds
            setTimeout(() => {
                closeNotification(notificationId);
            }, 10000);
        }

        // Show Review Notification (Toast)
        function showReviewNotification(data) {
            // Add to bell notifications
            addToBellNotifications({
                type: 'review',
                id: 'review-' + Date.now(),
                name: data.name,
                review: data.review,
                rating: data.rating,
                timestamp: new Date().toISOString()
            });

            // Show toast notification
            const container = document.getElementById('notification-container');
            if (!container) return;

            const notificationId = 'review-notification-' + Date.now();
            const notification = document.createElement('div');
            notification.id = notificationId;
            notification.className = 'transform transition-all duration-500 ease-in-out translate-x-full opacity-0';

            const stars = '★'.repeat(data.rating || 5) + '☆'.repeat(5 - (data.rating || 5));

            notification.innerHTML = `
        <div class="bg-white border-l-4 border-green-500 rounded-lg shadow-lg p-4 max-w-sm relative">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-gray-900">New Review!</p>
                    <p class="text-sm text-gray-600 mt-1">${data.review}</p>
                    <div class="mt-2 flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-gray-900">${data.name}</p>
                            <div class="text-yellow-400 text-sm">${stars}</div>
                        </div>
                    </div>
                </div>
            </div>
            <button onclick="closeNotification('${notificationId}')" 
                    class="absolute top-2 right-2 text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    `;

            container.appendChild(notification);

            setTimeout(() => {
                notification.className =
                    'transform transition-all duration-500 ease-in-out translate-x-0 opacity-100';
            }, 10);

            setTimeout(() => {
                closeNotification(notificationId);
            }, 7000);
        }

        // Global Toast Notification System
        window.toast = function(message, type = 'success', duration = 5000) {
            const container = document.getElementById('notification-container');
            if (!container) return;

            const toastId = 'toast-' + Date.now();
            const toast = document.createElement('div');
            toast.id = toastId;
            
            // Design variants
            const themes = {
                success: {
                    bg: 'bg-white',
                    border: 'border-l-4 border-green-500',
                    icon: 'text-green-500',
                    iconPath: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
                },
                error: {
                    bg: 'bg-white',
                    border: 'border-l-4 border-red-500',
                    icon: 'text-red-500',
                    iconPath: 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
                },
                info: {
                    bg: 'bg-white',
                    border: 'border-l-4 border-blue-500',
                    icon: 'text-blue-500',
                    iconPath: 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
                }
            };

            const theme = themes[type] || themes.success;

            toast.className = 'transform transition-all duration-500 ease-in-out translate-x-full opacity-0 max-w-sm w-full';
            toast.innerHTML = `
                <div class="${theme.bg} ${theme.border} rounded-xl shadow-2xl p-4 flex items-start gap-3 relative border border-gray-100">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 ${theme.icon}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${theme.iconPath}"></path>
                        </svg>
                    </div>
                    <div class="flex-1 pr-4">
                        <p class="text-sm font-semibold text-gray-900">${type.charAt(0).toUpperCase() + type.slice(1)}</p>
                        <p class="text-sm text-gray-600 mt-0.5">${message}</p>
                    </div>
                    <button onclick="closeNotification('${toastId}')" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            `;

            container.appendChild(toast);

            // Animate In
            setTimeout(() => {
                toast.classList.remove('translate-x-full', 'opacity-0');
            }, 10);

            // Auto Close
            setTimeout(() => {
                closeNotification(toastId);
            }, duration);
        };

        // Close notification toast
        function closeNotification(notificationId) {
            const notification = document.getElementById(notificationId);
            if (notification) {
                notification.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => notification.remove(), 500);
            }
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            const notificationDropdown = document.getElementById('notification-dropdown');

            if (notificationDropdown &&
                !event.target.closest('[onclick*="toggleNotificationDropdown"]') &&
                !event.target.closest('#notification-dropdown')) {
                notificationDropdown.classList.add('hidden');
            }
        });

        // Load notifications when page loads
        document.addEventListener('DOMContentLoaded', function() {
            loadBellNotifications();

            // Check for Laravel session notifications
            @if (session('new_registration_notification'))
                const registrationData = @json(session('new_registration_notification'));
                showRegistrationNotification(registrationData);
            @endif

            @if (session('new_review_notification'))
                const reviewData = @json(session('new_review_notification'));
                showReviewNotification(reviewData);
            @endif

            // Initialize Lucide Icons
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
    
    <script>
        window.deferredPrompt = null;
    
        window.addEventListener('beforeinstallprompt', (e) => {
            // e.preventDefault(); // Removed to allow automatic browser popup
            window.deferredPrompt = e;
    
            // Notify all Alpine components that install is available
            window.dispatchEvent(new CustomEvent('pwa-installable'));
        });
    
        window.addEventListener('appinstalled', () => {
            window.deferredPrompt = null;
            // Notify Alpine components app is installed
            window.dispatchEvent(new CustomEvent('pwa-installed'));
        });
    
        window.installPWA = function() {
            if (!window.deferredPrompt) return;
            window.deferredPrompt.prompt();
            window.deferredPrompt.userChoice.then(() => {
                window.deferredPrompt = null;
                window.dispatchEvent(new CustomEvent('pwa-installed'));
            });
        };
    </script>


</body>

</html>
