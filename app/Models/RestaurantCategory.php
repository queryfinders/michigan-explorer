<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantCategory extends Model
{
    protected $guarded = [];

    public function restaurants()
    {
        return $this->hasMany(Restaurant::class, 'restaurant_category_id');
    }
}
