<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttractionCategory extends Model
{
    protected $guarded = [];

    public function attractions()
    {
        return $this->hasMany(\App\Models\Attraction::class, 'attraction_category_id');
    }
}
