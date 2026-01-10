<?php

namespace Jankx\TemplateEngine;

use Jankx\TemplateEngine\Interfaces\TemplateEngine as TemplateEngineInterface;
use Jankx\TemplateEngine\Interfaces\Engine;

class TemplateEngine implements TemplateEngineInterface
{
    protected Engine $engine;
    protected string $defaultPath;
    protected string $themePath = 'templates';

    public function __construct(Engine $engine, string $defaultPath)
    {
        $this->engine = $engine;
        $this->defaultPath = rtrim($defaultPath, '/\\');
    }

    public function getEngine(): Engine
    {
        return $this->engine;
    }

    public function setEngine(Engine $engine)
    {
        $this->engine = $engine;
    }

    public function setThemePath(string $themePath)
    {
        $this->themePath = trim($themePath, '/\\');
    }

    public function searchTemplate(string|array $templates): ?string
    {
        if (is_string($templates)) {
            $templates = [$templates];
        }

        $extension = $this->engine->getFileExtension();
        $searchPaths = $this->getSearchPaths();

        foreach ($templates as $template) {
            $templateFile = $template;
            if (pathinfo($template, PATHINFO_EXTENSION) !== $extension) {
                $templateFile .= '.' . $extension;
            }

            foreach ($searchPaths as $path) {
                $fullPath = sprintf('%s/%s', $path, $templateFile);
                if (file_exists($fullPath)) {
                    return $fullPath;
                }
            }
        }

        return null;
    }

    protected function getSearchPaths(): array
    {
        $paths = [];

        // 1. Child Theme Path
        if (function_exists('get_stylesheet_directory')) {
            $paths[] = sprintf('%s/%s', get_stylesheet_directory(), $this->themePath);
        }

        // 2. Parent Theme Path
        if (function_exists('get_template_directory')) {
            $paths[] = sprintf('%s/%s', get_template_directory(), $this->themePath);
        }

        // 3. Default Path
        $paths[] = $this->defaultPath;

        $paths = array_unique($paths);

        if (function_exists('apply_filters')) {
            $paths = apply_filters('jankx_template_search_paths', $paths, $this->defaultPath, $this->themePath);
        }

        return $paths;
    }

    public function render(string|array $templates, array $data = []): string
    {
        $templateFile = $this->searchTemplate($templates);
        if (!$templateFile) {
            throw new \Exception(sprintf('Template(s) "%s" not found', is_array($templates) ? implode(', ', $templates) : $templates));
        }

        if (function_exists('apply_filters')) {
            $data = apply_filters('jankx_template_engine_data', $data, $templateFile, $templates);
            $templateFile = apply_filters('jankx_template_engine_file', $templateFile, $templates, $data);
        }

        return $this->engine->render($templateFile, $data);
    }
}
