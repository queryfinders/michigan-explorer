<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantFeature extends Model
{
    protected $guarded = [];

    public function restaurants()
    {
        return $this->belongsToMany(Restaurant::class, 'restaurant_feature', 'feature_id', 'restaurant_id');
    }
}
