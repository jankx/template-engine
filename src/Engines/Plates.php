<?php

namespace Jankx\TemplateEngine\Engines;

if (!defined('ABSPATH')) {
    exit('Cheatin huh?');
}

use League\Plates\Engine;
use Jankx\TemplateEngine\Engine as EngineAbstract;
use Jankx\TemplateEngine\Data\Post;
use Jankx\TemplateEngine\Data\Site;

class Plates extends EngineAbstract
{
    const ENGINE_NAME = 'plates';

    protected $templates;
    protected $childThemeHasTemplates = false;
    protected $themeHasTemplates = false;
    protected $extension = 'php';

    public function getName()
    {
        return static::ENGINE_NAME;
    }

    public function setDefaultTemplateDir($dir)
    {
        $this->templates = new Engine($dir);
    }

    public function setDirectoryInTheme($dirName)
    {
        $childThemeTemplatesDir = sprintf(
            '%s/%s',
            rtrim(get_stylesheet_directory(), '/'),
            $dirName
        );
        if (is_child_theme() && is_dir($childThemeTemplatesDir)) {
            $this->templates->addFolder(
                'child',
                $childThemeTemplatesDir,
                true
            );
            $this->childThemeHasTemplates = true;
        }

        $themeTemplatesDir = sprintf(
            '%s/%s',
            rtrim(get_template_directory(), '/'),
            $dirName
        );
        if (is_dir($themeTemplatesDir)) {
            $this->templates->addFolder(
                'theme',
                $themeTemplatesDir,
                true
            );
            $this->themeHasTemplates = true;
        }
    }

    public function setupEnvironment()
    {
        $this->templates->setFileExtension($this->extension);
        $this->templates->addData(array(
            'post' => new Post(),
            'site' => new Site(),
        ));
    }

    public function generateTemplatesWithFolders($template)
    {
        $templates = array();

        if ($this->childThemeHasTemplates) {
            $templates[] = sprintf('child::%s', $template);
        }
        if ($this->themeHasTemplates) {
            $templates[] = sprintf('theme::%s', $template);
        }
        $templates[] = $template;

        return $templates;
    }

    public function searchTemplate($templates, $returnFullPath = true)
    {
        foreach ((array)$templates as $template) {
            foreach ($this->generateTemplatesWithFolders($template) as $templateWithFolder) {
                if (!$this->templates->exists($templateWithFolder)) {
                    continue;
                }
                if ($returnFullPath) {
                    return $this->templates->path($templateWithFolder);
                }

                return $templateWithFolder;
            }
        }
        return false;
    }




    public function render($templates, $data = array(), $echo = true)
    {
        $content = '';
        $loadingTemplate = $this->searchTemplate($templates, false);
        if (!empty($loadingTemplate)) {
            $content = $this->templates->render($loadingTemplate, $data);
        }

        if (!$echo) {
            return $content;
        }
        echo $content;
    }

    public function isDirectRender()
    {
        return false;
    }

    public function getEngine()
    {
        return $this->templates;
    }

    public function registerFunction($funcName, $callable)
    {
        if ($this->templates && is_callable($callable)) {
            $this->templates->registerFunction(
                $funcName,
                $callable
            );
        }
    }
}
