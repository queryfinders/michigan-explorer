<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $guarded = [];

    public function seo()
    {
        return $this->morphOne(\App\Models\Seo::class, 'seoable');
    }

    public function category()
    {
        return $this->belongsTo(\App\Models\EventCategory::class, 'event_category_id');
    }

    public function faqs()
    {
        return $this->hasMany(\App\Models\EventFaq::class, 'event_id')->orderBy('sort_order', 'asc');
    }
}
