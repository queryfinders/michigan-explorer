<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingFeature extends Model
{
    protected $guarded = [];

    public function hotels()
    {
        return $this->belongsToMany(\App\Models\Hotel::class, 'hotel_booking_feature');
    }
}
