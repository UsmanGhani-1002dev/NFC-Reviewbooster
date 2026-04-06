@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col lg:flex-row gap-8 items-start">
        
        <!-- Left Side: Form Container -->
        <div class="w-full lg:w-1/2 bg-white rounded-3xl shadow-xl border border-gray-100 p-8 sm:p-10">
            <div class="mb-10">
                <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                    <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <span>Edit Review Card</span>
                </h2>
                <p class="text-gray-500 mt-2 ml-15">Modify your NFC card configuration</p>
            </div>

            @if ($errors->any())
                <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-700 px-4 py-3 rounded-xl mb-8">
                    <ul class="list-disc pl-5 text-sm font-medium">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('cards.update', $card) }}" method="POST" enctype="multipart/form-data" class="space-y-8" id="cardForm">
                @csrf
                @method('PUT')
                
                <div class="space-y-2.5">
                    <label class="block text-sm font-semibold text-gray-700 ml-1">Card Display Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" required id="card-name-input"
                        value="{{ old('name', $card->name) }}"
                        placeholder="e.g. Front Desk / Waiter Name"
                        class="w-full px-5 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none text-gray-700 font-medium placeholder:text-gray-400" />
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <div class="space-y-2.5">
                        <label for="type" class="block text-sm font-semibold text-gray-700 ml-1">Card Type <span class="text-rose-500">*</span></label>
                        <select name="type" id="type" required
                            class="w-full px-5 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none text-gray-700 font-medium">
                            <option value="google_review" {{ old('type', $card->type) === 'google_review' ? 'selected' : '' }}>Google Review</option>
                            <option value="facebook_page" {{ old('type', $card->type) === 'facebook_page' ? 'selected' : '' }}>Facebook Page</option>
                            <option value="instagram_page" {{ old('type', $card->type) === 'instagram_page' ? 'selected' : '' }}>Instagram Page</option>
                        </select>
                    </div>

                    <div class="space-y-3">
                        <label class="block text-sm font-semibold text-gray-700 ml-1 text-xs uppercase tracking-widest text-gray-400">Product / Hardware <span class="text-rose-500">*</span></label>
                        <div class="grid grid-cols-3 gap-4">
                            @php
                                $selectedProduct = old('product_type', $card->product_type ?? 'card');
                            @endphp
                            <label class="cursor-pointer group">
                                <input type="radio" name="product_type" value="card" class="hidden peer" {{ $selectedProduct === 'card' ? 'checked' : '' }}>
                                <div class="flex flex-col items-center gap-2 p-4 rounded-2xl border border-gray-100 bg-gray-50/50 peer-checked:border-blue-500 peer-checked:bg-blue-50/50 transition-all group-hover:bg-gray-100/50 shadow-sm">
                                    <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-gray-400 group-hover:scale-110 transition-transform peer-checked:text-blue-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                    </div>
                                    <span class="text-[10px] font-black text-gray-500 uppercase tracking-tighter peer-checked:text-blue-700">NFC Card</span>
                                </div>
                            </label>
                            <label class="cursor-pointer group">
                                <input type="radio" name="product_type" value="sticker" class="hidden peer" {{ $selectedProduct === 'sticker' ? 'checked' : '' }}>
                                <div class="flex flex-col items-center gap-2 p-4 rounded-2xl border border-gray-100 bg-gray-50/50 peer-checked:border-blue-500 peer-checked:bg-blue-50/50 transition-all group-hover:bg-gray-100/50 shadow-sm">
                                    <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-gray-400 group-hover:scale-110 transition-transform peer-checked:text-blue-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/></svg>
                                    </div>
                                    <span class="text-[10px] font-black text-gray-500 uppercase tracking-tighter peer-checked:text-blue-700">NFC Sticker</span>
                                </div>
                            </label>
                            <label class="cursor-pointer group">
                                <input type="radio" name="product_type" value="stand" class="hidden peer" {{ $selectedProduct === 'stand' ? 'checked' : '' }}>
                                <div class="flex flex-col items-center gap-2 p-4 rounded-2xl border border-gray-100 bg-gray-50/50 peer-checked:border-blue-500 peer-checked:bg-blue-50/50 transition-all group-hover:bg-gray-100/50 shadow-sm">
                                    <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-gray-400 group-hover:scale-110 transition-transform peer-checked:text-blue-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0H5m14 0v-2a2 2 0 00-2-2H7a2 2 0 00-2 2v2m14 0V9a2 2 0 00-2-2H7a2 2 0 00-2 2v12"/></svg>
                                    </div>
                                    <span class="text-[10px] font-black text-gray-500 uppercase tracking-tighter peer-checked:text-blue-700">Acrylic Stand</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="space-y-2.5">
                        <label id="url-label" class="block text-sm font-semibold text-gray-700 ml-1">
                            Review Link / Search <span class="text-rose-500">*</span>
                        </label>
                        
                        <div class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus-within:bg-white focus-within:border-blue-500 focus-within:ring-4 focus-within:ring-blue-500/10 transition-all min-h-[3.5rem] flex items-center flex-wrap gap-2 group">
                            <!-- Tag container (shown when business is selected) -->
                            <div id="selected-business-tag" class="flex items-center hidden">
                                <span class="bg-blue-50 text-blue-700 text-sm font-bold px-3 py-1.5 rounded-xl flex items-center max-w-full border border-blue-100 shadow-sm">
                                    <span id="tag-text" class="max-w-xs sm:max-w-sm"></span>
                                    <button type="button" id="remove-tag" class="ml-2 text-blue-400 hover:text-blue-600 font-bold text-xl leading-none flex-shrink-0 transition-colors">
                                        ×
                                    </button>
                                </span>
                            </div>
                        
                            <!-- Business Input (hidden when tag is shown) -->
                            <div class="flex-1 flex items-center gap-3">
                                <svg class="w-5 h-5 text-gray-400 group-focus-within:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <input type="text" id="business-input"
                                    placeholder="Search or paste link..."
                                    class="flex-1 border-none outline-none focus:ring-0 p-0 bg-transparent min-w-0 text-gray-700 font-medium placeholder:text-gray-400" />
                            </div>
                        </div>
                        
                        <input type="hidden" name="url" id="url-input" value="{{ old('url', $card->google_review_link) }}" />
                    </div>
                </div>


                <div class="flex flex-col sm:flex-row items-center gap-4">
                    <button type="submit"
                        class="w-full sm:flex-[2] px-6 py-3 sm:px-8 sm:py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl transition-all duration-300 transform hover:-translate-y-1 shadow-lg shadow-blue-200 flex items-center justify-center gap-2">
                        Update Card Changes
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>

                    <a href="{{ route('cards.index') }}"
                        class="w-full sm:flex-1 px-6 py-3 sm:px-8 sm:py-4 bg-white hover:bg-gray-50 text-gray-700 font-bold rounded-2xl transition-all duration-300 border border-gray-200 text-center">
                        Cancel
                    </a>
                </div>

                <div class="flex items-start gap-3 text-xs text-gray-400 py-6 border-t border-gray-50 mt-10">
                    <div class="mt-0.5">
                        <svg class="w-4 h-4 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <p class="leading-relaxed">Changes to your ReviewGate settings take effect immediately on all associated NFC tags and QR codes. No re-printing required.</p>
                </div>
            </form>
        </div>

        <!-- Right Side: Live Preview Container -->
        <div class="w-full lg:w-1/2 sticky top-10 flex justify-center">
            <div class="relative w-[320px] h-[640px] bg-[#142d63] rounded-[3rem] border-[8px] border-gray-800 shadow-2xl overflow-hidden">
                <!-- Speaker -->
                <div class="absolute top-0 left-1/2 -translate-x-1/2 w-32 h-6 bg-gray-800 rounded-b-2xl z-20"></div>
                
                <!-- Preview Content -->
                <div class="h-full bg-gray-50 overflow-y-auto custom-scrollbar pt-12 pb-8 px-6">
                    <div class="flex flex-col items-center text-center space-y-6">
                        <!-- Logo Placeholder -->
                        <div id="preview-logo" class="w-20 h-20 rounded-2xl bg-white shadow-lg flex items-center justify-center border-4 border-white">
                             <img id="preview-logo-img" src="" class="hidden w-full h-full object-cover rounded-xl">
                             <div id="preview-logo-placeholder" class="text-blue-500">
                                 <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24">
                                     <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"/>
                                 </svg>
                             </div>
                        </div>

                        <!-- Business Info -->
                        <div class="space-y-2">
                            <h4 id="preview-business-name" class="text-xl font-bold text-gray-900 leading-tight">
                                {{ $card->business->legal_business_name ?? 'Your Business' }}
                            </h4>
                            <p id="preview-card-name" class="text-blue-600 font-medium text-sm">@ALICE</p>
                        </div>

                        <!-- Main Call to Action -->
                        <div class="w-full space-y-4 pt-4">
                            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center gap-4">
                                <p class="text-gray-700 font-bold">How was your experience?</p>
                                <div class="flex gap-1 text-yellow-400">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                </div>
                                <div id="preview-button" class="w-full bg-[#01A0FF] text-white py-3 rounded-xl font-bold shadow-lg shadow-blue-200 flex items-center justify-center gap-2">
                                    <span id="preview-button-text">Write a Review</span>
                                </div>
                            </div>

                            <div class="text-center px-4">
                                <p class="text-[10px] text-gray-400">Powered by ReviewBoost - Professional Reputation Management</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Business Verification Popup -->
