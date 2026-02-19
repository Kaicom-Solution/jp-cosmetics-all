<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $guarded = [];
    
    protected $casts = [
        'is_live' => 'boolean',
        'status' => 'boolean',
    ];

    /**
     * Scope for active notices
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Scope for live notice
     */
    public function scopeLive($query)
    {
        return $query->where('is_live', 1)->where('status', 1);
    }

    /**
     * Set this notice as live and unset all others
     */
    public function setAsLive()
    {
        // Unset all other live notices
        self::where('is_live', 1)->update(['is_live' => 0]);
        
        // Set this notice as live
        $this->is_live = 1;
        $this->save();
    }
}
