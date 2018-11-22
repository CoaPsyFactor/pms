<?php
/**
 * Created by PhpStorm.
 * User: azivanovic
 * Date: 10/13/16
 * Time: 4:45 PM
 */

namespace App\Helpers\Plugin;

use App\ErrorCodes\PluginConfiguration as PluginConfigurationErrorCode;

class PluginConfiguration
{
    const CONFIG_NAME = 'name';

    const CONFIG_AUTHOR = 'author';

    const CONFIG_VERSION = 'version';

    const CONFIG_BASE_CLASS = 'base_class';

    const CONFIG_DEPENDENCIES = 'dependencies';

    const CONFIG_MIDDLEWARES = 'middlewares';

    /** @var string */
    private $name;

    /** @var string */
    private $author;

    /** @var string */
    private $baseClass;

    /** @var string */
    private $version;

    /** @var array */
    private $dependencies = [];

    /** @var array */
    private $middlewares = [];

    /** @var string */
    private $configDirectory;

    /**
     *
     * Loads and prepares plugin configuration
     *
     * @param string $configDirectory
     * @return int
     */
    public function loadConfiguration($configDirectory)
    {
        $configPath = "{$configDirectory}/config.json";

        if (false === \File::exists($configPath)) {
            return PluginConfigurationErrorCode::ERROR_CONFIG_NOT_FOUND;
        }

        if (false === \File::isReadable($configPath)) {
            return PluginConfigurationErrorCode::ERROR_CONFIG_NOT_READABLE;
        }

        $json = \File::get($configPath);

        $data = json_decode($json, true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            return PluginConfigurationErrorCode::ERROR_PARSING_CONFIG_FILE;
        }

        $status = $this->populateConfig($data);

        if (PluginConfigurationErrorCode::ERROR_NONE !== $status) {
            return $status;
        }

        $this->configDirectory = $configDirectory;

        return PluginConfigurationErrorCode::ERROR_NONE;
    }

    /**
     * @param array $data
     * @return int
     */
    public function populateConfig(array $data)
    {
        if (empty($data[self::CONFIG_NAME])) {
            return PluginConfigurationErrorCode::ERROR_MISSING_NAME;
        }

        $this->name = $data[self::CONFIG_NAME];

        if (empty($data[self::CONFIG_BASE_CLASS])) {
            return PluginConfigurationErrorCode::ERROR_MISSING_BASE_CLASS;
        }

        $this->baseClass = $data[self::CONFIG_BASE_CLASS];

        if (empty($data[self::CONFIG_VERSION])) {
            return PluginConfigurationErrorCode::ERROR_MISSING_VERSION;
        }

        $this->version = $data[self::CONFIG_VERSION];

        if (empty($data[self::CONFIG_AUTHOR])) {
            return PluginConfigurationErrorCode::ERROR_MISSING_AUTHOR;
        }

        $this->author = $data[self::CONFIG_AUTHOR];

        if (false === empty($data[self::CONFIG_DEPENDENCIES]) && is_array($data[self::CONFIG_DEPENDENCIES])) {
            $this->dependencies = $data[self::CONFIG_DEPENDENCIES];
        }

        if (false === empty($data[self::CONFIG_MIDDLEWARES]) && is_array($data[self::CONFIG_MIDDLEWARES])) {
            $this->middlewares = $data[self::CONFIG_MIDDLEWARES];
        }

        return PluginConfigurationErrorCode::ERROR_NONE;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @return string
     */
    public function getBaseClass()
    {
        return $this->baseClass;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @return array
     */
    public function getDependencies()
    {
        return $this->dependencies;
    }

    /**
     * @return string
     */
    public function getConfigDirectory()
    {
        return $this->configDirectory;
    }
}