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
            $args = wp_parse_args($args, array(
                'template_directory' => null,
                'template_location' => null,
            ));
            $args = apply_filters('jankx_template_engine_args', $args, $engineName, $id);

            if (!isset($args['template_directory'], $args['template_location'])) {
                throw new \Exception(__('Please set template directory location informations', 'jankx'));
            }

            $engine_classes = apply_filters('jankx_template_engines', [
                'wordpress' => WordPress::class,
            ]);

            if (!isset($engine_classes[$engineName]) || !class_exists($engine_classes[$engineName])) {
                throw new \Exception(sprintf(
                    'The `%s` template engine is not supported.',
                    $engineName
                ));
            }

            // Create the template for Jankx Framework
            $engine = new $engine_classes[$engineName](
                $id,
                $args['template_directory'],
                $args['template_location'],
                $args
            );
            if (!($engine instanceof Engine)) {
                throw new \Exception(
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
