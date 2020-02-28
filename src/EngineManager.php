<?php
namespace Jankx\Template\Engine;

if (!class_exists(EngineManager::class)) {
    class EngineManager
    {
        protected static $instance;

        protected $defaultTemplateEngine;

        public static function instance()
        {
            if (is_null(self::$instance)) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        public static function __callStatic($name, $argv)
        {
            if (empty(self::$instance)) {
                throw new Error('Call the template engine when the engine is not initialized');
            }
            $callback = [self::$instance, $name];
            if (!is_callable($callback)) {
                throw new Error(sprintf('Call method %s::%s is not defined', get_class(self::$instance), $name));
            }

            return call_user_func_array($callback, $argv);
        }

        public function __construct()
        {
        }

        public function setDefaultTemplateEngine($engine)
        {
            if (!($engine instanceof EngineAbstract)) {
                throw new Error(sprintf('%s class must is an instance of %s', __CLASS__, EngineAbstract::class));
            }
            $this->defaultTemplateEngine = apply_filters('jankx_template_engine', $engine);
        }
    }
}