<div id="business-popup" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 hidden px-4 sm:px-6 backdrop-blur-sm overflow-y-auto py-10">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden transform transition-all duration-300 my-auto">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4 flex items-start justify-between relative">
            <div>
                <h3 class="text-lg sm:text-xl font-bold text-white">Verify Business Details</h3>
                <p class="text-blue-100 text-sm">Please confirm this is the correct business</p>
            </div>
            <!-- Close Button inside header -->
            <button id="close-popup" class="text-white text-2xl font-bold leading-none hover:text-gray-200 focus:outline-none">
                ×
            </button>
        </div>

        <!-- Content -->
        <div class="p-6">
            <!-- Business Image -->
            <div class="text-center mb-6">
                <div class="relative inline-block">
                    <img id="business-image" src="" alt="Business Photo"
                         class="w-24 h-24 sm:w-32 sm:h-32 rounded-full object-cover mx-auto border-4 border-gray-100 shadow-lg">
                    <div id="no-image"
                         class="w-24 h-24 sm:w-32 sm:h-32 bg-gray-100 rounded-full mx-auto border-4 border-gray-100 shadow-lg flex items-center justify-center hidden">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0H5m14 0v-2a2 2 0 00-2-2H7a2 2 0 00-2 2v2m14 0V9a2 2 0 00-2-2H7a2 2 0 00-2 2v12"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Map -->
            <div class="mb-6">
                <div id="business-map" class="w-full h-48 sm:h-64 rounded-xl border-2 border-gray-100 shadow-inner"></div>
            </div>

            <!-- Business Details -->
            <div class="space-y-4">
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Business Name</label>
                    <div id="business-name" class="text-xl font-bold text-gray-900 break-words"></div>
                </div>

                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Address</label>
                    <p id="business-address" class="text-gray-700 text-sm break-words"></p>
                </div>

                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Rating</label>
                        <div class="flex items-center space-x-2">
                            <div id="business-rating" class="flex items-center text-yellow-500 text-lg"></div>
                            <span id="rating-count" class="text-gray-500 text-sm"></span>
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Review Link</label>
                        <p id="review-link" class="text-blue-600 text-sm break-words underline cursor-pointer"></p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 mt-8">
                <button id="confirm-business"
                        class="flex-1 flex items-center justify-center bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-bold py-4 px-6 rounded-xl transition-all duration-200 transform hover:translate-y-[-2px] shadow-lg">
                    ✓ Confirm This Business
                </button>
                <button id="cancel-selection"
                        class="flex-1 flex items-center justify-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-4 px-6 rounded-xl transition-all duration-200 transform hover:translate-y-[-2px] shadow-md">
                    ✕ Try Again
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Close popup with "×" button
document.getElementById('close-popup').addEventListener('click', function () {
    document.getElementById('business-popup').classList.add('hidden');
});
</script>


