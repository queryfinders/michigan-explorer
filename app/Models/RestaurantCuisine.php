<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantCuisine extends Model
{
    protected $guarded = [];

    public function restaurants()
    {
        return $this->belongsToMany(Restaurant::class, 'restaurant_cuisine', 'cuisine_id', 'restaurant_id');
    }
}
