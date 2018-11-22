<?php
/**
 * Created by PhpStorm.
 * User: azivanovic
 * Date: 10/12/16
 * Time: 1:01 AM
 */

namespace App\Helpers\Plugin;


use App\Exceptions\PluginPageException;

abstract class PluginPage
{
    const GUEST_PAGE = 0;

    const CLIENT_PAGE = 1;

    const ADMIN_PAGE = 2;

    /**
     * @return array
     */
    protected abstract function registerRoutes();

    /**
     * @return int
     */
    protected abstract function pluginPageType();

    /**
     * @return array
     */
    public function getRoutes()
    {
        return $this->registerRoutes();
    }

    /**
     * @return int
     * @throws PluginPageException
     */
    public function getPluginPageType()
    {
        $type = $this->pluginPageType();

        switch ($type) {
            case PluginPage::GUEST_PAGE:
            case PluginPage::CLIENT_PAGE:
            case PluginPage::ADMIN_PAGE:
                return $type;
            default:
                throw new PluginPageException("Invalid plugin page type {$type}");
        }
    }
}