<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotelFaq extends Model
{
    protected $guarded = [];

    public function hotel()
    {
        return $this->belongsTo(\App\Models\Hotel::class);
    }
}
