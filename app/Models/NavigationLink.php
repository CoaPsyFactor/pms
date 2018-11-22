<?php

namespace App\Models;

use App\Collections\NavigationLinksCollection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class NavigationLink extends Model
{
    /**
     * @return Collection
     */
    public static function getActiveLinks()
    {
        return self::where('is_active', true)->orderBy('sort', 'ASC')->get();
    }

    public function isInternal()
    {
        return (bool) $this->getAttribute('internal');
    }
}
