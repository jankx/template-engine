<?php

namespace Jankx\TemplateEngine;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

class Helpers
{
    public static function post_class($class = '')
    {
        $classes = get_post_class();
        if ($class) {
            array_push($classes, $class);
        }

        return implode(' ', $classes);
    }
}
