<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventCategory extends Model
{
    protected $guarded = [];

    public function events()
    {
        return $this->hasMany(\App\Models\Event::class, 'event_category_id');
    }
}
