@extends('layouts.guest')

@section('title', 'Terms of Service | Tap Review Cards UK')
@section('meta_description', 'Read the terms of service for Tap Review Cards. Understand our usage policies, payment terms, and disclaimer regarding our NFC review collection tools.')

@section('schema')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebPage",
    "name": "Terms of Service",
    "description": "The legal terms and conditions for using Tap Review Cards services.",
    "publisher": {
        "@type": "Organization",
        "name": "Tap Review Cards",
        "url": "https://tapreviewcards.co.uk"
    }
}
</script>
@endsection

@section('content')
<div class="min-h-screen bg-gray-50">
     <!-- Header Section -->
     <section class="relative -mt-[100px] pt-[100px] bg-cover bg-center bg-no-repeat text-[#1800ad] font-mulish" style="background-image: url('https://d1yei2z3i6k35z.cloudfront.net/161/609bb9ff8ffc9_Groupedemasques1.jpg');">
        <div class="relative max-w-3xl mx-auto text-center py-20 px-6 md:px-10">
            <h1 class="text-4xl sm:text-5xl font-bold mb-4 leading-tight font-ubuntu ">
                Terms of Service
            </h1>
            <p class="text-lg sm:text-md mb-8 leading-relaxed text-[#142D63]">
                Please read these terms carefully before using our platform <br>
                and NFC review products.
            </p>
        </div>
    </section>

    <!-- Content Section -->
    <section class="py-16 bg-white">
        <div class="max-w-6xl mx-auto px-6 font-mulish text-gray-700 leading-relaxed space-y-16">
            
            <!-- 1. Introduction -->
            <div class="space-y-4">
                <h2 class="text-xl font-bold text-[#0F172A] font-ubuntu">1. Acceptance of Terms</h2>
                <p>
                    By accessing or using the Tap Review Cards website and our NFC products, you agree to be bound by these Terms of Service. If you do not agree to all of these terms, do not use our services.
                </p>
            </div>

            <!-- 2. The Services -->
            <div class="space-y-4 pt-6">
                <h2 class="text-xl font-bold text-[#0F172A] font-ubuntu">2. Description of Service</h2>
                <p>
                    Tap Review Cards provides NFC-enabled physical products and a digital dashboard to help businesses facilitate the collection of customer reviews. Our service includes the "Smart Feedback Loop" feature, which allows businesses to collect private customer feedback for internal resolution and service improvement.
                </p>
            </div>

            <!-- 3. Important Disclaimer -->
            <div class="space-y-4 mt-4 p-6 bg-yellow-50 border-l-4 border-yellow-400 rounded-r-xl">
                <h2 class="text-xl font-bold text-yellow-900 font-ubuntu">3. Third-Party Disclaimer</h2>
                <p class="text-yellow-800">
                    <strong>Tap Review Cards is not affiliated with, endorsed by, or partnered with Google, Facebook, Trustpilot, or any other third-party review platform.</strong> Our products simply provide a streamlined way for your customers to access your public profile on these platforms. We do not guarantee specific rankings or results on these platforms.
                </p>
            </div>


            <!-- 4. Payments and Subscriptions -->
            <div class="space-y-4 pt-6">
                <h2 class="text-xl font-bold text-[#0F172A] font-ubuntu">4. Payments and Billing</h2>
                <p>
                    Some features of our service require a paid subscription. By signing up for a subscription, you agree to provide valid payment information and authorize us to charge the applicable fees. Subscriptions automatically renew unless cancelled at least 24 hours before the end of the current period.
                </p>
            </div>

            <!-- 5. Limitation of Liability -->
            <div class="space-y-4 pt-6">
                <h2 class="text-xl font-bold text-[#0F172A] font-ubuntu">5. Limitation of Liability</h2>
                <p>
                    To the maximum extent permitted by law, Tap Review Cards shall not be liable for any indirect, incidental, special, consequential, or punitive damages resulting from your use of or inability to use our services.
                </p>
            </div>

            <div class="mt-12 p-8 bg-gray-100 rounded-2xl text-center">
                <p class="text-sm text-gray-500 italic">Last Updated: April 2026</p>
                <p class="mt-4">If you have any questions about these Terms, please contact us.</p>
                <a href="{{ route('contact') }}" class="inline-block mt-4 text-[#1800ad] font-bold hover:underline">
                    Contact Legal Support
                </a>
            </div>
        </div>
    </section>
</div>
@endsection
