<?php
namespace Jankx\Template\Engine;

interface EngineInterface
{
    public function setDefaultTemplateDir($dir);

    public function setDirectoryInTheme($dirName);

    public function render($templates, $data = [], $echo = true);
}
