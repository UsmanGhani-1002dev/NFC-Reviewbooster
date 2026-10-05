<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/tap fevicon.png') }}" type="image/x-icon">
    @laravelPWA

    {{-- SEO: Dynamic Page Title --}}
    <title>@yield('title', 'Tap Review Cards — #1 NFC Google Review Cards UK | Collect Reviews Instantly')</title>

    {{-- SEO: Meta Description --}}
    <meta name="description" content="@yield('meta_description', 'Tap Review Cards helps UK businesses collect more Google reviews instantly with NFC tap cards. No app needed. Works on iPhone & Android. Smart dashboard with review gate, staff tracking & AI responses. Order yours today.')">

    {{-- SEO: Meta Keywords --}}
    <meta name="keywords" content="@yield('meta_keywords', 'NFC review cards, Google review cards UK, tap review cards, NFC tap cards, collect Google reviews, review management, Google review tap card, NFC Google reviews, online reputation management UK')">

    {{-- SEO: Canonical URL --}}
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- SEO: Robots --}}
    <meta name="robots" content="@yield('meta_robots', 'index, follow')">

    {{-- SEO: Open Graph Tags --}}
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="Tap Review Cards">
    <meta property="og:title" content="@yield('og_title', 'Tap Review Cards — #1 NFC Google Review Cards UK')">
    <meta property="og:description" content="@yield('og_description', 'Collect more Google reviews instantly with NFC tap cards. No app needed. Smart dashboard, review gate & AI responses. From £16.99.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('og_image', asset('images/logo.png'))">
    <meta property="og:locale" content="en_GB">

    {{-- SEO: Twitter Card Tags --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter_title', 'Tap Review Cards — #1 NFC Google Review Cards UK')">
    <meta name="twitter:description" content="@yield('twitter_description', 'Collect more Google reviews instantly with NFC tap cards. No app needed. Smart dashboard, review gate & AI responses.')">
    <meta name="twitter:image" content="@yield('twitter_image', asset('images/logo.png'))">

    {{-- SEO: Additional Meta --}}
    <meta name="author" content="Tap Review Cards">
    <meta name="geo.region" content="GB">
    <meta name="geo.placename" content="United Kingdom">

    {{-- SEO: Organization Schema (Global) --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "Tap Review Cards",
        "url": "https://tapreviewcards.co.uk",
        "logo": "{{ asset('images/logo.png') }}",
        "description": "Tap Review Cards helps UK businesses collect more Google reviews instantly with NFC tap cards and smart review management tools.",
        "contactPoint": {
            "@type": "ContactPoint",
            "telephone": "+44-1283-515606",
            "contactType": "customer service",
            "email": "info@tapreviewcards.co.uk",
            "availableLanguage": "English",
            "areaServed": "GB"
        },
        "sameAs": []
    }
    </script>

    {{-- Page-specific Schema --}}
    @yield('schema')

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@400;500;600;700&family=Ubuntu:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <script defer src="https://unpkg.com/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="font-sans antialiased bg-gray-50 flex flex-col min-h-screen">
    <!-- Global Toast Notification Banner -->
    <div x-data="{ 
            show: {{ session()->has('success') || session()->has('status') || session()->has('error') || session()->has('warning') ? 'true' : 'false' }},
            message: {{ json_encode(session('success') ?? session('status') ?? session('error') ?? session('warning') ?? '') }},
            type: '{{ session()->has('error') ? 'error' : (session()->has('warning') ? 'warning' : 'success') }}',
            init() {
                if (this.show) {
                    setTimeout(() => { this.show = false }, 8000);
                }
                window.addEventListener('toast', (e) => {
                    this.message = e.detail.message || e.detail;
                    this.type = e.detail.type || 'success';
                    this.show = true;
                    setTimeout(() => { this.show = false }, 8000);
                });
            }
         }"
         x-show="show"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="opacity-0 -translate-y-4 sm:translate-y-0 sm:translate-x-4 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0 scale-100"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed top-24 right-4 sm:right-6 z-[9999] max-w-md w-full"
         style="display: none;">
        <div class="rounded-2xl p-4 shadow-2xl border backdrop-blur-md flex items-start gap-3.5 transition-all duration-300"
             :class="{
                'bg-[#0f2942]/95 text-white border-[#00A0FF]/40 shadow-blue-900/30': type === 'success',
                'bg-rose-900/95 text-white border-rose-700/60 shadow-rose-900/30': type === 'error',
                'bg-amber-900/95 text-white border-amber-700/60 shadow-amber-900/30': type === 'warning'
             }">
            <!-- Icon -->
            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"
                 :class="{
                    'bg-[#00A0FF]/20 text-[#00A0FF] border border-[#00A0FF]/30': type === 'success',
                    'bg-rose-500/20 text-rose-300 border border-rose-400/30': type === 'error',
                    'bg-amber-500/20 text-amber-300 border border-amber-400/30': type === 'warning'
                 }">
                <template x-if="type === 'success'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </template>
                <template x-if="type === 'error'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </template>
                <template x-if="type === 'warning'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </template>
            </div>

            <!-- Text Content -->
            <div class="flex-1 pt-0.5">
                <div class="flex items-center gap-2 mb-1 flex-wrap">
                    <h4 class="font-bold text-sm font-ubuntu tracking-tight" x-text="type === 'success' ? 'Welcome to Tap Review Cards!' : (type === 'error' ? 'Notice' : 'Attention')"></h4>
                    @auth
                        @if(auth()->user()->isApprovedPartner())
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-purple-500/30 text-purple-200 border border-purple-400/30">👑 {{ auth()->user()->partner_type_label }}</span>
                        @elseif(auth()->user()->isPendingPartner())
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-500/30 text-amber-200 border border-amber-400/30">⏳ {{ auth()->user()->partner_type_label }} (Under Review)</span>
                        @endif
                    @endauth
                </div>
                <p class="text-xs font-mulish leading-relaxed text-gray-200" x-text="message"></p>
            </div>

            <!-- Close button -->
            <button @click="show = false" class="text-white/60 hover:text-white transition-colors p-1 rounded-lg hover:bg-white/10 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col">
        @include('layouts.navigation')

        <!-- Page Content -->
        <div class="w-full flex items-center justify-center bg-gray-50 flex-1">
            <main class="w-full">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- AI Support Chatbot -->
    @if(\App\Models\Setting::get('chatbot_enabled'))
        <x-chat-widget />
    @endif

     <!-- Scroll to Top Button -->
    <button id="scrollToTop"
        class="fixed {{ \App\Models\Setting::get('chatbot_enabled') ? 'bottom-24' : 'bottom-6' }} right-6 bg-[#00A0FF] text-white p-3 rounded-full shadow-lg hover:bg-blue-600 transition-all duration-300 transform hover:scale-110 opacity-0 invisible z-50"
        onclick="scrollToTop()">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
        </svg>
    </button>

    <script>
        // Show/hide scroll to top button based on scroll position
        window.addEventListener('scroll', function() {
            const scrollToTopBtn = document.getElementById('scrollToTop');
            if (window.pageYOffset > 300) {
                scrollToTopBtn.classList.remove('opacity-0', 'invisible');
                scrollToTopBtn.classList.add('opacity-100', 'visible');
            } else {
                scrollToTopBtn.classList.add('opacity-0', 'invisible');
                scrollToTopBtn.classList.remove('opacity-100', 'visible');
            }
        });

        // Smooth scroll to top function
        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
    </script>

    <!-- Footer -->
        <footer class="bg-white text-[#00A0FF] font-mulish">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <!-- Mobile Layout -->
                <div class="block md:hidden">
                    <!-- Logo - Centered on mobile -->
                    <div class="text-center mb-8">
                        <img src="images/logo.png" alt="Tap Review Cards" class="w-48 h-auto mx-auto mb-4">
                        <p class="text-[#142D63] font-mulish leading-[25px] text-sm px-4">
                            <span class="font-bold">Disclaimer:</span> Tap Review Cards is not affiliated with Google or Google Inc. This website is not endorsed by Google in any way.
                        </p>
                    </div>

                    <!-- Quick Links and Support in one row -->
                    <div class="grid grid-cols-2 gap-8 mb-8">
                        <!-- Quick Links -->
                        <div>
                            <h4 class="text-lg font-semibold mb-3 font-mulish text-[#1800ad] text-center">Quick Links</h4>
                            <ul class="space-y-3 text-center">
                                <li class="text-center"><a href="{{ route('home') }}" class="text-[#142D63] hover:text-[#244a9d] text-sm">Home</a></li>
                                <li class="text-center"><a href="{{ route('about') }}" class="text-[#142D63] hover:text-[#244a9d] text-sm">About</a></li>
                                <li class="text-center"><a href="{{ route('howitswork') }}" class="text-[#142D63] hover:text-[#244a9d] text-sm">How It Works</a></li>
                                <li class="text-center"><a href="{{ route('contact') }}" class="text-[#142D63] hover:text-[#244a9d] text-sm">Contact</a></li>
                            </ul>
                        </div>

                        <!-- Support -->
                        <div>
                            <h4 class="text-lg font-semibold mb-3 font-mulish text-[#1800ad] text-center">Support</h4>
                            <ul class="space-y-3 text-center">
                                <li class="text-center"><a href="{{ route('shipping-returns') }}" class="text-[#142D63] hover:text-[#244a9d] text-sm">Shipping & Returns</a></li>
                                <li class="text-center"><a href="{{ route('terms-of-service') }}" class="text-[#142D63] hover:text-[#244a9d] text-sm">Terms of Service</a></li>
                                <li class="text-center"><a href="{{ route('privacypolicy') }}" class="text-[#142D63] hover:text-[#244a9d] text-sm">Privacy Policy</a></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Follow Us on second row -->
                    <div class="text-center">
                        <h4 class="text-lg font-semibold mb-4 font-mulish text-[#1800ad]">Contact Us</h4>
                        <div class="flex flex-col items-center">
                            <a href="mailto:info@tapreviewcards.co.uk" class="text-left text-[#142D63]">
                                info@tapreviewcards.co.uk
                            </a>
                            <br>
                            <a href="tel:+447300401004" class="text-left text-[#142D63]">
                                +44 7300 401004
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Desktop Layout -->
                <div class="hidden md:grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
                    <!-- Brand -->
                    <div class="space-y-4">
                        <img src="images/logo.png" alt="Tap Review Cards" class="w-48 h-auto">
                        
                        <p class="text-[#142D63] font-mulish leading-[25px] text-sm">
                            <span class="font-bold">Disclaimer:</span> Tap Review Cards is not affiliated with Google or Google Inc. This website is not endorsed by Google in any way.
                        </p>
                    </div>

                    <!-- Quick Links -->
                    <div class="pl-12">
                        <h4 class="text-lg font-semibold mb-3 font-mulish text-[#1800ad]">Quick Links</h4>
                        <ul class="space-y-4">
                            <li><a href="{{ route('home') }}" class="text-[#142D63] hover:text-[#244a9d]">Home</a></li>
                            <li><a href="{{ route('about') }}" class="text-[#142D63] hover:text-[#244a9d]">About</a></li>
                            <li><a href="{{ route('howitswork') }}" class="text-[#142D63] hover:text-[#244a9d]">How It Works</a></li>
                            <li><a href="{{ route('contact') }}" class="text-[#142D63] hover:text-[#244a9d]">Contact</a></li>
                        </ul>
                    </div>

                    <!-- Support -->
                    <div class="pl-8">
                        <h4 class="text-lg font-semibold mb-3 font-mulish text-[#1800ad]">Support</h4>
                        <ul class="space-y-4">
                            <li><a href="{{ route('orders.track') }}" class="text-[#142D63] hover:text-[#244a9d]">Track Order</a></li>
                            <li><a href="{{ route('shipping-returns') }}" class="text-[#142D63] hover:text-[#244a9d]">Shipping & Returns</a></li>
                            <li><a href="{{ route('terms-of-service') }}" class="text-[#142D63] hover:text-[#244a9d]">Terms of Service</a></li>
                            <li><a href="{{ route('privacypolicy') }}" class="text-[#142D63] hover:text-[#244a9d]">Privacy Policy</a></li>
                        </ul>
                    </div>

                    <!-- Social -->
                    <div>
                        <h4 class="text-lg font-semibold mb-3 font-mulish text-[#1800ad]">Contact us</h4>
                        <div class="space-y-4 grid">
                            <a href="mailto:info@tapreviewcards.co.uk" class="text-left text-[#142D63]">
                                info@tapreviewcards.co.uk
                            </a>
                            <a href="tel:+447300401004" class="text-left text-[#142D63]">
                                +44 7300 401004
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Copyright -->
            <div class="bg-[#0cc0df] text-white py-4 text-base font-mulish">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row justify-between items-center gap-1 text-center sm:text-left">
                    <p>&copy; {{ date('Y') }} {{ config('app.name', 'Tap Review Cards') }}. All rights reserved.</p>
                    <p>Managed & operated under <a href="https://enovtec.com" target="_blank" class="text-[#142D63] font-bold">Enovtec Ltd.</a></p>
                </div>
            </div>  
        </footer>

    <script>
        // Standalone PWA App check: Only show Dashboard in App, not public frontpages
        (function() {
            const isStandalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;
            if (isStandalone) {
                const currentPath = window.location.pathname;
                // Exclude review flow or auth callback routes if needed
                if (!currentPath.startsWith('/dashboard') && !currentPath.startsWith('/login') && !currentPath.startsWith('/register') && !currentPath.startsWith('/admin') && !currentPath.startsWith('/r/')) {
                    window.location.href = '/dashboard';
                }
            }
        })();

        window.deferredPrompt = null;
    
        window.addEventListener('beforeinstallprompt', (e) => {
            window.deferredPrompt = e;
            window.dispatchEvent(new CustomEvent('pwa-installable'));
        });
    
        window.addEventListener('appinstalled', () => {
            window.deferredPrompt = null;
            window.dispatchEvent(new CustomEvent('pwa-installed'));
        });
    
        window.installPWA = function() {
            if (window.deferredPrompt) {
                window.deferredPrompt.prompt();
                window.deferredPrompt.userChoice.then(() => {
                    window.deferredPrompt = null;
                    window.dispatchEvent(new CustomEvent('pwa-installed'));
                });
            } else {
                const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
                if (isIOS) {
                    window.dispatchEvent(new CustomEvent('open-ios-install-modal'));
                } else {
                    alert('To install this app, tap your browser menu and select "Install App" or "Add to Home screen".');
                }
            }
        };
    </script>

    <!-- iOS PWA Install Modal -->
    <x-ios-pwa-modal />
</body>
</html>
