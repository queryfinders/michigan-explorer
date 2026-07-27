<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    public function author()
    {
        return $this->belongsTo(Author::class, 'author_id');
    }

    public function tags()
    {
        return $this->belongsToMany(BlogTag::class, 'blog_tag_maps', 'blog_id', 'blog_tag_id');
    }

    public function getReadingTimeAttribute()
    {
        $wordCount = str_word_count(strip_tags($this->content));
        $minutes = ceil($wordCount / 200);
        return $minutes;
    }

    public function seo()
    {
        return $this->morphOne(\App\Models\Seo::class, 'seoable');
    }

    public function faqs()
    {
        return $this->hasMany(\App\Models\BlogFaq::class)->orderBy('sort_order');
    }
}
