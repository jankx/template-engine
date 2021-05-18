<?php
namespace Jankx\TemplateEngine\Engines;

use Jankx\TemplateEngine\Engine as EngineAbstract;
use League\Plates\Engine;

class Plates extends EngineAbstract
{
    const ENGINE_NAME = 'plates';

    protected $templates;

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
        if (is_child_theme()) {
            $this->templates->addFolder(
                'child',
                sprintf(
                    '%s/%s',
                    rtrim(get_stylesheet_directory(), '/'),
                    $dirName
                ),
                true
            );
        }
        $this->templates->addFolder(
            'theme',
            sprintf(
                '%s/%s',
                rtrim(get_template_directory(), '/'),
                $dirName
            ),
            true
        );
    }

    public function searchTemplate($templates)
    {
        $is_child_theme = is_child_theme();
        foreach((array)$templates as $template) {
            if ($is_child_theme && $this->templates->exists('child::' . $template)) {
                return $this->templates->path('child::' . $template);
            } elseif ($this->templates->exists('theme::' . $template)) {
                return $this->templates->path('theme::' . $template);
            }
            if ($this->templates->exists($template)) {
                return $this->templates->path($template);
            }
        }
        return false;
    }

    public function render($templates, $data = array(), $echo = true)
    {
        $is_child_theme = is_child_theme();
        $content = '';
        foreach((array)$templates as $template) {
            if ($is_child_theme && $this->templates->exists('child::' . $template)) {
                $content = $this->templates->render('child::' . $template, $data);
                break;
            } elseif ($this->templates->exists('theme::' . $template)) {
                $content = $this->templates->render('theme::' . $template, $data);
                break;
            }
            if ($this->templates->exists($template)) {
                $content = $this->templates->render($template, $data);
                break;
            }
        }

        if (!$echo) {
            return $content;
        }
        echo $content;
    }

    public function isRenderDirectly()
    {
        return false;
    }

    public function getEngine()
    {
        return $this->templates;
    }
}
