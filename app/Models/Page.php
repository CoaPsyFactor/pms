<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    public function getUser()
    {
        return $this->hasOne(User::class, 'id', 'user');
    }

    /**
     * @param $id
     * @return Page
     */
    public static function getActivePageWithId($id)
    {
        return self::where(['id' => $id, 'active' => true])->first();
    }

    public function navigationLinks()
    {
        return $this->hasMany(NavigationLink::class, 'value');
    }
}
