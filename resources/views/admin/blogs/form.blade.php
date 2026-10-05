@extends('layouts.app')

@section('content')
<div class="p-4 sm:p-6 bg-white rounded-xl shadow-xl">
    <div class="flex items-center gap-4 mb-10">
        <a href="{{ route('admin.blogs.index') }}" class="p-2 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors">
            <i data-lucide="arrow-left" class="w-5 h-5 text-gray-600"></i>
        </a>
        <div>
            <h2 class="text-3xl font-bold text-gray-900 tracking-tight">{{ $post ? 'Edit Post' : 'Create New Post' }}</h2>
            <p class="text-sm font-medium text-gray-400 mt-1">{{ $post ? 'Update post details and content' : 'Share a new article with your audience' }}</p>
        </div>
    </div>

    @if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-xl text-sm">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ $post ? route('admin.blogs.update', $post) : route('admin.blogs.store') }}" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @if($post) @method('PUT') @endif

        <!-- Main Content Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <!-- Title -->
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 shadow-sm transition-all hover:shadow-md">
                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wider">Post Title *</label>
                    <input type="text" name="title" value="{{ old('title', $post->title ?? '') }}" 
                           class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 text-lg font-semibold" 
                           placeholder="Enter a title..." required>
                </div>

                <!-- Content -->
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 shadow-sm transition-all hover:shadow-md">
                    <label class="block text-sm font-bold text-gray-700 mb-4 uppercase tracking-wider">Content *</label>
                    <textarea name="content" id="blog-editor" rows="15" class="w-full border border-gray-300 rounded-xl px-4 py-3">{{ old('content', $post->content ?? '') }}</textarea>
                </div>
            </div>

            <!-- Sidebar Controls -->
            <div class="space-y-6">
                <!-- Status & Image -->
                <div class="bg-indigo-50/50 rounded-2xl p-6 border border-indigo-100 shadow-sm">
                    <h3 class="text-indigo-900 font-bold mb-6 flex items-center gap-2">
                        <i data-lucide="settings-2" class="w-5 h-5"></i>
                        Publishing
                    </h3>

                    <div class="space-y-6">
                        <!-- Featured Image -->
                        <div>
                            <label class="block text-xs font-black text-indigo-900 mb-4 uppercase tracking-widest">Featured Image</label>
                            @if($post && $post->featured_image)
                                <div class="mb-5 relative group ring-4 ring-white shadow-xl rounded-2xl overflow-hidden aspect-video">
                                    <img src="{{ asset('storage/' . $post->featured_image) }}" class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-500">
                                    <div class="absolute inset-0 bg-indigo-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <span class="text-white text-[10px] font-black uppercase tracking-widest bg-indigo-600/80 px-4 py-2 rounded-full backdrop-blur-sm">Replace Image</span>
                                    </div>
                                </div>
                            @endif
                            <div class="relative">
                                <input type="file" name="featured_image" id="featured_image" accept="image/*" class="hidden">
                                <label for="featured_image" class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-indigo-200 rounded-2xl bg-white hover:bg-indigo-50 hover:border-indigo-400 transition-all cursor-pointer group">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <i data-lucide="upload-cloud" class="w-8 h-8 text-indigo-400 mb-2 group-hover:scale-110 transition-transform"></i>
                                        <p class="text-[10px] font-black text-indigo-600 uppercase tracking-widest">Click to upload</p>
                                    </div>
                                </label>
                            </div>
                            <p class="text-[9px] text-indigo-400 mt-3 italic text-center font-bold tracking-tight">Recommended: 1200x630px (Max 2MB)</p>
                        </div>

                        <!-- Visibility -->
                        <div class="pt-6 border-t border-indigo-100" x-data="{ isPublished: {{ old('is_published', $post->is_published ?? false) ? 'true' : 'false' }} }">
                            <div class="flex items-center justify-between group">
                                <span class="text-xs font-black text-indigo-900 uppercase tracking-widest group-hover:text-indigo-600 transition-colors">Post Status</span>
                                <div class="flex items-center gap-4">
                                    <span :class="!isPublished ? 'text-indigo-600 font-black' : 'text-gray-400 font-medium'" class="text-[10px] uppercase tracking-widest transition-all">Draft</span>
                                    
                                    <div class="checkbox-apple">
                                        <input class="yep" id="is_published_toggle" type="checkbox" name="is_published" value="1" x-model="isPublished">
                                        <label for="is_published_toggle"></label>
                                    </div>

                                    <span :class="isPublished ? 'text-indigo-600 font-black' : 'text-gray-400 font-medium'" class="text-[10px] uppercase tracking-widest transition-all">Publish</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SEO Settings -->
                <div class="bg-blue-50/50 rounded-2xl p-6 border border-blue-100 shadow-sm">
                    <h3 class="text-blue-900 font-bold mb-6 flex items-center gap-2">
                        <i data-lucide="search" class="w-5 h-5"></i>
                        SEO Optimizer
                    </h3>
                    
                    <div class="space-y-5">
                        <div>
                            <label class="block text-xs font-bold text-blue-900 mb-1 uppercase tracking-widest">Meta Title</label>
                            <input type="text" name="seo_title" value="{{ old('seo_title', $post->seo_title ?? '') }}" 
                                   class="w-full border border-blue-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-400" 
                                   placeholder="Browser Post title...">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-blue-900 mb-1 uppercase tracking-widest">Meta Description</label>
                            <textarea name="seo_description" rows="3" 
                                      class="w-full border border-blue-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-400" 
                                      placeholder="Meta description">{{ old('seo_description', $post->seo_description ?? '') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-blue-900 mb-1 uppercase tracking-widest">Meta Keywords</label>
                            <input type="text" name="seo_keywords" value="{{ old('seo_keywords', $post->seo_keywords ?? '') }}" 
                                   class="w-full border border-blue-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-400" 
                                   placeholder="SEO focus keywords...">
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit" class="w-full bg-indigo-600 text-white px-8 py-4 rounded-2xl font-black text-lg shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition-all transform hover:-translate-y-1 active:scale-95">
                        {{ $post ? 'UPDATE POST' : 'PUBLISH POST' }}
                    </button>
                    <a href="{{ route('admin.blogs.index') }}" class="block w-full text-center mt-3 text-sm font-bold text-gray-400 hover:text-gray-600 transition-colors uppercase tracking-widest">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- TinyMCE CDN -->
<script src="https://cdn.tiny.cloud/1/1hamv8j8yqdcx3b2druoor43sqvhev9c4jobju4i89kvuota/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#blog-editor',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table wordcount fullscreen preview code help quickbars',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | forecolor backcolor | align lineheight | numlist bullist indent outdent | link image media table | blockquote hr codesample | emoticons charmap | fullscreen code | removeformat',
        toolbar_mode: 'wrap',
        height: 650,
        content_style: 'body { font-family:Figtree,Figtree-placeholder,Arial,sans-serif; font-size:16px; line-height:1.6; padding:10px; }',
        placeholder: 'Start writing your amazing story...',
        branding: false,
        promotion: false,
        color_cols: 8,
        custom_colors: true,
        font_family_formats: 'Ubuntu=Ubuntu,sans-serif; Mulish=Mulish,sans-serif; Figtree=Figtree,sans-serif; Arial=arial,helvetica,sans-serif; Georgia=georgia,palatino; Times New Roman=times new roman,times; Verdana=verdana,geneva',
        font_size_formats: '8pt 10pt 12pt 14pt 16pt 18pt 20pt 24pt 28pt 32pt 36pt 48pt',
        block_formats: 'Paragraph=p; Heading 2=h2; Heading 3=h3; Heading 4=h4; Heading 5=h5; Blockquote=blockquote; Preformatted=pre',
        image_advtab: true,
        image_caption: true,
        quickbars_selection_toolbar: 'bold italic | forecolor backcolor | quicklink h2 h3 blockquote',
        quickbars_insert_toolbar: 'image media table hr',
        contextmenu: 'link image table',
        menubar: 'file edit view insert format tools table help'
    });
</script>

<style>
    /* Apple Style Toggle */
    .checkbox-apple {
        position: relative;
        width: 50px;
        height: 25px;
        user-select: none;
    }

    .checkbox-apple label {
        position: absolute;
        top: 0;
        left: 0;
        width: 50px;
        height: 25px;
        border-radius: 50px;
        background: linear-gradient(to bottom, #b3b3b3, #e6e6e6);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .checkbox-apple label:after {
        content: '';
        position: absolute;
        top: 1px;
        left: 1px;
        width: 23px;
        height: 23px;
        border-radius: 50%;
        background-color: #fff;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        transition: all 0.3s ease;
    }

    .checkbox-apple input[type="checkbox"]:checked + label {
        background: linear-gradient(to bottom, #4cd964, #5de24e);
    }

    .checkbox-apple input[type="checkbox"]:checked + label:after {
        transform: translateX(25px);
    }

    .yep {
        position: absolute;
        top: 0;
        left: 0;
        width: 50px;
        height: 25px;
        opacity: 0;
        z-index: 10;
        cursor: pointer;
    }
</style>
@endsection
