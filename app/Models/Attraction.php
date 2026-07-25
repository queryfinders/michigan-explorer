<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attraction extends Model
{
    protected $guarded = [];

    public function seo()
    {
        return $this->morphOne(\App\Models\Seo::class, 'seoable');
    }

    public function category()
    {
        return $this->belongsTo(\App\Models\AttractionCategory::class, 'attraction_category_id');
    }
}
