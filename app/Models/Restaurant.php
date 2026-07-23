<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $guarded = [];

    protected $casts = [
        'opening_hours' => 'array',
    ];

    public function seo()
    {
        return $this->morphOne(\App\Models\Seo::class, 'seoable');
    }

    public function category()
    {
        return $this->belongsTo(RestaurantCategory::class, 'restaurant_category_id');
    }

    public function images()
    {
        return $this->hasMany(RestaurantImage::class)->orderBy('sort_order');
    }

    public function faqs()
    {
        return $this->hasMany(RestaurantFaq::class)->orderBy('sort_order');
    }

    public function cuisines()
    {
        return $this->belongsToMany(RestaurantCuisine::class, 'restaurant_cuisine', 'restaurant_id', 'cuisine_id');
    }

    public function features()
    {
        return $this->belongsToMany(RestaurantFeature::class, 'restaurant_feature', 'restaurant_id', 'feature_id');
    }
}
