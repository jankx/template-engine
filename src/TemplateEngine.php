<?php

namespace Jankx\TemplateEngine;

use Jankx\TemplateEngine\Interfaces\TemplateEngine as TemplateEngineInterface;
use Jankx\TemplateEngine\Interfaces\Engine;

class TemplateEngine implements TemplateEngineInterface
{
    protected Engine $engine;
    protected string $defaultPath;
    protected string $themePath = 'templates';
    protected array $cache = [];

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
        $templateKey = is_array($templates) ? implode('|', $templates) : $templates;
        $cacheKey = 'jkx_path_' . md5(sprintf('%s_%s', $templateKey, $this->defaultPath));

        // Attempt to get from local memory cache first
        if (isset($this->cache[$cacheKey])) {
            return $this->cache[$cacheKey];
        }

        // Attempt to get from persistent cache (Redis via WP Object Cache)
        if (function_exists('wp_cache_get')) {
            $cachedPath = wp_cache_get($cacheKey, 'jankx_templates');
            if ($cachedPath !== false) {
                $this->cache[$cacheKey] = $cachedPath;
                return $cachedPath;
            }
        }

        if (is_string($templates)) {
            $templates = [$templates];
        }

        $extension = $this->engine->getFileExtension();
        $searchPaths = $this->getSearchPaths();
        $resolvedPath = null;

        foreach ($templates as $template) {
            $templateFile = $template;
            if (pathinfo($template, PATHINFO_EXTENSION) !== $extension) {
                $templateFile .= '.' . $extension;
            }

            foreach ($searchPaths as $path) {
                $fullPath = sprintf('%s/%s', $path, $templateFile);
                if (file_exists($fullPath)) {
                    $resolvedPath = $fullPath;
                    break 2;
                }
            }
        }

        // Save to cache if found
        if ($resolvedPath) {
            $this->cache[$cacheKey] = $resolvedPath;
            if (function_exists('wp_cache_set')) {
                $expiry = defined('HOUR_IN_SECONDS') ? HOUR_IN_SECONDS : 3600;
                wp_cache_set($cacheKey, $resolvedPath, 'jankx_templates', $expiry);
            }
        }

        return $resolvedPath;
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
        $cacheKey = 'jkx_view_' . md5(serialize([$templates, $data, $this->defaultPath]));
        if (isset($this->cache[$cacheKey])) {
            return $this->cache[$cacheKey];
        }

        if (function_exists('wp_cache_get')) {
            $cachedContent = wp_cache_get($cacheKey, 'jankx_views');
            if ($cachedContent !== false) {
                $this->cache[$cacheKey] = $cachedContent;
                return $cachedContent;
            }
        }

        $templateFile = $this->searchTemplate($templates);
        if (!$templateFile) {
            throw new \Exception(sprintf('Template(s) "%s" not found', is_array($templates) ? implode(', ', $templates) : $templates));
        }

        if (function_exists('apply_filters')) {
            $data = apply_filters('jankx_template_engine_data', $data, $templateFile, $templates);
            $templateFile = apply_filters('jankx_template_engine_file', $templateFile, $templates, $data);
        }

        $content = $this->engine->render($templateFile, $data);
        $this->cache[$cacheKey] = $content;

        if (function_exists('wp_cache_set')) {
            $expiry = defined('HOUR_IN_SECONDS') ? HOUR_IN_SECONDS : 3600;
            wp_cache_set($cacheKey, $content, 'jankx_views', $expiry);
        }

        return $content;
    }
}
