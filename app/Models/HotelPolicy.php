<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotelPolicy extends Model
{
    protected $fillable = ['name', 'input_type', 'sort_order', 'is_active'];

    public function hotelValues()
    {
        return $this->hasMany(HotelPolicyValue::class);
    }
}
