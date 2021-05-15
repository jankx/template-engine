<?php
namespace Jankx\Template\Engine;

interface EngineInterface
{
    public function getName();

    public function setDefaultTemplateDir($dir);

    public function setDirectoryInTheme($dirName);

    public function searchTemplate($templates);

    public function render($templates, $data = [], $echo = true);
}
