<?php
/**
 * Created by PhpStorm.
 * User: azivanovic
 * Date: 10/10/16
 * Time: 2:45 PM
 */

namespace App\Helpers\Plugin;

use App\Exceptions\PluginWidgetParserException;
use App\Models\Plugin as PluginModel;
use Illuminate\View\View;

class PluginWidgetParser
{
    private static $_loadedPlugins;

    protected $pluginOptions;

    public function __construct()
    {
        if (empty(self::$_loadedPlugins)) {
            self::$_loadedPlugins = PluginModel::all();
        }
    }

    /** @var array */
    protected $helperDefinition = [];

    /** @var string */
    const MODULE_METHOD_ATTR_REGEX = '/\{\{(?<module>[\w]+) (?<method>[\w]+)(?<parameters>[a-z0-9\=\"\s]+){0,}\}\}/i';

    /** @var string */
    const PARAMETER_PARSE_REGEX = '/(?<parameter>[\w]+)=\"(?<parameter_value>[0-9a-z]+)\"/i';

    /**
     * @param string $content
     * @return array
     */
    public function parsePageContent($content)
    {
        $matches = [];

        preg_match_all(PluginWidgetParser::MODULE_METHOD_ATTR_REGEX, $content, $matches);

        $mapped = $this->mapMatches($matches['module'], $matches['method'], $matches['parameters'], $matches[0]);

        return $this->replaceContent($content, $mapped);
    }

    /**
     * @param string $content
     * @param array $mappedData
     * @return string
     */
    private function replaceContent($content, array $mappedData)
    {
        foreach ($mappedData as $currentData) {
            $parsed = $this->execute($currentData['module'], $currentData['method'], $currentData['parameters']);

            $content = str_replace($currentData['identifier'], $parsed, $content);
        }

        return $content;
    }

    /**
     * @param string $module
     * @param string $method
     * @param array $parameters
     * @return mixed
     * @throws PluginWidgetParserException
     */
    private function execute($module, $method, array $parameters)
    {
        $plugin = $this->getPlugin($module);

        if (null === $plugin) {
            return response()->view('errors.404', ['type' => "(plugin) {$module}"])->throwResponse();
        }

        $object = $this->validateAndGetWidget($plugin, $method);

        $response = call_user_func_array([$object, $method], $this->mapParameters($object, $method, $parameters));

        if ($response instanceof View) {
            $response = $response->render();
        }

        if (false === is_string($response)) {
            throw new PluginWidgetParserException('Page content must be string.');
        }

        return $response;
    }

    /**
     * @param PluginWidget $widget
     * @param string $method
     * @param array $parameters
     * @return array
     */
    private function mapParameters(PluginWidget $widget, $method, array $parameters)
    {
        $outParameters = [];

        $methodParameters = (new \ReflectionMethod($widget ,$method))->getParameters();

        foreach ($methodParameters as $parameter) {
            if (false === isset($parameters[$parameter->getName()])) {
                continue;
            }

            $outParameters[] = $parameters[$parameter->getName()];
        }

        return $outParameters;
    }

    /**
     * @param PluginModel $plugin
     * @param string $method
     * @return PluginWidget
     */
    private function validateAndGetWidget(PluginModel $plugin, $method)
    {
        /** @var Plugin $object */
        $object = new $plugin->base_class;

        if (false === $object instanceof Plugin) {
            return response()->view('errors.404', ['type' => Plugin::class])->throwResponse();
        }

        /** @var PluginWidget $pluginWidget */
        $pluginWidget = $object->registerWidget();

        if (false === $pluginWidget instanceof PluginWidget) {
            return response()->view('errors.404', ['type' => "{$plugin->base_class}"])->throwResponse();
        }

        if (false === method_exists($pluginWidget, $method)) {
            return response()->view('errors.404', ['type' => "{$plugin->base_class}::{$method}"])->throwResponse();
        }

        return $pluginWidget;
    }

    /**
     * @param array $modules
     * @param array $methods
     * @param array $parameters
     * @param array $identifiers
     * @return array
     */
    private function mapMatches(array $modules, array $methods, array $parameters, array $identifiers)
    {
        $mappedContent = [];

        foreach ($modules as $index => $module) {
            $mappedContent[] = [
                'module' => $module,
                'method' => $methods[$index],
                'parameters' => $this->parametersStringToArray($parameters[$index]),
                'identifier' => $identifiers[$index]
            ];
        }

        return $mappedContent;
    }

    /**
     * @param string $parametersString
     * @return array
     */
    private function parametersStringToArray($parametersString)
    {
        $matches = [];

        preg_match_all(PluginWidgetParser::PARAMETER_PARSE_REGEX, $parametersString, $matches);

        $parameters = [];

        foreach ($matches['parameter'] as $index => $parameter) {
            $parameters[$parameter] = $matches['parameter_value'][$index];
        }

        return $parameters;
    }

    /**
     * @param string $name
     * @return PluginModel|null
     */
    private function getPlugin($name)
    {
        if (empty(self::$_loadedPlugins)) {
            return null;
        }

        $plugin = self::$_loadedPlugins->where('name', $name)->first();

        return ($plugin instanceof PluginModel && $plugin->exists && $plugin->active ? $plugin : null);
    }
}