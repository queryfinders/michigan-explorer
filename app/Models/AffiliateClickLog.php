<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliateClickLog extends Model
{
    protected $fillable = [
        'affiliate_link_id',
        'entity_type',
        'entity_id',
        'visitor_id',
        'ip_address',
        'user_agent',
        'referer',
        'country_code',
        'country_name',
        'state',
        'city',
        'clicked_at'
    ];

    protected $casts = [
        'clicked_at' => 'datetime'
    ];

    public function affiliateLink()
    {
        return $this->belongsTo(AffiliateLink::class);
    }
}
