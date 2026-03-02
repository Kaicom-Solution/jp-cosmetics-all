<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SkinType extends Model
{
    protected $guarded = [];
    
    protected $casts = [
        'status' => 'boolean',
    ];

    // Auto generate slug from name
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($skinType) {
            if (empty($skinType->slug)) {
                $skinType->slug = Str::slug($skinType->name);
            }
        });

        static::updating(function ($skinType) {
            if (empty($skinType->slug)) {
                $skinType->slug = Str::slug($skinType->name);
            }
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
