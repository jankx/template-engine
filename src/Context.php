<?php
namespace Jankx\TemplateEngine;

class Context
{
    protected static $data = array();

    public static function add($key, $value)
    {
        static::$data[$key] = $value;
    }

    public static function shares($data)
    {
        if (!is_array($data)) {
            return false;
        }
        static::$data = array_merge(static::$data, $data);
    }

    public static function get($key = null, $defaultValue = null)
    {
        if (is_null($key)) {
            return static::$data;
        }

        if (isset(static::$data[$key])) {
            return static::$data[$key];
        }
        return $defaultValue;
    }
}