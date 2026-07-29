<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliateLink extends Model
{
    protected $fillable = [
        'name',
        'provider',
        'link',
        'description',
        'is_active',
        'total_clicks'
    ];

    public function hotels()
    {
        return $this->hasMany(Hotel::class);
    }

    public function restaurants()
    {
        return $this->hasMany(Restaurant::class);
    }

    public function clickLogs()
    {
        return $this->hasMany(AffiliateClickLog::class);
    }
}
