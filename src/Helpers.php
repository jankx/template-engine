<?php

use Jankx\TemplateEngine\Context;

if (!function_exists('jankx_template')) {
    function jankx_template(string $defaultPath, string|array $templates, array $data = [], bool $echo = true, array $options = []): string
    {
        $engine = Context::get($defaultPath);
        if (!$engine) {
            $engine = Context::init($defaultPath, $options['engine'] ?? 'latte', $options);
        }

        $content = $engine->render($templates, $data);
        if ($echo) {
            echo $content;
        }
        return $content;
    }
}
