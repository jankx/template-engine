<?php
namespace Jankx\Template\Engine;

class Data
{
    protected static $data = array();

    public static function share($key, $value)
    {
        static::$data[$key] = $value;
    }

    public static function get($key, $defaultValue = null)
    {
        if (isset(static::$data[$key])) {
            return static::$data[$key];
        }
        return $defaultValue;
    }

    public function all()
    {
        is_array(static::$data) {
            return static::$data;
        }
        return array();
    }
}
