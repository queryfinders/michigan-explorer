<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    protected $guarded = [];

    public function seo()
    {
        return $this->morphOne(\App\Models\Seo::class, 'seoable');
    }

    public function category()
    {
        return $this->belongsTo(\App\Models\HotelCategory::class, 'hotel_category_id');
    }

    public function amenities()
    {
        return $this->belongsToMany(\App\Models\Amenity::class, 'hotel_amenity');
    }

    public function images()
    {
        return $this->hasMany(\App\Models\HotelImage::class)->orderBy('sort_order');
    }

    public function faqs()
    {
        return $this->hasMany(\App\Models\HotelFaq::class)->orderBy('sort_order');
    }

    public function bookingFeatures()
    {
        return $this->belongsToMany(\App\Models\BookingFeature::class, 'hotel_booking_feature');
    }

    public function policyValues()
    {
        return $this->hasMany(HotelPolicyValue::class);
    }

    public function affiliateLink()
    {
        return $this->belongsTo(AffiliateLink::class);
    }

    public function getAffiliateUrlAttribute()
    {
        return $this->affiliateLink?->link;
    }
}
