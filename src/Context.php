<?php

namespace Jankx\TemplateEngine;

use Jankx\TemplateEngine\Interfaces\TemplateEngine as TemplateEngineInterface;
use Jankx\TemplateEngine\Engines\LatteEngine;

class Context
{
    protected static array $engines = [];

    public static function init(string $defaultPath, string $engineName = 'latte', array $options = []): TemplateEngineInterface
    {
        if (isset(self::$engines[$defaultPath])) {
            return self::$engines[$defaultPath];
        }

        $engine = null;
        switch ($engineName) {
            case 'latte':
                $tempDir = $options['tempDir'] ?? null;
                if (!$tempDir && function_exists('wp_upload_dir')) {
                    $uploadDir = wp_upload_dir();
                    $tempDir = sprintf('%s/jankx/latte', $uploadDir['basedir']);
                    if (!file_exists($tempDir)) {
                        wp_mkdir_p($tempDir);
                    }
                }
                $engine = new LatteEngine($tempDir);
                break;
            default:
                throw new \Exception(sprintf('Template engine "%s" is not supported', $engineName));
        }

        $templateEngine = new TemplateEngine($engine, $defaultPath);
        if (isset($options['themePath'])) {
            $templateEngine->setThemePath($options['themePath']);
        }
        self::$engines[$defaultPath] = $templateEngine;

        return $templateEngine;
    }

    public static function get(string $defaultPath): ?TemplateEngineInterface
    {
        return self::$engines[$defaultPath] ?? null;
    }
}
