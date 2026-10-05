<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {
        $posts = BlogPost::where('is_published', true)->latest()->paginate(9);
        

        return view('blog.index', compact('posts'));
    }

    public function show($slug)
    {
        $post = BlogPost::where('slug', $slug)->where('is_published', true)->firstOrFail();

        // Calculate reading time
        $wordCount = str_word_count(strip_tags($post->content));
        $readingTime = max(1, ceil($wordCount / 200));

        // Related posts (latest 3 excluding current)
        $relatedPosts = BlogPost::where('is_published', true)
            ->where('id', '!=', $post->id)
            ->latest()
            ->take(3)
            ->get();
        
        // SEO Meta Tags
        $seo = [
            'title' => $post->seo_title ?? $post->title . ' - ' . config('app.name'),
            'description' => $post->seo_description ?? Str::limit(strip_tags($post->content), 160),
            'keywords' => $post->seo_keywords,
            'image' => $post->featured_image ? asset('storage/' . $post->featured_image) : null,
        ];

        return view('blog.show', compact('post', 'seo', 'readingTime', 'relatedPosts'));
    }
}
