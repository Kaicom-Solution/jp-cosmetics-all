<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromotionPopup extends Model
{
    protected $guarded = [];
    
    protected $casts = [
        'is_live' => 'boolean',
        'status' => 'boolean',
    ];

    /**
     * Scope for active popups
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Scope for live popup
     */
    public function scopeLive($query)
    {
        return $query->where('is_live', 1)->where('status', 1);
    }

    /**
     * Set this popup as live and unset all others
     */
    public function setAsLive()
    {
        // Unset all other live popups
        self::where('is_live', 1)->update(['is_live' => 0]);
        
        // Set this popup as live
        $this->is_live = 1;
        $this->save();
    }
}
