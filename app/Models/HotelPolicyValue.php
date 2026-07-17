<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotelPolicyValue extends Model
{
    protected $fillable = ['hotel_id', 'hotel_policy_id', 'value'];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function policy()
    {
        return $this->belongsTo(HotelPolicy::class, 'hotel_policy_id');
    }
}
