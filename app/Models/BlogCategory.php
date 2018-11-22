<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\BlogCategory
 *
 * @mixin \Eloquent
 */
class BlogCategory extends Model
{
    public function posts()
    {
        return $this->hasMany(BlogPost::class, 'category_id');
    }
}
