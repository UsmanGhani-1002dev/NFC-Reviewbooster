<nav x-data="{ open: false, userMenuOpen: false, shopMenuOpen: false }" class="py-2 z-50 relative">
    <x-cart-drawer />
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <!-- Left: Logo -->
                    <div class="flex items-center flex-shrink-0">
                        <a href="{{ url('/') }}" class="flex items-center">
                            <div class="block h-auto w-40 flex items-center justify-center text-white font-bold">
                                 <x-application-logo class="block h-auto w-40 fill-current text-blue-500" />
                            </div>
                        </a>
                    </div>

                    <!-- Center: Navigation Links -->
                    <div class="hidden sm:flex sm:space-x-12 justify-center flex-1">
                        <x-nav-link :href="route('home')" :active="request()->routeIs('home')" class="text-gray-600 hover:text-gray-900 py-2 text-md font-medium">
                            {{ __('Home') }}
                        </x-nav-link>
                        <x-nav-link :href="route('about')" :active="request()->routeIs('about')" class="text-gray-600 hover:text-gray-900 py-2 text-md font-medium">
                            {{ __('About') }}
                        </x-nav-link>
                        <x-nav-link :href="route('howitswork')" :active="request()->routeIs('howitswork')" class="text-gray-600 hover:text-gray-900 py-2 text-md font-medium">
                            {{ __("How it Works") }}
                        </x-nav-link>
                        <div @mouseenter="shopMenuOpen = true" @mouseleave="shopMenuOpen = false" class="relative group h-full flex items-center">
                            <x-nav-link :href="route('shop.index')" :active="request()->routeIs('shop.*')" class="text-gray-600 hover:text-gray-900 py-2 text-md font-medium flex items-center gap-1.5 transition-colors">
                                {{ __('Shop') }}
                                <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': shopMenuOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </x-nav-link>

                            <!-- Shop Dropdown -->
                            <div x-show="shopMenuOpen"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 translate-y-1"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 translate-y-1"
                                class="absolute left-1/2 -translate-x-1/2 top-12 w-64 bg-white border border-gray-200 shadow-xl rounded-2xl z-50 overflow-hidden"
                                style="display: none;">
                                <div class="p-2">
                                    <a href="{{ route('landing.cards') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-[#1800ad] rounded-xl transition-colors">
                                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center text-[#1800ad]">
                                            <i class="fa-solid fa-id-card"></i>
                                        </div>
                                        <div>
                                            <p class="font-bold">Review Cards</p>
                                            <p class="text-xs text-gray-500">Tap to review cards</p>
                                        </div>
                                    </a>
                                    <a href="{{ route('landing.stand') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-[#1800ad] rounded-xl transition-colors">
                                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center text-[#1800ad]">
                                            <i class="fa-solid fa-envelopes-bulk"></i>
                                        </div>
                                        <div>
                                            <p class="font-bold">Review Stands</p>
                                            <p class="text-xs text-gray-500">Tabletop review plaques</p>
                                        </div>
                                    </a>
                                    <a href="{{ route('landing.keychain') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-[#1800ad] rounded-xl transition-colors">
                                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center text-[#1800ad]">
                                            <i class="fa-solid fa-key"></i>
                                        </div>
                                        <div>
                                            <p class="font-bold">Review Keychains</p>
                                            <p class="text-xs text-gray-500">Portable review tags</p>
                                        </div>
                                    </a>
                                    <a href="{{ route('orders.track') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-indigo-50 hover:text-[#1800ad] rounded-xl transition-colors border-t border-gray-100">
                                        <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center text-[#1800ad]">
                                            <i class="fa-solid fa-truck-fast"></i>
                                        </div>
                                        <div>
                                            <p class="font-bold">Track Order</p>
                                            <p class="text-xs text-gray-500">Royal Mail parcel tracking</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <x-nav-link :href="route('blog.index')" :active="request()->routeIs('blog.*')" class="text-gray-600 hover:text-gray-900 py-2 text-md font-medium">
                            {{ __('Blog') }}
                        </x-nav-link>
                        <x-nav-link :href="route('contact')" :active="request()->routeIs('contact')" class="text-gray-600 hover:text-gray-900 py-2 text-md font-medium">
                            {{ __('Contact') }}
                        </x-nav-link>
                    </div>

                    <!-- Right: Auth Links -->
                    <div class="hidden sm:flex items-center space-x-4">
                        <!-- Cart Icon (Triggers Drawer) -->
                        <button @click="$dispatch('open-cart')" class="p-2 text-gray-600 hover:text-[#01A0FF] transition-colors relative group" title="Open Cart">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span class="cart-count-badge absolute top-1 right-1 bg-[#007cb5] text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center shadow-sm" style="display: none;">0</span>
                        </button>
                        @auth
                        <div @mouseenter="userMenuOpen = true" @mouseleave="userMenuOpen = false" class="relative">
                            <!-- User Pill Button -->
                            <button class="flex items-center gap-2.5 px-3 py-1.5 rounded-full border border-gray-200 bg-white hover:border-[#00A0FF] hover:shadow-md transition-all duration-200 focus:outline-none">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-[#142D63] to-[#00A0FF] text-white flex items-center justify-center font-bold text-xs shadow-inner">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </div>
                                <div class="text-left hidden md:block">
                                    <div class="text-xs font-bold text-[#142D63] leading-none">{{ auth()->user()->name }}</div>
                                    @if(auth()->user()->isApprovedPartner())
                                        <div class="text-[10px] font-extrabold text-purple-700 leading-none mt-0.5">👑 {{ auth()->user()->partner_type_label }}</div>
                                    @else
                                        <div class="text-[10px] text-gray-500 leading-none mt-0.5">{{ auth()->user()->role === 'admin' ? 'Administrator' : 'My Account' }}</div>
                                    @endif
                                </div>
                                <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': userMenuOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Dropdown -->
                            <div x-show="userMenuOpen" 
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 translate-y-1"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 translate-y-1"
                                class="absolute right-0 mt-2 w-60 bg-white border border-gray-200 shadow-xl rounded-2xl z-50 overflow-hidden"
                                style="display: none;">
                                
                                <!-- User Header -->
                                <div class="p-4 bg-gray-50 border-b border-gray-100">
                                    <p class="text-xs font-bold text-gray-900 leading-tight truncate">{{ auth()->user()->name }}</p>
                                    <p class="text-[11px] text-gray-500 truncate mt-0.5">{{ auth()->user()->email }}</p>
                                    @if(auth()->user()->isApprovedPartner())
                                        <span class="inline-block mt-2 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-purple-100 text-purple-800 border border-purple-200">
                                            👑 {{ auth()->user()->partner_type_label }}
                                        </span>
                                    @elseif(auth()->user()->isPendingPartner())
                                        <span class="inline-block mt-2 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-800 border border-amber-200">
                                            ⏳ {{ auth()->user()->partner_type_label }} (Pending Approval)
                                        </span>
                                    @endif
                                </div>

                                <div class="p-1">
                                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 px-3 py-2.5 text-xs font-semibold text-gray-700 hover:bg-blue-50 hover:text-[#00A0FF] rounded-xl transition-colors">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                        Dashboard
                                    </a>
                                    <a href="{{ route('user.orders.index') }}" class="flex items-center gap-2.5 px-3 py-2.5 text-xs font-semibold text-gray-700 hover:bg-blue-50 hover:text-[#00A0FF] rounded-xl transition-colors">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                        My Orders
                                    </a>
                                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-3 py-2.5 text-xs font-semibold text-gray-700 hover:bg-blue-50 hover:text-[#00A0FF] rounded-xl transition-colors">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        Profile Settings
                                    </a>
                                </div>

                                <div class="p-1 border-t border-gray-100">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2 text-xs font-semibold text-red-600 hover:bg-red-50 rounded-xl transition-colors">
                                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                            Log Out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @else
                            <a href="{{ route('login') }}" class="text-[#0cc0df] hover:text-[#1800ad] px-3 py-2 text-lg font-medium font-mulish">
                                {{ __('Sign in') }}
                            </a>
                            <a href="{{ route('register') }}" class="bg-[#0cc0df] hover:bg-[#1800ad] text-white px-[15px] py-[12px] text-[17px] font-medium font-mulish rounded-md transition-colors duration-200">
                                {{ __('Get your account') }}
                            </a>
                        @endauth
                    </div>

                    <!-- Hamburger (Mobile) -->
                    <div class="flex items-center sm:hidden gap-2">
                        <!-- Mobile Cart Icon (Triggers Drawer) -->
                        <button @click="$dispatch('open-cart')" class="p-2 text-gray-600 hover:text-[#01A0FF] transition-colors relative">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span class="cart-count-badge absolute top-0 right-0 bg-[#007cb5] text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center shadow-sm" style="display: none;">0</span>
                        </button>

                        @auth
                            <div class="flex items-center">
                                <a href="{{ route('dashboard') }}" class="w-8 h-8 rounded-full bg-gradient-to-tr from-[#142D63] to-[#00A0FF] text-white flex items-center justify-center font-bold text-xs shadow-inner">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </a>
                            </div>
                        @endauth

                        <button @click="open = !open" class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition-colors">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Overlay -->
            <div x-show="open" 
                 x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-black bg-opacity-50 z-40 sm:hidden"
                 @click="open = false"
                 style="display: none;"></div>

            <!-- Slide-in Mobile Menu -->
            <div x-show="open"
                 x-transition:enter="transition ease-in-out duration-300 transform"
                 x-transition:enter-start="-translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in-out duration-300 transform"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="-translate-x-full"
                 class="fixed top-0 left-0 w-80 h-full bg-white shadow-xl z-50 sm:hidden"
                 style="display: none;">
                
                <!-- Menu Header -->
                <div class="flex items-center justify-between p-4 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="h-16 w-32 flex items-center justify-center text-white font-bold text-sm">
                             <x-application-logo class="block h-16 w-auto fill-current text-blue-500" />
                        </div>
                    </div>
                    <button @click="open = false" class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Menu Content -->
                <div class="p-4">
                    <!-- Navigation Links -->
                    <div class="space-y-1 mb-6">
                        <a href="{{ route('home') }}" 
                           class="block px-4 py-3 text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50 rounded-md transition-colors"
                           @click="open = false">
                            Home
                        </a>
                        <a href="{{ route('about') }}" 
                           class="block px-4 py-3 text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50 rounded-md transition-colors"
                           @click="open = false">
                            About
                        </a>
                        <a href="{{ route('howitswork') }}" 
                           class="block px-4 py-3 text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50 rounded-md transition-colors"
                           @click="open = false">
                            How it Works
                        </a>
                        <div x-data="{ shopOpen: false }">
                            <button @click="shopOpen = !shopOpen" class="w-full flex items-center justify-between px-4 py-3 text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50 rounded-md transition-colors">
                                <span>Shop</span>
                                <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': shopOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </button>
                            <div x-show="shopOpen" x-collapse class="pl-4 space-y-1">
                                <a href="{{ route('landing.cards') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-[#1800ad]">Review Cards</a>
                                <a href="{{ route('landing.stand') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-[#1800ad]">Review Stands</a>
                                <a href="{{ route('landing.keychain') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-[#1800ad]">Review Keychains</a>
                            </div>
                        </div>
                        <a href="{{ route('blog.index') }}" 
                           class="block px-4 py-3 text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50 rounded-md transition-colors"
                           @click="open = false">
                            Blog
                        </a>
                        <a href="{{ route('contact') }}" 
                           class="block px-4 py-3 text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50 rounded-md transition-colors"
                           @click="open = false">
                            Contact
                        </a>
                    </div>

                    <!-- Auth Links -->
                    <div class="border-t border-gray-200 pt-4 space-y-3">
                        @auth
                            <!-- For Authenticated Users -->
                            <div class="bg-gray-50 rounded-xl p-3 border border-gray-100 mb-2">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-[#142D63] to-[#00A0FF] text-white flex items-center justify-center font-bold text-xs shadow-inner">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-bold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                                        <p class="text-[11px] text-gray-500 truncate">{{ auth()->user()->email }}</p>
                                    </div>
                                </div>
                                @if(auth()->user()->isApprovedPartner())
                                    <span class="inline-block mt-2 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-purple-100 text-purple-800 border border-purple-200">
                                        👑 {{ auth()->user()->partner_type_label }} ({{ auth()->user()->getPartnerDiscountPercent() }}% OFF)
                                    </span>
                                @elseif(auth()->user()->isPendingPartner())
                                    <span class="inline-block mt-2 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-800 border border-amber-200">
                                        ⏳ {{ auth()->user()->partner_type_label }} (Pending Approval)
                                    </span>
                                @endif
                            </div>

                            <a href="{{ route('dashboard') }}" 
                               class="block w-full text-center bg-[#00A0FF] hover:bg-blue-600 text-white px-4 py-2.5 font-bold font-mulish rounded-xl transition-colors duration-200 text-sm mb-2"
                               @click="open = false">
                                Go to Dashboard
                            </a>

                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit"
                                    class="block w-full text-center bg-gray-100 hover:bg-gray-200 text-red-600 px-4 py-2.5 font-medium font-mulish rounded-xl transition-colors duration-200 text-sm"
                                    @click="open = false">
                                    {{ __('Log Out') }}
                                </button>
                            </form>
                        @else
                            <!-- For Guest Users -->
                            <a href="{{ route('login') }}" 
                            class="block w-full text-center px-4 py-3 text-[#0cc0df] hover:text-[#1800ad] hover:bg-blue-50 font-medium font-mulish rounded-md transition-colors"
                            @click="open = false">
                                Sign in
                            </a>
                            <a href="{{ route('register') }}" 
                            class="block w-full text-center bg-[#0cc0df] hover:bg-[#1800ad] text-white px-4 py-3 font-medium font-mulish rounded-md transition-colors duration-200"
                            @click="open = false">
                                Get your account
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>