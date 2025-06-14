<?php

namespace Jankx\TemplateEngine;

if (!defined('ABSPATH')) {
    exit('Cheatin huh?');
}

use Jankx\TemplateEngine\Helpers;

class Filters
{
    public function getFilters()
    {
        $filters = array(
            'post_class' => array(Helpers::class, 'post_class'),
        );

        return $filters;
    }
}
