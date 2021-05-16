<?php
namespace Jankx\TemplateEngine;

class Data
{
    protected static $data = array();

    public static function share($key, $value)
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

    public static function get($key, $defaultValue = null)
    {
        if (isset(static::$data[$key])) {
            return static::$data[$key];
        }
        return $defaultValue;
    }

    public static function all()
    {
        if (is_array(static::$data)) {
            return static::$data;
        }
        return array();
    }
}
