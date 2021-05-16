<?php
namespace Jankx\TemplateEngine\Engines;

use Jankx\TemplateEngine\Engine;

class Plates extends Engine
{
    const ENGINE_NAME = 'plates';

    public function __construct()
    {
    }

    public function getName()
    {
        return static::ENGINE_NAME;
    }

    public function setDefaultTemplateDir($dir)
    {
    }

    public function setDirectoryInTheme($dirName)
    {
    }

    public function searchTemplate($templates)
    {
    }

    public function render($templates, $data = array(), $echo = true)
    {
    }

    public function isRenderDirectly()
    {
        return false;
    }
}
