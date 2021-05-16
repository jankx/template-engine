<?php
namespace Jankx\TemplateEngine;

abstract class Engine implements EngineInterface
{
    protected $id;
    protected $args;

    public function __construct($id, $template_directory, $template_location, $args)
    {
        $this->id = $id;
        $this->args = $args;

        $this->setDefaultTemplateDir($template_location);
        $this->setDirectoryInTheme($template_directory);
    }
}
