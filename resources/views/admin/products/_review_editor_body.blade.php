{{-- Per-item review-link editor body. Expects the surrounding element to have
     x-data="orderItemLocation(...)" providing: mode, link, name, saving, save(),
     setMode(), and x-ref="search" target for Google Places autocomplete. --}}
<div x-show="editing" x-cloak class="mt-2 space-y-2 bg-gray-50 border border-gray-200 rounded-lg p-3">
    <div class="flex gap-1.5">
        <button type="button" @click="setMode('search')"
                :class="mode === 'search' ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-100'"
                class="px-2.5 py-1 rounded-md text-[10px] font-bold transition">Google Search</button>
        <button type="button" @click="setMode('direct')"
                :class="mode === 'direct' ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-100'"
                class="px-2.5 py-1 rounded-md text-[10px] font-bold transition">Paste Direct Link / URL</button>
    </div>

    {{-- Mode A: Google business search (autocomplete) --}}
    <div x-show="mode === 'search'" class="relative">
        <input x-ref="search" type="text" placeholder="Search your business on Google…"
               class="w-full text-xs border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none">
        <p class="text-[10px] text-green-600 mt-1" x-show="mode === 'search' && placeId">
            ✓ Selected: <span class="font-bold" x-text="name"></span>
        </p>
    </div>

    {{-- Mode B: Paste direct review URL --}}
    <div x-show="mode === 'direct'" class="space-y-2">
        <input x-model="link" type="text" placeholder="Paste review URL (https://g.page/r/… )"
               class="w-full text-xs border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none font-mono">
        <input x-model="name" type="text" placeholder="Business name (optional)"
               class="w-full text-xs border border-gray-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none">
    </div>

    <div class="flex gap-2">
        <button type="button" @click="save()" :disabled="saving"
                class="bg-blue-600 text-white text-xs font-bold px-4 py-2 rounded-lg hover:bg-blue-700 transition disabled:opacity-50">
            <span x-text="saving ? 'Saving…' : 'Save link'"></span>
        </button>
        <button type="button" @click="editing = false" class="text-xs font-bold text-gray-500 px-3 py-2">Cancel</button>
    </div>
</div>
