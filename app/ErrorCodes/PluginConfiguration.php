<?php
/**
 * Created by PhpStorm.
 * User: azivanovic
 * Date: 10/18/16
 * Time: 3:12 PM
 */

namespace App\ErrorCodes;


interface PluginConfiguration
{
    const ERROR_NONE = 2000;

    const ERROR_MISSING_NAME = 2001;

    const ERROR_MISSING_BASE_CLASS = 2002;

    const ERROR_MISSING_VERSION = 2003;

    const ERROR_MISSING_AUTHOR = 2004;

    const ERROR_CONFIG_NOT_FOUND = 2005;

    const ERROR_CONFIG_NOT_READABLE = 2006;

    const ERROR_PARSING_CONFIG_FILE = 2007;
}