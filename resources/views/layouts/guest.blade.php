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

     <!-- Scroll to Top Button -->
    <button id="scrollToTop" 
        class="fixed bottom-6 right-6 bg-[#00A0FF] text-white p-3 rounded-full shadow-lg hover:bg-blue-600 transition-all duration-300 transform hover:scale-110 opacity-0 invisible z-50"
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
                            <h4 class="text-lg font-semibold mb-3 font-mulish text-[#1800ad]">Quick Links</h4>
                            <ul class="space-y-3">
                                <li class="text-center"><a href="{{ route('home') }}" class="text-[#142D63] hover:text-[#244a9d] text-sm">Home</a></li>
                                <li class="text-center"><a href="{{ route('about') }}" class="text-[#142D63] hover:text-[#244a9d] text-sm">About</a></li>
                                <li class="text-center"><a href="{{ route('howitswork') }}" class="text-[#142D63] hover:text-[#244a9d] text-sm">How It Works</a></li>
                                <li class="text-center"><a href="{{ route('contact') }}" class="text-[#142D63] hover:text-[#244a9d] text-sm">Contact</a></li>
                            </ul>
                        </div>

                        <!-- Support -->
                        <div>
                            <h4 class="text-lg font-semibold mb-3 font-mulish text-[#1800ad]">Support</h4>
                            <ul class="space-y-3">
                                <li class="text-center"><a href="{{ route('shipping-returns') }}" class="text-[#142D63] hover:text-[#244a9d] text-sm">Shipping & Returns</a></li>
                                <li class="text-center"><a href="{{ route('terms-of-service') }}" class="text-[#142D63] hover:text-[#244a9d] text-sm">Terms of Service</a></li>
                                <li class="text-center"><a href="{{ route('privacypolicy') }}" class="text-[#142D63] hover:text-[#244a9d] text-sm">Privacy Policy</a></li>
                                <li class="text-center text-[#142D63] text-sm font-semibold mt-4">
                                    info@tapreviewcards.co.uk
                                </li>
                                <li class="text-center text-[#142D63] text-sm font-semibold">
                                    +44 1283 515606
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Follow Us on second row -->
                    <div class="text-center">
                        <h4 class="text-lg font-semibold mb-4 font-mulish text-[#1800ad]">Follow Us</h4>
                        <div class="flex justify-center space-x-6">
                            <!-- Twitter -->
                            <a href="https://twitter.com/yourhandle" class="text-[#142D63] hover:text-[#244a9d]" aria-label="Twitter">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.5 4.8a9.6 9.6 0 01-2.8.8A4.9 4.9 0 0023 3a9.7 9.7 0 01-3.1 1.2 4.8 4.8 0 00-8.1 4.4A13.6 13.6 0 013 3.8a4.8 4.8 0 001.5 6.5 4.9 4.9 0 01-2.2-.6v.1a4.8 4.8 0 003.9 4.7 4.9 4.9 0 01-2.2.1 4.8 4.8 0 004.5 3.3 9.7 9.7 0 01-6 2.1c-.4 0-.8 0-1.2-.1a13.7 13.7 0 007.4 2.2c8.8 0 13.6-7.3 13.6-13.6v-.6a9.7 9.7 0 002.4-2.5z"/>
                                </svg>
                            </a>

                            <!-- Facebook -->
                            <a href="https://facebook.com/yourpage" class="text-[#142D63] hover:text-[#244a9d]" aria-label="Facebook">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M22.675 0H1.325C.593 0 0 .593 0 1.326v21.348C0 23.406.593 24 1.325 24H12.82v-9.294H9.692v-3.622h3.128V8.413c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.464.099 2.795.143v3.24l-1.918.001c-1.504 0-1.796.715-1.796 1.763v2.31h3.59l-.467 3.622h-3.123V24h6.116C23.407 24 24 23.407 24 22.674V1.326C24 .593 23.407 0 22.675 0z"/>
                                </svg>
                            </a>

                            <!-- Instagram -->
                            <a href="https://instagram.com/yourhandle" class="text-[#142D63] hover:text-[#244a9d]" aria-label="Instagram">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.2c3.2 0 3.584.012 4.85.07 1.17.055 1.96.24 2.41.4.51.18.88.39 1.26.77.38.38.59.75.77 1.26.16.45.34 1.24.39 2.41.06 1.27.07 1.65.07 4.85s-.01 3.584-.07 4.85c-.055 1.17-.23 1.96-.39 2.41-.18.51-.39.88-.77 1.26-.38.38-.75.59-1.26.77-.45.16-1.24.34-2.41.39-1.27.06-1.65.07-4.85.07s-3.584-.01-4.85-.07c-1.17-.055-1.96-.23-2.41-.39-.51-.18-.88-.39-1.26-.77-.38-.38-.59-.75-.77-1.26-.16-.45-.34-1.24-.39-2.41C2.21 15.584 2.2 15.2 2.2 12s.01-3.584.07-4.85c.055-1.17.23-1.96.39-2.41.18-.51.39-.88.77-1.26.38-.38.75-.59 1.26-.77.45-.16 1.24-.34 2.41-.39C8.416 2.21 8.8 2.2 12 2.2zm0-2.2C8.735 0 8.332.01 7.052.07 5.772.128 4.77.33 3.96.66a6.4 6.4 0 00-2.36 1.55A6.4 6.4 0 00.66 4.57C.33 5.38.13 6.38.07 7.66.01 8.94 0 9.343 0 12s.01 3.06.07 4.34c.058 1.28.26 2.28.59 3.09a6.4 6.4 0 001.55 2.36 6.4 6.4 0 002.36 1.55c.81.33 1.81.53 3.09.59C8.94 23.99 9.343 24 12 24s3.06-.01 4.34-.07c1.28-.058 2.28-.26 3.09-.59a6.4 6.4 0 002.36-1.55 6.4 6.4 0 001.55-2.36c.33-.81.53-1.81.59-3.09.06-1.28.07-1.683.07-4.34s-.01-3.06-.07-4.34c-.058-1.28-.26-2.28-.59-3.09a6.4 6.4 0 00-1.55-2.36 6.4 6.4 0 00-2.36-1.55C18.28.33 17.28.13 16 .07 14.72.01 14.317 0 12 0zm0 5.8a6.2 6.2 0 110 12.4 6.2 6.2 0 010-12.4zm0 10.2a4 4 0 100-8 4 4 0 000 8zm6.4-11.6a1.44 1.44 0 11-2.88 0 1.44 1.44 0 012.88 0z"/>
                                </svg>
                            </a>

                            <!-- LinkedIn -->
                            <a href="https://linkedin.com/in/yourprofile" class="text-[#142D63] hover:text-[#244a9d]" aria-label="LinkedIn">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 0H5C2.24 0 0 2.24 0 5v14c0 2.76 2.24 5 5 5h14c2.76 0 5-2.24 5-5V5c0-2.76-2.24-5-5-5zm-8 19H8v-8h3v8zm-1.5-9.3c-.97 0-1.5-.65-1.5-1.45s.53-1.45 1.5-1.45 1.5.65 1.5 1.45-.53 1.45-1.5 1.45zM19 19h-3v-4.4c0-1.04-.7-1.6-1.63-1.6-.95 0-1.37.65-1.37 1.6V19h-3v-8h3v1.1c.44-.7 1.23-1.1 2.2-1.1 1.67 0 2.8 1.1 2.8 3.4V19z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Desktop Layout -->
                <div class="hidden md:grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
                    <!-- Brand -->
                    <div class="space-y-4">
                        <img src="images/logo.png" alt="Tap Review Cards" class="w-48 h-auto">
                        <p class="text-[#142D63] font-mulish leading-[25px]">
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
                            <li><a href="{{ route('shipping-returns') }}" class="text-[#142D63] hover:text-[#244a9d]">Shipping & Returns</a></li>
                            <li><a href="{{ route('terms-of-service') }}" class="text-[#142D63] hover:text-[#244a9d]">Terms of Service</a></li>
                            <li><a href="{{ route('privacypolicy') }}" class="text-[#142D63] hover:text-[#244a9d]">Privacy Policy</a></li>
                            <li>
                                <a href="mailto:info@tapreviewcards.co.uk" class="text-[#142D63] hover:text-[#244a9d] text-sm font-semibold">info@tapreviewcards.co.uk</a>
                            </li>
                            <li>
                                <a href="tel:+441283515606" class="text-[#142D63] hover:text-[#244a9d] text-sm font-semibold">+44 1283 515606</a>
                            </li>
                        </ul>
                    </div>

                    <!-- Social -->
                    <div>
                        <h4 class="text-lg font-semibold mb-3 font-mulish text-[#1800ad]">Follow Us</h4>
                        <div class="flex space-x-4">
                            <!-- Twitter -->
                            <a href="https://twitter.com/yourhandle" class="text-gray-300 hover:text-[#244a9d]" aria-label="Twitter">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.5 4.8a9.6 9.6 0 01-2.8.8A4.9 4.9 0 0023 3a9.7 9.7 0 01-3.1 1.2 4.8 4.8 0 00-8.1 4.4A13.6 13.6 0 013 3.8a4.8 4.8 0 001.5 6.5 4.9 4.9 0 01-2.2-.6v.1a4.8 4.8 0 003.9 4.7 4.9 4.9 0 01-2.2.1 4.8 4.8 0 004.5 3.3 9.7 9.7 0 01-6 2.1c-.4 0-.8 0-1.2-.1a13.7 13.7 0 007.4 2.2c8.8 0 13.6-7.3 13.6-13.6v-.6a9.7 9.7 0 002.4-2.5z"/>
                                </svg>
                            </a>

                            <!-- Facebook -->
                            <a href="https://facebook.com/yourpage" class="text-gray-300 hover:text-[#244a9d]" aria-label="Facebook">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M22.675 0H1.325C.593 0 0 .593 0 1.326v21.348C0 23.406.593 24 1.325 24H12.82v-9.294H9.692v-3.622h3.128V8.413c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.464.099 2.795.143v3.24l-1.918.001c-1.504 0-1.796.715-1.796 1.763v2.31h3.59l-.467 3.622h-3.123V24h6.116C23.407 24 24 23.407 24 22.674V1.326C24 .593 23.407 0 22.675 0z"/>
                                </svg>
                            </a>

                            <!-- Instagram -->
                            <a href="https://instagram.com/yourpage" class="text-gray-300 hover:text-[#244a9d]" aria-label="Instagram">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.2c3.2 0 3.584.012 4.85.07 1.17.055 1.96.24 2.41.4.51.18.88.39 1.26.77.38.38.59.75.77 1.26.16.45.34 1.24.39 2.41.06 1.27.07 1.65.07 4.85s-.01 3.584-.07 4.85c-.055 1.17-.23 1.96-.39 2.41-.18.51-.39.88-.77 1.26-.38.38-.75.59-1.26.77-.45.16-1.24.34-2.41.39-1.27.06-1.65.07-4.85.07s-3.584-.01-4.85-.07c-1.17-.055-1.96-.23-2.41-.39-.51-.18-.88-.39-1.26-.77-.38-.38-.59-.75-.77-1.26-.16-.45-.34-1.24-.39-2.41C2.21 15.584 2.2 15.2 2.2 12s.01-3.584.07-4.85c.055-1.17.23-1.96.39-2.41.18-.51.39-.88.77-1.26.38-.38.75-.59 1.26-.77.45-.16 1.24-.34 2.41-.39C8.416 2.21 8.8 2.2 12 2.2zm0-2.2C8.735 0 8.332.01 7.052.07 5.772.128 4.77.33 3.96.66a6.4 6.4 0 00-2.36 1.55A6.4 6.4 0 00.66 4.57C.33 5.38.13 6.38.07 7.66.01 8.94 0 9.343 0 12s.01 3.06.07 4.34c.058 1.28.26 2.28.59 3.09a6.4 6.4 0 001.55 2.36 6.4 6.4 0 002.36 1.55c.81.33 1.81.53 3.09.59C8.94 23.99 9.343 24 12 24s3.06-.01 4.34-.07c1.28-.058 2.28-.26 3.09-.59a6.4 6.4 0 002.36-1.55 6.4 6.4 0 001.55-2.36c.33-.81.53-1.81.59-3.09.06-1.28.07-1.683.07-4.34s-.01-3.06-.07-4.34c-.058-1.28-.26-2.28-.59-3.09a6.4 6.4 0 00-1.55-2.36 6.4 6.4 0 00-2.36-1.55C18.28.33 17.28.13 16 .07 14.72.01 14.317 0 12 0zm0 5.8a6.2 6.2 0 110 12.4 6.2 6.2 0 010-12.4zm0 10.2a4 4 0 100-8 4 4 0 000 8zm6.4-11.6a1.44 1.44 0 11-2.88 0 1.44 1.44 0 012.88 0z"/>
                                </svg>
                            </a>

                            <!-- LinkedIn -->
                            <a href="https://linkedin.com/in/yourprofile" class="text-gray-300 hover:text-[#244a9d]" aria-label="LinkedIn">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 0H5C2.24 0 0 2.24 0 5v14c0 2.76 2.24 5 5 5h14c2.76 0 5-2.24 5-5V5c0-2.76-2.24-5-5-5zm-8 19H8v-8h3v8zm-1.5-9.3c-.97 0-1.5-.65-1.5-1.45s.53-1.45 1.5-1.45 1.5.65 1.5 1.45-.53 1.45-1.5 1.45zM19 19h-3v-4.4c0-1.04-.7-1.6-1.63-1.6-.95 0-1.37.65-1.37 1.6V19h-3v-8h3v1.1c.44-.7 1.23-1.1 2.2-1.1 1.67 0 2.8 1.1 2.8 3.4V19z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Copyright -->
            <div class="bg-[#0cc0df] text-center text-white py-4 text-sm">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.</p>
            </div>
        </footer>

    <script>
        window.deferredPrompt = null;
    
        window.addEventListener('beforeinstallprompt', (e) => {
            // Uncomment the next line if you want to prevent the automatic browser popup and show your own button instead
            // e.preventDefault(); 
            window.deferredPrompt = e;
    
            window.dispatchEvent(new CustomEvent('pwa-installable'));
        });
    
        window.addEventListener('appinstalled', () => {
            window.deferredPrompt = null;
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
