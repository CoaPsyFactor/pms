<?php
/**
 * Created by PhpStorm.
 * User: zvekete
 * Date: 12.10.2016.
 * Time: 21.47
 */

namespace App\Helpers\Plugin;


use App\Exceptions\PluginLoaderException;
use App\Models\Plugin as PluginModel;

class PluginLoader
{
    public function loadPlugins()
    {
        /** @var PluginModel[] $plugins */
        $plugins = PluginModel::where(['active' => true])->get();

        /** @var PluginModel $plugin */
        foreach ($plugins as $plugin) {
            $this->loadPlugin($plugin);
        }
    }

    /**
     * @param PluginModel $pluginModel
     * @throws PluginLoaderException
     */
    public function loadPlugin(PluginModel $pluginModel)
    {
        if (false === class_exists($pluginModel->base_class)) {
            throw new PluginLoaderException("Invalid base class {$pluginModel->base_class} in plugin {$pluginModel->name}");
        }

        /** @var Plugin $plugin */
        $plugin = new $pluginModel->base_class;

        if (false === $plugin instanceof Plugin) {
            throw new PluginLoaderException("Plugin {$pluginModel->name} base class must be instance of " . Plugin::class);
        }

        $this->registerPluginPages($plugin);
    }

    /**
     * @param Plugin $plugin
     */
    private function registerPluginPages(Plugin $plugin)
    {
        /** @var PluginPage[] $pages */
        $pages = $plugin->registerPages();

        if (false === is_array($pages)) {
            return;
        }

        /** @var PluginPage $page */
        foreach ($pages as $page) {
            $this->registerPluginPage($page);
        }
    }

    /**
     * @param PluginPage $page
     */
    private function registerPluginPage(PluginPage $page)
    {
        $routes = $page->getRoutes();

        $class = get_class($page);

        foreach ($routes as $route => $routeInformation) {
            $this->addRoute(
                $route,
                "{$class}@{$routeInformation['callback']}",
                $routeInformation['name'],
                empty($routeInformation['method']) ? 'get' : strtolower($routeInformation['method'])
            );
        }
    }

    /**
     * @param string $route
     * @param string $classCallback
     * @param string $routeName
     * @param string $routeMethod
     */
    private function addRoute($route, $classCallback, $routeName, $routeMethod = 'get')
    {
        switch ($routeMethod) {
            case 'get':
                \Route::group(['namespace' => null], function () use ($route, $classCallback, $routeName) {
                    \Route::get($route, $classCallback)->name($routeName);
                });

                break;
            case 'post':
                \Route::group(['namespace' => null], function () use ($route, $classCallback, $routeName) {
                    \Route::post($route, $classCallback)->name($routeName);
                });

                break;
        }
    }
}