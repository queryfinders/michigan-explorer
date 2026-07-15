<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotelCategory extends Model
{
    protected $guarded = [];

    public function hotels()
    {
        return $this->hasMany(\App\Models\Hotel::class, 'hotel_category_id');
    }
}
