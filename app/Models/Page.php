<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $guarded = [];

    public function seo()
    {
        return $this->morphOne(\App\Models\Seo::class, 'seoable');
    }
}
