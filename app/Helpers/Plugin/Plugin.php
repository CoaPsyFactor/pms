<?php
/**
 * Created by PhpStorm.
 * User: azivanovic
 * Date: 10/12/16
 * Time: 1:00 AM
 */

namespace App\Helpers\Plugin;


abstract class Plugin
{
    /**
     * @return PluginWidget
     */
    public function registerWidget()
    {
        return null;
    }

    /**
     * @return PluginPage[]
     */
    public function registerPages()
    {
        return [];
    }
}