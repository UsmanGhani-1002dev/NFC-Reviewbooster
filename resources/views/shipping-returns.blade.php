@extends('layouts.guest')

@section('title', 'Shipping & Returns Policy | Tap Review Cards UK')
@section('meta_description', 'Learn about our UK shipping times, international delivery options, and our 30-day return policy for Tap Review Cards. We offer tracked 48-hour delivery for all UK orders.')

@section('schema')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebPage",
    "name": "Shipping and Returns Policy",
    "description": "Shipping information and return policy for Tap Review Cards UK.",
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
                Shipping & Returns Policy
            </h1>
            <p class="text-lg sm:text-md mb-8 leading-relaxed text-[#142D63]">
                Everything you need to know about how we deliver your Tap Review Cards <br>
                and our straightforward returns process.
            </p>
        </div>
    </section>

    <!-- Content Section -->
    <section class="py-16 bg-white">
        <div class="max-w-6xl mx-auto px-6 font-mulish text-gray-700 leading-relaxed space-y-12">
            
            <!-- Shipping Info -->
            <div class="space-y-6">
                <h2 class="text-3xl font-bold text-[#0F172A] font-ubuntu">Shipping Information</h2>
                <p>
                    We aim to dispatch all orders within 24–48 hours after your order is confirmed.<br/>

                    UK orders are typically delivered within 1–2 working days using tracked delivery services.

                    International orders may take between 5–10 working days depending on your location.

                    Once your order has been dispatched, you will receive a tracking number via email.
                </p>
                <div class="grid md:grid-cols-2 gap-6 mt-6">
                    <div class="p-6 bg-blue-50 rounded-xl border border-blue-100">
                        <h3 class="font-bold text-blue-900 mb-2">UK Delivery</h3>
                        <ul class="space-y-2 text-blue-800 text-sm">
                            <li>• Standard Tracked (2-3 working days)</li>
                            <li>• Express Tracked (1-2 working days)</li>
                            <li>• Free shipping on orders over £25</li>
                        </ul>
                    </div>
                    <div class="p-6 bg-green-50 rounded-xl border border-green-100">
                        <h3 class="font-bold text-green-900 mb-2">International Delivery</h3>
                        <ul class="space-y-2 text-green-800 text-sm">
                            <li>• Europe: 5-7 working days</li>
                            <li>• Rest of World: 7-14 working days</li>
                            <li>• Tracking provided for all shipments</li>
                        </ul>
                    </div>
                </div>
            </div>

      

            <!-- Returns Policy -->
            <div class="space-y-4 pt-2">
                <h2 class="text-2xl font-bold text-[#0F172A] font-ubuntu">Returns & Refunds</h2>
                <p>
                    We want you to be 100% satisfied with your Tap Review Cards. If you're not happy with your purchase, we offer a <strong>30-day return policy</strong> for non-customized products.
                </p>
                <h3 class="text-md font-bold text-gray-900 mt-6">Eligibility for Returns:</h3>
                <ul class="list-disc pl-6 space-y-2">
                    <li>Items must be in the same condition that you received them.</li>
                    <li>Items must be in the original packaging.</li>
                    <li>Custom-printed or personalized cards are <strong>non-returnable</strong> unless they arrive damaged or defective.</li>
                </ul>
            </div>

            <!-- NON-RETURNABLE -->
            <div class="space-y-4 pt-2">
                <h2 class="text-2xl font-bold text-[#0F172A] font-ubuntu">Non-Returnable</h2>
                <p>
                    We cannot accept returns for:
                </p>
                <ul class="list-disc pl-6 space-y-2">
                    <li>Custom-configured NFC cards</li>
                    <li>Used or activated products</li>
                    <li>Digital services or subscriptions</li>
                </ul>
            </div>

            <!-- How to start a return -->
            <div class="space-y-4 pt-2">
                <h2 class="text-2xl font-bold text-[#0F172A] font-ubuntu">How to Initiate a Return</h2>
                <ol class="list-decimal pl-6 space-y-4">
                    <li>Email us at <a href="mailto:info@tapreviewcards.co.uk" class="text-blue-600 font-semibold underline">info@tapreviewcards.co.uk</a> with your order number and reason for return.</li>
                    <li>Once approved, we will provide you with the return shipping address.</li>
                    <li>Pack the item securely and ship it back to us (we recommend using a tracked service).</li>
                    <li>Once received and inspected, we will process your refund to your original payment method.</li>
                </ol>
            </div>

            <div class="mt-12 p-8 bg-gradient-to-r from-[#1800ad] to-[#0cc0df] text-white rounded-2xl text-center">
                <h3 class="text-2xl font-bold mb-4 font-ubuntu">Still have questions?</h3>
                <p class="mb-6">Our UK-based support team is here to help you.</p>
                <a href="{{ route('contact') }}" class="inline-block bg-white text-[#1800ad] px-8 py-3 rounded-full font-bold hover:bg-gray-100 transition">
                    Contact Support
                </a>
            </div>
        </div>
    </section>
</div>
@endsection
