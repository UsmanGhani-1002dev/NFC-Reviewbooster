@extends('layouts.app')

@section('full_content')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <style>
        .ts-control {
            padding: 0.90rem 1rem !important;
            border-radius: 0.5rem !important;
            background-color: #f9fafb !important; /* bg-gray-50 */
            font-size: 16px !important;
            border:0px !important;
        }
        .ts-wrapper.focus .ts-control {
            border-color: #6366f1 !important; /* border-indigo-500 */
            box-shadow: 0 0 0 2px rgba(199, 210, 254, 0.5) !important; /* ring-indigo-200 */
        }
    </style>
    <div class="mx-auto p-6">
        <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100 max-w-3xl mx-auto">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-3xl font-bold text-gray-800">
                    Create New Business
                </h2>
                <a href="{{ route('admin.manage_business.index') }}" class="text-indigo-600 hover:text-indigo-800 transition font-medium">
                    &larr; Back to List
                </a>
            </div>

            @if ($errors->any())
                <div class="mb-6 p-4 rounded-lg bg-red-100 text-red-800 border-l-4 border-red-500 shadow-sm">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.manage_business.store') }}" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Assign to User</label>
                    <select id="user_id_select" name="user_id" required class="w-full rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition bg-gray-50">
                        <option value="">Select a Business Owner...</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Business Name (Display Name)</label>
                    <input 
                        type="text"
                        name="business_name"
                        id="business_name"
                        value="{{ old('business_name') }}"
                        oninput="autocompleteBusinessName()"
                        required
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition bg-gray-50"
                        placeholder="Start typing to search for your business...">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Legal Business Name</label>
                    <input
                        type="text"
                        name="legal_business_name"
                        id="legal_business_name"
                        value="{{ old('legal_business_name') }}"
                        required
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition bg-gray-50"
                        placeholder="Auto-filled from Google search">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Business Website</label>
                    <input
                        type="url"
                        name="website"
                        id="website"
                        value="{{ old('website') }}"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition bg-gray-50"
                        placeholder="https://example.com">
                </div>

                <input type="hidden" name="google_review_link" id="google_review_link" value="{{ old('google_review_link') }}">

                <div class="pt-4 flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-md transition transform hover:-translate-y-0.5">
                        Create Business
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- Business Preview Modal -->
<div id="business-preview-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-60 backdrop-blur-sm px-4 sm:px-6 overflow-y-auto py-10">
    <div class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden transform transition-all duration-300 scale-95 opacity-0 my-auto" id="modal-content">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4 flex items-start justify-between relative">
            <div>
                <h3 class="text-lg sm:text-xl font-bold text-white">Confirm Business Details</h3>
                <p class="text-blue-100 text-sm">Please confirm this is the correct business</p>
            </div>
            <button id="close-modal" class="text-white text-2xl font-bold leading-none hover:text-gray-200 focus:outline-none">
                ×
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <!-- Business Image -->
            <div class="text-center mb-6">
                <div class="relative inline-block">
                    <img id="business-img" src="" alt="Business Photo"
                         class="w-24 h-24 sm:w-32 sm:h-32 rounded-full object-cover mx-auto border-4 border-gray-100 shadow-lg">
                    <div id="no-image"
                         class="    ">
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
                            <div id="businessRating" class="flex text-lg items-center"></div>
                            <span id="ratingCount" class="text-sm text-gray-600 font-medium"></span>
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
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Confirm This Business
                </button>
                <button id="cancel-selection" 
                        class="flex-1 flex items-center justify-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-4 px-6 rounded-xl transition-all duration-200 transform hover:translate-y-[-2px] shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Try Again
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let autocomplete;
    let selectedPlace;

    function initAutocomplete() {
        const input = document.getElementById('business_name');
        autocomplete = new google.maps.places.Autocomplete(input, {
            types: ['establishment']
        });

        autocomplete.addListener('place_changed', () => {
            selectedPlace = autocomplete.getPlace();
            if (!selectedPlace.name) return;

            // Fill modal with place data
            document.getElementById('business-name').textContent = selectedPlace.name || '';
            document.getElementById('business-address').textContent = selectedPlace.formatted_address || '';

            // Image Handling
            const imgEl = document.getElementById('business-img');
            const noImageDiv = document.getElementById('no-image');
            
            if (selectedPlace.photos && selectedPlace.photos.length > 0) {
                imgEl.src = selectedPlace.photos[0].getUrl({ maxWidth: 400, maxHeight: 400 });
                imgEl.classList.remove('hidden');
                noImageDiv.classList.add('hidden');
            } else {
                imgEl.classList.add('hidden');
                noImageDiv.classList.remove('hidden');
            }

            // Set rating
            const businessRating = document.getElementById('businessRating');
            const ratingCount = document.getElementById('ratingCount');
            businessRating.innerHTML = '';
            ratingCount.textContent = '';

            if (selectedPlace.rating) {
                const rating = Math.round(selectedPlace.rating * 10) / 10;
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

                ratingCount.textContent = selectedPlace.user_ratings_total
                    ? `${rating} (${selectedPlace.user_ratings_total} reviews)`
                    : `${rating}`;
            } else {
                businessRating.innerHTML = '<span class="text-gray-400 text-sm">No rating available</span>';
            }

            // Set review link
            const generatedReviewLink = `https://search.google.com/local/writereview?placeid=${selectedPlace.place_id}`;
            document.getElementById('review-link').textContent = generatedReviewLink;

            // Map
            if (selectedPlace.geometry && selectedPlace.geometry.location) {
                const map = new google.maps.Map(document.getElementById('business-map'), {
                    center: selectedPlace.geometry.location,
                    zoom: 18,
                    styles: [
                        {
                            featureType: "poi",
                            elementType: "labels",
                            stylers: [{ visibility: "off" }]
                        }
                    ]
                });

                new google.maps.Marker({
                    position: selectedPlace.geometry.location,
                    map: map,
                    title: selectedPlace.name,
                    animation: google.maps.Animation.DROP
                });
            }

            // Show modal with animation
            const modal = document.getElementById('business-preview-modal');
            const modalContent = document.getElementById('modal-content');
            modal.classList.remove('hidden');
            
            // Trigger animation
            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 10);
        });
    }

    // Confirm button
    document.getElementById('confirm-business').addEventListener('click', () => {
        document.getElementById('legal_business_name').value = selectedPlace.name || '';
        document.getElementById('google_review_link').value = `https://search.google.com/local/writereview?placeid=${selectedPlace.place_id}`;
        
        if (selectedPlace.website) {
            document.getElementById('website').value = selectedPlace.website;
        }
        
        hideModal();
    });

    // Cancel button
    document.getElementById('cancel-selection').addEventListener('click', () => {
        document.getElementById('business_name').value = '';
        document.getElementById('legal_business_name').value = '';
        document.getElementById('google_review_link').value = '';
        document.getElementById('website').value = '';
        hideModal();
    });

    // Close modal button
    document.getElementById('close-modal').addEventListener('click', hideModal);

    // Close modal when clicking outside
    document.getElementById('business-preview-modal').addEventListener('click', (e) => {
        if (e.target.id === 'business-preview-modal') {
            hideModal();
        }
    });

    function hideModal() {
        const modal = document.getElementById('business-preview-modal');
        const modalContent = document.getElementById('modal-content');
        
        modalContent.classList.add('scale-95', 'opacity-0');
        modalContent.classList.remove('scale-100', 'opacity-100');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    function autocompleteBusinessName() {
        document.getElementById('legal_business_name').value = '';
        document.getElementById('website').value = '';
    }

    // Add loading animation to form
    document.addEventListener('DOMContentLoaded', function() {
        const userSelect = new TomSelect("#user_id_select",{
            create: false,
            placeholder: "Select or search for a Business Owner...",
            maxOptions: 100
        });

        const form = document.querySelector('form');
        form.addEventListener('submit', function() {
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Saving...
            `;
            submitBtn.disabled = true;
        });
    });
</script>

<script
    src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&libraries=places&callback=initAutocomplete"
    async defer>
</script>
@endsection
