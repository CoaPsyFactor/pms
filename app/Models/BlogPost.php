<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    /**
     * @return User
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}