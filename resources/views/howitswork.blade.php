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
    <!-- Software Platform Teaser Section -->
    <style>
        @keyframes fade-in-up {
            0% { opacity: 0; transform: translateY(40px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up { animation: fade-in-up 1s ease forwards; }
        @keyframes float-slow {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .animate-float-slow { animation: float-slow 4s ease-in-out infinite; }
        html {
            scroll-behavior: smooth;
        }
        @keyframes pulse-soft {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.8; }
        }
        .animate-pulse-soft { animation: pulse-soft 2s infinite; }
    </style>

    <div class="relative bg-gradient-to-br from-[#0d0060] via-[#1800ad] to-[#0cc0df] py-24 overflow-hidden">
        <!-- Background decorations -->
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-0 left-0 w-96 h-96 bg-white/5 rounded-full -translate-x-1/2 -translate-y-1/2 blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-[#0cc0df]/10 rounded-full translate-x-1/3 translate-y-1/3 blur-3xl"></div>
            <div class="absolute top-1/2 left-1/2 w-[300px] h-[300px] bg-white/5 rounded-full -translate-x-1/2 -translate-y-1/2 blur-2xl"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

                <!-- Left: Copy -->
                <div class="text-white space-y-8">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 border border-white/20 text-white/80 text-xs font-bold uppercase tracking-wider">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#0cc0df] opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-[#0cc0df]"></span>
                        </span>
                        Powered by Tap Review Cards Platform
                    </div>

                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-black font-ubuntu !leading-[1.15]">
                        More Than Cards —
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#0cc0df] to-white">A Complete Review Growth Platform</span>
                    </h2>

                    <p class="text-white/80 text-lg leading-relaxed font-mulish">
                        Behind every NFC card is a powerful software platform. Manage your team, track analytics, collect private customer insights, and grow your reputation — all from one intelligent dashboard.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 pt-2">
                        <a href="{{ route('howitswork') }}#platform"
                            class="inline-flex items-center justify-center gap-2 bg-white text-[#1800ad] px-6 py-4 rounded-full font-extrabold text-sm uppercase tracking-wider hover:bg-[#0cc0df] hover:text-white transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                            Explore the Platform
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                        <a href="{{ route('howitswork') }}#pricing"
                            class="inline-flex items-center justify-center gap-2 border-2 border-white/40 text-white px-6 py-4 rounded-full font-extrabold text-sm uppercase tracking-wider hover:border-white hover:bg-white/10 transition-all duration-300 transform hover:-translate-y-1">
                            View Pricing Plans
                        </a>
                    </div>

                    <!-- Trust Bar -->
                    <div class="flex flex-wrap items-center gap-x-8 gap-y-4 opacity-70">
                        <div class="flex items-center gap-2">
                            <i class="fab fa-google text-blue-500 text-lg"></i>
                            <span class="text-[12px] font-black uppercase tracking-tighter text-white">Partner</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <div class="flex text-amber-400 text-sm">
                                ★★★★★
                            </div>
                            <span class="text-[12px] font-bold text-white">5.0 Rating</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            <span class="text-[12px] font-black uppercase tracking-tighter text-white">UK Verified</span>
                        </div>
                    </div>
                </div>

                <!-- Right: Mini Dashboard Preview Cards -->
                <div class="hidden lg:grid grid-cols-2 gap-5 animate-fade-in-up">
                    <!-- Card 1 -->
                    <div class="bg-white/10 backdrop-blur border border-white/20 rounded-2xl p-6 animate-float-slow" style="animation-delay:0s">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 rounded-lg bg-emerald-400/20 flex items-center justify-center">
                                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                            </div>
                            <span class="text-white/70 text-[10px] uppercase font-bold tracking-widest">Review Growth</span>
                        </div>
                        <div class="text-3xl font-black text-white mb-1">+142%</div>
                        <div class="text-white/50 text-xs font-mulish">vs last month</div>
                        <div class="mt-4 flex items-end gap-1 h-10">
                            <div class="flex-1 bg-emerald-400/30 rounded-t-sm" style="height:40%"></div>
                            <div class="flex-1 bg-emerald-400/40 rounded-t-sm" style="height:60%"></div>
                            <div class="flex-1 bg-emerald-400/50 rounded-t-sm" style="height:50%"></div>
                            <div class="flex-1 bg-emerald-400/70 rounded-t-sm" style="height:80%"></div>
                            <div class="flex-1 bg-emerald-400 rounded-t-sm" style="height:100%"></div>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="bg-white/10 backdrop-blur border border-white/20 rounded-2xl p-6 animate-float-slow" style="animation-delay:0.8s">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 rounded-lg bg-amber-400/20 flex items-center justify-center">
                                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            </div>
                            <span class="text-white/70 text-[10px] uppercase font-bold tracking-widest">Avg. Rating</span>
                        </div>
                        <div class="text-3xl font-black text-white mb-1">4.9 ★</div>
                        <div class="text-white/50 text-xs font-mulish">Google score</div>
                        <div class="mt-4 flex gap-1">
                            @for($i = 0; $i < 5; $i++)
                            <span class="text-amber-400 text-xl">★</span>
                            @endfor
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="bg-white/10 backdrop-blur border border-white/20 rounded-2xl p-6 animate-float-slow" style="animation-delay:0.4s">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 rounded-lg bg-[#0cc0df]/20 flex items-center justify-center">
                                <svg class="w-4 h-4 text-[#0cc0df]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </div>
                            <span class="text-white/70 text-[10px] uppercase font-bold tracking-widest">Smart Feedback Loop</span>
                        </div>
                        <div class="text-3xl font-black text-white mb-1">98%</div>
                        <div class="text-white/50 text-xs font-mulish">Positive routed to Google</div>
                        <div class="mt-3 w-full bg-white/10 rounded-full h-2">
                            <div class="bg-[#0cc0df] h-2 rounded-full" style="width:98%"></div>
                        </div>
                    </div>

                    <!-- Card 4 -->
                    <div class="bg-white/10 backdrop-blur border border-white/20 rounded-2xl p-6 animate-float-slow" style="animation-delay:1.2s">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 rounded-lg bg-violet-400/20 flex items-center justify-center">
                                <svg class="w-4 h-4 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <span class="text-white/70 text-[10px] uppercase font-bold tracking-widest">Top Performer</span>
                        </div>
                        <div class="text-lg font-black text-white mb-1">James D.</div>
                        <div class="text-white/50 text-xs font-mulish">47 reviews this week</div>
                        <div class="mt-3 flex gap-1.5">
                            <div class="w-2 h-2 rounded-full bg-violet-400"></div>
                            <div class="w-2 h-2 rounded-full bg-violet-400/60"></div>
                            <div class="w-2 h-2 rounded-full bg-violet-400/30"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <!-- ─── PLATFORM INTRO ─────────────────────────────────────── -->
       <section id="platform" class="relative bg-white py-24 px-4 sm:px-6 lg:px-8 overflow-hidden">
            <!-- Soft gradient bg blobs -->
            <div class="absolute inset-0 pointer-events-none overflow-hidden">
                <div class="absolute -top-32 -left-32 w-[500px] h-[500px] bg-indigo-50 rounded-full blur-3xl opacity-60"></div>
                <div class="absolute -bottom-32 -right-32 w-[400px] h-[400px] bg-cyan-50 rounded-full blur-3xl opacity-60"></div>
            </div>

            <div class="relative z-10 max-w-6xl mx-auto">
                <!-- Section header -->
                <div class="text-center mb-16">
                    <h2 class="text-4xl md:text-5xl font-black font-ubuntu text-[#1800ad] !leading-[1.15] mb-6">
                        Your Complete Review<br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#1800ad] to-[#0cc0df]">Management Dashboard</span>
                    </h2>
                    <p class="text-lg text-gray-600 max-w-3xl mx-auto font-mulish leading-relaxed">
                        Behind every NFC card is a powerful software platform built specifically for UK businesses. Monitor every review, track your team, collect private customer insights, and respond automatically — all from one beautiful dashboard.
                    </p>
                </div>

                <!-- 3-col feature grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-20">
                    <!-- Feature 1 -->
                    <div class="group bg-gradient-to-br from-indigo-50 to-white rounded-3xl p-8 border border-indigo-100 hover:shadow-2xl hover:border-indigo-300 transition-all duration-500 hover:-translate-y-2">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[#1800ad] to-[#0d0060] flex items-center justify-center mb-6 group-hover:scale-110 group-hover:shadow-[0_8px_20px_rgba(24,0,173,0.3)] transition-all duration-300">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-[#1800ad] mb-3 font-ubuntu">Real-Time Analytics</h3>
                        <p class="text-gray-600 font-mulish leading-relaxed">Track review growth, average ratings, and conversion trends across all your locations — updated live, at a glance.</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="group bg-gradient-to-br from-cyan-50 to-white rounded-3xl p-8 border border-cyan-100 hover:shadow-2xl hover:border-cyan-300 transition-all duration-500 hover:-translate-y-2">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[#0cc0df] to-[#1800ad] flex items-center justify-center mb-6 group-hover:scale-110 group-hover:shadow-[0_8px_20px_rgba(12,192,223,0.3)] transition-all duration-300">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-[#1800ad] mb-3 font-ubuntu">Smart Feedback Loop</h3>
                        <p class="text-gray-600 font-mulish leading-relaxed">Unhappy customers are redirected privately to you instead of Google. Only your happiest customers leave public reviews.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="group bg-gradient-to-br from-violet-50 to-white rounded-3xl p-8 border border-violet-100 hover:shadow-2xl hover:border-violet-300 transition-all duration-500 hover:-translate-y-2">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[#7c3aed] to-[#1800ad] flex items-center justify-center mb-6 group-hover:scale-110 group-hover:shadow-[0_8px_20px_rgba(124,58,237,0.3)] transition-all duration-300">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-[#1800ad] mb-3 font-ubuntu">Team Leaderboard</h3>
                        <p class="text-gray-600 font-mulish leading-relaxed">See which team members are driving the most reviews. Motivate staff with friendly competition and trackable goals.</p>
                    </div>

                    <!-- Feature 4 -->
                    <div class="group bg-gradient-to-br from-emerald-50 to-white rounded-3xl p-8 border border-emerald-100 hover:shadow-2xl hover:border-emerald-300 transition-all duration-500 hover:-translate-y-2">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[#10b981] to-[#059669] flex items-center justify-center mb-6 group-hover:scale-110 group-hover:shadow-[0_8px_20px_rgba(16,185,129,0.3)] transition-all duration-300">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-[#1800ad] mb-3 font-ubuntu">AI Review Responder</h3>
                        <p class="text-gray-600 font-mulish leading-relaxed">Automatically generate professional, personalised responses to every Google review — saving you hours every week.</p>
                    </div>

                    <!-- Feature 5 -->
                    <div class="group bg-gradient-to-br from-amber-50 to-white rounded-3xl p-8 border border-amber-100 hover:shadow-2xl hover:border-amber-300 transition-all duration-500 hover:-translate-y-2">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[#f59e0b] to-[#d97706] flex items-center justify-center mb-6 group-hover:scale-110 group-hover:shadow-[0_8px_20px_rgba(245,158,11,0.3)] transition-all duration-300">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-[#1800ad] mb-3 font-ubuntu">Multi-Card Management</h3>
                        <p class="text-gray-600 font-mulish leading-relaxed">Manage all your NFC cards, QR codes, and staff assignments from one dashboard. Add, reassign, or deactivate cards instantly.</p>
                    </div>

                    <!-- Feature 6 -->
                    <div class="group bg-gradient-to-br from-rose-50 to-white rounded-3xl p-8 border border-rose-100 hover:shadow-2xl hover:border-rose-300 transition-all duration-500 hover:-translate-y-2">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[#f43f5e] to-[#e11d48] flex items-center justify-center mb-6 group-hover:scale-110 group-hover:shadow-[0_8px_20px_rgba(244,63,94,0.3)] transition-all duration-300">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-[#1800ad] mb-3 font-ubuntu">Instant Notifications</h3>
                        <p class="text-gray-600 font-mulish leading-relaxed">Get notified immediately when a new review arrives or when a customer submits private feedback — never miss a moment.</p>
                    </div>
                </div>

                <!-- Dashboard mockup screenshot area -->
                <div class="relative bg-white rounded-3xl shadow-[0_30px_80px_rgba(0,0,0,0.12)] border border-gray-100 overflow-hidden">
                    <!-- Browser bar -->
                    <div class="bg-[#1800ad] px-6 py-4 flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-[#FF5F56] rounded-full"></div>
                            <div class="w-3 h-3 bg-[#FFBD2E] rounded-full"></div>
                            <div class="w-3 h-3 bg-[#27C93F] rounded-full"></div>
                        </div>
                        <div class="hidden md:block bg-white/10 px-4 py-1.5 rounded-lg text-[11px] text-white/60 font-mono tracking-wider">
                            tapreviewcards.co.uk/dashboard
                        </div>
                        <div class="w-12"></div>
                    </div>

                    <!-- Dashboard content -->
                    <div class="bg-[#F8FAFC] p-4 md:p-8">
                        <!-- Topbar -->
                        <div class="flex items-center justify-between mb-8">
                            <img src="/images/logo.png" class="w-[130px] h-auto" alt="Logo" loading="lazy">
                            <div class="flex items-center gap-6 text-[#1800ad] font-ubuntu font-bold text-sm">
                                <span class="text-[#1800ad]">Dashboard</span>
                                <span class="hover:text-[#0cc0df] transition-colors cursor-pointer">Reviews</span>
                                <span class="hover:text-[#0cc0df] transition-colors cursor-pointer">Cards</span>
                                <span class="hover:text-[#0cc0df] transition-colors cursor-pointer">Team</span>
                                <div class="w-8 h-8 rounded-full bg-indigo-100 border-2 border-white shadow flex items-center justify-center text-[10px] text-indigo-700 font-bold uppercase">JD</div>
                            </div>
                        </div>

                        <!-- Stat cards row -->
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                            <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl p-5 text-white shadow-lg shadow-blue-200">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.175 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                    </div>
                                    <span class="text-[9px] font-bold uppercase opacity-80 tracking-wide">Total Reviews</span>
                                </div>
                                <div class="text-3xl font-black">2,140</div>
                                <div class="text-[10px] opacity-60 mt-1">All time</div>
                            </div>
                            <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl p-5 text-white shadow-lg shadow-green-200">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <span class="text-[9px] font-bold uppercase opacity-80 tracking-wide">Positive</span>
                                </div>
                                <div class="text-3xl font-black">1,980</div>
                                <div class="text-[10px] opacity-60 mt-1">This month</div>
                            </div>
                            <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl p-5 text-white shadow-lg shadow-indigo-200">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <span class="text-[9px] font-bold uppercase opacity-80 tracking-wide">Smart Feedback Loop</span>
                                </div>
                                <div class="text-3xl font-black">98%</div>
                                <div class="text-[10px] opacity-60 mt-1">Positive routed</div>
                            </div>
                            <div class="bg-gradient-to-br from-violet-600 to-violet-700 rounded-2xl p-5 text-white shadow-lg shadow-purple-200">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                    </div>
                                    <span class="text-[9px] font-bold uppercase opacity-80 tracking-wide">Active Cards</span>
                                </div>
                                <div class="text-3xl font-black">24</div>
                                <div class="text-[10px] opacity-60 mt-1">NFC & QR</div>
                            </div>
                        </div>

                        <!-- Charts row -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Growth chart -->
                            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                                <div class="flex items-center justify-between mb-5">
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></div>
                                        <span class="text-sm font-bold text-gray-800 font-ubuntu">Review Growth</span>
                                    </div>
                                    <span class="text-[10px] bg-indigo-50 text-indigo-600 px-2.5 py-1 rounded-full font-bold border border-indigo-100">+142% this week</span>
                                </div>
                                <div class="h-[160px] relative">
                                    <svg class="w-full h-full" viewBox="0 0 400 140" preserveAspectRatio="none">
                                        <defs>
                                            <linearGradient id="gGrowth" x1="0%" y1="0%" x2="0%" y2="100%">
                                                <stop offset="0%" style="stop-color:#1800ad;stop-opacity:0.15"/>
                                                <stop offset="100%" style="stop-color:#1800ad;stop-opacity:0"/>
                                            </linearGradient>
                                        </defs>
                                        <path d="M0,125 Q50,120 80,105 T160,90 T220,98 T280,65 T330,32 T380,18 L400,15" stroke="#1800ad" stroke-width="2.5" fill="none" stroke-linecap="round"/>
                                        <path d="M0,125 Q50,120 80,105 T160,90 T220,98 T280,65 T330,32 T380,18 L400,15 V140 H0 Z" fill="url(#gGrowth)"/>
                                        <circle cx="330" cy="32" r="4" fill="#1800ad"/>
                                    </svg>
                                </div>
                            </div>
                            <!-- Distribution donut -->
                            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex flex-col items-center">
                                <div class="w-full flex items-center gap-2 mb-5">
                                    <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                                    <span class="text-sm font-bold text-gray-800 font-ubuntu">Review Distribution</span>
                                </div>
                                <div class="relative w-32 h-32 mb-4">
                                    <svg class="w-full h-full -rotate-90" viewBox="0 0 36 36">
                                        <circle cx="18" cy="18" r="15" fill="none" stroke="#F1F5F9" stroke-width="3"/>
                                        <circle cx="18" cy="18" r="15" fill="none" stroke="#10B981" stroke-width="3" stroke-dasharray="88, 100" stroke-linecap="round"/>
                                        <circle cx="18" cy="18" r="15" fill="none" stroke="#F43F5E" stroke-width="3" stroke-dasharray="9, 100" stroke-dashoffset="-91" stroke-linecap="round"/>
                                    </svg>
                                    <div class="absolute inset-0 flex items-center justify-center flex-col">
                                        <span class="text-sm font-black text-gray-800">92%</span>
                                        <span class="text-[8px] text-gray-400 font-bold uppercase tracking-tight">Positive</span>
                                    </div>
                                </div>
                                <div class="flex gap-5 text-[11px] font-bold font-ubuntu">
                                    <div class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span>Positive (92%)</div>
                                    <div class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-rose-400 inline-block"></span>Negative (8%)</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <!-- ─── ANALYTICS SECTION ────────────────────────────────────── -->
        <section class="bg-gradient-to-b from-gray-50 to-white py-24 px-4 sm:px-6 lg:px-8">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-4xl md:text-5xl font-black font-ubuntu text-[#1800ad] !leading-[1.15] mb-6">
                        Powerful Analytics,<br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#1800ad] to-[#0cc0df]">Built for Growth</span>
                    </h2>
                    <p class="text-lg text-gray-600 max-w-3xl mx-auto font-mulish">
                        Every tap, every review, every customer interaction — tracked and visualised so you always know where you stand and what to do next.
                    </p>
                </div>

                <!-- Side-by-side analytics features -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                    <div class="space-y-8">
                        <div class="flex gap-5">
                            <div class="flex-shrink-0 w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center">
                                <svg class="w-6 h-6 text-[#1800ad]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 mb-1 font-ubuntu">Monthly Trends</h3>
                                <p class="text-gray-500 font-mulish text-sm leading-relaxed">See exactly which days and times you collect the most reviews and optimise your team's workflow accordingly.</p>
                            </div>
                        </div>
                        <div class="flex gap-5">
                            <div class="flex-shrink-0 w-12 h-12 rounded-2xl bg-cyan-50 flex items-center justify-center">
                                <svg class="w-6 h-6 text-[#0cc0df]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 mb-1 font-ubuntu">Per-Cards & Per-Staff Stats</h3>
                                <p class="text-gray-500 font-mulish text-sm leading-relaxed">Drill down into individual card performance. Know which staff member is your top review collector this week.</p>
                            </div>
                        </div>
                        <div class="flex gap-5">
                            <div class="flex-shrink-0 w-12 h-12 rounded-2xl bg-violet-50 flex items-center justify-center">
                                <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 mb-1 font-ubuntu">Sentiment Breakdown</h3>
                                <p class="text-gray-500 font-mulish text-sm leading-relaxed">Visualise the ratio of positive to private feedback. Watch your public reputation grow while capturing all concerns privately.</p>
                            </div>
                        </div>
                        <div class="flex gap-5">
                            <div class="flex-shrink-0 w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center">
                                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 mb-1 font-ubuntu">Instant Alerts</h3>
                                <p class="text-gray-500 font-mulish text-sm leading-relaxed">Receive real-time notifications for every new review or private complaint so you can act before it escalates.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Analytics visual -->
                    <div class="relative">
                        <div class="bg-white rounded-2xl shadow-xl space-y-6">
                            <img src="images/staff-dashboard.png" alt="Analytics" class="w-full h-auto rounded-2xl">
                        </div>
                    </div>
                </div>
            </div>
        </section>

    <section class="py-24 bg-gray-50 px-4 sm:px-6 lg:px-8 relative overflow-hidden">

        <div class="max-w-7xl mx-auto text-center relative z-10">
            <div class="mb-20">
                <h2 class="text-4xl md:text-5xl font-black text-[#1800ad] font-ubuntu mb-6">
                    Activate Your Growth in 3 Steps
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto font-mulish">
                    We've removed all the friction. From unboxing to your first 5-star review in under 5 minutes.
                </p>
            </div>

            <div class="relative">

                <div class="grid md:grid-cols-3 gap-12 relative">
                    <!-- Step 1 -->
                    <div class="group relative">
                        <div class="h-full bg-white rounded-3xl p-8 border border-gray-100 shadow-xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 flex flex-col items-center text-center">
                            <div class="rounded-2xl p-4 mb-8 overflow-hidden">
                                <img src="images/step1.png" alt="Create Your Card" class="w-[180px] h-[180px] object-contain group-hover:scale-110 transition-transform duration-500" loading="lazy">
                            </div>
                            <h3 class="text-2xl font-bold text-[#1800ad] font-ubuntu mb-4">Create Your Card</h3>
                            <p class="text-gray-600 font-mulish leading-relaxed">
                                Input your business details in the dashboard. Our system instantly creates your unique review portal and NFC configuration.
                            </p>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="group relative">
                        <div class="h-full bg-white rounded-3xl p-8 border border-gray-100 shadow-xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 flex flex-col items-center text-center">
                            <div class="rounded-2xl p-4 mb-8 overflow-hidden">
                                <img src="images/step2.png" alt="Share Your Card" class="w-[180px] h-[180px] object-contain group-hover:scale-110 transition-transform duration-500" loading="lazy">
                            </div>
                            <h3 class="text-2xl font-bold text-[#1800ad] font-ubuntu mb-4">Tap to Collect</h3>
                            <p class="text-gray-600 font-mulish leading-relaxed">
                                Place your card at checkout. Customers simply tap their phone — no apps, no typing, no searching for your business.
                            </p>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="group relative">
                        <div class="h-full bg-white rounded-3xl p-8 border border-gray-100 shadow-xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 flex flex-col items-center text-center" >
                            <div class="rounded-2xl p-4 mb-8 overflow-hidden">
                                <img src="images/step3.png" alt="Start Collecting Reviews" class="w-[180px] h-[180px] object-contain group-hover:scale-110 transition-transform duration-500" loading="lazy">
                            </div>
                            <h3 class="text-2xl font-bold text-[#1800ad] font-ubuntu mb-4">Smart Growth</h3>
                            <p class="text-gray-600 font-mulish leading-relaxed">
                                Watch your ratings climb. Happy customers go to Google, while private feedback helps you improve out of the public eye.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

        
    <!-- ─── MEMBERSHIP PLANS ──────────────────────────────────────── -->
    <section id="pricing" class="bg-white py-24 px-4 sm:px-6 lg:px-8">
        <style>
            @keyframes fade-in-up {
                0%   { opacity: 0; transform: translateY(40px); }
                100% { opacity: 1; transform: translateY(0); }
            }
            .animate-fade-in-up { animation: fade-in-up 0.9s ease forwards; }
        </style>

        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-50 text-indigo-600 text-xs font-bold uppercase tracking-wider mb-6">
                    Simple, Transparent Pricing
                </div>
                <h2 class="text-4xl md:text-5xl font-black font-ubuntu text-[#1800ad] !leading-[1.15] mb-6">
                    Membership Plans
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto font-mulish">
                    Simple, transparent pricing that grows with you. Choose the plan that's right for your business.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                @foreach($cards->take(2) as $index => $card)
                    @php
                        $planColors = [
                            0 => [
                                'badge_bg'      => 'bg-indigo-100',
                                'badge_text'    => 'text-indigo-600',
                                'button_bg'     => 'bg-[#1800ad]',
                                'button_hover'  => 'hover:bg-[#0cc0df]',
                                'border'        => 'border-gray-200',
                                'card_bg'       => '',
                                'is_popular'    => false,
                            ],
                            1 => [
                                'badge_bg'      => 'bg-indigo-200',
                                'badge_text'    => 'text-indigo-700',
                                'button_bg'     => 'bg-[#1800ad]',
                                'button_hover'  => 'hover:bg-[#0cc0df]',
                                'border'        => 'border-2 border-[#1800ad]',
                                'card_bg'       => 'bg-indigo-50',
                                'is_popular'    => true,
                            ],
                        ];
                        $colors = $planColors[$index] ?? $planColors[0];
                        if (isset($card->is_popular) && $card->is_popular) {
                            $colors['is_popular'] = true;
                        }
                        $animationDelay = $index * 0.15;
                    @endphp

                    <div class="relative rounded-2xl {{ $colors['border'] }} p-8 shadow-md transition-all duration-500 ease-in-out transform hover:scale-105 {{ $colors['card_bg'] }} opacity-0 animate-fade-in-up"
                        style="animation-delay: {{ $animationDelay }}s">

                        @if ($colors['is_popular'])
                            <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                                <span class="bg-[#1800ad] text-white text-xs px-4 py-1 rounded-full uppercase tracking-wider shadow">
                                    {{ $card->popular_badge ?? 'Most Popular' }}
                                </span>
                            </div>
                        @endif

                        <div class="mb-6">
                            <span class="inline-block text-sm font-medium capitalize {{ $colors['badge_text'] }} {{ $colors['badge_bg'] }} px-3 py-1 rounded-full">
                                {{ $card->name }}
                            </span>
                        </div>

                        <h3 class="text-4xl font-bold text-gray-900 mb-2 font-ubuntu">
                            &pound;{{ number_format($card->price, 2) }}<span class="text-base font-medium text-gray-500">{{ $card->price_suffix ?? '/month' }}</span>
                        </h3>

                        <div class="text-gray-600 mb-6 text-sm font-mulish !leading-[35px] mt-4">{!! $card->description !!}</div>

                        <a href="/register?plan={{ $card->id }}"
                            class="block text-center w-full {{ $colors['button_bg'] }} text-white py-3 rounded-xl font-semibold transition-all duration-500 {{ $colors['button_hover'] }}">
                            Order Now
                        </a>
                    </div>
                @endforeach

                <!-- Enterprise Plan -->
                <div class="relative rounded-2xl border border-gray-200 p-8 shadow-md transition-all duration-500 ease-in-out transform hover:scale-105 opacity-0 animate-fade-in-up"
                    style="animation-delay: 0.45s">
                    <div class="mb-6">
                        <span class="inline-block text-sm font-medium capitalize text-indigo-600 bg-indigo-100 px-3 py-1 rounded-full">
                            Enterprise
                        </span>
                    </div>
                    <h3 class="text-4xl font-bold text-gray-900 mb-2 font-ubuntu">
                        Custom
                    </h3>
                    <div class="text-gray-600 mb-6 text-sm font-mulish !leading-[35px] mt-4">
                        <ul>
                            <li class="flex items-center">🔷 Unlimited Cards &amp; Businesses</li>
                            <li class="flex items-center">🔷 Advanced Dashboard with Insights</li>
                            <li class="flex items-center">🔷 Team Leaderboard &amp; Staff Tracking</li>
                            <li class="flex items-center">🔷 Dedicated Account Manager</li>
                            <li class="flex items-center">🔷 AI Response Generator</li>
                        </ul>
                    </div>
                    <a href="{{ route('contact', ['inquiry' => 'enterprise']) }}"
                        class="block text-center w-full bg-[#1800ad] text-white py-3 rounded-xl font-semibold transition-all duration-500 hover:bg-[#0cc0df]">
                        Contact Us
                    </a>
                </div>
            </div>

            <!-- Bottom CTA under pricing -->
            <div class="mt-16 text-center">
                <p class="text-gray-500 font-mulish mb-6">Every plan includes your NFC Dashboard, Smart Feedback Loop, and UK Support.</p>
                <a href="{{ route('shop.index') }}"
                    class="inline-flex items-center gap-2 bg-[#1800ad] text-white px-10 py-4 rounded-full font-extrabold text-sm uppercase tracking-widest hover:bg-[#0cc0df] transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                    Shop NFC Review Cards
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
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
                Discover how businesses across the UK are transforming their online reputation with Tap Review Cards
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
                    "The Smart Feedback Loop is brilliant! It’s helped us resolve & improve customer issues privately and improve our service before they even left our store. Our average rating has stayed consistently high."
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
            <div class="flex flex-col sm:flex-row justify-center items-center gap-6 font-mulish">
                <a href="register"
                    class="w-full sm:w-auto bg-[#1800ad] text-white hover:bg-[#0cc0df] px-10 py-4 rounded-full font-extrabold uppercase tracking-widest shadow-[0_10px_30px_rgba(24,0,173,0.3)] hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                    Get Started Now
                </a>
                <a href="contact"
                    class="w-full sm:w-auto border-2 border-[#1800ad] text-[#1800ad] px-10 py-4 rounded-full font-extrabold uppercase tracking-widest hover:bg-[#1800ad] hover:text-white transition-all duration-300 transform hover:-translate-y-1">
                    Contact Us
                </a>
            </div>
        </div>
    </section>

    <!-- Floating Success Notifications -->
    <div id="success-notification" class="fixed bottom-8 left-8 z-[100] transform translate-y-24 opacity-0 transition-all duration-700 pointer-events-none">
        <div class="bg-white rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.15)] border border-indigo-50 p-4 flex items-center gap-4 max-w-sm">
            <div id="notification-icon" class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#1800ad] to-[#0cc0df] flex items-center justify-center text-white shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p id="notification-text" class="text-sm font-black text-[#142D63] leading-tight mb-0.5 text-left"></p>
                <p id="notification-subtext" class="text-[10px] uppercase font-bold tracking-widest text-[#1800ad] opacity-60 text-left"></p>
            </div>
        </div>
    </div>

    <script>
        const notifications = [
            { text: "Sarah Mitchell just filtered a 1-star review!", sub: "Feedback Loop Active" },
            { text: "Spice Garden added 15 Google reviews today!", sub: "Review Cards Active" },
            { text: "James D. reached 50 reviews this week!", sub: "Staff Leaderboard" },
            { text: "New NFC Card activated in Manchester!", sub: "Order Dispatched" },
            { text: "AI Assistant responded to 5 new reviews!", sub: "Automation Active" }
        ];

        let currentIndex = 0;
        const notificationBox = document.getElementById('success-notification');
        const textEl = document.getElementById('notification-text');
        const subEl = document.getElementById('notification-subtext');

        function showNotification() {
            const data = notifications[currentIndex];
            textEl.innerText = data.text;
            subEl.innerText = data.sub;

            // Slide In
            notificationBox.classList.remove('translate-y-24', 'opacity-0');
            notificationBox.classList.add('translate-y-0', 'opacity-100');

            // Slide Out after 5s
            setTimeout(() => {
                notificationBox.classList.remove('translate-y-0', 'opacity-100');
                notificationBox.classList.add('translate-y-24', 'opacity-0');
                currentIndex = (currentIndex + 1) % notifications.length;
            }, 6000);
        }

        // Start cycle after 4 seconds
        setTimeout(() => {
            showNotification();
            setInterval(showNotification, 20000); // Every 20 seconds
        }, 4000);
    </script>
@endsection