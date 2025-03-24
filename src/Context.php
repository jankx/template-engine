<?php

namespace Jankx\TemplateEngine;

class Context
{
    protected static $data = array();
    protected static $initilized = false;

    public static function add($key, $value)
    {
        static::$data[$key] = $value;
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

    public static function init()
    {
        if (static::$initilized) {
            return;
        }
        static::$data = array_merge(
            static::$data,
            array(
                'body_class' => new FunctionWrapper('get_body_class'),
                'open_container' => new FunctionWrapper('jankx_open_container'),
                'close_container' => new FunctionWrapper('jankx_close_container'),
            )
        );
    }
}
