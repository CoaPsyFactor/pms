<?php
/**
 * Created by PhpStorm.
 * User: azivanovic
 * Date: 10/12/16
 * Time: 1:27 AM
 */

namespace App\Plugins\Blog;

use App\Plugins\Blog\Pages\BlogClient;
use App\Helpers\Plugin\Plugin;

class BlogPlugin extends Plugin
{
    /**
     * @return BlogWidget
     */
    public function registerWidget()
    {
        return new BlogWidget();
    }

    /**
     * @return array
     */
    public function registerPages()
    {
        return [
            new BlogClient(),
        ];
    }
}