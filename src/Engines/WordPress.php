<?php

namespace Jankx\TemplateEngine\Engines;

if (!defined('ABSPATH')) {
    exit('Cheatin huh?');
}

class WordPress extends Plates
{
    const ENGINE_NAME = 'wordpress';

    public function isDirectRender()
    {
        return true;
    }
}
