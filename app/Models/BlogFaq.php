<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogFaq extends Model
{
    protected $guarded = [];

    public function blog()
    {
        return $this->belongsTo(\App\Models\Blog::class);
    }
}
