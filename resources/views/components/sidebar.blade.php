{{-- resources/views/components/adminsidebar.blade.php --}}
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

    <!-- Mobile menu button (only visible on mobile) -->
    <button 
        @click="sidebarOpen = !sidebarOpen"
        class="lg:hidden fixed top-4 left-4 z-50 p-2 rounded-md bg-white shadow-lg text-gray-600 hover:text-blue-600 hover:bg-blue-50 transition-colors border border-gray-200"
        aria-label="Toggle sidebar"
    >
        <i data-lucide="menu" x-show="!sidebarOpen" class="h-6 w-6"></i>
        <i data-lucide="x" x-show="sidebarOpen" class="h-6 w-6"></i>
    </button>

    <!-- Mobile overlay (backdrop) — sits OVER content but UNDER sidebar -->
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
    <!-- 
        On MOBILE:  fixed + off-screen by default, slides in on open (overlay — doesn't push content)
        On DESKTOP: fixed to left, always visible (use ml-64 on your main content wrapper)
    -->
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
               @click="sidebarOpen = false">
                <i data-lucide="layout-dashboard" class="h-5 w-5 flex-shrink-0"></i>
                <span class="truncate">Dashboard</span>
            </a>

            <!-- Analytics -->
            <a href="{{ route('admin.analytics') }}"
               class="flex items-center gap-3 text-gray-700 hover:text-blue-600 px-3 py-2 rounded-lg hover:bg-blue-100 transition group {{ request()->routeIs('admin.analytics') ? 'bg-blue-100 text-blue-600' : '' }}"
               @click="sidebarOpen = false">
                <i data-lucide="bar-chart-3" class="h-5 w-5 flex-shrink-0"></i>
                <span class="truncate">Analytics</span>
            </a>

            <!-- Manage Reviews -->
            <a href="{{ route('admin.reviews') }}"
               class="flex items-center gap-3 text-gray-700 hover:text-blue-600 px-3 py-2 rounded-lg hover:bg-blue-100 transition group {{ request()->routeIs('admin.reviews') ? 'bg-blue-100 text-blue-600' : '' }}"
               @click="sidebarOpen = false">
                <i data-lucide="message-square" class="h-5 w-5 flex-shrink-0"></i>
                <span class="truncate">Manage Reviews</span>
            </a>

            <!-- Manage Users -->
            <a href="{{ route('admin.users.index') }}" 
               class="flex items-center gap-3 text-gray-700 hover:text-blue-600 px-3 py-2 rounded-lg hover:bg-blue-100 transition group {{ request()->routeIs('admin.users.index') ? 'bg-blue-100 text-blue-600' : '' }}"
               @click="sidebarOpen = false">
                <i data-lucide="users" class="h-5 w-5 flex-shrink-0"></i>
                <span class="truncate">Manage Users</span>
            </a>
            
            <!-- Manage Businesses -->
            <a href="{{ route('admin.manage_business.index') }}" 
               class="flex items-center gap-3 text-gray-700 hover:text-blue-600 px-3 py-2 rounded-lg hover:bg-blue-100 transition group {{ request()->routeIs('admin.manage_business.index') ? 'bg-blue-100 text-blue-600' : '' }}"
               @click="sidebarOpen = false">
                <i data-lucide="building-2" class="h-5 w-5 flex-shrink-0"></i>
                <span class="truncate">Manage Businesses</span>
            </a>

            <!-- Manage Products -->
            <a href="{{ route('admin.products.index') }}" 
               class="flex items-center gap-3 text-gray-700 hover:text-blue-600 px-3 py-2 rounded-lg hover:bg-blue-100 transition group {{ request()->routeIs('admin.products.*') ? 'bg-blue-100 text-blue-600' : '' }}"
               @click="sidebarOpen = false">
                <i data-lucide="package" class="h-5 w-5 flex-shrink-0"></i>
                <span class="truncate">Manage Products</span>
            </a>

            <!-- Manage Orders -->
            <a href="{{ route('admin.orders.index') }}" 
               class="flex items-center gap-3 text-gray-700 hover:text-blue-600 px-3 py-2 rounded-lg hover:bg-blue-100 transition group {{ request()->routeIs('admin.orders.*') ? 'bg-blue-100 text-blue-600' : '' }}"
               @click="sidebarOpen = false">
                <i data-lucide="shopping-cart" class="h-5 w-5 flex-shrink-0"></i>
                <span class="truncate">Manage Orders</span>
            </a>

            <!-- Manage Blog -->
            <a href="{{ route('admin.blogs.index') }}" 
               class="flex items-center gap-3 text-gray-700 hover:text-blue-600 px-3 py-2 rounded-lg hover:bg-blue-100 transition group {{ request()->routeIs('admin.blogs.*') ? 'bg-blue-100 text-blue-600' : '' }}"
               @click="sidebarOpen = false">
                <i data-lucide="newspaper" class="h-5 w-5 flex-shrink-0"></i>
                <span class="truncate">Manage Blog</span>
            </a>

            <!-- Manage Subscriptions -->
            <a href="{{ route('admin.manage-subscription.index') }}" 
               class="flex items-center gap-3 text-gray-700 hover:text-blue-600 px-3 py-2 rounded-lg hover:bg-blue-100 transition group {{ request()->routeIs('admin.manage-subscription.index') ? 'bg-blue-100 text-blue-600' : '' }}"
               @click="sidebarOpen = false">
                <i data-lucide="book-check" class="h-5 w-5 flex-shrink-0"></i>
                <span class="truncate">Manage Subscriptions</span>
            </a>

            <!-- Subscription Plans -->
            <a href="{{ route('admin.subscription-plans.index') }}" 
               class="flex items-center gap-3 text-gray-700 hover:text-blue-600 px-3 py-2 rounded-lg hover:bg-blue-100 transition group {{ request()->routeIs('admin.subscription-plans.*') ? 'bg-blue-100 text-blue-600' : '' }}"
               @click="sidebarOpen = false">
                <i data-lucide="credit-card" class="h-5 w-5 flex-shrink-0"></i>
                <span class="truncate">Subscription Plans</span>
            </a>

            <!-- Contact Submissions -->
            <a href="{{ route('admin.contact-submissions.index') }}" 
               class="flex items-center gap-3 text-gray-700 hover:text-blue-600 px-3 py-2 rounded-lg hover:bg-blue-100 transition group {{ request()->routeIs('admin.contact-submissions.index') ? 'bg-blue-100 text-blue-600' : '' }}"
               @click="sidebarOpen = false">
                <i data-lucide="mail" class="h-5 w-5 flex-shrink-0"></i>
                <span class="truncate">Contact Submissions</span>
            </a>

            <!-- Edit Profile -->
            <a href="{{ route('profile.edit') }}" 
               class="flex items-center gap-3 text-gray-700 hover:text-blue-600 px-3 py-2 rounded-lg hover:bg-blue-100 transition group {{ request()->routeIs('profile.edit') ? 'bg-blue-100 text-blue-600' : '' }}"
               @click="sidebarOpen = false">
                <i data-lucide="user-cog" class="h-5 w-5 flex-shrink-0"></i>
                <span class="truncate">Edit Profile</span>
            </a>

            <!-- Settings -->
            <a href="{{ route('admin.settings.index') }}" 
               class="flex items-center gap-3 text-gray-700 hover:text-blue-600 px-3 py-2 rounded-lg hover:bg-blue-100 transition group {{ request()->routeIs('admin.settings.*') ? 'bg-blue-100 text-blue-600' : '' }}"
               @click="sidebarOpen = false">
                <i data-lucide="settings" class="h-5 w-5 flex-shrink-0"></i>
                <span class="truncate">Settings</span>
            </a>

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}" class="mt-6">
                @csrf
                <button type="submit" 
                        class="flex items-center gap-3 text-gray-700 hover:text-red-600 px-3 py-2 rounded-lg hover:bg-red-50 transition group w-full"
                        @click="sidebarOpen = false">
                    <i data-lucide="log-out" class="h-5 w-5 flex-shrink-0 text-red-500"></i>
                    <span class="truncate">Logout</span>
                </button>
            </form>
        </nav>

        <!-- Install App (PWA) -->
        <div class="mt-6 border-t border-gray-100 pt-6" x-show="canInstall" x-cloak>
            <button @click="window.installPWA(); sidebarOpen = false"
                    class="flex items-center justify-center gap-3 w-full text-white bg-indigo-600 hover:bg-indigo-700 px-4 py-3.5 rounded-xl transition-all duration-300 font-bold shadow-lg shadow-indigo-100 active:scale-95">
                <i data-lucide="download" class="h-5 w-5 flex-shrink-0"></i>
                <span class="truncate">Install App</span>
            </button>
        </div>
    </aside>
</div>

<script src="//unpkg.com/alpinejs" defer></script>