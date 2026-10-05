{{-- resources/views/components/usersidebar.blade.php --}}
<div x-data="{
        sidebarOpen: false,
        canInstall: (function() {
            const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
            const isStandalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;
            return isIOS && !isStandalone;
        })(),
        init() {
            window.addEventListener('pwa-installable', () => { this.canInstall = true; });
            window.addEventListener('pwa-installed', () => { this.canInstall = false; });
        }
    }"
    x-effect="sidebarOpen ? document.body.classList.add('overflow-hidden') : document.body.classList.remove('overflow-hidden')"
>
    <!-- Mobile menu button -->
    <button 
        @click="sidebarOpen = !sidebarOpen"
        class="lg:hidden fixed top-4 left-4 z-50 p-2 rounded-md bg-white shadow-lg text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition-colors border border-gray-200"
        aria-label="Toggle sidebar"
    >
        <i data-lucide="menu" x-show="!sidebarOpen" class="h-6 w-6"></i>
        <i data-lucide="x" x-show="sidebarOpen" class="h-6 w-6"></i>
    </button>

    <!-- Overlay for mobile -->
    <div 
        x-show="sidebarOpen" 
        @click="sidebarOpen = false"
        x-transition:enter="transition-opacity ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="lg:hidden fixed inset-0 bg-black bg-opacity-50 z-40"
        style="display: none;"
    ></div>

    <!-- Sidebar -->
    <aside 
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        class="fixed top-0 left-0 w-64 bg-white px-4 py-6 z-50 transform transition-transform duration-300 ease-in-out flex flex-col shadow-md" style="height: 100dvh; overflow-y: auto; -webkit-overflow-scrolling: touch;"
    >
        <!-- Logo Section -->
        <div>
            <div class="flex mb-4">
                <a href="{{ url('/') }}" class="text-gray-700 hover:text-blue-600">
                    <x-application-logo class="block h-auto w-40 fill-current text-blue-500" />
                </a>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="space-y-2 mt-6">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" 
               class="flex items-center gap-3 text-gray-700 hover:text-blue-600 px-3 py-2 rounded-lg hover:bg-blue-100 transition group {{ request()->routeIs('dashboard') ? 'bg-blue-100 text-blue-600' : '' }}"
               @click="window.innerWidth < 1024 && (sidebarOpen = false)">
                <i data-lucide="layout-dashboard" class="h-5 w-5 flex-shrink-0"></i>
                <span class="truncate">Dashboard</span>
            </a>

            <!-- Manage Reviews -->
            <a href="{{ route('business.reviews') }}" 
               class="flex items-center gap-3 text-gray-700 hover:text-blue-600 px-3 py-2 rounded-lg hover:bg-blue-100 transition group {{ request()->routeIs('business.reviews') ? 'bg-blue-100 text-blue-600' : '' }}"
               @click="window.innerWidth < 1024 && (sidebarOpen = false)">
                <i data-lucide="message-square" class="h-5 w-5 flex-shrink-0"></i>
                <span class="truncate">Manage Reviews</span>
            </a>

            <!-- View All Reviews -->
            <a href="{{ route('business.reviews.feedback') }}" 
               class="flex items-center gap-3 text-gray-700 hover:text-blue-600 px-3 py-2 rounded-lg hover:bg-blue-100 transition group {{ request()->routeIs('business.reviews.feedback') ? 'bg-blue-100 text-blue-600' : '' }}"
               @click="window.innerWidth < 1024 && (sidebarOpen = false)">
                <i data-lucide="message-square-off" class="h-5 w-5 flex-shrink-0"></i>
                <span class="truncate">View Negative Reviews</span>
            </a>
            
            <a href="{{ route('businesses.index') }}" 
               class="flex items-center gap-3 text-gray-700 hover:text-blue-600 px-3 py-2 rounded-lg hover:bg-blue-100 transition group {{ request()->routeIs('businesses.index.*') ? 'bg-blue-100 text-blue-600' : '' }}"
               @click="window.innerWidth < 1024 && (sidebarOpen = false)">
                <i data-lucide="building-2" class="h-5 w-5 flex-shrink-0"></i>
                <span class="truncate">Manage Business</span>
            </a>


            <!-- Create ReviewCard Dropdown -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" 
                        class="flex items-center gap-3 text-gray-700 hover:text-blue-600 px-3 py-2 rounded-lg hover:bg-blue-100 transition w-full focus:outline-none group {{ request()->routeIs('cards.*') ? 'bg-blue-100 text-blue-600' : '' }}">
                    <i data-lucide="credit-card" class="h-5 w-5 flex-shrink-0"></i>
                    <span class="truncate flex-1 text-left">Create ReviewCard</span>
                    <svg xmlns="http://www.w3.org/2000/svg" :class="{ 'rotate-180': open }" class="h-4 w-4 flex-shrink-0 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="open" @click.away="open = false" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95"
                     class="absolute left-0 mt-1 w-full bg-white rounded-lg shadow-lg z-30 border border-gray-200 overflow-hidden"
                     style="display: none;">
                    <a href="{{ route('cards.create') }}" 
                       class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-100 hover:text-blue-600 transition {{ request()->routeIs('cards.create') ? 'bg-blue-50 text-blue-600' : '' }}"
                       @click="window.innerWidth < 1024 && (sidebarOpen = false)">Create Card</a>
                    <a href="{{ route('cards.index') }}" 
                       class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-100 hover:text-blue-600 transition border-t border-gray-100 {{ request()->routeIs('cards.index') ? 'bg-blue-50 text-blue-600' : '' }}"
                       @click="window.innerWidth < 1024 && (sidebarOpen = false)">View Cards</a>
                </div>
            </div>
            
            <!-- Manage Subscription -->
            <a href="{{ route('user.subscription.index') }}" 
               class="flex items-center gap-3 text-gray-700 hover:text-blue-600 px-3 py-2 rounded-lg hover:bg-blue-100 transition group {{ request()->routeIs('user.subscription.*') ? 'bg-blue-100 text-blue-600' : '' }}"
               @click="window.innerWidth < 1024 && (sidebarOpen = false)">
                <i data-lucide="package" class="h-5 w-5 flex-shrink-0"></i>
                <span class="truncate">Manage Subscription</span>
            </a>

            <!-- My Orders -->
            <a href="{{ route('user.orders.index') }}" 
               class="flex items-center gap-3 text-gray-700 hover:text-blue-600 px-3 py-2 rounded-lg hover:bg-blue-100 transition group {{ request()->routeIs('user.orders.*') ? 'bg-blue-100 text-blue-600' : '' }}"
               @click="window.innerWidth < 1024 && (sidebarOpen = false)">
                <i data-lucide="shopping-bag" class="h-5 w-5 flex-shrink-0"></i>
                <span class="truncate">My Orders</span>
            </a>

            <!-- Edit Profile -->
            <a href="{{ route('profile.edit') }}" 
               class="flex items-center gap-3 text-gray-700 hover:text-blue-600 px-3 py-2 rounded-lg hover:bg-blue-100 transition group {{ request()->routeIs('profile.edit') ? 'bg-blue-100 text-blue-600' : '' }}"
               @click="window.innerWidth < 1024 && (sidebarOpen = false)">
                <i data-lucide="user-cog" class="h-5 w-5 flex-shrink-0"></i>
                <span class="truncate">Edit Profile</span>
            </a>

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}" class="mt-6">
                @csrf
                <button type="submit" 
                        class="flex items-center gap-3 text-gray-700 hover:text-red-600 px-3 py-2 rounded-lg hover:bg-red-50 transition group w-full"
                        @click="window.innerWidth < 1024 && (sidebarOpen = false)">
                    <i data-lucide="log-out" class="h-5 w-5 flex-shrink-0 text-red-500"></i>
                    <span class="truncate">Logout</span>
                </button>
            </form>
        </nav>

        <!-- Mobile Install App (PWA) -->
        <div class="mt-6 border-t border-gray-100 pt-6" x-show="canInstall" x-cloak>
            <button @click="window.installPWA(); window.innerWidth < 1024 && (sidebarOpen = false)"
                    class="flex items-center justify-center gap-3 w-full text-white bg-indigo-600 hover:bg-indigo-700 px-4 py-3.5 rounded-xl transition-all duration-300 font-bold shadow-lg shadow-indigo-100 active:scale-95">
                <i data-lucide="download" class="h-5 w-5 flex-shrink-0"></i>
                <span class="truncate">Install App</span>
            </button>
        </div>
    </aside>
</div>

<script src="//unpkg.com/alpinejs" defer></script>