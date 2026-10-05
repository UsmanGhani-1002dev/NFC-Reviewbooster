@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8 pb-6 border-b border-gray-100">
        <div>
            <div class="flex items-center gap-2 text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">
                <a href="{{ route('admin.manage_business.index') }}" class="hover:text-blue-600 transition-colors">Businesses</a>
                <span>/</span>
                <span class="text-gray-900">Manage Business & Cards</span>
            </div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ $business->legal_business_name }}</h1>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.manage_business.cards.create', $business->id) }}" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-blue-100">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Create New Card
            </a>
        </div>
    </div>
    
    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-4 p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 text-sm font-bold rounded-xl flex items-center gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-emerald-500"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('warning'))
        <div class="mb-4 p-4 bg-amber-50 border border-amber-100 text-amber-800 text-sm font-bold rounded-xl flex items-center gap-3 shadow-sm">
            <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center text-amber-600 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01M12 5c-3.866 0-7 3.134-7 7s3.134 7 7 7 7-3.134 7-7-3.134-7-7-7z" /></svg>
            </div>
            {{ session('warning') }}
        </div>
    @endif

    <!-- 1. Quick Info Bar -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Status</p>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full {{ $business->status === 'active' ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                <span class="text-gray-900 font-bold capitalize">{{ $business->status }}</span>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Owner</p>
            <p class="text-gray-900 font-bold truncate">{{ $business->user->name ?? 'N/A' }}</p>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Card Count</p>
            <p class="text-gray-900 font-bold">{{ $business->cards->count() }} Cards</p>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Subscription Plan</p>
            <p class="text-gray-900 font-bold capitalize">{{ $business->user->subscription->plan->name ?? $business->user->activeSubscription->plan->name ?? 'N/A' }}</p>
        </div>
    </div>

    <!-- 2. Business Settings Section -->
    <div class="bg-white md:p-8 p-6 rounded-3xl border border-gray-100 shadow-sm mb-12">
        <h3 class="text-xl font-black text-gray-900 mb-8 flex items-center gap-3">
            <i data-lucide="settings" class="w-6 h-6 text-blue-600"></i>
            Business Administration
        </h3>
        
        <form method="POST" action="{{ route('admin.manage_business.update_status', $business->id) }}" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Legal Business Name</label>
                    <input type="text" name="legal_business_name" value="{{ $business->legal_business_name }}" class="w-full bg-white border border-gray-200 rounded-xl p-4 text-sm font-semibold text-gray-700 focus:ring-4 focus:ring-blue-50 transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Business Website</label>
                    <input type="url" name="website" value="{{ $business->website }}" class="w-full bg-white border border-gray-200 rounded-xl p-4 text-sm font-semibold text-gray-700 focus:ring-4 focus:ring-blue-50 transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Business Name</label>
                    <input type="text" name="business_name" value="{{ $business->business_name }}" class="w-full bg-white border border-gray-200 rounded-xl p-4 text-sm font-semibold text-gray-700 focus:ring-4 focus:ring-blue-50 transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Email</label>
                    <input type="text" value="{{ $business->user->email ?? 'N/A' }}" readonly class="w-full bg-gray-50 border border-gray-100 rounded-xl p-4 text-sm font-semibold text-gray-500 cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Update Status</label>
                    <select name="status" class="w-full bg-white border border-gray-200 rounded-xl p-3.5 text-base font-semibold text-gray-900 focus:ring-4 focus:ring-blue-50 transition-all cursor-pointer">
                        <option value="active" {{ $business->status === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="blocked" {{ $business->status === 'blocked' ? 'selected' : '' }}>Blocked</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Subscription Plan</label>
                    <select name="plan_id" class="w-full bg-white border border-gray-200 rounded-xl p-3.5 text-base font-semibold text-gray-900 focus:ring-4 focus:ring-blue-50 transition-all cursor-pointer capitalize">
                        <option value="">-- No Plan --</option>
                        @foreach ($plans as $plan)
                            <option value="{{ $plan->id }}" {{ ($business->user->subscription->subscription_plan_id ?? null) == $plan->id ? 'selected' : '' }}>
                                {{ $plan->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Business-level Google Review Link (submitted with form) --}}
            <input type="hidden" name="google_review_link" id="hidden-google-review-link" value="{{ $business->google_review_link }}">

            <div class="mt-6 p-6 bg-blue-50 rounded-2xl border border-blue-100">
                <h4 class="text-xs font-black text-blue-600 uppercase tracking-widest mb-4 flex items-center gap-2">
                    <i data-lucide="link" class="w-4 h-4"></i>
                    Direct Review Link (Default for all cards)
                </h4>

                {{-- Single unified row that toggles between view & edit --}}
                <div class="flex items-center bg-white rounded-xl border border-blue-200 shadow-sm overflow-hidden min-w-0">

                    {{-- View: truncated text --}}
                    <p id="rl-view-text" class="flex-1 min-w-0 px-4 py-3 text-sm font-medium text-gray-700 truncate">
                        {{ $business->google_review_link ?: 'No default link set' }}
                    </p>

                    {{-- Edit: url input (hidden by default) --}}
                    <input type="url" id="rl-edit-input"
                           value="{{ $business->google_review_link }}"
                           placeholder="https://search.google.com/local/writereview?placeid=..."
                           class="hidden flex-1 min-w-0 px-4 py-3 text-sm font-medium text-gray-700 border-0 focus:ring-0 focus:outline-none bg-white">

                    {{-- View mode buttons --}}
                    <div id="rl-view-actions" class="flex items-center gap-1 px-2 shrink-0">
                        <button type="button"
                                onclick="window.navigator.clipboard.writeText(document.getElementById('hidden-google-review-link').value); window.toast('Link Copied!', 'success')"
                                class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="Copy Link">
                            <i data-lucide="copy" class="w-4 h-4"></i>
                        </button>
                        <button type="button" onclick="enableRLEdit()"
                                class="flex items-center gap-1.5 px-3 py-1.5 text-[10px] font-black text-blue-600 bg-blue-100 hover:bg-blue-200 rounded-lg transition-all uppercase tracking-widest">
                            <i data-lucide="pencil" class="w-3 h-3"></i>
                            Edit Default Link
                        </button>
                    </div>

                    {{-- Edit mode buttons (hidden by default) --}}
                    <div id="rl-edit-actions" class="hidden items-center gap-1 px-2 shrink-0">
                        <button type="button" onclick="saveRL()"
                                class="px-3 py-1.5 text-[10px] font-black text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-all uppercase tracking-wide">
                            Save
                        </button>
                        <button type="button" onclick="cancelRL()"
                                class="px-3 py-1.5 text-[10px] font-black text-gray-500 bg-gray-100 hover:bg-gray-200 rounded-lg transition-all uppercase tracking-wide ml-1">
                            Cancel
                        </button>
                    </div>
                </div>

                @if($business->cards->count() > 0)
                <div class="mt-4 pt-4 border-t border-blue-100">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Cards using this link</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($business->cards as $card)
                        <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-full border border-blue-200 text-xs">
                            <span class="font-bold text-gray-700">{{ $card->name }}</span>
                            <span class="text-gray-400">&middot;</span>
                            <span class="text-gray-400 uppercase tracking-wide font-medium">{{ str_replace('_', ' ', $card->type) }}</span>
                        </div>
                        @endforeach
                    </div>
                    <p class="text-[10px] text-gray-400 mt-2">To update an individual card's link separately, use the <span class="font-bold">&#9999; edit</span> icon in the Active NFC Cards section below.</p>
                </div>
                @endif
            </div>

            <div class="flex justify-start">
                <button type="submit" class="px-8 py-3.5 bg-gray-900 text-white text-[11px] font-black rounded-xl hover:bg-black transition-all shadow-md uppercase tracking-widest">
                    Update Business Details
                </button>
            </div>
        </form>
    </div>

    <!-- 3. NFC Cards Section -->
    <div class="mb-12">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-xl font-black text-gray-900 flex items-center gap-3">
                <i data-lucide="credit-card" class="w-6 h-6 text-indigo-500"></i>
                Active NFC Cards
            </h2>
        </div>

        @if($business->cards && $business->cards->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @foreach($business->cards as $card)
                    @php
                        $typeColors = match($card->type) {
                            'facebook_page' => ['text' => 'text-blue-600', 'bg' => 'bg-blue-50', 'border' => 'border-blue-100'],
                            'instagram_page' => ['text' => 'text-pink-600', 'bg' => 'bg-pink-50', 'border' => 'border-pink-100'],
                            'google_review' => ['text' => 'text-red-600', 'bg' => 'bg-red-50', 'border' => 'border-red-100'],
                            default => ['text' => 'text-gray-600', 'bg' => 'bg-gray-50', 'border' => 'border-gray-100'],
                        };
                    @endphp
                    <div class="bg-white rounded-3xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-all flex flex-col sm:flex-row gap-6">
                        <!-- Left: QR -->
                        <div class="shrink-0 flex flex-col items-center">
                            <div id="qr-code-{{ $card->id }}" class="p-3 bg-gray-50 rounded-2xl border border-gray-100">
                                {!! QrCode::size(100)->generate(url('/r/' . $card->token)) !!}
                            </div>
                            <button onclick="downloadQrCode('qr-code-{{ $card->id }}', '{{ $card->token }}-qrcode.png')" class="mt-3 text-[10px] font-bold text-gray-400 hover:text-blue-600 uppercase tracking-widest flex items-center gap-1 transition-colors">
                                <i data-lucide="download" class="w-3 h-3"></i>
                                Download QR
                            </button>
                        </div>

                        <!-- Right: Info -->
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h4 class="text-xl font-bold text-gray-900 truncate mb-1">{{ $card->name }}</h4>
                                    <span class="inline-block px-2 py-0.5 {{ $typeColors['bg'] }} {{ $typeColors['text'] }} text-[10px] font-bold rounded border {{ $typeColors['border'] }} uppercase tracking-widest mb-1">
                                        {{ str_replace('_', ' ', $card->type) }}
                                    </span>
                                    @php
                                        $productIcon = match($card->product_type) {
                                            'sticker' => 'fa-circle-notch',
                                            'stand' => 'fa-tablet-screen-button',
                                            default => 'fa-credit-card',
                                        };
                                        $productLabel = match($card->product_type) {
                                            'sticker' => 'NFC Sticker',
                                            'stand' => 'Acrylic Stand',
                                            default => 'NFC Card',
                                        };
                                    @endphp
                                    <span class="inline-block px-2 py-0.5 bg-gray-50 text-gray-500 text-[10px] font-bold rounded border border-gray-100 uppercase tracking-widest mb-1">
                                        <i class="fas {{ $productIcon }} text-[8px] mr-1"></i>
                                        {{ $productLabel }}
                                    </span>
                                    
                                </div>
                                <div class="flex gap-1">
                                    <a href="{{ route('admin.manage_business.cards.edit', ['business_id' => $business->id, 'card' => $card->id]) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </a>
                                    <button onclick="confirmAdminCardDelete('{{ route('admin.manage_business.cards.destroy', ['business_id' => $business->id, 'card' => $card->id]) }}', {{ json_encode($card->name) }})" class="p-2 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="space-y-2 mb-6">
                                <div class="flex items-center gap-2 text-xs">
                                    <span class="text-gray-400 font-medium w-14 shrink-0">Link:</span>
                                    <div class="flex items-center gap-2 min-w-0">
                                        <a href="{{ url('/r/' . $card->token) }}" target="_blank" class="text-blue-600 font-bold hover:underline truncate">{{ $card->token }}</a>
                                        <button onclick="window.navigator.clipboard.writeText('{{ url('/r/' . $card->token) }}'); window.toast('Link Copied!', 'success')" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="Copy Card Link">
                                            <i data-lucide="copy" class="w-3.5 h-3.5 pt-0.5"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3 mt-3 pt-5 border-t border-gray-100">
                                <button onclick="writeNfc('{{ url('/r/' . $card->token) }}')" class="flex items-center justify-center gap-2 py-2 px-4 bg-gray-900 hover:bg-black text-white text-xs font-bold rounded-xl transition-all shadow-sm">
                                    <i data-lucide="nfc" class="w-4 h-4"></i>
                                    Write NFC
                                </button>
                                <button onclick="readNfc('{{ url('/r/' . $card->token) }}')" class="flex items-center justify-center gap-2 py-2 px-4 bg-white border border-gray-200 hover:border-gray-900 text-gray-900 text-xs font-bold rounded-xl transition-all">
                                    <i data-lucide="scan" class="w-4 h-4"></i>
                                    Read NFC
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-gray-50 border-2 border-dashed border-gray-200 rounded-3xl p-16 text-center">
                <div class="w-16 h-16 bg-white rounded-2xl shadow-sm border border-gray-100 flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="credit-card" class="w-8 h-8 text-gray-300"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">No cards yet</h3>
                <a href="{{ route('admin.manage_business.cards.create', $business->id) }}" class="inline-flex items-center gap-2 px-8 py-3 bg-blue-600 text-white font-bold rounded-xl shadow-lg uppercase text-xs tracking-widest">
                    Create first card
                </a>
            </div>
        @endif
    </div>

    <!-- 4. Danger Zone Section -->
    <div class="p-8 bg-rose-50 rounded-3xl border border-rose-100 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h4 class="text-rose-600 font-extrabold text-xl mb-1">Danger Zone</h4>
            <p class="text-rose-700 text-sm font-medium opacity-80">Deleting this business will permanently remove all associated review cards and data.</p>
        </div>
        <form id="delete-business-form-{{ $business->id }}" method="POST" action="{{ route('admin.manage_business.delete', $business->id) }}" class="shrink-0">
            @csrf
            @method('DELETE')
            <button type="button" onclick="confirmBusinessDelete({{ $business->id }}, {{ json_encode($business->legal_business_name) }})" class="w-full md:w-auto px-10 py-4 bg-rose-600 text-white text-[11px] font-black rounded-xl hover:bg-rose-700 transition-all shadow-lg shadow-rose-200 uppercase tracking-widest">
                Delete Business
            </button>
        </form>
    </div>
</div>

{{-- Hidden form for card delete (submitted by SweetAlert2 confirm) --}}
<form id="admin-delete-card-form" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

<div id="nfc-fallback-modal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-[110] hidden px-4 sm:px-6 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all duration-300 relative">
        
        <div class="bg-gray-50 px-6 py-5 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                NFC Compatibility
            </h3>
            <button onclick="closeNFCModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="p-6 space-y-6">
            <!-- Status Alert -->
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 flex gap-3">
                <svg class="w-6 h-6 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <div>
                    <p class="font-bold text-amber-800 text-sm mb-1" id="nfc-modal-title">Web NFC is not active</p>
                    <p class="text-amber-700 text-xs" id="nfc-modal-desc">This usually happens because you are on an iPhone, a desktop computer, or NFC is turned off in your Android settings.</p>
                </div>
            </div>

            <!-- Explanation / Solution -->
            <div>
                <h4 class="font-bold text-gray-900 mb-3 text-sm uppercase tracking-wide">How to write your card anyway:</h4>
                
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">1</div>
                        <div>
                            <p class="text-sm text-gray-800 font-medium mb-2">Copy the link below:</p>
                            <div class="flex">
                                <input type="text" id="fallback-url-input" class="text-xs bg-gray-100 border-gray-200 border-r-0 rounded-l-lg py-2 px-3 w-full text-gray-600 outline-none" readonly>
                                <button onclick="copyFallbackUrl()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-r-lg text-xs font-bold transition">Copy</button>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">2</div>
                        <div>
                            <p class="text-sm text-gray-800 font-medium mb-1">Download & Open "NFC Tools"</p>
                            <p class="text-xs text-gray-500 mb-2">A free app available on all platforms.</p>
                            <div class="flex gap-2">
                                <a href="https://apps.apple.com/us/app/nfc-tools/id1252962749" target="_blank" class="inline-flex items-center gap-1.5 text-xs bg-gray-900 hover:bg-black text-white px-3 py-1.5 rounded-lg transition">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.11-2.51 1.28-.02 2.5.87 3.28.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.36 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.14 1.36-.59 2.57-1.4 3.36-.78.78-2.11 1.36-3.1 1.25-.13-1.3.47-2.38 1.56-3.11z"/></svg>
                                    App Store
                                </a>
                                <a href="https://play.google.com/store/apps/details?id=com.wakdev.wdnfc&hl=en&gl=US" target="_blank" class="inline-flex items-center gap-1.5 text-xs bg-[#01875F] hover:bg-[#016849] text-white px-3 py-1.5 rounded-lg transition">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M3,20.5V3.5C3,2.91 3.34,2.39 3.84,2.15L13.69,12L3.84,21.85C3.34,21.6 3,21.09 3,20.5M16.81,15.12L6.05,21.34L14.54,12.85L16.81,15.12M20.16,10.81C20.5,11.08 20.75,11.5 20.75,12C20.75,12.5 20.5,12.92 20.16,13.19L17.89,14.5L15.39,12L17.89,9.5L20.16,10.81M6.05,2.66L16.81,8.88L14.54,11.15L6.05,2.66Z"/></svg>
                                    Play Store
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">3</div>
                        <div class="mt-1">
                            <p class="text-sm text-gray-800 font-medium">In the app, go to <strong>Write > Add a record > URL / URI</strong>, paste the link, and click Write!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
            <button onclick="closeNFCModal()" class="w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 rounded-xl transition">Got it</button>
        </div>
    </div>
</div>

<script>
    // --- Inline Edit: Business Default Google Review Link ---
    function enableRLEdit() {
        document.getElementById('rl-view-text').classList.add('hidden');
        document.getElementById('rl-view-actions').classList.add('hidden');
        document.getElementById('rl-edit-input').classList.remove('hidden');
        const ea = document.getElementById('rl-edit-actions');
        ea.classList.remove('hidden');
        ea.classList.add('flex');
        const input = document.getElementById('rl-edit-input');
        input.focus();
        input.select();
    }

    function saveRL() {
        const val = document.getElementById('rl-edit-input').value.trim();
        document.getElementById('hidden-google-review-link').value = val;
        document.getElementById('rl-view-text').textContent = val || 'No default link set';
        cancelRL();
    }

    function cancelRL() {
        document.getElementById('rl-edit-input').classList.add('hidden');
        const ea = document.getElementById('rl-edit-actions');
        ea.classList.add('hidden');
        ea.classList.remove('flex');
        document.getElementById('rl-view-text').classList.remove('hidden');
        document.getElementById('rl-view-actions').classList.remove('hidden');
        // restore input to last saved value
        document.getElementById('rl-edit-input').value = document.getElementById('hidden-google-review-link').value;
    }

    document.addEventListener('DOMContentLoaded', function () {
        const inp = document.getElementById('rl-edit-input');
        if (inp) {
            inp.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') { e.preventDefault(); saveRL(); }
                if (e.key === 'Escape') { cancelRL(); }
            });
        }
    });

    function downloadQrCode(containerId, filename) {
        const container = document.getElementById(containerId);
        if (!container) return;
        const svg = container.querySelector('svg');
        if (!svg) return;
        const svgClone = svg.cloneNode(true);
        svgClone.style.backgroundColor = 'white';
        const width = 300, height = 300;
        const serializer = new XMLSerializer();
        let svgStr = serializer.serializeToString(svgClone);
        const canvas = document.createElement('canvas');
        canvas.width = width;
        canvas.height = height;
        const ctx = canvas.getContext('2d');
        ctx.fillStyle = 'white';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        const DOMURL = window.URL || window.webkitURL || window;
        const img = new Image();
        const svgBlob = new Blob([svgStr], { type: 'image/svg+xml;charset=utf-8' });
        const url = DOMURL.createObjectURL(svgBlob);
        img.onload = function() {
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
            DOMURL.revokeObjectURL(url);
            const a = document.createElement('a');
            a.download = filename;
            a.href = canvas.toDataURL('image/png');
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        };
        img.src = url;
    }

    function showNFCModal(url, title, desc) {
        document.getElementById('fallback-url-input').value = url || '';
        if (title) document.getElementById('nfc-modal-title').textContent = title;
        if (desc) document.getElementById('nfc-modal-desc').textContent = desc;
        document.getElementById('nfc-fallback-modal').classList.remove('hidden');
    }

    function closeNFCModal() {
        document.getElementById('nfc-fallback-modal').classList.add('hidden');
    }

    function copyFallbackUrl() {
        const urlInput = document.getElementById('fallback-url-input');
        urlInput.select();
        document.execCommand('copy');
        if (window.toast) window.toast('Link copied!', 'success');
    }

    async function writeNfc(url) {
        try {
            if ("NDEFReader" in window) {
                const ndef = new NDEFReader();
                window.toast('📲 Ready to write! Tap your NFC card to the back of your phone.', 'info');
                
                // Set a timeout to cancel writing if not done in 30 seconds
                const controller = new AbortController();
                const timeoutId = setTimeout(() => {
                    controller.abort();
                    window.toast('⏳ Writing timed out. Please try again.', 'error');
                }, 30000);

                await ndef.write(url, { signal: controller.signal });
                clearTimeout(timeoutId);
                
                window.toast('✅ NFC Tag written successfully with URL: ' + url, 'success');
            } else {
                showNFCModal(url, "Web NFC is not supported", "Your browser or device does not support native NFC writing from websites. If you are on an iPhone, you must use an app.");
            }
        } catch (error) {
            console.error(error);
            if (error.name === 'AbortError') {
                console.log('NFC write aborted');
            } else if (error.name === 'NotAllowedError') {
                showNFCModal(url, "NFC Permission Denied", "Your browser has blocked NFC access, or NFC is disabled in your phone's settings.");
            } else if (error.name === 'NotSupportedError') {
                showNFCModal(url, "NFC Hardware Error", "Your device appears to not have NFC capability or it is strictly limited by the OS.");
            } else {
                showNFCModal(url, "NFC Error: " + error.name, "We couldn't connect to your NFC hardware: " + error.message);
            }
        }
    }

    async function readNfc(url = "") {
        const controller = new AbortController();
        let scanTimeout;

        try {
            if ("NDEFReader" in window) {
                const ndef = new NDEFReader();
                
                window.toast('📲 Ready to read! Tap an NFC card to the back of your phone.', 'info');

                scanTimeout = setTimeout(() => {
                    controller.abort();
                    window.toast('⏳ Scan timed out after 60 seconds.', 'error');
                }, 60000);

                await ndef.scan({ signal: controller.signal });

                let lastErrorTime = 0;

                ndef.onreading = event => {
                    clearTimeout(scanTimeout);
                    controller.abort(); // Stop scanning after first successful read

                    const decoder = new TextDecoder();
                    let urlData = null;

                    for (const record of event.message.records) {
                        if (
                            record.recordType === "absolute-url" ||
                            record.recordType === "url"          ||
                            record.recordType === "uri"          ||
                            record.recordType === "text"
                        ) {
                            urlData = decoder.decode(record.data);
                            break;
                        }
                    }

                    if (urlData) {
                        window.toast('📡 NFC Tag Contains: ' + urlData, 'success');
                    } else {
                        window.toast('📡 Tag read, but no readable URL or text was found.', 'warning');
                    }
                };

                ndef.onreadingerror = () => {
                    const now = Date.now();
                    if (now - lastErrorTime > 3000) {
                        window.toast('❌ Failed to read the NFC tag. Try positioning it differently.', 'error');
                        lastErrorTime = now;
                    }
                };

            } else {
                showNFCModal(
                    url,
                    "Not Supported for Reading",
                    "Web NFC reading is not supported here. To test your card, simply tap it to your phone like a normal user!"
                );
            }

        } catch (error) {
            clearTimeout(scanTimeout);
            console.error(error);

            if (error.name === 'AbortError') {
                // Silently ignore — AbortError is expected after successful read or timeout
                console.log('NFC scan stopped.');
            } else if (error.name === 'NotAllowedError') {
                showNFCModal(
                    url,
                    "Permission Denied",
                    "Please allow NFC access or enable NFC in your phone's settings to read tags."
                );
            } else if (error.name === 'NotSupportedError') {
                showNFCModal(
                    url,
                    "NFC Hardware Error",
                    "Your device appears to not have NFC capability or it is strictly limited by the OS."
                );
            } else {
                showNFCModal(
                    url,
                    "NFC Error: " + error.name,
                    "We couldn't connect to your NFC hardware: " + error.message
                );
            }
        }
    }

    function confirmAdminCardDelete(url, cardName) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Delete Card?',
                html: `<p class="text-gray-700 text-sm">Are you sure you want to delete <strong class="text-red-600">${cardName || 'this card'}</strong>? This action cannot be undone.</p>`,
                icon: 'warning',
                showCancelButton: true,
                focusCancel: true,
                confirmButtonText: 'Yes, Delete it',
                cancelButtonText: 'Cancel',
                customClass: {
                    popup: 'rounded-xl shadow-xl',
                    confirmButton: 'bg-rose-600 text-white px-4 py-2 rounded-md hover:bg-rose-700 ml-2',
                    cancelButton: 'bg-gray-200 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-300'
                },
                buttonsStyling: false,
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('admin-delete-card-form');
                    form.action = url;
                    form.submit();
                }
            });
        } else {
            if (confirm('Are you sure you want to delete this card?')) {
                const form = document.getElementById('admin-delete-card-form');
                form.action = url;
                form.submit();
            }
        }
    }

    function confirmBusinessDelete(id, name) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Confirm Deletion',
                html: `<p class="text-gray-700 text-sm">Are you sure you want to delete <strong class="text-red-600">${name}</strong>? This action cannot be undone.</p>`,
                icon: 'warning',
                showCancelButton: true,
                focusCancel: true,
                confirmButtonText: 'Yes, Delete it',
                cancelButtonText: 'Cancel',
                customClass: {
                    popup: 'rounded-xl shadow-xl',
                    confirmButton: 'bg-rose-600 text-white px-4 py-2 rounded-md hover:bg-rose-700 ml-2',
                    cancelButton: 'bg-gray-200 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-300'
                },
                buttonsStyling: false,
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById(`delete-business-form-${id}`);
                    if (form) { form.submit(); }
                }
            });
        } else {
            if (confirm('Are you sure you want to delete "' + name + '"?')) {
                const form = document.getElementById(`delete-business-form-${id}`);
                if (form) { form.submit(); }
            }
        }
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endsection
