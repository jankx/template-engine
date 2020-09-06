<?php
namespace Jankx\Template\Engine;

if (!class_exists(EngineManager::class)) {
    class EngineManager
    {
        protected static $instance;
        protected static $engines;

        public static function getInstance()
        {
            if (is_null(static::$instance)) {
                static::$instance = new self();
            }
            return static::$instance;
        }

        private function __construct()
        {
            $this->loadHelpers();

            do_action('jankx_template_engine_init');
        }

        protected function loadHelpers()
        {
            $helperLoader = realpath(dirname(__FILE__) . '/../../helpers/loader.php');
            if (file_exists($helperLoader)) {
                require_once $helperLoader;
            }
        }

        protected function create($id, $engineName = 'wordpress', $args = array())
        {
            $engine_classes = apply_filters('jankx_template_engines', [
                'wordpress' => WordPress::class,
            ]);

            if (!isset($engine_classes[$engineName]) || !class_exists($engine_classes[$engineName])) {
                throw new \Error('The template engine is not supported.');
            }
            $engine = new $engine_classes[$engineName](wp_parse_args(
                $args,
                array(
                    'id' => $id,
                )
            ));

            if (!($engine instanceof Engine)) {
                throw new \Error(
                    sprintf('The template engine must is an instance of %s', Engine::class)
                );
            }

            return $engine;
        }

        public static function createEngine($id, $engineName = 'wordpress', $args = array())
        {
            $engine = static::getInstance()->create(
                $id,
                $engineName,
                $args
            );
            /**
             * Save the engine in EngineManager
             */
            static::$engines[$id] = $engine;

            return static::$engines[$id];
        }

        public static function getEngine($id = null)
        {
            if (empty(static::$engines[$id])) {
                return static::$engines;
            }
            return static::$engines[$id];
        }
    }
}
