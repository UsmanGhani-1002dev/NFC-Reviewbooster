@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <!-- Header Section -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full mb-4 shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Create New Business</h1>
            <p class="text-lg text-gray-600">Add your business to our platform</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-6">
                <h2 class="text-2xl font-bold text-white">Business Information</h2>
                <p class="text-blue-100 mt-1">Fill in the details below to get started</p>
            </div>

            <form action="{{ route('businesses.store') }}" method="POST" class="p-8 space-y-8">
                @csrf

                <!-- Business Name Field -->
                <div class="space-y-2">
                    <label for="business_name" class="flex items-center text-sm font-semibold text-gray-700">
                        <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Business Name
                        <span class="text-red-500 ml-1">*</span>
                    </label>
                    <div class="relative">
                        <input 
                            id="business_name" 
                            name="business_name" 
                            type="text" 
                            class="block w-full border-2 border-gray-200 rounded-xl px-6 py-4 text-lg focus:ring-4 focus:ring-blue-100 focus:border-blue-500 focus:outline-none transition-all duration-200 bg-gray-50 hover:bg-white"
                            oninput="autocompleteBusinessName()" 
                            placeholder="Start typing to search for your business..." 
                            required>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500">Search for your business using Google Places</p>
                </div>

                <!-- Legal Business Name Field -->
                <div class="space-y-2">
                    <label for="legal_business_name" class="flex items-center text-sm font-semibold text-gray-700">
                        <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Legal Business Name
                    </label>
                    <input 
                        id="legal_business_name" 
                        name="legal_business_name" 
                        type="text" 
                        class="block w-full border-2 border-gray-200 rounded-xl px-6 py-4 text-lg bg-gray-50 text-gray-700 focus:ring-4 focus:ring-green-100 focus:border-green-500 focus:outline-none transition-all duration-200" 
                        placeholder="Auto-filled from Google search" 
                        readonly>
                    <p class="text-sm text-gray-500">This will be automatically filled when you select a business</p>
                </div>

                <!-- Website Field -->
                <div class="space-y-2">
                    <label for="website" class="flex items-center text-sm font-semibold text-gray-700">
                        <svg class="w-4 h-4 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                        </svg>
                        Business Website
                    </label>
                    <input 
                        id="website" 
                        name="website" 
                        type="url" 
                        class="block w-full border-2 border-gray-200 rounded-xl px-6 py-4 text-lg focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 focus:outline-none transition-all duration-200 bg-gray-50 hover:bg-white" 
                        placeholder="https://example.com">
                    <p class="text-sm text-gray-500">Auto-filled if available on Google, or enter manually. Used for redirect after review.</p>
                </div>

                <input type="hidden" name="google_review_link" id="google_review_link" value="{{ old('google_review_link') }}">

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6">
                    <a href="{{ route('businesses.index') }}"
                       class="flex-1 flex items-center justify-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-4 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 shadow-md">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Cancel
                    </a>
                    <button type="submit"
                        class="flex-1 flex items-center justify-center bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Save Business
                    </button>
                </div>
            </form>
        </div>
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
        
        // Auto-fill website if available from Google
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

