<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $guarded = [];

    protected static function booted()
    {
        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forget('home_events');
            \Illuminate\Support\Facades\Cache::forget('home_upcoming_events_widget');
            \Illuminate\Support\Facades\Cache::forget('llms.txt');
            \Illuminate\Support\Facades\Cache::forget('llms-full.txt');
        });
        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forget('home_events');
            \Illuminate\Support\Facades\Cache::forget('home_upcoming_events_widget');
            \Illuminate\Support\Facades\Cache::forget('llms.txt');
            \Illuminate\Support\Facades\Cache::forget('llms-full.txt');
        });
    }

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
