<?php
namespace Jankx\TemplateEngine;

interface EngineInterface
{
    public function getName();

    public function setDefaultTemplateDir($dir);

    public function setDirectoryInTheme($dirName);

    public function searchTemplate($templates);

    public function render($templates, $data = [], $echo = true);

    public function isDirectRender();

    public function getEngine();

    public function registerFunction($funcName, $callable);
}
