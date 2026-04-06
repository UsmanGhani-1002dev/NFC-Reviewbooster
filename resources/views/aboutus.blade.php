@extends('layouts.guest')

@section('title', 'About Tap Review Cards | NFC Review Solutions UK')
@section('meta_description', 'Tap Review Cards is a UK-based company helping local businesses collect more Google reviews using NFC technology. Learn about our mission to make reputation management simple, honest, and effective.')
@section('meta_keywords', 'about tap review cards, NFC review card company UK, review management solutions, Google review tools UK')

@section('schema')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "AboutPage",
    "name": "About Tap Review Cards",
    "description": "Learn about Tap Review Cards — the UK company helping businesses collect more Google reviews with smart NFC tap cards.",
    "url": "https://tapreviewcards.co.uk/about",
    "mainEntity": {
        "@type": "Organization",
        "name": "Tap Review Cards",
        "url": "https://tapreviewcards.co.uk",
        "foundingLocation": "United Kingdom",
        "description": "Tap Review Cards helps businesses grow by boosting their online reputation through NFC-enabled review collection tools."
    }
}
</script>
@endsection

@section('content')


    <!-- SECTION 1: WHO WE ARE -->
    <section class="relative -mt-[100px] pt-[100px] text-[#1800ad] overflow-hidden" style="background-image: url('https://d1yei2z3i6k35z.cloudfront.net/161/609e5084b32cc_Groupe2589.jpg'); background-size: cover; background-repeat: no-repeat; background-position: top center;">
        <!-- Overlay content -->
        <div class="text-center py-20 px-6 md:px-10">
            <h1 class="text-4xl sm:text-5xl font-bold mb-4 leading-tight font-ubuntu ">About Us</h1>
            <p class="text-base md:text-lg max-w-3xl mx-auto leading-relaxed font-mulish text-[#142D63]">
                At Tap Review Cards, we help businesses grow by boosting their online reputation. Our tools are designed to make it easy to collect, manage, and display reviews — building trust with your customers and turning feedback into opportunity.
            </p>
        </div>
    </section>

  
    <!-- SECTION 2: HOW IT WORKS (Text + Image) -->
    <section class="py-16 mx-4 lg:mx-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <h3 class="text-4xl font-semibold text-[#1800ad] mb-6 font-ubuntu">Smart Feedback Loop</h3>
                    <p class="text-gray-700 text-base leading-relaxed mb-4 font-mulish">
                        Tap Review Cards combines smart NFC technology with a powerful backend to streamline the review
                        collection process. Each team member or department gets a unique NFC-enabled review card. When a
                        customer taps the card with their phone, they're taken to a review page tailored to your brand.
                    </p>

                    {{-- Tick List --}}
                    <ul class="space-y-3 mb-6 font-mulish">
                        <li class="flex items-start text-gray-700">
                            <span class="text-green-600 mt-1 mr-2">✔️</span>
                            <span>Customers tap the card — no app download required</span>
                        </li>
                        <li class="flex items-start text-gray-700">
                            <span class="text-green-600 mt-1 mr-2">✔️</span>
                            <span>They are offered the opportunity to share their internal feedback</span>
                        </li>
                        <li class="flex items-start text-gray-700">
                            <span class="text-green-600 mt-1 mr-2">✔️</span>
                            <span>Happy customers are encouraged to share their feedback publicly on Google</span>
                        </li>
                        <li class="flex items-start text-gray-700">
                            <span class="text-green-600 mt-1 mr-2">✔️</span>
                            <span>Private concerns lead to an internal resolution form</span>
                        </li>
                        <li class="flex items-start text-gray-700">
                            <span class="text-green-600 mt-1 mr-2">✔️</span>
                            <span>All interactions are tracked and visualized in your management dashboard</span>
                        </li>
                    </ul>

                    <p class="text-gray-700">
                        This ensures your team gets credit for great service, and any issues are handled privately and
                        professionally.
                    </p>
                </div>

                <div class="flex justify-center">
                    <img src="images/review_image.gif" alt="How Tap Review Cards Works"
                        class="rounded-xl w-full h-auto object-cover">
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 3: HOW IT WORKS (4 Steps) -->
    <section class="py-20 rounded-lg mb-8 mx-4 lg:mx-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-[38px] font-bold text-[#1800ad] mb-6 font-ubuntu">
                    How It Works.
                </h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto font-mulish">
                    Tap Review Cards helps you collect, manage, and showcase real reviews to grow your reputation and win more
                    customers.
                </p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Step 1 -->
                <div
                    class="relative text-center p-6 transition-transform transform hover:scale-105 shadow-lg hover:border hover:border-[#e2e8f0] rounded-xl bg-white">
                    <div class="absolute -top-5 left-1/2 transform -translate-x-1/2">
                        <div
                            class="w-10 h-10 rounded-full bg-[#e0ebff] text-[#1800ad] font-bold flex items-center justify-center shadow-md">
                            1
                        </div>
                    </div>
                    <div class="w-16 h-16 flex items-center justify-center mx-auto mb-6 mt-5">
                        <img src="images/customer.png" class="w-16 h-16 text-blue-600" alt="Tap Card Icon" />
                    </div>
                    <h3 class="text-lg font-semibold text-[#1800ad] mb-3 font-ubuntu">Setup Your Tap Cards</h3>
                    <p class="text-gray-600 font-mulish">Order your NFC tap cards. Cards are pre-configured with your business details
                        and shipped worldwide within 48 hours.</p>
                </div>

                <!-- Step 2 -->
                <div
                    class="relative text-center p-6 transition-transform transform hover:scale-105 shadow-lg hover:border hover:border-[#e2e8f0] rounded-xl bg-white">
                    <div class="absolute -top-5 left-1/2 transform -translate-x-1/2">
                        <div
                            class="w-10 h-10 rounded-full bg-[#e0ebff] text-[#1800ad] font-bold flex items-center justify-center shadow-md">
                            2
                        </div>
                    </div>
                    <div class="w-20 h-20 flex items-center justify-center mx-auto mb-2 mt-5">
                        <img src="images/cardss.png" class="w-16 h-14 text-yellow-600" alt="Card Icon"/>
                    </div>
                    <h3 class="text-lg font-semibold text-[#1800ad] mb-3 font-ubuntu">Card Creation & Distribution</h3>
                    <p class="text-gray-600 font-mulish">Receive your personalized plastic cards and distribute them to your staff.
                        Each employee gets their own trackable card with unique ID.</p>
                </div>

                <!-- Step 3 -->
                <div
                    class="relative text-center p-6 transition-transform transform hover:scale-105 shadow-lg hover:border hover:border-[#e2e8f0] rounded-xl bg-white">
                    <div class="absolute -top-5 left-1/2 transform -translate-x-1/2">
                        <div
                            class="w-10 h-10 rounded-full bg-[#e0ebff] text-[#1800ad] font-bold flex items-center justify-center shadow-md">
                            3
                        </div>
                    </div>
                    <div class="w-16 h-16 flex items-center justify-center mx-auto mb-6 mt-5">
                        <img src="images/app.png" class="w-16 h-16 text-green-600" alt="Review Icon" />
                    </div>
                    <h3 class="text-lg font-semibold text-[#1800ad] mb-3 font-ubuntu">Smart Feedback Loop</h3>
                    <p class="text-gray-600 font-mulish">Customers tap the NFC card on their phone and are instantly taken to your review page. The Smart Feedback Loop helps you resolve issues before they escalate.</p>
                </div>

                <!-- Step 4 -->
                <div
                    class="relative text-center p-6 transition-transform transform hover:scale-105 shadow-lg hover:border hover:border-[#e2e8f0] rounded-xl bg-white">
                    <div class="absolute -top-5 left-1/2 transform -translate-x-1/2">
                        <div
                            class="w-10 h-10 rounded-full bg-[#e0ebff] text-[#1800ad] font-bold flex items-center justify-center shadow-md">
                            4
                        </div>
                    </div>
                    <div class="w-16 h-16 flex items-center justify-center mx-auto mb-6 mt-5">
                        <img src="images/analytics.png" class="w-16 h-16 text-purple-600" alt="Analytics Icon" />
                    </div>
                    <h3 class="text-lg font-semibold text-[#1800ad] mb-3 font-ubuntu">Monitor, Track & Optimize</h3>
                    <p class="text-gray-600 font-mulish">Track all reviews in your dashboard, monitor staff performance with
                        leaderboards, and use AI to automatically respond to every review.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 4: OUR MISSION & VALUES -->
    <style>
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .hover-lift {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .hover-lift:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        .floating-animation {
            animation: float 6s ease-in-out infinite;
        }
        .floating-animation:nth-child(2) {
            animation-delay: -2s;
        }
        .floating-animation:nth-child(3) {
            animation-delay: -4s;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .mission-card {
            background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }
    </style>

     <section class="py-20 relative overflow-hidden" style="background-image: url('https://d1yei2z3i6k35z.cloudfront.net/161/609bb9ff8ffc9_Groupedemasques1.jpg'); background-size: cover; background-repeat: no-repeat; background-position: top center;">
        <!-- Background Elements -->
        <div class="absolute inset-0 opacity-5">
            <div class="floating-animation absolute top-20 left-10 w-32 h-32 bg-blue-500 rounded-full"></div>
            <div class="floating-animation absolute top-40 right-20 w-24 h-24 bg-purple-500 rounded-full"></div>
            <div class="floating-animation absolute bottom-20 left-1/3 w-20 h-20 bg-green-500 rounded-full"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Section Header -->
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-[38px] text-[#1800ad] font-bold mb-6">
                    <span class="text-[#1800ad] font-ubuntu">Mission & Values</span>
                </h2>
                
                <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed font-mulish">
                    Empowering businesses to build exceptional online reputations through innovative review management solutions
                </p>
            </div>

            <div class="grid lg:grid-cols-2 gap-16 items-start font-mulish">
                <!-- Mission Section -->
                <div class="mission-card rounded-3xl p-10 hover-lift">
                    <div class="flex items-center gap-5 mb-8">
                        <!-- Icon Container with Gradient -->
                        <div class="w-16 h-16 bg-gradient-to-br from-[#00A0FF] to-cyan-500 rounded-2xl flex items-center justify-center shadow-md">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>

                        <!-- Text Content -->
                        <h3 class="text-2xl sm:text-3xl md:text-4xl font-bold text-[#1800ad] font-ubuntu">
                            Our Mission
                        </h3>
                    </div>


                    
                    <div class="space-y-6">
                        <p class="text-gray-700 text-lg leading-relaxed">
                            We revolutionize how businesses collect and manage customer reviews by making the process effortless, authentic, and impactful. Every positive customer interaction should become a five-star review that drives growth.
                        </p>
                        
                        <p class="text-gray-700 text-lg leading-relaxed">
                            Tap Review Cards transforms ordinary customer experiences into powerful marketing assets, helping businesses of all sizes build trust, credibility, and sustainable growth in the digital marketplace.
                        </p>
                        
                        <p class="text-gray-700 text-lg leading-relaxed">
                            Whether you're a local shop or a global brand, our platform adapts to your needs—enabling you to collect more reviews, respond faster, and make data-driven decisions that shape a better customer journey.
                        </p>
                        
                        <div class="flex items-center space-x-2">
                            <div class="flex -space-x-2">
                                <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-bold">5K+</div>
                                <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-white text-sm font-bold">★</div>
                                <div class="w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center text-white text-sm font-bold">∞</div>
                            </div>
                            <span class="text-gray-600 font-medium">Businesses • Reviews • Growth</span>
                        </div>
                    </div>
                </div>

                <!-- Values Section -->
                <div>

                    <div class="space-y-8 font-mulish">
                        <!-- Innovation -->
                        <div class="group bg-white rounded-2xl p-6 hover-lift border border-gray-100">
                            <div class="flex items-start space-x-6">
                                <div class="w-14 h-14 bg-gradient-to-br from-[#00A0FF] to-cyan-500 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-2xl font-bold text-[#1800ad] font-ubuntu mb-3">Innovation</h4>
                                    <p class="text-gray-600 text-lg leading-relaxed">
                                        We continuously push boundaries with cutting-edge technology, creating intuitive solutions that make review collection seamless and automated for modern businesses.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Authenticity -->
                        <div class="group bg-white rounded-2xl p-6 hover-lift border border-gray-100">
                            <div class="flex items-start space-x-6">
                                <div class="w-14 h-14 bg-gradient-to-br from-[#00A0FF] to-cyan-500 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-2xl font-bold text-[#1800ad] font-ubuntu mb-3">Authenticity</h4>
                                    <p class="text-gray-600 text-lg leading-relaxed">
                                        We champion genuine customer feedback and transparent business practices, building trust through authentic relationships and real experiences.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Results -->
                        <div class="group bg-white rounded-2xl p-6 hover-lift border border-gray-100">
                            <div class="flex items-start space-x-6">
                                <div class="w-14 h-14 bg-gradient-to-br from-[#00A0FF] to-cyan-500 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-2xl font-bold text-[#1800ad] font-ubuntu mb-3">Results-Driven</h4>
                                    <p class="text-gray-600 text-lg leading-relaxed">
                                        We're obsessed with delivering measurable outcomes - more reviews, higher ratings, increased trust, and tangible business growth for every client we serve.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Why Choose Tap Review Cards Section -->
    <div class="py-16 mx-4 lg:mx-8 rounded-xl mb-8">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <h3 class="text-3xl md:text-4xl font-bold text-[#1800ad] mb-6 font-ubuntu">Why Tap Review Cards?</h3>
            <p class="text-lg text-gray-600 mb-12 max-w-3xl mx-auto font-mulish">
                Transform your business reputation with our innovative NFC review system that makes collecting positive
                reviews effortless.
            </p>

            <div class="grid md:grid-cols-3 gap-8">
                <div
                    class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-3xl">🚀</span>
                    </div>
                    <h4 class="text-xl font-bold text-[#1800ad] mb-4 font-ubuntu">Easy Setup</h4>
                    <p class="text-gray-600 leading-relaxed font-mulish">
                        Get started in minutes. Order your NFC cards, distribute to staff, and start collecting reviews
                        immediately. No technical expertise required.
                    </p>
                </div>

                <div
                    class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-3xl">⭐</span>
                    </div>
                    <h4 class="text-xl font-bold text-[#1800ad] mb-4 font-ubuntu">Collect Private Insights</h4>
                    <p class="text-gray-600 leading-relaxed font-mulish">
                        Collect private customer feedback to resolve issues before they escalate. Improve your service and understand your customers better with private insights.
                    </p>
                </div>

                <div
                    class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-3xl">📊</span>
                    </div>
                    <h4 class="text-xl font-bold text-[#1800ad] mb-4 font-ubuntu">Powerful Analytics</h4>
                    <p class="text-gray-600 leading-relaxed font-mulish">
                        Track team performance with leaderboards, monitor review trends, and use AI to respond
                        automatically. Data-driven reputation management.
                    </p>
                </div>
            </div>
        </div>
    </div>

     <!-- Testimonial Section -->
    <style>
        .testimonial-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid #e2e8f0;
        }
        .star-rating {
            color: #fbbf24;
        }
    </style>

    <div class="max-w-6xl mx-auto px-4 pt-8">
        <!-- Header Section -->
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-[#1800ad] mb-4 font-ubuntu">What Our Customers Say</h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto font-mulish">
                Discover how businesses across the UK are transforming their online reputation with Review Boost
            </p>
        </div>

        <!-- Testimonials Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
            <!-- Testimonial 1 -->
            <div class="testimonial-card rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow">
                <div class="flex star-rating mb-4">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
                <blockquote class="text-gray-700 mb-4 italic">
                    "The staff leaderboard has been a game-changer! My team is now competing to get the most reviews. We've gone from 2-3 reviews per month to 25+ reviews. Our Google ranking has improved dramatically."
                </blockquote>
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-sm mr-3">
                        SM
                    </div>
                    <div>
                        <cite class="text-[#1800ad] font-semibold">Sarah Mitchell</cite>
                        <p class="text-gray-500 text-sm">Salon Owner, Manchester</p>
                    </div>
                </div>
            </div>

            <!-- Testimonial 2 -->
            <div class="testimonial-card rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow">
                <div class="flex star-rating mb-4">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
                <blockquote class="text-gray-700 mb-4 italic">
                    "The Review Gate feature is brilliant! It's helped us avoid several negative reviews by letting us address issues privately first. Our average rating has stayed consistently high at 4.8 stars."
                </blockquote>
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center text-white font-bold text-sm mr-3">
                        MT
                    </div>
                    <div>
                        <cite class="text-[#1800ad] font-semibold">Mike Thompson</cite>
                        <p class="text-gray-500 text-sm">Plumbing Services, Birmingham</p>
                    </div>
                </div>
            </div>

            <!-- Testimonial 3 -->
            <div class="testimonial-card rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow">
                <div class="flex star-rating mb-4">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
                <blockquote class="text-gray-700 mb-4 italic">
                    "Best investment I've made for my business! The AI Review Responder saves me hours every week. I now have over 200 Google reviews and we're the top-rated dental practice in our area."
                </blockquote>
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-sm mr-3">
                        EH
                    </div>
                    <div>
                        <cite class="text-[#1800ad] font-semibold">Dr. Emma Harrison</cite>
                        <p class="text-gray-500 text-sm">Dental Practice, Leeds</p>
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
            Start collecting real, verified reviews and grow your business reputation. Try Tap Review Cards today — it’s
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