@extends('layouts.app')

@section('content')

<style>
    @keyframes blink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.2; }
    }
    .animate-blink { animation: blink 1s infinite; }
    
    .card-gradient-border {
        position: relative;
        background: white;
        transition: all 0.3s ease;
    }
    .card-gradient-border:hover {
        transform: translateY(-4px);
    }
</style>

<div x-data="{ openModal: false, selectedCard: '' ,showExpiredModal: false }" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    @if(session('success'))
        <div class="mb-8 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center shadow-sm">
            <svg class="w-5 h-5 mr-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-8 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-3 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01M12 5c-3.866 0-7 3.134-7 7s3.134 7 7 7 7-3.134 7-7-3.134-7-7-7z" />
                </svg>
                <span class="font-bold">{{ session('error') }}</span>
            </div>
            @if(str_contains(strtolower(session('error')), 'limit'))
                <a href="{{ route('user.subscription.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-rose-600 text-white text-xs font-bold rounded-xl hover:bg-rose-700 transition-all shadow-sm shadow-rose-100 whitespace-nowrap">
                    Upgrade Plan
                    <svg class="w-3 h-3 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            @elseif(str_contains(strtolower(session('error')), 'expired'))
                <a href="{{ route('user.subscription.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-rose-600 text-white text-xs font-bold rounded-xl hover:bg-rose-700 transition-all shadow-sm shadow-rose-100 whitespace-nowrap">
                    Renew Subscription
                    <svg class="w-3 h-3 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            @endif
        </div>
    @endif

    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Your ReviewGate Cards</h1>
            <p class="text-gray-500 mt-2 font-medium">
                @if(!empty($selectedBusiness->legal_business_name))
                    Managing assets for <span class="text-blue-600 font-bold">{{ $selectedBusiness->legal_business_name }}</span>
                @else
                    Generate and manage your NFC and QR review assets
                @endif
            </p>
        </div>
    
        @if ($isSubscriptionExpired)
            <button @click="showExpiredModal = true"
                class="inline-flex items-center justify-center px-6 py-3.5 bg-rose-500 hover:bg-rose-600 text-white font-bold rounded-2xl shadow-lg shadow-rose-100 transition-all duration-300 transform hover:-translate-y-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 animate-blink text-yellow-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01M12 5c-3.866 0-7 3.134-7 7s3.134 7 7 7 7-3.134 7-7-3.134-7-7-7z" />
                </svg>
                Create New Card
            </button>
        @else
            <a href="{{ route('cards.create', ['business_id' => $selectedBusiness?->id]) }}"
                class="inline-flex items-center justify-center px-6 py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl shadow-lg shadow-blue-100 transition-all duration-300 transform hover:-translate-y-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                Create New Card
            </a>
        @endif
    </div>

    @if($cards->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            @foreach($cards as $card)
                <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden group hover:border-blue-200 transition-all duration-500 flex flex-col sm:flex-row">
                    @php
                        $typeTheme = match($card->type) {
                            'facebook_page' => ['icon' => 'fab fa-facebook', 'color' => 'text-blue-600', 'bg' => 'bg-blue-50', 'border' => 'border-blue-100'],
                            'instagram_page' => ['icon' => 'fab fa-instagram', 'color' => 'text-pink-600', 'bg' => 'bg-pink-50', 'border' => 'border-pink-100'],
                            'google_review' => ['icon' => 'fab fa-google', 'color' => 'text-red-500', 'bg' => 'bg-red-50', 'border' => 'border-red-100'],
                            default => ['icon' => 'fas fa-link', 'color' => 'text-gray-500', 'bg' => 'bg-gray-50', 'border' => 'border-gray-100'],
                        };
                    @endphp
                    <!-- Left Section: Visuals -->
                    <div class="sm:w-1/3 {{ $typeTheme['bg'] }}/30 p-6 flex flex-col items-center justify-center border-b sm:border-b-0 sm:border-r border-gray-50 bg-opacity-30">
                        @php
                            $icon = match($card->type) {
                                'facebook_page' => 'facebook.png',
                                'instagram_page' => 'instagram.png',
                                'google_review' => 'google.png',
                                default => 'default.png',
                            };
                        @endphp
                        <div class="relative group/img mb-6">
                            <img src="{{ asset('/images/' . $icon) }}" alt="{{ $card->type }}" class="w-full h-32 object-contain drop-shadow-md group-hover:scale-105 transition-transform duration-500">
                        </div>
                        
                        <!-- QR Part -->
                        <div id="qr-code-{{ $card->id }}" class="bg-white p-2 rounded-xl shadow-sm border border-gray-100">
                            {!! QrCode::size(90)->generate(url('/r/' . $card->token)) !!}
                        </div>
                        <button onclick="downloadQrCode('qr-code-{{ $card->id }}', '{{ $card->token }}-qrcode.png')" 
                            class="mt-4 flex items-center gap-2 px-2 py-2 bg-white text-gray-700 text-[10px] font-bold uppercase tracking-widest rounded-xl shadow-sm border border-gray-100 hover:bg-gray-50 hover:text-blue-600 transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                            Download QR
                        </button>
                    </div>

                    <!-- Right Section: Details -->
                    <div class="flex-1 p-6 md:p-8 flex flex-col">
                        <div class="flex items-start justify-between mb-2">
                             <div>
                                <h3 class="text-2xl font-extrabold text-gray-900 leading-tight mb-2 group-hover:text-blue-600 transition-colors">{{ $card->name }}</h3>
                                <div class="flex flex-wrap gap-2">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-600 text-[9px] font-bold rounded-lg border border-emerald-100 uppercase w-fit">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                                        System Active
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
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-gray-50 text-gray-600 text-[9px] font-bold rounded-lg border border-gray-100 uppercase w-fit">
                                        <i class="fas {{ $productIcon }} text-[8px]"></i>
                                        {{ $productLabel }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('cards.edit', $card) }}" class="p-2.5 bg-white text-gray-400 rounded-xl hover:text-blue-600 hover:bg-blue-50 transition-all border border-gray-100 hover:border-blue-100 shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                </a>
                                <button @click="openModal = true; selectedCard = '{{ route('cards.destroy', $card) }}';" class="p-2.5 bg-white text-gray-400 rounded-xl hover:bg-rose-50 hover:text-rose-600 transition-all border border-gray-100 hover:border-rose-100 shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </div>
                        </div>

                        <a href="{{ url('/r/' . $card->token) }}" target="_blank" class="text-[12px] font-mono text-blue-600 font-bold mb-6 overflow-hidden text-ellipsis whitespace-nowrap" title="{{ url('/r/' . $card->token) }}">
                            {{ url('/r/' . $card->token) }}
                        </a>

                        <div class="grid grid-cols-1 gap-4 mb-8">
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Scans</p>
                                <p class="text-base font-bold text-gray-800">{{ $reviewCounts[$card->id] ?? 0 }} Reviews</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Business</p>
                                <p class="text-base font-bold text-gray-800 capitalize">{{ $card->business->legal_business_name ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="mt-auto pt-6 border-t border-gray-50 flex items-center justify-between">
                            @php
                                $typeTheme = match($card->type) {
                                    'facebook_page' => ['icon' => 'fab fa-facebook', 'color' => 'text-blue-600', 'bg' => 'bg-blue-50', 'border' => 'border-blue-100'],
                                    'instagram_page' => ['icon' => 'fab fa-instagram', 'color' => 'text-pink-600', 'bg' => 'bg-pink-50', 'border' => 'border-pink-100'],
                                    'google_review' => ['icon' => 'fab fa-google', 'color' => 'text-red-500', 'bg' => 'bg-red-50', 'border' => 'border-red-100'],
                                    default => ['icon' => 'fas fa-link', 'color' => 'text-gray-500', 'bg' => 'bg-gray-50', 'border' => 'border-gray-100'],
                                };
                            @endphp
                            <span class="flex items-center gap-1.5 px-2.5 py-1 {{ $typeTheme['bg'] }} {{ $typeTheme['color'] }} text-[11px] font-bold rounded-lg border {{ $typeTheme['border'] }} uppercase">
                                <i class="{{ $typeTheme['icon'] }} text-[10px]"></i>
                                {{ str_replace('_', ' ', $card->type) }}
                            </span>
                            <div x-data="{ copied: false }">
                                <button @click="navigator.clipboard.writeText('{{ url('/r/' . $card->token) }}').then(() => { copied = true; setTimeout(() => copied = false, 2000); })"
                                    class="inline-flex items-center text-[11px] font-extrabold text-blue-600 hover:text-blue-700 uppercase tracking-widest transition-all bg-blue-50 p-2 rounded-xl border border-transparent hover:border-blue-100">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                                    <span x-text="copied ? 'Copied' : 'Copy Link'"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-xl shadow-gray-200/50 border border-gray-100 p-16 text-center">
            <div class="w-24 h-24 bg-gray-50 rounded-3xl flex items-center justify-center text-gray-300 mx-auto mb-8 border border-gray-100">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
            <h3 class="text-2xl font-extrabold text-gray-900 mb-3">Create your first card</h3>
            <p class="text-gray-500 max-w-sm mx-auto mb-10 font-medium leading-relaxed">Boost your business reviews by creating professional NFC and QR review cards for your location.</p>
            <a href="{{ route('cards.create') }}" class="inline-flex items-center px-10 py-4 bg-blue-600 text-white font-bold rounded-2xl shadow-xl shadow-blue-100 hover:bg-blue-700 transition-all transform hover:-translate-y-1">
                Let's get started
            </a>
        </div>
    @endif

    <!-- Delete Modal -->
    <div x-show="openModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/40 backdrop-blur-sm p-4" style="display: none;">
        <div @click.away="openModal = false" class="bg-white rounded-3xl shadow-2xl max-w-sm w-full p-8 text-center animate-celebration">
            <div class="w-20 h-20 bg-rose-50 rounded-2xl flex items-center justify-center text-rose-500 mx-auto mb-6">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
            </div>
            <h3 class="text-2xl font-extrabold text-gray-900 mb-3 text-center">Delete Card?</h3>
            <p class="text-gray-500 mb-8 font-medium">This action is permanent and all associated review data will be disconnected from this card.</p>
            <div class="flex gap-4">
                <button @click="openModal = false" class="flex-1 px-6 py-3.5 bg-gray-50 hover:bg-gray-100 text-gray-900 font-bold rounded-2xl transition-all">Cancel</button>
                <form :action="selectedCard" method="POST" class="flex-1">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full px-6 py-3.5 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-2xl shadow-lg shadow-rose-100 transition-all">Delete</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Subscription Expired Modal -->
    <div x-show="showExpiredModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/40 p-4" style="display: none;">
        <div @click.away="showExpiredModal = false" class="bg-white rounded-3xl shadow-2xl max-w-sm w-full p-8 text-center">
            <div class="w-20 h-20 bg-amber-100 rounded-2xl flex items-center justify-center text-amber-500 mx-auto mb-6">
                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 5c-3.866 0-7 3.134-7 7s3.134 7 7 7 7-3.134 7-7-3.134-7-7-7z" /></svg>
            </div>
            <h3 class="text-2xl font-extrabold text-gray-900 mb-3">Subscription Expired</h3>
            <p class="text-gray-500 mb-8 font-medium">Your subscription has expired. Please renew to continue creating and managing review cards.</p>
            <div class="space-y-4">
                <a href="{{ route('user.subscription.index') }}" class="block w-full px-6 py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl shadow-lg shadow-blue-100 transition-all">Renew Now</a>
                <button @click="showExpiredModal = false" class="w-full px-6 py-3.5 text-gray-700 font-bold hover:text-gray-600 transition-colors border border-gray-200 rounded-2xl bg-gray-200">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    function downloadQrCode(containerId, filename) {
        const container = document.getElementById(containerId);
        if (!container) return;
        const svg = container.querySelector('svg');
        if (!svg) { alert('Cannot find QR code to download.'); return; }
        const svgClone = svg.cloneNode(true);
        svgClone.style.backgroundColor = 'white';
        const width = 100, height = 100;
        const serializer = new XMLSerializer();
        let svgStr = serializer.serializeToString(svgClone);
        const canvas = document.createElement('canvas');
        canvas.width = width * 3;
        canvas.height = height * 3;
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
</script>

@endsection
