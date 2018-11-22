<?php
/**
 * Created by PhpStorm.
 * User: zvekete
 * Date: 13.10.2016.
 * Time: 01.20
 */

namespace App\Helpers\Plugin;

use App\Models\Plugin as PluginModel;
use App\ErrorCodes\PluginConfiguration as PluginConfigurationError;
use App\ErrorCodes\PluginManager as PluginManagerErrorCode;
use Carbon\Carbon;

class PluginManager
{
    /**
     * @param string $zipPath
     * @return int
     */
    public function installPlugin($zipPath)
    {
        /** @var string $unzipDirectoryName Path where to extract files */
        $unzipDirectoryName = storage_path(uniqid('plugins/'));

        /** @var int $status */
        $status = $this->extractPlugin($zipPath, $unzipDirectoryName);

        if (PluginManagerErrorCode::ERROR_NONE !== $status) {
            return $status;
        }

        $status = $this->configurePlugin($unzipDirectoryName);

        if (in_array($status, [PluginManagerErrorCode::ERROR_NONE, PluginConfigurationError::ERROR_NONE])) {
            \File::deleteDirectory($unzipDirectoryName);

            return PluginManagerErrorCode::ERROR_NONE;
        }

        return $status;
    }

    /**
     * @param $pluginId
     * @return int
     */
    public function uninstallPlugin($pluginId)
    {
        /** @var PluginModel $plugin */
        $plugin = PluginModel::find($pluginId);

        if (false === is_object($plugin) || false === $plugin->exists) {
            return PluginManagerErrorCode::ERROR_PLUGIN_NOT_FOUND;
        }

        $pluginName = $plugin->name;

        if (false === $plugin->delete()) {
            return PluginManagerErrorCode::ERROR_FAILED_TO_REMOVE_PLUGIN;
        }

        if (false === $this->removePluginFiles($pluginName)) {
            return PluginManagerErrorCode::ERROR_FAILED_TO_REMOVE_PLUGIN_DATA;
        }

        return PluginManagerErrorCode::ERROR_NONE;
    }

    /**
     * @param string $pluginName
     * @return int
     */
    private function removePluginFiles($pluginName)
    {
        return \File::deleteDirectory(__DIR__ . "/../../Plugins/{$pluginName}");
    }

    /**
     * @param $pluginDirectory
     * @return int
     */
    private function configurePlugin($pluginDirectory)
    {
        if (false === is_dir($pluginDirectory) || false === is_readable($pluginDirectory)) {
            return PluginManagerErrorCode::ERROR_FAILED_TO_UPLOAD;
        }

        $pluginConfiguration = new PluginConfiguration();

        $status = $pluginConfiguration->loadConfiguration($pluginDirectory);

        if (PluginConfigurationError::ERROR_NONE !== $status) {
            return $status;
        }

        $status = $this->copyFiles($pluginConfiguration);

        if (PluginManagerErrorCode::ERROR_NONE !== $status) {
            return $status;
        }

        return $this->savePlugin($pluginConfiguration);
    }

    /**
     * @param PluginConfiguration $pluginConfiguration
     * @return int
     */
    private function copyFiles(PluginConfiguration $pluginConfiguration)
    {
        $status = $this->copyPlugin($pluginConfiguration);

        if (PluginManagerErrorCode::ERROR_NONE !== $status) {
            return $status;
        }

        $status = $this->copyViews($pluginConfiguration);

        if (PluginManagerErrorCode::ERROR_NONE !== $status) {
            return $status;
        }

        return PluginManagerErrorCode::ERROR_NONE;
    }

    /**
     * @param PluginConfiguration $pluginConfiguration
     * @return int
     */
    private function copyPlugin(PluginConfiguration $pluginConfiguration)
    {
        $pluginClasses = "{$pluginConfiguration->getConfigDirectory()}/plugin";

        if (false === \File::exists($pluginClasses)) {
            return PluginManagerErrorCode::ERROR_INSTALLATION_PATH_NOT_FOUND;
        }

        if (false === \File::copyDirectory($pluginClasses, __DIR__ . '/../../Plugins')) {
            return PluginManagerErrorCode::ERROR_FAILED_TO_COPY_PLUGIN;
        }

        return PluginManagerErrorCode::ERROR_NONE;
    }

    /**
     * @param PluginConfiguration $pluginConfiguration
     * @return int
     */
    private function copyViews(PluginConfiguration $pluginConfiguration)
    {
        $viewsPath = "{$pluginConfiguration->getConfigDirectory()}/views";

        if (false === \File::exists($viewsPath)) {
            return PluginManagerErrorCode::ERROR_NONE;
        }

        if (false === \File::copyDirectory($viewsPath, __DIR__ . '/../../../resources/views')) {
            return PluginManagerErrorCode::ERROR_FAILED_TO_COPY_VIEWS;
        }

        return PluginManagerErrorCode::ERROR_NONE;
    }

    /**
     * @param string $zipPath
     * @param string $unzipDirectoryName
     * @return int|string
     */
    private function extractPlugin($zipPath, $unzipDirectoryName)
    {
        /** @var \ZipArchive $zip */
        $zip = new \ZipArchive();

        /** Open zip file */
        $zip->open($zipPath, \ZipArchive::FL_COMPRESSED);

        /** Try making temp directory, if it fails return error code */
        if (false === \File::makeDirectory($unzipDirectoryName)) {
            $zip->close();

            return PluginManagerErrorCode::ERROR_FAILED_TO_CREATE_DIR;
        }

        /** Extract files */
        $zip->extractTo($unzipDirectoryName);

        /** Close zip file */
        $zip->close();

        return PluginManagerErrorCode::ERROR_NONE;
    }

    /**
     * @param PluginConfiguration $pluginConfiguration
     * @return int
     */
    private function savePlugin(PluginConfiguration $pluginConfiguration)
    {
        $plugin = new PluginModel();

        $plugin->name = $pluginConfiguration->getName();

        $plugin->base_class = $pluginConfiguration->getBaseClass();

        $plugin->active = false;

        $plugin->created_at = Carbon::now()->toDateTimeString();

        if ($plugin->save()) {
            return PluginManagerErrorCode::ERROR_NONE;
        }

        return PluginManagerErrorCode::ERROR_FAILED_TO_SAVE_PLUGIN;
    }
}