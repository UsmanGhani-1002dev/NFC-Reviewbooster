<?php

namespace App\Models;

class BlogPost extends \Illuminate\Database\Eloquent\Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'featured_image',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    /**
     * Boot function for creating unique slugs
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = \Illuminate\Support\Str::slug($post->title);
            }
        });
    }
}
