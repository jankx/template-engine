<?php
namespace Jankx\TemplateEngine;

use Jankx\TemplateEngine\Engines\Plates;

abstract class Engine implements EngineInterface
{
    protected static $support_engines;

    protected $id;
    protected $args = array();

    protected static function get_support_engines()
    {
        return apply_filters('jankx_template_engines', [
            'wordpress' => Plates::class,
            Plates::ENGINE_NAME     => Plates::class,
        ]);
    }

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

    public function setId($id)
    {
        if (empty($id)) {
            throw \Exception('Engine ID must be have a value');
        }
        $this->id = $id;
    }

    public function setArgs($args)
    {
        if (!is_array($args)) {
            return;
        }

        $this->args = array_merge($this->args, $args);
    }

    public function setupEnvironment()
    {
    }
}
