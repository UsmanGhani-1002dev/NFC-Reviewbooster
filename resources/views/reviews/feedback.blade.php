@extends('layouts.plain')

@section('content')
<style>
    @keyframes floatUp {
        0% { opacity: 0; transform: translateY(30px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .animate-float-up {
        animation: floatUp 0.6s ease-out forwards;
    }
    .animate-float-up-delay {
        animation: floatUp 0.6s ease-out 0.15s forwards;
        opacity: 0;
    }
    .form-input {
        transition: all 0.3s ease;
    }
    .form-input:focus {
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }
</style>

<div class="max-w-xl w-full mx-auto px-4 mt-8 sm:mt-12">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 animate-float-up">

        {{-- Header --}}
        <div class="relative bg-gradient-to-br from-indigo-600 via-purple-600 to-indigo-700 px-6 py-8 text-center overflow-hidden">
            {{-- Decorative elements --}}
            <div class="absolute top-0 left-0 w-full h-full pointer-events-none">
                <div class="absolute -top-10 -left-10 w-36 h-36 bg-white/5 rounded-full"></div>
                <div class="absolute -bottom-6 -right-6 w-28 h-28 bg-white/5 rounded-full"></div>
            </div>

            <div class="relative z-10">
                {{-- Icon --}}
                <div class="w-14 h-14 bg-white/15 backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-4 border border-white/20">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.076-4.076a1.526 1.526 0 011.037-.443 48.282 48.282 0 005.68-.494c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"/></svg>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold text-white mb-1">Feedback</h1>
                <p class="text-indigo-200 text-sm">We'd love to hear how we can improve</p>
            </div>
        </div>

        {{-- Form Body --}}
        <div class="px-6 py-8 sm:px-8 animate-float-up-delay">

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl mb-6">
                    <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6">
                    <div class="flex items-center gap-2 mb-1">
                        <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                        <span class="text-sm font-bold">Please fix the following:</span>
                    </div>
                    <ul class="list-disc pl-9 text-sm space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form --}}
            <form id="review-form" action="{{ route('reviews.feedbackstore') }}" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="card_id" value="{{ $card->id }}">
                <input type="hidden" name="rating" id="rating-input" value="1">

                {{-- Star Rating --}}
                <div class="space-y-3 pb-2">
                    <label class="block text-sm font-semibold text-gray-700">
                        How would you rate your experience? <span class="text-red-400">*</span>
                    </label>
                    <div class="flex items-center gap-2" id="star-rating-container">
                        @for ($i = 1; $i <= 5; $i++)
                            <button type="button" data-value="{{ $i }}" class="star-btn p-1 transition-all duration-200 hover:scale-125 focus:outline-none focus:ring-2 focus:ring-indigo-200 rounded-lg">
                                <svg class="w-8 h-8 star-icon {{ $i === 1 ? 'text-amber-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </button>
                        @endfor
                    </div>
                </div>

                {{-- Name & Email --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Name <span class="text-red-400">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400 text-sm"></i>
                            </div>
                            <input type="text" id="name" name="name" required placeholder="John Doe" value="{{ old('name') }}"
                                class="form-input w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none text-sm bg-gray-50/50 hover:bg-white" />
                        </div>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Email <span class="text-red-400">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400 text-sm"></i>
                            </div>
                            <input type="email" id="email" name="email" required placeholder="john@example.com" value="{{ old('email') }}"
                                class="form-input w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none text-sm bg-gray-50/50 hover:bg-white" />
                        </div>
                    </div>
                </div>

                {{-- Feedback --}}
                <div>
                    <label for="review" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Your Feedback <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <textarea id="review" name="review" rows="5" required placeholder="Tell us what happened and how we can do better..."
                            class="form-input w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none text-sm bg-gray-50/50 hover:bg-white resize-none">{{ old('review') }}</textarea>
                    </div>
                    <p class="text-xs text-gray-400 mt-1.5">Your honest feedback helps us improve our service.</p>
                </div>

                {{-- Submit --}}
                <div class="pt-2">
                    <button type="submit" id="submit-btn"
                        class="w-full flex items-center justify-center gap-2 py-3.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white text-sm font-bold rounded-xl transition-all duration-300 shadow-lg shadow-indigo-200 hover:shadow-xl hover:shadow-indigo-300 hover:-translate-y-0.5 active:translate-y-0 focus:outline-none focus:ring-4 focus:ring-indigo-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                        Submit Feedback
                    </button>
                </div>
            </form>
        </div>

        {{-- Footer --}}
        <div class="px-6 pb-5 sm:px-8">
            <div class="flex items-center justify-center gap-2 text-gray-300 text-xs text-nowrap">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                <span>Powered by Tap Review Cards</span>
            </div>
        </div>
    </div>
</div>

{{-- Loading Spinner --}}
<div id="loading-spinner" class="hidden fixed inset-0 bg-gray-900/60 backdrop-blur-sm flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-8 shadow-2xl flex flex-col items-center gap-4 animate-float-up">
        <div class="relative w-14 h-14">
            <div class="absolute inset-0 border-4 border-indigo-100 rounded-full"></div>
            <div class="absolute inset-0 border-4 border-transparent border-t-indigo-600 rounded-full animate-spin"></div>
        </div>
        <p class="text-gray-600 font-semibold text-sm">Submitting your feedback...</p>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('review-form');
        const submitBtn = document.getElementById('submit-btn');
        const starBtns = document.querySelectorAll('.star-btn');
        const ratingInput = document.getElementById('rating-input');
        
        // Initialize stars if needed (already defaulted to 1 in HTML)
        updateStars(1);

        starBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const value = parseInt(btn.getAttribute('data-value'));
                ratingInput.value = value;
                updateStars(value);
            });

            btn.addEventListener('mouseenter', () => {
                const value = parseInt(btn.getAttribute('data-value'));
                updateStars(value, true);
            });

            btn.addEventListener('mouseleave', () => {
                updateStars(parseInt(ratingInput.value));
            });
        });

        function updateStars(value, isHover = false) {
            starBtns.forEach((btn, index) => {
                const icon = btn.querySelector('.star-icon');
                if (index < value) {
                    icon.classList.remove('text-gray-200');
                    icon.classList.add(isHover ? 'text-amber-300' : 'text-amber-400');
                } else {
                    icon.classList.remove('text-amber-400', 'text-amber-300');
                    icon.classList.add('text-gray-200');
                }
            });
        }

        form.addEventListener('submit', function (event) {
            event.preventDefault();

            // Disable button and show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Sending...
            `;

            document.getElementById('loading-spinner').classList.remove('hidden');
            setTimeout(() => form.submit(), 1500);
        });
    });
</script>
@endsection
