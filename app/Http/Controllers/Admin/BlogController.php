<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function index()
    {
        $posts = BlogPost::latest()->paginate(10);
        return view('admin.blogs.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.blogs.form', ['post' => null]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'featured_image' => 'nullable|image|max:2048',
            'is_published' => 'nullable|boolean',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'seo_keywords' => 'nullable|string|max:255',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->title);
        $data['is_published'] = $request->has('is_published') ? 1 : 0;

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('blogs', 'public');
        }

        // Ensure unique slug
        $originalSlug = $data['slug'];
        $i = 1;
        while (BlogPost::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $originalSlug . '-' . $i++;
        }

        BlogPost::create($data);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog post created successfully.');
    }

    public function edit(BlogPost $blog)
    {
        return view('admin.blogs.form', [
            'post' => $blog
        ]);
    }

    public function update(Request $request, BlogPost $blog)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'featured_image' => 'nullable|image|max:2048',
            'is_published' => 'nullable|boolean',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'seo_keywords' => 'nullable|string|max:255',
        ]);

        $data = $request->all();
        $data['is_published'] = $request->has('is_published') ? 1 : 0;

        if ($request->hasFile('featured_image')) {
            if ($blog->featured_image) {
                Storage::disk('public')->delete($blog->featured_image);
            }
            $data['featured_image'] = $request->file('featured_image')->store('blogs', 'public');
        }

        if ($request->title != $blog->title) {
            $data['slug'] = Str::slug($request->title);
            $originalSlug = $data['slug'];
            $i = 1;
            while (BlogPost::where('slug', $data['slug'])->where('id', '!=', $blog->id)->exists()) {
                $data['slug'] = $originalSlug . '-' . $i++;
            }
        }

        $blog->update($data);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog post updated successfully.');
    }

    public function destroy(BlogPost $blog)
    {
        if ($blog->featured_image) {
            Storage::disk('public')->delete($blog->featured_image);
        }
        $blog->delete();

        return redirect()->route('admin.blogs.index')->with('success', 'Blog post deleted successfully.');
    }
}
