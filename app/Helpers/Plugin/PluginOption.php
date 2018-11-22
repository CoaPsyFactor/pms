<?php
/**
 * Created by PhpStorm.
 * User: zvekete
 * Date: 11.10.2016.
 * Time: 05.10
 */

namespace App\Helpers\Plugin;


use App\Exceptions\PluginOptionException;

abstract class PluginOption
{
    protected $_labels = [];

    const TYPE_INPUT = 0;

    const TYPE_SELECT = 1;

    const TYPE_CHECK = 2;

    /**
     * @return string
     */
    public abstract function getName();

    /**
     * @return mixed
     */
    public abstract function getValue();

    /**
     * @return array
     */
    protected abstract function optionLabels();

    /**
     * @return int
     */
    protected abstract function optionType();

    /**
     * @param int $id
     * @return mixed|null
     */
    public function getLabel($id = 0)
    {
        $labels = $this->getLabels();

        return empty($labels[$id]) ? null : $labels[$id];
    }

    /**
     * @return array
     * @throws PluginOptionException
     */
    public function getLabels()
    {
        $labels = $this->optionLabels();

        if (false === is_array($labels)) {
            throw new PluginOptionException(static::class . '::optionLabels() must return array.');
        }

        return $labels;
    }

    /**
     * @return int
     * @throws PluginOptionException
     */
    public function getType()
    {
        $type = $this->optionType();

        switch ($type) {
            case PluginOption::TYPE_INPUT:
            case PluginOption::TYPE_SELECT:
            case PluginOption::TYPE_CHECK:
                return $type;
            default:
                throw new PluginOptionException("Invalid plugin option type {$type}");
        }
    }
}