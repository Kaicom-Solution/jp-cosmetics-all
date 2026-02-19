<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $guarded = [];
    
    protected $casts = [
        'status' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Scope for active FAQs
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Scope for ordered FAQs
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }
}
