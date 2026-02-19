<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_featured' => 'boolean',
        'status' => 'boolean',
    ];

    /**
     * Boot method to auto-generate slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($blog) {
            if (empty($blog->slug)) {
                $blog->slug = Str::slug($blog->title);
            }
        });

        static::updating(function ($blog) {
            if ($blog->isDirty('title') && empty($blog->slug)) {
                $blog->slug = Str::slug($blog->title);
            }
        });
    }

    /**
     * Get the category for this blog
     */
    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    /**
     * Scope for active blogs
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Scope for featured blogs
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', 1);
    }

}
