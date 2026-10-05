@extends('layouts.app')

@section('content')
<div class="p-4 sm:p-6 bg-white rounded-xl shadow-xl">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-indigo-50 rounded-2xl text-indigo-600 shadow-sm border border-indigo-100/50">
                <i data-lucide="newspaper" class="w-8 h-8"></i>
            </div>
            <div>
                <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Blog Posts</h2>
                <p class="text-sm font-medium text-gray-400 mt-1">Manage your website articles and content</p>
            </div>
        </div>
        
        <div class="flex gap-3 w-full md:w-auto">
            <a href="{{ route('admin.blogs.create') }}"
               class="inline-flex items-center justify-center bg-indigo-600 text-white px-8 py-3.5 rounded-2xl hover:bg-indigo-700 transition-all duration-300 text-sm font-bold group w-full md:w-auto transform hover:-translate-y-1 active:scale-95">
                <i data-lucide="plus" class="w-5 h-5 mr-3 transition-transform duration-300 group-hover:rotate-90"></i>
                Add New Post
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-xl flex items-center gap-3">
        <i data-lucide="check-circle" class="w-5 h-5 text-green-600 flex-shrink-0"></i>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
    @endif

    <div class="overflow-hidden rounded-xl border border-gray-200 shadow-sm">
        {{-- Desktop Table --}}
        <div class="hidden md:block">
            <table class="w-full text-sm text-left text-gray-700 bg-white">
                <thead class="bg-indigo-600 text-white text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Image</th>
                        <th class="px-6 py-4">Title</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Created At</th>
                        <th class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($posts as $post)
                        <tr class="hover:bg-indigo-50 transition duration-150">
                            <td class="px-6 py-4">
                                @if($post->featured_image)
                                    <img src="{{ asset('storage/' . $post->featured_image) }}" class="w-12 h-12 rounded-lg object-cover" alt="{{ $post->title }}">
                                @else
                                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                        <i data-lucide="image" class="w-6 h-6 text-gray-400"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-900">{{ $post->title }}</div>
                                <div class="text-xs text-gray-400 mt-0.5">{{ Str::limit(strip_tags($post->content), 50) }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($post->is_published)
                                    <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full font-semibold">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> Published
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-600 text-xs px-3 py-1 rounded-full font-semibold">
                                        <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span> Draft
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-500">
                                {{ $post->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center items-center gap-3">

                                    <a href="{{ route('blog.show', $post->slug) }}"
                                       class="p-2 bg-gray-50 text-gray-600 rounded-xl hover:bg-indigo-50 hover:text-indigo-600 transition-all border border-transparent hover:border-indigo-100 shadow-sm">
                                        <i data-lucide="eye" class="h-5 w-5"></i>
                                    </a>

                                    <a href="{{ route('admin.blogs.edit', $post) }}"
                                       class="p-2 bg-gray-50 text-gray-600 rounded-xl hover:bg-indigo-50 hover:text-indigo-600 transition-all border border-transparent hover:border-indigo-100 shadow-sm">
                                        <i data-lucide="edit-3" class="h-5 w-5"></i>
                                    </a>

                                    <form method="POST" action="{{ route('admin.blogs.destroy', $post) }}" class="inline delete-post-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="delete-post-btn p-2 bg-gray-50 text-rose-600 rounded-xl hover:bg-rose-50 transition-all border border-transparent hover:border-rose-100 shadow-sm">
                                            <i data-lucide="trash-2" class="h-5 w-5"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500 italic">
                                <i data-lucide="newspaper" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                                No blog posts yet. Click "Add New Post" to get started!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Card Layout --}}
        <div class="block md:hidden divide-y divide-gray-100">
            @foreach ($posts as $post)
                <div class="p-6 space-y-4 hover:bg-gray-50 transition-colors">
                    <div class="flex justify-between items-start">
                        <div class="flex items-center gap-3">
                            @if($post->featured_image)
                                <img src="{{ asset('storage/' . $post->featured_image) }}" class="w-14 h-14 rounded-xl object-cover" alt="{{ $post->title }}">
                            @else
                                <div class="w-14 h-14 bg-gray-100 rounded-xl flex items-center justify-center">
                                    <i data-lucide="image" class="w-7 h-7 text-gray-400"></i>
                                </div>
                            @endif
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">{{ $post->title }}</h3>
                                <div class="flex items-center gap-2 mt-1">
                                    @if($post->is_published)
                                        <span class="text-xs text-green-600 font-semibold">Published</span>
                                    @else
                                        <span class="text-xs text-gray-500 font-semibold">Draft</span>
                                    @endif
                                    <span class="text-gray-300">·</span>
                                    <span class="text-xs text-gray-400">{{ $post->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex md:flex-row flex-col gap-2">
                            <a href="{{ route('blog.show', $post->slug) }}" class="p-2 bg-indigo-50 text-indigo-600 rounded-xl active:scale-95 transition-all shadow-sm border border-indigo-100">
                                <i data-lucide="eye" class="h-4 w-4"></i>
                            </a>
                            <a href="{{ route('admin.blogs.edit', $post) }}" class="p-2 bg-indigo-50 text-indigo-600 rounded-xl active:scale-95 transition-all shadow-sm border border-indigo-100">
                                <i data-lucide="edit-3" class="h-4 w-4"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.blogs.destroy', $post) }}" class="inline delete-post-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="delete-post-btn p-2 bg-rose-50 text-rose-600 rounded-xl active:scale-95 transition-all shadow-sm border border-rose-100">
                                    <i data-lucide="trash-2" class="h-4 w-4"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    
    <div class="mt-6">
        {{ $posts->links() }}
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white p-6 rounded-xl shadow-lg max-w-md text-center border-t-4 border-red-600 mx-4">
        <div class="flex justify-center mb-4">
            <i data-lucide="alert-triangle" class="w-12 h-12 text-red-600"></i>
        </div>
        <h3 class="text-xl font-bold text-red-700 mb-2">Delete Blog Post?</h3>
        <p class="text-sm text-red-600">This will permanently delete this post. This cannot be undone.</p>
        <div class="flex justify-center gap-4 mt-6">
            <button id="confirmDeleteBtn" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-lg font-semibold transition-colors">Yes, Delete</button>
            <button id="cancelDeleteBtn" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-5 py-2.5 rounded-lg font-semibold transition-colors">Cancel</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let formToSubmit = null;
    const modal = document.getElementById('deleteModal');
    
    document.querySelectorAll('.delete-post-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            formToSubmit = this.closest('form');
            modal.classList.remove('hidden');
        });
    });

    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if (formToSubmit) formToSubmit.submit();
    });

    document.getElementById('cancelDeleteBtn').addEventListener('click', function() {
        modal.classList.add('hidden');
        formToSubmit = null;
    });
});
</script>
@endsection
