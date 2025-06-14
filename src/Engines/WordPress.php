<?php

namespace Jankx\TemplateEngine\Engines;

if (!defined('ABSPATH')) {
    exit('Cheating huh?');
}

class WordPress extends Plates
{
    const ENGINE_NAME = 'wordpress';

    public function isDirectRender()
    {
        return true;
    }
}
