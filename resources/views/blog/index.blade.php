@extends('layouts.guest')

@section('title', 'Expert Insights & Tips — Tap Review Cards Blog')
@section('meta_description', 'Discover the latest strategies for collecting Google reviews, boosting your online reputation, and growing your UK business with expert tips from Tap Review Cards.')
@section('meta_keywords', 'Google review tips, business growth UK, online reputation management, NFC review cards blog, collect more customer reviews')

@section('content')
<div class="bg-white">
    <!-- Hero Header -->
    <section class="relative -mt-[100px] pt-[100px] text-[#1800ad] overflow-hidden" style="background-image: url('https://d1yei2z3i6k35z.cloudfront.net/161/609bb9ff8ffc9_Groupedemasques1.jpg'); background-size: cover; background-repeat: no-repeat; background-position: top center;">
        <div class="text-center py-20 px-6 md:px-10">
            <h1 class="text-4xl sm:text-5xl font-bold mb-4 leading-tight font-ubuntu">Insights & Resources</h1>
            <p class="text-base md:text-lg max-w-3xl mx-auto leading-relaxed font-mulish text-[#142D63]">
                Expert insights, product updates, and proven strategies to help you dominate your local market with Google reviews.
            </p>
        </div>
    </section>

    <!-- Blog Grid -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            @forelse($posts as $post)
                <article class="group flex flex-col bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden border border-gray-100 transform hover:-translate-y-2">
                    <!-- Featured Image -->
                    <a href="{{ route('blog.show', $post->slug) }}" class="relative aspect-[10/9] flex justify-center overflow-hidden block">
                        @if($post->featured_image)
                            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center p-8 text-center group-hover:scale-110 transition-transform duration-700">
                                <span class="text-white font-black text-xl leading-snug">{{ $post->title }}</span>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-black/5 group-hover:bg-black/0 transition-colors"></div>
                        <div class="absolute top-6 left-6">
                            <span class="bg-[#0cc0df] text-white text-[10px] font-black uppercase tracking-widest px-4 py-2 rounded-full shadow-lg">Latest Post</span>
                        </div>
                    </a>

                    <!-- Content -->
                    <div class="p-8 flex flex-col flex-1">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="text-xs text-gray-400 font-bold uppercase tracking-wider">{{ $post->created_at->format('M d, Y') }}</span>
                            <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                            <span class="text-xs text-gray-400 font-bold uppercase tracking-wider">{{ max(1, ceil(str_word_count(strip_tags($post->content)) / 200)) }} min read</span>
                        </div>
                        
                        <h2 class="text-2xl font-black text-gray-900 mb-4 group-hover:text-[#1800ad] transition-colors leading-tight font-ubuntu">
                            <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                        </h2>
                        
                        <p class="text-gray-600 mb-8 line-clamp-3 font-mulish leading-relaxed">
                            {{ Str::limit(strip_tags($post->content), 120) }}
                        </p>

                        <div class="mt-auto pt-5 border-t border-gray-100 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 bg-blue-50 rounded-full flex items-center justify-center text-[#1800ad] font-black text-[10px]">TR</div>
                                <span class="text-[11px] text-gray-400 font-bold">Tap Review Cards</span>
                            </div>
                            <a href="{{ route('blog.show', $post->slug) }}" class="inline-flex items-center gap-1.5 text-[#1800ad] font-black text-xs uppercase tracking-widest group/link">
                                Read Article
                                <svg class="w-3.5 h-3.5 transform group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-20 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
                    <i data-lucide="newspaper" class="w-20 h-20 text-gray-300 mx-auto mb-6"></i>
                    <h3 class="text-2xl font-black text-gray-400 font-ubuntu">Stay Tuned!</h3>
                    <p class="text-gray-400 mt-2 font-mulish">We are preparing some amazing insights for you. Check back soon!</p>
                </div>
            @endforelse
        </div>

        <div class="mt-16">
            {{ $posts->links() }}
        </div>

        <!-- CTA Section -->
        <div class="bg-gradient-to-br from-[#1800ad] to-[#0a0060] mt-20 py-16 relative max-w-6xl mx-auto rounded-3xl px-4 md:px-4 overflow-hidden shadow-2xl">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-[#0cc0df] mb-4">Get Started Today</p>
                <h3 class="text-3xl md:text-4xl font-black text-white mb-5 font-ubuntu leading-tight">Ready to collect more Google reviews?</h3>
                <p class="text-lg text-blue-200 mb-10 font-mulish max-w-2xl mx-auto">Join thousands of UK businesses using Tap Review Cards to boost their online reputation and attract new customers.</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('shop.index') }}" class="bg-white text-[#1800ad] px-10 py-4 rounded-full font-black uppercase tracking-widest hover:bg-[#0cc0df] hover:text-white transition-all transform hover:-translate-y-1 shadow-xl text-sm">Shop Review Cards</a>
                <a href="{{ route('contact') }}" class="border-2 border-white/30 text-white px-10 py-4 rounded-full font-black uppercase tracking-widest hover:bg-white hover:text-[#1800ad] transition-all transform hover:-translate-y-1 text-sm">Contact Us</a>
            </div>
        </div>
    </div>
    </div>

</div>
@endsection
