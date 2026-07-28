<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $fillable = [
        'email',
        'source',
        'ip_address',
        'user_agent',
        'verification_token',
        'verification_token_expires_at',
        'verified_at',
        'is_verified',
        'is_active',
        'unsubscribed_at'
    ];

    protected $casts = [
        'verification_token_expires_at' => 'datetime',
        'verified_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Scope for verified subscribers.
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Scope for active subscribers.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for pending subscribers.
     */
    public function scopePending($query)
    {
        return $query->where('is_verified', false);
    }
}
