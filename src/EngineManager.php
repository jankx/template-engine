<?php
namespace Jankx\Template\Engine;

if (!class_exists(EngineManager::class)) {
    class EngineManager
    {
        protected static $instance;
        protected static $engines;

        public static function instance()
        {
            if (is_null(self::$instance)) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        public static function createEngine($id, $engineName = 'wordpress', $args = array())
        {
            $engines = apply_filters('jankx_template_engines', [
                'wordpress' => WordPress::class,
            ]);

            if (!isset($engines[$engineName]) || !class_exists($engines[$engineName])) {
                throw new \Error('The template engine is not supported.');
            }
            $engine = new $engines[$engineName]($args);
            if (!($engine instanceof Engine)) {
                throw new \Error(
                    sprintf('The template engine must is an instance of %s', Engine::class)
                );
            }

            self::$engines[$id] = $engine;

            return self::$engines[$id];
        }

        public static function getEngine($id)
        {
            if (empty(self::$engines[$id])) {
                return;
            }
            return self::$engines[$id];
        }
    }
}
