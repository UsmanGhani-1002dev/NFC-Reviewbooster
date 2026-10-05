{{-- Admin command palette: Ctrl+K / ⌘K — jump to a page, user, order, business or product. --}}
@php
    $cmdPages = [
        ['label' => 'Analytics',            'url' => route('admin.analytics'),                   'keywords' => 'stats reports traffic sales abandoned carts revenue', 'icon' => 'bar-chart-3'],
        ['label' => 'Manage Users',         'url' => route('admin.users.index'),                 'keywords' => 'customers accounts members', 'icon' => 'users'],
        ['label' => 'Manage Businesses',    'url' => route('admin.manage_business.index'),       'keywords' => 'companies stores', 'icon' => 'building-2'],
        ['label' => 'Manage Products',      'url' => route('admin.products.index'),              'keywords' => 'shop items variants cards keyrings stands', 'icon' => 'package'],
        ['label' => 'Manage Orders',        'url' => route('admin.orders.index'),                'keywords' => 'sales purchases shipping', 'icon' => 'shopping-cart'],
        ['label' => 'Manage Subscriptions', 'url' => route('admin.manage-subscription.index'),   'keywords' => 'subs billing', 'icon' => 'credit-card'],
        ['label' => 'Contact Submissions',  'url' => route('admin.contact-submissions.index'),   'keywords' => 'messages inbox enquiries', 'icon' => 'mail'],
        ['label' => 'Settings',             'url' => route('admin.settings.index'),              'keywords' => 'config preferences delivery fees stripe', 'icon' => 'settings'],
    ];
@endphp

<div
    x-data="commandPalette()"
    @keydown.window.ctrl.k.prevent="toggle()"
    @keydown.window.meta.k.prevent="toggle()"
    @keydown.window.escape="if (open) close()"
>
    {{-- Trigger (full "Search…" pill on desktop, icon-only on phones) --}}
    <button type="button" @click="openPalette()"
            class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-gray-50 hover:bg-gray-100 hover:border-gray-300 transition-colors text-gray-500
                   p-2 md:pl-3 md:pr-2 md:py-1.5 lg:w-60"
            title="Search (Ctrl/⌘ + K)">
        <i data-lucide="search" class="w-5 h-5 md:w-4 md:h-4 shrink-0"></i>
        <span class="hidden md:inline text-sm">Search…</span>
        <span class="ml-auto hidden md:inline-flex items-center rounded-md border border-gray-200 bg-white px-1.5 py-0.5 text-[10px] font-bold text-gray-500" x-text="shortcutLabel"></span>
    </button>

    {{-- Modal --}}
    <template x-teleport="body">
        <div x-show="open" x-cloak class="fixed inset-0 z-[100]" style="display:none;">
            <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm" x-transition.opacity @click="close()"></div>

            <div class="relative mx-auto mt-[12vh] w-[92%] max-w-xl"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100">
                <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">
                    {{-- Search input --}}
                    <div class="flex items-center gap-3 px-4 border-b border-gray-100">
                        <i data-lucide="search" class="w-5 h-5 text-gray-400 shrink-0"></i>
                        <input x-ref="input" x-model="query" type="text"
                               placeholder="Search pages, users, orders, businesses…"
                               class="w-full py-4 text-[15px] text-gray-800 placeholder-gray-400 border-0 focus:ring-0 outline-none bg-transparent"
                               @input="onQuery()"
                               @keydown.down.prevent="move(1)"
                               @keydown.up.prevent="move(-1)"
                               @keydown.enter.prevent="selectActive()">
                        <svg x-show="loading" class="w-4 h-4 text-blue-500 animate-spin shrink-0" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        <kbd class="hidden sm:inline-flex items-center rounded-md border border-gray-200 bg-gray-50 px-1.5 py-0.5 text-[10px] font-bold text-gray-400">ESC</kbd>
                    </div>

                    {{-- Results --}}
                    <ul x-ref="list" class="max-h-[55vh] overflow-y-auto py-2">
                        <template x-for="(item, idx) in filtered" :key="item.group + '|' + item.url">
                            <li>
                                <template x-if="idx === 0 || filtered[idx - 1].group !== item.group">
                                    <p class="px-4 pt-3 pb-1 text-[10px] font-bold uppercase tracking-widest text-gray-400" x-text="item.group"></p>
                                </template>
                                <a :href="item.url" :data-index="idx"
                                   @mouseenter="activeIndex = idx"
                                   :class="idx === activeIndex ? 'bg-blue-50 text-blue-700' : 'text-gray-800'"
                                   class="flex items-center gap-2 px-4 py-2.5 cursor-pointer transition-colors">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg shrink-0"
                                          :class="idx === activeIndex ? 'text-blue-600' : 'text-[#667085]'">
                                        <i :data-lucide="iconFor(item.icon)" class="w-5 h-5"></i>
                                    </span>
                                    <span class="min-w-0 flex-1">
                                        <span class="block text-[14px] font-medium truncate" x-text="item.label"></span>
                                        <span class="block text-[12px] text-gray-400 truncate" x-show="item.detail" x-text="item.detail"></span>
                                    </span>
                                    <span class="text-[11px] text-blue-600 shrink-0" x-show="idx === activeIndex"><i data-lucide="corner-down-left" class="w-4 h-4"></i></span>
                                </a>
                            </li>
                        </template>

                        <li x-show="loading && filtered.length === 0" class="px-4 py-10 text-center text-sm text-gray-400">Searching…</li>
                        <li x-show="!loading && filtered.length === 0 && query.trim().length >= 2" class="px-4 py-10 text-center text-sm text-gray-400">
                            No results for "<span x-text="query"></span>"
                        </li>
                    </ul>

                    <div class="flex items-center gap-4 px-4 py-2.5 border-t border-gray-100 bg-gray-50/60 text-[11px] text-gray-400">
                        <span class="inline-flex items-center gap-1"><kbd class="rounded border border-gray-200 bg-white px-1">↑</kbd><kbd class="rounded border border-gray-200 bg-white px-1">↓</kbd> navigate</span>
                        <span class="inline-flex items-center gap-1"><kbd class="rounded border border-gray-200 bg-white px-1">↵</kbd> open</span>
                        <span class="ml-auto hidden sm:inline" x-text="shortcutLabel + ' to toggle'"></span>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>

