<!-- iOS PWA Install Bottom Sheet -->
<div x-data="{ open: false }"
     @open-ios-install-modal.window="open = true"
     @keydown.escape.window="open = false"
     x-show="open"
     style="display:none;"
     class="fixed inset-0 z-[100]"
     role="dialog" aria-modal="true" aria-labelledby="ios-modal-title">

    <!-- Backdrop -->
    <div x-show="open"
         @click="open = false"
         x-transition:enter="transition-opacity ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"
         style="display:none;"></div>

    <!-- Bottom Sheet -->
    <div x-show="open"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="translate-y-full"
         x-transition:enter-end="translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-y-0"
         x-transition:leave-end="translate-y-full"
         class="absolute bottom-0 inset-x-0 mx-auto w-full max-w-md bg-white rounded-t-3xl shadow-2xl border border-gray-100 px-6 pt-3"
         style="display:none; padding-bottom: max(1rem, env(safe-area-inset-bottom));">

        <!-- Drag handle -->
        <div class="w-10 h-1 bg-gray-300 rounded-full mx-auto mb-5"></div>

        <!-- Header -->
        <div class="flex justify-between items-start mb-5">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 id="ios-modal-title" class="text-lg font-black text-gray-900 font-ubuntu">Install App</h3>
                    <p class="text-xs text-gray-500 font-mulish">Follow 3 simple steps to add to Home Screen</p>
                </div>
            </div>
            <button @click="open = false" class="text-gray-400 hover:text-gray-600 p-1.5 rounded-full hover:bg-gray-100 transition-colors" aria-label="Close">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Steps -->
        <div class="space-y-2 mb-4">
            <div class="flex items-center gap-3.5 p-3.5 rounded-2xl bg-indigo-50/50 border border-indigo-100">
                <div class="w-8 h-8 rounded-full bg-indigo-600 text-white font-bold flex items-center justify-center text-sm shrink-0 font-ubuntu shadow-sm">1</div>
                <div class="text-xs font-mulish text-gray-700 leading-relaxed">
                    Tap the <strong class="text-indigo-900">Share</strong> button
                    <span class="inline-flex items-center gap-1 bg-white border border-gray-200 px-2 py-0.5 rounded-md text-indigo-600 font-bold text-[11px] shadow-sm">
                        <svg class="w-3.5 h-3.5 inline text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                        Share
                    </span>
                    in the Safari bar.
                </div>
            </div>

            <div class="flex items-center gap-3.5 p-3.5 rounded-2xl bg-indigo-50/50 border border-indigo-100">
                <div class="w-8 h-8 rounded-full bg-indigo-600 text-white font-bold flex items-center justify-center text-sm shrink-0 font-ubuntu shadow-sm">2</div>
                <div class="text-xs font-mulish text-gray-700 leading-relaxed">
                    Scroll down and tap
                    <span class="inline-flex items-center gap-1 bg-white border border-gray-200 px-2 py-0.5 rounded-md text-indigo-600 font-bold text-[11px] shadow-sm">
                        <svg class="w-3.5 h-3.5 inline text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Add to Home Screen
                    </span>.
                </div>
            </div>

            <div class="flex items-center gap-3.5 p-3.5 rounded-2xl bg-indigo-50/50 border border-indigo-100">
                <div class="w-8 h-8 rounded-full bg-indigo-600 text-white font-bold flex items-center justify-center text-sm shrink-0 font-ubuntu shadow-sm">3</div>
                <div class="text-xs font-mulish text-gray-700 leading-relaxed">
                    Tap <strong class="text-indigo-900">Add</strong> in the top-right corner, then find the app icon on your Home Screen.
                </div>
            </div>
        </div>

        <!-- Bouncing arrow pointing to Safari's Share button -->
        <div class="flex flex-col items-center gap-1 pt-1 pb-1">
            <span class="text-[11px] font-bold text-indigo-600 font-mulish">Share button is right below</span>
            <svg class="w-8 h-8 text-indigo-600 animate-bounce" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>
    </div>
</div>