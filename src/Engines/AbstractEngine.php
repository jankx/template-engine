<?php

namespace Jankx\TemplateEngine\Engines;

use Jankx\TemplateEngine\Interfaces\Engine;

abstract class AbstractEngine implements Engine
{
    protected string $templateDirectory;

    public function setDirectory(string $directory)
    {
        $this->templateDirectory = $directory;
    }

    public function getDirectory(): string
    {
        return $this->templateDirectory;
    }
}
