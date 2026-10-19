<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $guarded = [];

    protected static function booted()
    {
        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forget('llms.txt');
            \Illuminate\Support\Facades\Cache::forget('llms-full.txt');
        });
        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forget('llms.txt');
            \Illuminate\Support\Facades\Cache::forget('llms-full.txt');
        });
    }

    public function seo()
    {
        return $this->morphOne(\App\Models\Seo::class, 'seoable');
    }
}