<script>
    let autocomplete;
    let selectedPlace = null;
    const existingUrl = "{{ old('url', $card->google_review_link) }}";
    const cardType = "{{ old('type', $card->type) }}";
    
    const businessInput = document.getElementById('business-input');
    const urlInput = document.getElementById('url-input');

    // Function to show business as a tag
    function showBusinessTag(businessName, businessAddress = '') {
        const tagContainer = document.getElementById('selected-business-tag');
        const tagText = document.getElementById('tag-text');
        const businessInput = document.getElementById('business-input');
        
        if (businessName) {
            // Format: Business Name — Address
            const fullText = businessAddress ? 
                `${businessName} — ${businessAddress}` : 
                businessName;
            tagText.textContent = fullText;
            tagText.title = fullText; // Show full text on hover
            tagContainer.classList.remove('hidden');
            businessInput.style.display = 'none';
        }
    }

    // Function to hide business tag and show input
    function hideBusinessTag() {
        const tagContainer = document.getElementById('selected-business-tag');
        const businessInput = document.getElementById('business-input');
        
        tagContainer.classList.add('hidden');
        businessInput.style.display = 'block';
        businessInput.value = '';
        businessInput.focus(); // Focus the input when tag is removed
    }

    function enableGoogleAutocomplete() {
        const input = document.getElementById('business-input');

        autocomplete = new google.maps.places.Autocomplete(input, {
            types: ['establishment'],
        });

        autocomplete.addListener('place_changed', function () {
            const place = autocomplete.getPlace();
            if (!place.place_id) {
                alert('Please select a place from the dropdown.');
                return;
            }

            selectedPlace = place;
            showBusinessVerificationPopup(place);
        });
    }

    function showBusinessVerificationPopup(place) {
        const popup = document.getElementById('business-popup');
        const businessImage = document.getElementById('business-image');
        const noImageDiv = document.getElementById('no-image');
        const businessName = document.getElementById('business-name');
        const businessAddress = document.getElementById('business-address');
        const businessRating = document.getElementById('business-rating');
        const ratingCount = document.getElementById('rating-count');
        const reviewLink = document.getElementById('review-link');

        // Set business name
        businessName.textContent = place.name || 'Unknown Business';

        // Set business address
        businessAddress.textContent = place.formatted_address || 'Address not available';

        // Set business image
        if (place.photos && place.photos.length > 0) {
            businessImage.src = place.photos[0].getUrl({ maxWidth: 400, maxHeight: 400 });
            businessImage.classList.remove('hidden');
            noImageDiv.classList.add('hidden');
        } else {
            businessImage.classList.add('hidden');
            noImageDiv.classList.remove('hidden');
        }

        // Set rating
        businessRating.innerHTML = '';
        if (place.rating) {
            const rating = Math.round(place.rating * 10) / 10;
            const fullStars = Math.floor(rating);
            const hasHalfStar = rating % 1 !== 0;

            for (let i = 0; i < fullStars; i++) {
                businessRating.innerHTML += '<span class="text-yellow-400">★</span>';
            }
            if (hasHalfStar) {
                businessRating.innerHTML += '<span class="text-yellow-400">☆</span>';
            }
            for (let i = fullStars + (hasHalfStar ? 1 : 0); i < 5; i++) {
                businessRating.innerHTML += '<span class="text-gray-300">★</span>';
            }
            
            // Display the map
            if (place.geometry && place.geometry.location) {
                const map = new google.maps.Map(document.getElementById('business-map'), {
                    center: place.geometry.location,
                    zoom: 18,
                    styles: [{ featureType: "poi", elementType: "labels", stylers: [{ visibility: "off" }] }]
                });
            
                new google.maps.Marker({
                    position: place.geometry.location,
                    map: map,
                    title: place.name,
                    animation: google.maps.Animation.DROP
                });
            }

            ratingCount.textContent = place.user_ratings_total ? 
                `(${place.user_ratings_total} reviews)` : '';
        } else {
            businessRating.innerHTML = '<span class="text-gray-400 text-sm">No rating available</span>';
            ratingCount.textContent = '';
        }

        // Set review link
        const generatedReviewLink = `https://search.google.com/local/writereview?placeid=${place.place_id}`;
        reviewLink.textContent = generatedReviewLink;

        // Show popup
        popup.classList.remove('hidden');
    }

    function hideBusinessVerificationPopup() {
        const popup = document.getElementById('business-popup');
        popup.classList.add('hidden');
    }

    // Fetch place details by place_id and fill input
    function fetchPlaceDetails(placeId) {
        const service = new google.maps.places.PlacesService(document.createElement('div'));
        service.getDetails({ placeId: placeId }, (place, status) => {
            if (status === google.maps.places.PlacesServiceStatus.OK && place) {
                selectedPlace = place;
                
                // Show the business as a tag with full address
                showBusinessTag(place.name, place.formatted_address);
                urlInput.value = existingUrl;  // Keep the existing URL as is
                
                // Update Preview to show business name and logo
                updatePreview();
            } else {
                console.warn('Failed to fetch place details for placeId:', placeId);
                hideBusinessTag();
            }
        });
    }

    // Extract place_id from the Google review URL
    function extractPlaceIdFromUrl(url) {
        // The placeid param is usually present as ?placeid=PLACE_ID or &placeid=PLACE_ID
        const match = url.match(/placeid=([^&]+)/);
        return match ? match[1] : null;
    }

    function updatePreview() {
        const businessName = document.getElementById('preview-business-name');
        const cardName = document.getElementById('preview-card-name');
        const buttonText = document.getElementById('preview-button-text');
        const previewButton = document.getElementById('preview-button');
        
        const cardNameInput = document.getElementById('card-name-input');
        const typeSelect = document.getElementById('type');
        const tagText = document.getElementById('tag-text');
        const logoImg = document.getElementById('preview-logo-img');
        const logoPlaceholder = document.getElementById('preview-logo-placeholder');

        // Update Card Name
        cardName.textContent = cardNameInput.value ? `@${cardNameInput.value.toUpperCase()}` : '@ALICE';
        
        // Update Business Name & Logo
        if (selectedPlace) {
            businessName.textContent = selectedPlace.name;
            
            // Update Preview Logo from Google Place Photos
            if (selectedPlace.photos && selectedPlace.photos.length > 0) {
                const photoUrl = typeof selectedPlace.photos[0].getUrl === 'function' 
                    ? selectedPlace.photos[0].getUrl({ maxWidth: 200, maxHeight: 200 })
                    : selectedPlace.photos[0].url;
                
                logoImg.src = photoUrl;
                logoImg.classList.remove('hidden');
                logoPlaceholder.classList.add('hidden');
            } else {
                logoImg.classList.add('hidden');
                logoPlaceholder.classList.remove('hidden');
            }
        } else if (tagText.textContent) {
            businessName.textContent = tagText.textContent.split(' — ')[0];
        } else {
            businessName.textContent = @json($card->business->legal_business_name ?? 'Your Business');
        }


        // Update Button Style and Text based on Type
        if (typeSelect.value === 'google_review') {
            buttonText.textContent = 'Write a Review';
            previewButton.className = 'w-full bg-[#01A0FF] text-white py-3 rounded-xl font-bold shadow-lg shadow-blue-200 flex items-center justify-center gap-2';
        } else if (typeSelect.value === 'facebook_page') {
            buttonText.textContent = 'Visit Facebook';
            previewButton.className = 'w-full bg-[#1877F2] text-white py-3 rounded-xl font-bold shadow-lg shadow-blue-200 flex items-center justify-center gap-2';
        } else if (typeSelect.value === 'instagram_page') {
            buttonText.textContent = 'Follow Instagram';
            previewButton.className = 'w-full bg-gradient-to-tr from-[#f09433] via-[#dc2743] to-[#bc1888] text-white py-3 rounded-xl font-bold shadow-lg shadow-pink-200 flex items-center justify-center gap-2';
        }
    }

    window.initAutocomplete = function () {
        const typeSelect = document.getElementById('type');
        const input = businessInput;
        const urlInput = document.getElementById('url-input');
        const label = document.getElementById('url-label');
        const cardNameInput = document.getElementById('card-name-input');
        
        // Initial setup
        handleTypeChange(typeSelect.value, true);
        updatePreview();

        // Listeners for real-time preview
        typeSelect.addEventListener('change', updatePreview);
        cardNameInput.addEventListener('input', updatePreview);

        // Listen to type selector changes
        typeSelect.addEventListener('change', function () {
            handleTypeChange(this.value);
        });

        // Sync manual input to hidden field for non-Google types
        input.addEventListener('input', function () {
            if (typeSelect.value !== 'google_review') {
                urlInput.value = this.value;
            }
        });

        // Remove tag functionality
        document.getElementById('remove-tag').addEventListener('click', function () {
            hideBusinessTag();
            urlInput.value = '';
            selectedPlace = null;
            updatePreview();
        });

        // Confirm business listener
        document.getElementById('confirm-business').addEventListener('click', function () {
            if (selectedPlace) {
                const reviewLink = `https://search.google.com/local/writereview?placeid=${selectedPlace.place_id}`;
                urlInput.value = reviewLink;
                hideBusinessVerificationPopup();

                // Show the selected business as a tag with full address
                showBusinessTag(selectedPlace.name, selectedPlace.formatted_address);

                // Autofill card name if empty
                if (!cardNameInput.value.trim()) {
                    cardNameInput.value = selectedPlace.name;
                }
                updatePreview();
            }
        });

        // Cancel selection listener
        document.getElementById('cancel-selection').addEventListener('click', function () {
            if (typeSelect.value === 'google_review') {
                urlInput.value = existingUrl; // Restore original URL
                // If we had an existing business, show it again
                if (existingUrl && cardType === 'google_review') {
                    const placeId = extractPlaceIdFromUrl(existingUrl);
                    if (placeId) {
                        fetchPlaceDetails(placeId);
                    } else {
                        hideBusinessTag();
                    }
                } else {
                    hideBusinessTag();
                }
            } else {
                hideBusinessTag();
                urlInput.value = '';
            }
            selectedPlace = null;
            hideBusinessVerificationPopup();
            updatePreview();
        });

        // Close popup on clicking outside
        document.getElementById('business-popup').addEventListener('click', function (e) {
            if (e.target === this) {
                document.getElementById('cancel-selection').click();
            }
        });

        function handleTypeChange(type, isInitial = false) {
            if (autocomplete) {
                google.maps.event.clearInstanceListeners(input);
                autocomplete.unbindAll();
                autocomplete = null;
            }

            if (!isInitial) {
                hideBusinessTag();
                urlInput.value = '';
                selectedPlace = null;
            }

            if (type === 'google_review') {
                label.innerHTML = 'Business Search <span class="text-red-500">*</span>';
                input.placeholder = 'Start typing business name...';
                input.disabled = false;
                input.style.display = 'block';
                enableGoogleAutocomplete();

                if (isInitial && existingUrl && cardType === 'google_review') {
                    // Try to extract place_id from URL and fetch business name
                    const placeId = extractPlaceIdFromUrl(existingUrl);
                    if (placeId) {
                        fetchPlaceDetails(placeId);
                    } else {
                        hideBusinessTag();
                    }
                }
            } else {
                label.innerHTML = 'Direct Link <span class="text-red-500">*</span>';
                input.placeholder = 'Paste the page link';
                input.disabled = false;
                input.style.display = 'block';
                hideBusinessTag(); // Hide tag for non-Google types

                if (isInitial && existingUrl && cardType !== 'google_review') {
                    input.value = existingUrl;
                    urlInput.value = existingUrl;
                }
            }
        }
    };
</script>


<!-- Load Google Maps with callback -->
<script
    src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&libraries=places&callback=initAutocomplete"
    async defer>
</script>
@endsection