<?php
namespace Jankx\Template\Engine;

class WordPress extends Engine
{
    protected $defaultTemplateDir;

    public function __construct($args)
    {
        $this->setDefaultTemplateDir($args['template_location']);
    }

    public function setDefaultTemplateDir($dir)
    {
        if (!file_exists($dir)) {
            throw new \Error(sprintf('Directory %s is not exists', $dir));
        }
        $this->defaultTemplateDir = $dir;
    }
}
