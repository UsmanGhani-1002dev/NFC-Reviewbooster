@extends('layouts.guest')

@section('title', 'How Tap Review Cards Work | Simple 3-Step Google Review Process')
@section('meta_description', 'See how easy it is to collect Google reviews with our NFC cards. 1. Customer taps card. 2. Leaves review. 3. Your business grows. No apps, no friction, just results for UK businesses.')
@section('meta_keywords', 'how NFC review cards work, Google review process, tap to review UK, NFC review setup, collect reviews fast')

@section('schema')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "HowTo",
    "name": "How to Collect Google Reviews with NFC Tap Cards",
    "description": "Get more Google reviews for your business in 3 simple steps using Tap Review Cards NFC technology.",
    "totalTime": "PT5M",
    "step": [
        {
            "@type": "HowToStep",
            "position": 1,
            "name": "Create Your Card",
            "text": "Log in to your dashboard and create a new review card. Select your business and review platform (Google, Facebook, or Instagram).",
            "image": "https://tapreviewcards.co.uk/images/step1.png"
        },
        {
            "@type": "HowToStep",
            "position": 2,
            "name": "Share Your Card",
            "text": "Your card gets a unique token instantly. Share it via QR code, link, or digital display with your customers.",
            "image": "https://tapreviewcards.co.uk/images/step2.png"
        },
        {
            "@type": "HowToStep",
            "position": 3,
            "name": "Collect Reviews",
            "text": "Customers scan your card and leave reviews. Track all feedback directly in your dashboard!",
            "image": "https://tapreviewcards.co.uk/images/step3.png"
        }
    ]
}
</script>
@endsection

