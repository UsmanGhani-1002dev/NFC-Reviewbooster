@extends('layouts.guest')

@section('title', $seo['title'])
@section('meta_description', $seo['description'])
@section('meta_keywords', $seo['keywords'])
@section('og_image', $seo['image'] ?? asset('images/logo.png'))
@section('og_type', 'article')

@section('schema')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BlogPosting",
    "headline": "{{ $post->title }}",
    "datePublished": "{{ $post->created_at->toIso8601String() }}",
    "dateModified": "{{ $post->updated_at->toIso8601String() }}",
    "author": {
        "@type": "Organization",
        "name": "Tap Review Cards"
    },
    "publisher": {
        "@type": "Organization",
        "name": "Tap Review Cards",
        "logo": {
            "@type": "ImageObject",
            "url": "{{ asset('images/logo.png') }}"
        }
    },
    "description": "{{ $seo['description'] }}"
    @if($post->featured_image)
    ,"image": "{{ asset('storage/' . $post->featured_image) }}"
    @endif
}
</script>
@endsection

@section('content')

<section class="relative -mt-[100px] pt-[100px] text-[#1800ad] overflow-hidden" style="background-image: url('https://d1yei2z3i6k35z.cloudfront.net/161/609bb9ff8ffc9_Groupedemasques1.jpg'); background-size: cover; background-repeat: no-repeat; background-position: top center;">
    <div class="text-center py-20 px-6 md:px-10">
        <!-- Breadcrumbs -->
        <nav class="flex justify-center mb-6 text-sm font-bold uppercase tracking-widest" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="text-[#142D63] hover:text-[#1800ad] transition-colors">Home</a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-[#142D63] mx-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <a href="{{ route('blog.index') }}" class="text-[#142D63] hover:text-[#1800ad] transition-colors">Blog</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-[#142D63] mx-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <span class="text-[#1800ad] truncate max-w-[200px] md:max-w-xs">{{ $post->title }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <h1 class="text-3xl sm:text-5xl font-bold mb-6 leading-tight font-ubuntu max-w-4xl mx-auto">{{ $post->title }}</h1>
        
        <div class="flex items-center justify-center gap-4 flex-wrap">
            <div class="flex items-center gap-2">
                <span class="text-sm font-bold text-[#142D63]">Tap Review Cards</span>
            </div>
            <span class="w-1 h-1 bg-[#142D63]/40 rounded-full hidden sm:block"></span>
            <span class="text-sm font-bold text-[#142D63]">{{ $post->created_at->format('F d, Y') }}</span>
            <span class="w-1 h-1 bg-[#142D63]/40 rounded-full hidden sm:block"></span>
            <span class="text-sm font-bold text-[#0cc0df]">{{ $readingTime }} min read</span>
        </div>
    </div>
</section>

<div class="bg-gray-50 flex flex-col pt-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <article class="bg-white rounded-[2.5rem] shadow-2xl overflow-hidden border border-gray-100">
            <!-- Featured Image -->
            @if($post->featured_image)
                <div class="aspect-video w-full overflow-hidden flex justify-center">
                    <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-full object-contain">
                </div>
            @endif

            <div class="p-8 md:p-16">

                <!-- Meta Info -->
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-[#1800ad] font-black text-xs">
                        TR
                    </div>
                    <div>
                        <p class="text-sm font-black text-gray-900 uppercase tracking-widest">By Tap Review Cards Team</p>
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">{{ $post->created_at->format('F d, Y') }}</p>
                    </div>
                </div>

                <!-- Content Area -->
                <div class="prose prose-xl prose-indigo max-w-none font-mulish text-gray-700 blog-content-area
                    prose-headings:text-[#1800ad] prose-headings:font-ubuntu prose-headings:font-black
                    prose-a:text-[#0cc0df] prose-a:font-black prose-a:no-underline hover:prose-a:underline
                    prose-strong:text-gray-900 prose-strong:font-black
                    prose-img:rounded-3xl prose-img:shadow-2xl">
                    {!! $post->content !!}
                </div>

                <!-- Tags / Keywords -->
                @if($post->seo_keywords)
                    <div class="mt-12 pt-8 border-t border-gray-100">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-xs font-black text-gray-400 uppercase tracking-widest mr-2">Tags:</span>
                            @foreach(explode(',', $post->seo_keywords) as $keyword)
                                <span class="bg-gray-50 text-gray-600 text-xs font-bold px-4 py-1.5 rounded-full border border-gray-100 hover:bg-blue-50 hover:text-[#1800ad] hover:border-blue-100 transition-all cursor-default">{{ trim($keyword) }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Footer / Share -->
                <div class="mt-10 pt-8 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="flex items-center gap-4">
                        <span class="text-xs font-black text-gray-400 uppercase tracking-widest">Share:</span>
                        <div class="flex gap-2">
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}" target="_blank" rel="noopener" class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center text-gray-400 hover:bg-black hover:text-white transition-all">
                                <i class="fa-brands fa-x-twitter"></i>
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" rel="noopener" class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center text-gray-400 hover:bg-[#1877F2] hover:text-white transition-all">
                                <i class="fa-brands fa-facebook-f"></i>
                            </a>
                            <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(url()->current()) }}&title={{ urlencode($post->title) }}" target="_blank" rel="noopener" class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center text-gray-400 hover:bg-[#0A66C2] hover:text-white transition-all">
                                <i class="fa-brands fa-linkedin-in"></i>
                            </a>
                            <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . url()->current()) }}" target="_blank" rel="noopener" class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center text-gray-400 hover:bg-[#25D366] hover:text-white transition-all">
                                <i class="fa-brands fa-whatsapp"></i>
                            </a>
                        </div>
                    </div>

                    <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 text-[#0cc0df] font-black text-sm uppercase tracking-widest group">
                        <svg class="w-4 h-4 transform group-hover:-translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M7 16l-4-4m0 0l4-4m-4 4h18"/></svg>
                        Back to Articles
                    </a>
                </div>
            </div>
        </article>

        <!-- Related Posts -->
        @if($relatedPosts->count() > 0)
        <div class="mt-20">
            <div class="flex items-center gap-4 mb-10">
                <h2 class="text-2xl font-black text-gray-900 font-ubuntu whitespace-nowrap">You might also like</h2>
                <div class="flex-1 h-px bg-gray-200"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($relatedPosts as $related)
                    <a href="{{ route('blog.show', $related->slug) }}" class="group block bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-400 overflow-hidden border border-gray-100 transform hover:-translate-y-1">
                        <div class="relative aspect-[16/10] overflow-hidden">
                            @if($related->featured_image)
                                <img src="{{ asset('storage/' . $related->featured_image) }}" alt="{{ $related->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-[#1800ad] to-[#0cc0df] flex items-center justify-center p-6">
                                    <span class="text-white font-black text-base font-ubuntu text-center">{{ $related->title }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="p-5">
                            <span class="text-[11px] text-gray-400 font-bold uppercase tracking-wider">{{ $related->created_at->format('M d, Y') }}</span>
                            <h3 class="text-base font-black text-gray-900 mt-2 group-hover:text-[#1800ad] transition-colors leading-snug font-ubuntu line-clamp-2">{{ $related->title }}</h3>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- CTA Banner -->
        <div class="mt-20 bg-gradient-to-br from-[#1800ad] to-[#0a0060] rounded-3xl p-8 md:p-14 text-center relative overflow-hidden shadow-2xl">
            <div class="absolute inset-0 opacity-5">
                <div class="absolute -bottom-10 -left-10 w-64 h-64 bg-cyan-400 rounded-full filter blur-3xl animate-pulse"></div>
                <div class="absolute -top-10 -right-10 w-48 h-48 bg-indigo-400 rounded-full filter blur-3xl animate-pulse delay-1000"></div>
            </div>
            <div class="relative z-10">
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-[#0cc0df] mb-3">Get Started Today</p>
                <h2 class="text-2xl md:text-3xl font-black text-white mb-5 font-ubuntu leading-tight">Ready to boost your Google ratings?</h2>
                <p class="text-blue-200 text-base mb-8 max-w-xl mx-auto font-mulish">Join thousands of UK businesses collecting verified Google reviews in seconds with our smart NFC technology.</p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="{{ route('shop.index') }}" class="bg-white text-[#1800ad] px-8 py-3.5 rounded-full font-black uppercase tracking-widest hover:bg-[#0cc0df] hover:text-white transition-all transform hover:-translate-y-1 shadow-xl text-sm">Shop Cards</a>
                    <a href="{{ route('contact') }}" class="border-2 border-white/30 text-white px-8 py-3.5 rounded-full font-black uppercase tracking-widest hover:bg-white hover:text-[#1800ad] transition-all transform hover:-translate-y-1 text-sm">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .blog-content-area p {
        margin-bottom: 2rem;
        line-height: 1.8;
    }
    .blog-content-area h2, .blog-content-area h3 {
        margin-top: 3.5rem;
        margin-bottom: 1.5rem;
    }
    .blog-content-area ul, .blog-content-area ol {
        margin-bottom: 2rem;
        padding-left: 1.5rem;
    }
    .blog-content-area li {
        margin-bottom: 0.75rem;
    }
</style>
@endsection
