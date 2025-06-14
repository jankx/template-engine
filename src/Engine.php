<?php

namespace Jankx\TemplateEngine;

if (!defined('ABSPATH')) {
    exit('Cheatin huh?');
}

use Jankx\TemplateEngine\Engines\Plates;
use Jankx\TemplateEngine\Engines\WordPress;

abstract class Engine implements EngineInterface
{
    protected static $support_engines;

    protected $id;
    protected $args = array();

    /**
     * @return \Jankx\TemplateEngine\EngineInterface[];
     */
    protected static function get_support_engines()
    {
        return apply_filters('jankx_template_engines', [
            WordPress::ENGINE_NAME  => WordPress::class,
            Plates::ENGINE_NAME     => Plates::class,
        ]);
    }

    /**
     * @return \Jankx\TemplateEngine\EngineInterface
     */
    public static function create($id, $engine_name = null)
    {
        if (is_null($engine_name)) {
            $engine = new static();
            $engine->setId($id);

            return $engine;
        }

        if (is_null(static::$support_engines)) {
            static::$support_engines = static::get_support_engines();
        }

        if (isset(static::$support_engines[$engine_name])) {
            $engineCls = static::$support_engines[$engine_name];

            $engine = new $engineCls();
            $engine->setId($id);

            return $engine;
        }
    }

    /**
     * @return void
     */
    public function setId($id)
    {
        if (empty($id)) {
            throw new \Exception('Engine ID must be have a value');
        }
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    public function setArgs($args)
    {
        if (!is_array($args)) {
            return;
        }

        $this->args = array_merge($this->args, $args);
    }

    /**
     * @return void
     */
    public function setupEnvironment()
    {
    }
}
