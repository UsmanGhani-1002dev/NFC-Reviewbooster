@extends('layouts.guest')

@section('title', 'Contact Us — Tap Review Cards | Get in Touch for NFC Review Card Support')
@section('meta_description', 'Need help with your NFC Google review cards? Contact Tap Review Cards for expert support, custom solutions, or bulk orders. Based in the UK — we respond within 24 hours.')
@section('meta_keywords', 'contact tap review cards, NFC review card support UK, Google review card help')

@section('schema')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "ContactPage",
    "name": "Contact Tap Review Cards",
    "description": "Get in touch with Tap Review Cards for support, sales, or partnerships.",
    "url": "https://tapreviewcards.co.uk/contact",
    "mainEntity": {
        "@type": "Organization",
        "name": "Tap Review Cards",
        "telephone": "+44-1283-515606",
        "email": "info@tapreviewcards.co.uk",
        "address": {
            "@type": "PostalAddress",
            "addressCountry": "GB"
        },
        "openingHoursSpecification": {
            "@type": "OpeningHoursSpecification",
            "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"],
            "opens": "09:00",
            "closes": "18:00"
        }
    }
}
</script>
@endsection

@section('content')
<div class="min-h-screen bg-gray-50">
     <!-- CTA Section -->
    <section class="relative -mt-[100px] pt-[100px] bg-cover bg-center bg-no-repeat text-[#1800ad] font-mulish" style="background-image: url('https://d1yei2z3i6k35z.cloudfront.net/161/609bb9ff8ffc9_Groupedemasques1.jpg');">
        <div class="relative max-w-3xl mx-auto text-center py-20 px-6 md:px-10">
            <h1 class="text-4xl sm:text-5xl font-bold mb-4 leading-tight font-ubuntu ">
            Contact Us
            </h1>
            <p class="text-lg sm:text-md mb-8 leading-relaxed text-[#142D63]">
                Have questions about Tap Review Cards? Need support with your tap cards? <br>
                We're here to help you boost your Google reviews and grow your business.
            </p>
        </div>
    </section>

    <!-- Contact Form & Info Section -->
    <section class="py-16 bg-white">
        <div class="max-w-6xl mx-auto px-4">
            <div class="grid lg:grid-cols-3 gap-12">
                <!-- Contact Form -->
                <div class="lg:col-span-2 font-mulish">
                    <h2 class="text-3xl font-bold mb-6 font-ubuntu text-[#1800ad]">Send Us a Message</h2>
                    <p class="text-gray-600 mb-8">
                        Fill out the form below and we'll get back to you as soon as possible. 
                        Whether you have questions about our tap cards, need technical support, 
                        or want to discuss how ReviewBoost can help your business.
                    </p>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('contact.submit') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                                <input type="text" id="first_name" name="first_name" value="{{ old('first_name', request('first_name')) }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                       required>
                            </div>
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                                <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                       required>
                            </div>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                            <input type="email" id="email" name="email" value="{{ old('email', request('email')) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                   required>
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div>
                            <label for="business_name" class="block text-sm font-medium text-gray-700 mb-2">Business Name</label>
                            <input type="text" id="business_name" name="business_name" value="{{ old('business_name', request('business_name')) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div>
                            <label for="inquiry_type" class="block text-sm font-medium text-gray-700 mb-2">Inquiry Type *</label>
                            <select id="inquiry_type" name="inquiry_type" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                    required>
                                <option value="">Select an option</option>
                                <option value="general" {{ old('inquiry_type', request('inquiry')) == 'general' ? 'selected' : '' }}>General Inquiry</option>
                                <option value="support" {{ old('inquiry_type', request('inquiry')) == 'support' ? 'selected' : '' }}>Technical Support</option>
                                <option value="sales" {{ old('inquiry_type', request('inquiry')) == 'sales' ? 'selected' : '' }}>Sales Question</option>
                                <option value="billing" {{ old('inquiry_type', request('inquiry')) == 'billing' ? 'selected' : '' }}>Billing Issue</option>
                                <option value="enterprise" {{ old('inquiry_type', request('inquiry')) == 'enterprise' ? 'selected' : '' }}>Enterprise Solutions</option>
                                <option value="partnership" {{ old('inquiry_type', request('inquiry')) == 'partnership' ? 'selected' : '' }}>Partnership</option>
                                <option value="feedback" {{ old('inquiry_type', request('inquiry')) == 'feedback' ? 'selected' : '' }}>Feedback</option>
                            </select>
                        </div>

                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject *</label>
                            <input type="text" id="subject" name="subject" value="{{ old('subject') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                   required>
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message *</label>
                            <textarea id="message" name="message" rows="6" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                      placeholder="Please provide details about your inquiry..." 
                                      required>{{ old('message') }}</textarea>
                        </div>

                        <div class="flex items-start">
                            <input type="checkbox" id="privacy_policy" name="privacy_policy" value="1" 
                                   class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                                   required>
                            <label for="privacy_policy" class="ml-2 text-sm text-gray-600">
                                I agree to the <a href="#" class="text-blue-600 hover:text-blue-800">Privacy Policy</a> 
                                and consent to being contacted regarding my inquiry. *
                            </label>
                        </div>

                        <button type="submit" 
                                class="w-full bg-[#1800ad] hover:bg-[#0cc0df] text-white py-3 px-6 rounded-lg font-semibold transition duration-200">
                            Send Message
                        </button>
                    </form>
                </div>

                <!-- Contact Info & FAQ -->
                <!-- Contact Info & FAQ - Takes 1 column (1/3 of the width) -->
                <div class="lg:col-span-1"> 
                    <div class="space-y-8 font-mulish">
                        <!-- Phone -->
                        <div class="text-center p-6 bg-white rounded-lg shadow-lg">
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-[#1800ad] mb-3 font-ubuntu">Call Us</h3>
                            <p class="text-gray-600 mb-2">Speak directly with our team</p>
                            <p class="text-blue-600 font-semibold">+44 1283 515606</p>
                            <p class="text-sm text-gray-500 mt-2">Mon - Fri: 9AM - 6PM GMT</p>
                        </div>

                        <!-- Email -->
                        <div class="text-center p-6 bg-white rounded-lg shadow-lg">
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-[#1800ad] mb-3 font-ubuntu">Email Us</h3>
                            <p class="text-gray-600 mb-2">Get detailed support via email</p>
                            <p class="text-green-600 font-semibold">info@tapreviewcards.co.uk</p>
                            <p class="text-sm text-gray-500 mt-2">Response within 24 hours</p>
                        </div>

                        <!-- Chat -->
                        {{-- <div class="text-center p-6 bg-white rounded-lg shadow-lg">
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-purple-100 rounded-full mb-4">
                                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-[#1800ad] mb-3 font-ubuntu">Live Chat</h3>
                            <p class="text-gray-600 mb-2">Instant support when you need it</p>
                            <button class="text-purple-600 font-semibold hover:text-purple-800 transition duration-200" onclick="startChat()">Start Chat</button>
                            <p class="text-sm text-gray-500 mt-2">Available during business hours</p>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="relative bg-cover bg-center bg-no-repeat text-[#1800ad] py-24 px-4 sm:px-6 lg:px-8 font-mulish" style="background-image: url('https://d1yei2z3i6k35z.cloudfront.net/161/609bb9ff8ffc9_Groupedemasques1.jpg');">
        <div class="relative max-w-3xl mx-auto text-center">
            <h2 class="text-3xl sm:text-4xl font-bold mb-4 leading-tight font-ubuntu ">
            Ready to boost your customer reviews?
            </h2>
            <p class="text-lg sm:text-md mb-8 leading-relaxed text-[#142D63]">
            Start collecting real, verified reviews and grow your business reputation. Try Tap Review Cards today — it’s fast, simple, and effective.
            </p>
            <div class="flex justify-center gap-4 flex-wrap">
            <a href="register" class="bg-white text-[#00A0FF] hover:bg-gray-100 px-6 py-3 rounded-full font-semibold shadow-md transition">
                Get Started Free
            </a>
            <a href="contact" class="border border-[#00A0FF] px-6 py-3 rounded-full font-semibold hover:bg-white hover:text-[#00A0FF] transition">
                Contact Us
            </a>
            </div>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script>
    // Form validation
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const submitButton = form.querySelector('button[type="submit"]');
        
        form.addEventListener('submit', function() {
            submitButton.disabled = true;
            submitButton.textContent = 'Sending...';
        });
    });
</script>
@endsection