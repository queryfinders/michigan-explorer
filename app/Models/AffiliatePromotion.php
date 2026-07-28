<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliatePromotion extends Model
{
    protected $guarded = [];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Scope to return only active promotions matching dates
     */
    public function scopeActive($query)
    {
        $now = now();
        return $query->where('is_active', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('starts_at')
                  ->orWhere('starts_at', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('ends_at')
                  ->orWhere('ends_at', '>=', $now);
            });
    }

    /**
     * Relationship to AffiliateLink
     */
    public function affiliateLink()
    {
        return $this->belongsTo(\App\Models\AffiliateLink::class, 'affiliate_link_id');
    }

    /**
     * Fetch the highest-priority active promotion for a given placement.
     */
    public static function forPlacement(string $placement): ?self
    {
        return static::active()
            ->where('placement', $placement)
            ->orderBy('priority')
            ->first();
    }
}
