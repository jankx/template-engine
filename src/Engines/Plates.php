<?php
namespace Jankx\TemplateEngine\Engines;

use Jankx\TemplateEngine\Engine as EngineAbstract;
use League\Plates\Engine;

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

    public function searchTemplate($templates)
    {
        foreach ((array)$templates as $template) {
            foreach ($this->generateTemplatesWithFolders($template) as $templateWidthFolder) {
                if (!$this->templates->exists($templateWidthFolder)) {
                    continue;
                }
                return $this->templates->path($templateWidthFolder);
            }
        }
        return false;
    }

    public function render($templates, $data = array(), $echo = true)
    {
        $content = '';
        foreach ((array)$templates as $template) {
            foreach ($this->generateTemplatesWithFolders($template) as $templateWidthFolder) {
                if (!$this->templates->exists($templateWidthFolder)) {
                    continue;
                }
                $content = $this->templates->render($templateWidthFolder, $data);
                break;
            }

            if ($content) {
                break;
            }
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
}
