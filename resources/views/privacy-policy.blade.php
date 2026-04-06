@extends('layouts.guest')

@section('title', 'Privacy Policy — Tap Review Cards | How We Protect Your Data')
@section('meta_description', 'Read the Tap Review Cards privacy policy. Learn how we collect, use, and protect your personal data in compliance with UK GDPR and the Data Protection Act 2018.')

@section('content')

{{-- Hero --}}
<section class="relative -mt-[100px] pt-[100px] bg-cover bg-center bg-no-repeat text-[#1800ad] font-mulish" style="background-image: url('https://d1yei2z3i6k35z.cloudfront.net/161/609e5084b32cc_Groupe2589.jpg'); background-size: cover; background-repeat: no-repeat; background-position: top center;">
    <div class="relative max-w-3xl mx-auto text-center py-20 px-6 md:px-10">
        <h1 class="text-4xl sm:text-5xl font-bold mb-4 leading-tight font-ubuntu ">
            Privacy Policy
        </h1>
        <p class="text-lg sm:text-md mb-8 leading-relaxed text-[#142D63]">
            Last updated: <time datetime="{{ date('Y-m-d') }}">{{ date('d F Y') }}</time>
        </p>
    </div>
</section>

<div class="max-w-6xl mx-auto px-6 py-16 text-slate-700">

    {{-- Intro --}}
    <p class="text-slate-600 leading-relaxed mb-14 text-[15px]">
        Welcome to <strong class="text-slate-800">Tap Review Cards</strong>. We are committed to protecting your personal data and your right to privacy. This policy explains how we collect, use, store, and protect your information when you visit <a href="https://tapreviewcards.co.uk" class="text-emerald-600 hover:underline">tapreviewcards.co.uk</a> or place an order with us. It complies with the <strong class="text-slate-800">UK General Data Protection Regulation (UK GDPR)</strong> and the <strong class="text-slate-800">Data Protection Act 2018</strong>.
    </p>
 
    {{-- 1 --}}
    <div class="mb-10">
        <h2 class="text-xl font-bold text-slate-900 mb-3">Who We Are</h2>
        <p class="text-slate-600 leading-relaxed text-[15px]">
                Tap Review Cards is a UK-based business selling NFC and QR tap-to-review cards to help businesses collect online customer reviews. We are the <strong class="text-slate-800">data controller</strong> for all personal data collected through this website.
            </p>
            <p class="text-slate-600 leading-relaxed text-[15px] mt-3">
                <strong class="text-slate-800">Website:</strong> <a href="https://tapreviewcards.co.uk" class="text-emerald-600 hover:underline">tapreviewcards.co.uk</a><br>
                <strong class="text-slate-800">Email:</strong> <a href="mailto:info@tapreviewcards.co.uk" class="text-emerald-600 hover:underline">info@tapreviewcards.co.uk</a><br>
                <strong class="text-slate-800">Country:</strong> United Kingdom
            </p>
    </div>

    {{-- 2 --}}
    <div class="mb-10">
        <h2 class="text-xl font-bold text-slate-900 mb-3">What Data We Collect</h2>
        <p class="leading-relaxed mb-3">When you use our website or place an order, we may collect the following:</p>
        <ul class="list-disc list-inside space-y-1.5 text-slate-600">
            <li>Your name, email address, and phone number</li>
            <li>Your delivery and billing address</li>
            <li>Your order details, including any review page URLs you provide</li>
            <li>Payment details (processed securely — we never store card numbers)</li>
            <li>Technical data such as your IP address, browser type, and pages visited</li>
            <li>Marketing preferences if you opt in to our newsletter</li>
        </ul>
    </div>
 
    {{-- 3 --}}
    <div class="mb-10">
        <h2 class="text-xl font-bold text-slate-900 mb-3">How We Use Your Data</h2>
        <p class="leading-relaxed mb-3">We use your information to:</p>
        <ul class="list-disc list-inside space-y-1.5 text-slate-600">
            <li>Process and fulfil your order, including programming your review card</li>
            <li>Send you order confirmations and delivery updates</li>
            <li>Respond to your enquiries and provide customer support</li>
            <li>Send marketing emails, but only if you've opted in</li>
            <li>Improve our website and understand how it's being used</li>
            <li>Comply with our legal and tax obligations</li>
        </ul>
        <p class="leading-relaxed mt-3">
            We process your data based on contract performance, legitimate interest, your consent, and legal obligation — as required under <strong>UK GDPR</strong>.
        </p>
    </div>
 
    {{-- 4 --}}
    <div class="mb-10">
        <h2 class="text-xl font-bold text-slate-900 mb-3">Who We Share Your Data With</h2>
        <p class="leading-relaxed mb-3">We do not sell your data. We only share it with trusted third parties where necessary, such as:</p>
        <ul class="list-disc list-inside space-y-1.5 text-slate-600">
            <li>Payment processors (e.g. Stripe or PayPal) to handle transactions securely</li>
            <li>Delivery couriers (e.g. Royal Mail) to ship your order</li>
            <li>Email platforms to send you transactional and marketing emails</li>
            <li>Analytics tools (e.g. Google Analytics) to understand site usage</li>
        </ul>
        <p class="leading-relaxed mt-3">All third parties are required to keep your data secure and use it only for the stated purpose.</p>
    </div>
 
    {{-- 5 --}}
    <div class="mb-10">
        <h2 class="text-xl font-bold text-slate-900 mb-3">Cookies</h2>
        <p class="leading-relaxed">
            We use cookies to make our website work properly and to understand how visitors use it. Essential cookies are always active. Analytics and marketing cookies are only used with your consent, which you can manage via our cookie banner or your browser settings.
        </p>
    </div>
 
    {{-- 6 --}}
    <div class="mb-10">
        <h2 class="text-xl font-bold text-slate-900 mb-3">How Long We Keep Your Data</h2>
        <p class="leading-relaxed">
            We keep order data for 7 years to meet our legal and tax obligations. Account data is kept while your account is active and for 2 years afterwards. Marketing data is kept until you unsubscribe. Enquiry data is kept for 2 years.
        </p>
    </div>
 
    {{-- 7 --}}
    <div class="mb-10">
        <h2 class="text-xl font-bold text-slate-900 mb-3">Your Rights</h2>
        <p class="leading-relaxed mb-3">Under UK GDPR, you have the right to:</p>
        <ul class="list-disc list-inside space-y-1.5 text-slate-600">
            <li>Access the personal data we hold about you</li>
            <li>Correct any inaccurate or incomplete information</li>
            <li>Request deletion of your data ("right to be forgotten")</li>
            <li>Object to or restrict how we process your data</li>
            <li>Receive your data in a portable format</li>
            <li>Withdraw marketing consent at any time</li>
        </ul>
        <p class="leading-relaxed mt-3">
            To exercise any of these rights, email us at <a href="mailto:info@tapreviewcards.co.uk" class="text-emerald-600 hover:underline">info@tapreviewcards.co.uk</a>. We'll respond within 30 days.
        </p>
        <p class="leading-relaxed mt-3">
            You also have the right to complain to the <a href="https://ico.org.uk" target="_blank" rel="noopener noreferrer" class="text-emerald-600 hover:underline">Information Commissioner's Office (ICO)</a> if you're unhappy with how we've handled your data.
        </p>
    </div>
 
    {{-- 8 --}}
    <div class="mb-10">
        <h2 class="text-xl font-bold text-slate-900 mb-3">Data Security</h2>
        <p class="leading-relaxed">
            We take appropriate steps to protect your data, including SSL encryption across our website, secure servers, and limiting access to personal data to staff who need it. Payments are handled by PCI-DSS compliant providers — we never see or store your full card details.
        </p>
    </div>
 
    {{-- 9 --}}
    <div class="mb-10">
        <h2 class="text-xl font-bold text-slate-900 mb-3">Changes to This Policy</h2>
        <p class="leading-relaxed">
            We may update this policy from time to time. When we do, we'll update the date at the top of this page. For significant changes, we'll notify you by email if we hold your contact details.
        </p>
    </div>
 
     {{-- 10 --}}
        <div>
            <h2 class="text-xl font-bold text-slate-900 mb-4">Contact Us</h2>
            <p class="text-slate-600 leading-relaxed text-[15px] mb-4">
                For any privacy-related questions, requests, or complaints, please get in touch:
            </p>
            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 text-[15px] text-slate-600 space-y-2">
                <p><strong class="text-slate-800">Website:</strong> <a href="https://tapreviewcards.co.uk" class="text-emerald-600 hover:underline">tapreviewcards.co.uk</a></p>
                <p><strong class="text-slate-800">Email:</strong> <a href="mailto:info@tapreviewcards.co.uk" class="text-emerald-600 hover:underline">info@tapreviewcards.co.uk</a></p>
                <p><strong class="text-slate-800">Country:</strong> United Kingdom</p>
            </div>
            <p class="text-slate-500 text-sm leading-relaxed mt-5">
                You also have the right to lodge a complaint with the <a href="https://ico.org.uk" target="_blank" rel="noopener noreferrer" class="text-emerald-600 hover:underline">Information Commissioner's Office (ICO)</a>, the UK's independent data protection authority.
            </p>
        </div>
 
</div>
@endsection