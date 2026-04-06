@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center py-10 px-4">
    <div class="w-full max-w-2xl bg-white rounded-2xl shadow-lg p-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-3">Edit Business</h2>

        <form action="{{ route('businesses.update', $business->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="business_name" class="block text-sm font-medium text-gray-700 mb-1">
                    Business Name <span class="text-red-500">*</span>
                </label>
                <input 
                    id="business_name" 
                    name="business_name" 
                    type="text" 
                    value="{{ old('business_name', $business->business_name) }}"
                    class="block w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none transition"
                    oninput="autocompleteBusinessName()" 
                    placeholder="e.g., Starbucks" 
                    required>
            </div>

            <div>
                <label for="legal_business_name" class="block text-sm font-medium text-gray-700 mb-1">
                    Legal Business Name
                </label>
                <input 
                    id="legal_business_name" 
                    name="legal_business_name" 
                    type="text" 
                    value="{{ old('legal_business_name', $business->legal_business_name) }}"
                    class="block w-full border border-gray-300 bg-gray-100 text-gray-700 rounded-lg px-4 py-3" 
                    placeholder="Auto-filled from Google" 
                    required>
            </div>

            <div>
                <label for="website" class="block text-sm font-medium text-gray-700 mb-1">
                    Business Website
                </label>
                <input 
                    id="website" 
                    name="website" 
                    type="url" 
                    value="{{ old('website', $business->website) }}"
                    class="block w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none transition" 
                    placeholder="https://example.com">
                <p class="text-xs text-gray-500 mt-1">Customers will be redirected here after leaving a review.</p>
            </div>

            <div class="pt-4 flex justify-between gap-4">
                <a href="{{ route('businesses.index') }}"
                   class="w-full text-center bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-3 px-6 rounded-lg shadow-md transition">
                    Cancel
                </a>
            
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg shadow-md transition">
                    Update Business
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
                    styles: [{ featureType: "poi", elementType: "labels", stylers: [{ visibility: "off" }] }]
                });

                new google.maps.Marker({
                    position: selectedPlace.geometry.location,
                    map: map,
                    title: selectedPlace.name,
                    animation: google.maps.Animation.DROP
                });
            }

            // Show modal
            const modal = document.getElementById('business-preview-modal');
            const content = document.getElementById('modal-content');
            
            modal.classList.remove('hidden');
            setTimeout(() => {
                content.classList.remove('opacity-0', 'scale-95');
                content.classList.add('opacity-100', 'scale-100');
            }, 10);
        });
    }

    function closeModal() {
        const modal = document.getElementById('business-preview-modal');
        const content = document.getElementById('modal-content');
        
        content.classList.remove('opacity-100', 'scale-100');
        content.classList.add('opacity-0', 'scale-95');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    // Modal Close logic
    document.getElementById('close-modal').addEventListener('click', closeModal);
    document.getElementById('business-preview-modal').addEventListener('click', (e) => {
        if (e.target.id === 'business-preview-modal') closeModal();
    });

    // Confirm button
    document.getElementById('confirm-business').addEventListener('click', () => {
        document.getElementById('legal_business_name').value = selectedPlace.name || '';
        
        // Auto-fill website if available from Google
        if (selectedPlace.website) {
            document.getElementById('website').value = selectedPlace.website;
        }
        closeModal();
    });

    // Cancel button
    document.getElementById('cancel-selection').addEventListener('click', () => {
        document.getElementById('business_name').value = '';
        document.getElementById('legal_business_name').value = '';
        document.getElementById('website').value = '';
        document.getElementById('google_review_link').value = '';
        closeModal();
    });

    function autocompleteBusinessName() {
        document.getElementById('legal_business_name').value = '';
        document.getElementById('website').value = '';
    }
</script>

<script
    src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&libraries=places&callback=initAutocomplete"
    async defer>
</script>

@endsection
