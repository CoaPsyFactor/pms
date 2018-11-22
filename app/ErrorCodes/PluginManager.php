<?php
/**
 * Created by PhpStorm.
 * User: azivanovic
 * Date: 10/18/16
 * Time: 3:12 PM
 */

namespace App\ErrorCodes;


interface PluginManager
{
    const ERROR_NONE = 1000;

    const ERROR_MISSING_CONFIG = 1001;

    const ERROR_INVALID_CONFIG = 1002;

    const ERROR_FAILED_TO_CREATE_DIR = 1003;

    const ERROR_PLUGIN_NOT_FOUND = 1004;

    const ERROR_FAILED_TO_REMOVE_PLUGIN = 1005;

    const ERROR_FAILED_TO_REMOVE_PLUGIN_DATA = 1006;

    const ERROR_FAILED_TO_UPLOAD = 1007;

    const ERROR_INSTALLATION_PATH_NOT_FOUND = 1008;

    const ERROR_FAILED_TO_COPY_PLUGIN = 1009;

    const ERROR_FAILED_TO_COPY_VIEWS = 1010;

    const ERROR_FAILED_TO_REMOVE_TEMP_DIR = 1011;

    const ERROR_FAILED_TO_SAVE_PLUGIN = 1012;
}