@once
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<script>
    function commandPalette() {
        // Agar backend search results ka 'type' kisi aur naam se aata hai (user/order/business/product),
        // to usay yahan Lucide icon naam se map karo.
        const TYPE_ICON_MAP = {
            page:     'chevron-right',
            user:     'user',
            order:    'shopping-cart',
            business: 'building-2',
            product:  'package',
        };

        const rawPages = @json($cmdPages);

        return {
            open: false,
            query: '',
            activeIndex: 0,
            loading: false,
            results: [],
            _timer: null,
            searchUrl: @json(route('admin.command-search')),
            pages: rawPages.map(i => ({ ...i, group: 'Pages', icon: i.icon || 'chevron-right' })),
            isMac: /Mac|iPhone|iPod|iPad/.test(navigator.platform || navigator.userAgent || ''),
            get shortcutLabel() { return this.isMac ? '⌘K' : 'Ctrl K'; },

            // item.icon pehle se Lucide ka naam ho sakta hai (pages ke liye),
            // ya ek type key ho sakta hai (search results ke liye) — dono handle karo
            iconFor(name) {
                return TYPE_ICON_MAP[name] || name || 'file';
            },

            get filtered() {
                const q = this.query.trim().toLowerCase();
                const pages = !q
                    ? this.pages
                    : this.pages.filter(i => (i.label + ' ' + (i.keywords || '')).toLowerCase().includes(q));
                return [...pages, ...this.results];
            },

            refreshIcons() {
                this.$nextTick(() => {
                    if (window.lucide) lucide.createIcons();
                });
            },

            onQuery() {
                this.activeIndex = 0;
                const q = this.query.trim();
                clearTimeout(this._timer);
                if (q.length < 2) { this.results = []; this.loading = false; this.refreshIcons(); return; }
                this.loading = true;
                this._timer = setTimeout(() => this.fetchResults(q), 180);
            },

            async fetchResults(q) {
                try {
                    const res = await fetch(this.searchUrl + '?q=' + encodeURIComponent(q), {
                        headers: { 'Accept': 'application/json' },
                        credentials: 'same-origin',
                    });
                    if (res.ok) {
                        const data = await res.json();
                        if (this.query.trim() === q) { this.results = data.results || []; this.activeIndex = 0; }
                    }
                } catch (e) {
                    if (this.query.trim() === q) this.results = [];
                } finally {
                    if (this.query.trim() === q) this.loading = false;
                    this.refreshIcons();
                }
            },

            toggle() { this.open ? this.close() : this.openPalette(); },

            openPalette() {
                this.open = true;
                this.query = '';
                this.results = [];
                this.activeIndex = 0;
                document.body.style.overflow = 'hidden';
                this.$nextTick(() => {
                    if (this.$refs.input) this.$refs.input.focus();
                    this.refreshIcons();
                });
            },

            close() {
                this.open = false;
                document.body.style.overflow = '';
            },

            move(dir) {
                const n = this.filtered.length;
                if (!n) return;
                this.activeIndex = (this.activeIndex + dir + n) % n;
                this.$nextTick(() => {
                    const el = this.$refs.list && this.$refs.list.querySelector('[data-index="' + this.activeIndex + '"]');
                    if (el) el.scrollIntoView({ block: 'nearest' });
                });
            },

            selectActive() {
                const it = this.filtered[this.activeIndex];
                if (it) window.location.href = it.url;
            },
        };
    }
</script>
@endonce