@section('content')
    <div class="min-h-screen bg-gray-50">
        <!-- CTA Section -->
        <section class="relative -mt-[100px] pt-[100px] bg-cover bg-center bg-no-repeat text-[#1800ad] font-mulish"
            style="background-image: url('https://d1yei2z3i6k35z.cloudfront.net/161/609bb9ff8ffc9_Groupedemasques1.jpg');">
            <div class="relative max-w-3xl mx-auto text-center py-20 px-6 md:px-10">
                <h1 class="text-4xl sm:text-5xl font-bold mb-4 leading-tight font-ubuntu ">
                    How Its Work
                </h1>
                <p class="text-lg sm:text-md leading-relaxed text-[#142D63]">
                    Getting more <b>Google reviews</b> is the fastest way to rank <b>#1</b> on Google.<br>
                    You're already in touch with your customers — don’t miss out!
                </p>
            </div>
        </section>

        <section class="py-20 bg-gray-50 px-4 sm:px-6 lg:px-8">
            <div class="max-w-6xl mx-auto text-center">
                <div class="mb-16">
                    <h2 class="text-3xl sm:text-3xl font-bold text-[#1800ad] font-ubuntu mb-4 relative inline-block">
                        ⏱️ Activate Your Card in 3 Simple Steps
                    </h2>
                    <p class="text-lg text-gray-600 max-w-3xl mx-auto font-mulish">
                        Get your ReviewBoost card active and start collecting reviews in just minutes. No technical setup
                        required!
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-8 md:gap-12 text-left">
                    <!-- Step 1 -->
                    <div
                        class="bg-white rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 p-8 flex flex-col items-center text-center">
                        <img src="images/step1.png" alt="Create Your Card"
                            class="w-[180px] h-[180px] mb-6 transition-transform duration-300 hover:scale-105" loading="lazy">
                        <h3 class="text-[21px] font-bold text-[#1800ad] font-ubuntu mb-3">Step 1: Create Your Card</h3>
                        <p class="text-gray-600 font-mulish leading-relaxed">
                            Log in to your dashboard and create a new review card. Select your business and review platform
                            (Google, Facebook, or Instagram).
                        </p>
                    </div>

                    <!-- Step 2 -->
                    <div
                        class="bg-white rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 p-8 flex flex-col items-center text-center">
                        <img src="images/step2.png" alt="Share Your Card"
                            class="w-[180px] h-[180px] mb-6 transition-transform duration-300 hover:scale-105" loading="lazy">
                        <h3 class="text-[21px] font-bold text-[#1800ad] font-ubuntu mb-3">Step 2: Share Your Card</h3>
                        <p class="text-gray-600 font-mulish leading-relaxed">
                            Your card gets a unique token instantly. Share it via QR code, link, or digital display with
                            your customers.
                        </p>
                    </div>

                    <!-- Step 3 -->
                    <div
                        class="bg-white rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 p-8 flex flex-col items-center text-center">
                        <img src="images/step3.png" alt="Start Collecting Reviews"
                            class="w-[180px] h-[180px] mb-6 transition-transform duration-300 hover:scale-105" loading="lazy">
                        <h3 class="text-[21px] font-bold text-[#1800ad] font-ubuntu mb-3">Step 3: Collect Reviews</h3>
                        <p class="text-gray-600 font-mulish leading-relaxed">
                            Customers scan your card and leave reviews. Track all feedback directly in your dashboard!
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="relative bg-cover bg-center bg-no-repeat text-[#1800ad] py-24 px-4 sm:px-6 lg:px-8 font-mulish"
            style="background-image: url('https://d1yei2z3i6k35z.cloudfront.net/161/609bb9ff8ffc9_Groupedemasques1.jpg');">

            <div class="relative z-10 max-w-6xl mx-auto px-4 text-black">
                <h2 class="text-4xl font-extrabold text-center mb-4 font-ubuntu text-[#1800ad]">Supported Platforms</h2>
                <p class="text-center text-gray-800 mb-16 text-lg font-mulish">Grow your presence across the platforms that
                    matter most.</p>

                <div class="grid md:grid-cols-3 gap-10">
                    <!-- Each platform card -->
                    <div
                        class="bg-white/70 rounded-2xl p-8 text-center shadow-xl border border-white/20 hover:bg-white/90 transition duration-300">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-yellow-400 to-red-500 text-white rounded-full flex items-center justify-center text-3xl mx-auto mb-6 shadow-md">
                            <i class="fab fa-google"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-2 text-[#1800ad]">Google Reviews</h3>
                        <p class="text-gray-800">Improve search visibility and build trust through real customer feedback.
                        </p>
                    </div>

                    <div
                        class="bg-white/70 rounded-2xl p-8 text-center shadow-xl border border-white/20 hover:bg-white/90 transition duration-300">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-pink-500 via-red-500 to-yellow-500 text-white rounded-full flex items-center justify-center text-3xl mx-auto mb-6 shadow-md">
                            <i class="fab fa-instagram"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-2 text-[#1800ad]">Instagram</h3>
                        <p class="text-gray-800">Showcase your brand, connect with followers, and grow engagement
                            organically.</p>
                    </div>

                    <div
                        class="bg-white/70 rounded-2xl p-8 text-center shadow-xl border border-white/20 hover:bg-white/90 transition duration-300">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-blue-600 to-blue-400 text-white rounded-full flex items-center justify-center text-3xl mx-auto mb-6 shadow-md">
                            <i class="fab fa-facebook-f"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-2 text-[#1800ad]">Facebook</h3>
                        <p class="text-gray-800">Engage your audience and grow brand awareness with targeted content.</p>
                    </div>
                </div>
            </div>
        </section>



        <!-- Testimonial Section -->
        <style>
            .testimonial-card {
                background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
                border: 1px solid #e2e8f0;
            }

            .quote-icon {
                background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            }

            .star-rating {
                color: #fbbf24;
            }
        </style>

        <div class="max-w-6xl mx-auto px-4 pt-20">
            <!-- Header Section -->
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-[#1800ad] mb-4 font-ubuntu">What Our Customers Say</h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto font-mulish">
                    Discover how businesses across the UK are transforming their online reputation with Review Boost
                </p>
            </div>

            <!-- Featured Success Story -->
            <div class="bg-gradient-to-r from-[#1800ad] to-[#0cc0df] rounded-2xl p-8 mb-16 text-white">
                <div class="max-w-4xl mx-auto text-center">
                    <div class="quote-icon w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h4v10h-10z" />
                        </svg>
                    </div>
                    <blockquote class="text-base sm:text-xl md:text-2xl font-medium mb-6 italic font-mulish">
                        "We went from getting less than 4 reviews per month to 15 reviews in our very first week! Our Google
                        rating jumped from 4.2 to 4.7 stars. The tap cards are so simple - customers love how easy it is."
                    </blockquote>
                    <div class="flex justify-center mb-4">
                        <div class="flex star-rating">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        </div>
                    </div>
                    <cite class="text-lg font-semibold">Raj Patel</cite>
                    <p class="text-blue-100 mt-1">Owner, Spice Garden Indian Restaurant, London</p>
                </div>
            </div>

            <!-- Testimonials Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
                <!-- Testimonial 1 -->
                <div class="testimonial-card rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow">
                    <div class="flex star-rating mb-4">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </div>
                    <blockquote class="text-gray-700 mb-4 italic">
                        "The staff leaderboard has been a game-changer! My team is now competing to get the most reviews.
                        We've gone from 2-3 reviews per month to 25+ reviews. Our Google ranking has improved dramatically."
                    </blockquote>
                    <div class="flex items-center">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-sm mr-3">
                            SM
                        </div>
                        <div>
                            <cite class="text-[#1800ad] font-semibold">Sarah Mitchell</cite>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="testimonial-card rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow">
                    <div class="flex star-rating mb-4">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </div>
                    <blockquote class="text-gray-700 mb-4 italic">
                        "The Smart Feedback Loop is brilliant! It’s helped us resolve customer issues privately and improve our service before they even left our store. Our average rating has stayed consistently high."
                    </blockquote>
                    <div class="flex items-center">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center text-white font-bold text-sm mr-3">
                            MT
                        </div>
                        <div>
                            <cite class="text-[#1800ad] font-semibold">Mike Thompson</cite>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="testimonial-card rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow">
                    <div class="flex star-rating mb-4">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </div>
                    <blockquote class="text-gray-700 mb-4 italic">
                        "Best investment I've made for my business! The AI Review Responder saves me hours every week. I now
                        have over 200 Google reviews and we're the top-rated dental practice in our area."
                    </blockquote>
                    <div class="flex items-center">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-purple-400 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-sm mr-3">
                            EH
                        </div>
                        <div>
                            <cite class="text-[#1800ad] font-semibold">Dr. Emma Harrison</cite>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 4 -->
                <div class="testimonial-card rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow">
                    <div class="flex star-rating mb-4">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </div>
                    <blockquote class="text-gray-700 mb-4 italic">
                        "They were so easy to get up and running! I'm hopeful these will make it easier for people to leave
                        reviews 😊 I really like the concept and everything about these! Thank you for making such a great
                        little product!!"
                    </blockquote>
                    <div class="flex items-center">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-green-400 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-sm mr-3">
                            JE
                        </div>
                        <div>
                            <cite class="text-[#1800ad] font-semibold">Julie E</cite>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 5 -->
                <div class="testimonial-card rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow">
                    <div class="flex star-rating mb-4">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </div>
                    <blockquote class="text-gray-700 mb-4 italic">
                        "As a new business owner, I am really glad this is going to help me boost my reviews. The set up is
                        quite easy and the video in the setting up process has been very helpful."
                    </blockquote>
                    <div class="flex items-center">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-yellow-400 to-orange-600 rounded-full flex items-center justify-center text-white font-bold text-sm mr-3">
                            KS
                        </div>
                        <div>
                            <cite class="text-[#1800ad] font-semibold">Kalesh Brown</cite>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 6 -->
                <div class="testimonial-card rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow">
                    <div class="flex star-rating mb-4">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </div>
                    <blockquote class="text-gray-700 mb-4 italic">
                        "I highly recommend these products . Really easy to set up and when we purchased more again so easy
                        to set up our clients love it and so do we ."
                    </blockquote>
                    <div class="flex items-center">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-red-400 to-yellow-600 rounded-full flex items-center justify-center text-white font-bold text-sm mr-3">
                            LD
                        </div>
                        <div>
                            <cite class="text-[#1800ad] font-semibold">Linzi D.</cite>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Call to Action Section -->
        <section class="py-8 sm:px-6 lg:px-8 font-mulish">
            <div class="max-w-6xl mx-auto rounded-2xl bg-cover bg-center bg-no-repeat text-[#1800ad] text-center px-8 md:px-20 py-20"
                style="background-image: url('https://d1yei2z3i6k35z.cloudfront.net/161/609bb9ff8ffc9_Groupedemasques1.jpg');">

                <h2 class="text-3xl sm:text-4xl font-bold mb-4 leading-tight font-ubuntu">
                    Ready to boost your customer reviews?
                </h2>
                <p class="text-lg sm:text-md mb-8 leading-relaxed font-mulish text-[#142D63]">
                    Start collecting real, verified reviews and grow your business reputation. Try Tap Review Cards today —
                    it’s
                    fast, simple, and effective.
                </p>
                <div class="flex justify-center gap-4 flex-wrap font-mulish">
                    <a href="register"
                        class="bg-white text-[#00A0FF] hover:bg-gray-100 px-6 py-3 rounded-full font-semibold shadow-md transition">
                        Get Started Free
                    </a>
                    <a href="contact"
                        class="border border-[#00A0FF] px-6 py-3 rounded-full font-semibold hover:bg-white hover:text-[#00A0FF] transition">
                        Contact Us
                    </a>
                </div>
            </div>
        </section>



@endsection