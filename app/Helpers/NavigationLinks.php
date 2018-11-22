<?php
/**
 * Created by PhpStorm.
 * User: zvekete
 * Date: 8.10.2016.
 * Time: 18.52
 */

namespace App\Helpers;

use App\Models\NavigationLink;
use Illuminate\Database\Eloquent\Collection;

class NavigationLinks
{
    /** @var Collection */
    private static $_navigationLinks = [];

    /**
     * @return Collection
     */
    public static function getFormattedNavigationLinks()
    {
        $navigationLinks = NavigationLink::all();

        self::formatNavigationLinks($navigationLinks);

        return self::$_navigationLinks;
    }

    /**
     * @param Collection $linksCollection
     * @return string
     */
    public static function formatNavigationLinks(Collection $linksCollection)
    {
        $links = [];

        if (false === self::$_navigationLinks instanceof Collection) {
            self::$_navigationLinks = new Collection();
        }

        /** @var NavigationLink $link */
        foreach ($linksCollection as $link) {
            if (0 !== $link->parent) {
                continue;
            }

            $_link = self::$_navigationLinks->where('id', $link->id);

            if (is_object($_link) && $_link->count()) {
                $links[] = $_link->toArray();

                continue;
            }

            $link->slug = '/' . app()->getLocale() . '/' . $link->slug;

            $link->path = [$link->title];

            $link->children = self::getLinkChildren($link, $linksCollection, $link->path);

            self::$_navigationLinks->add($link);

            $links[] = $link->toArray();
        }

        return json_encode($links);
    }

    /**
     *
     * Returns string "active" if current route is match
     *
     * @param string $routeName
     * @return string
     */
    public static function active($routeName)
    {
        if (is_array($routeName)) {
            foreach ($routeName as $route) {
                if (0 === strcasecmp($route, request()->route()->getName())) {
                    return 'active';
                }
            }

            return '';
        }

        return 0 === strcasecmp($routeName, request()->route()->getName()) ? 'active' : '';
    }

    /**
     * @param NavigationLink $parentLink
     * @param Collection $links
     * @param array $linkPath
     * @return array
     */
    private static function getLinkChildren(NavigationLink $parentLink, Collection $links, $linkPath = [])
    {
        $childrenLinks = [];

        $data = $links->where('parent', $parentLink->id);

        /** @var NavigationLink $link */
        foreach ($data as $link) {
            if ($link === $parentLink) {
                continue;
            }

            $link->slug = '/' . app()->getLocale() . '/' . $link->slug;

            $linkPath[] = $link->title;

            $link->path = $linkPath;

            $link->children = self::getLinkChildren($link, $links, $link->path);

            self::$_navigationLinks->add($link);

            $childrenLinks[] = $link;
        }

        return $childrenLinks;
    }
}