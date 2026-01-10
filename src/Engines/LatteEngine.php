<?php

namespace Jankx\TemplateEngine\Engines;

use Latte\Engine as Latte;

class LatteEngine extends AbstractEngine
{
    protected Latte $latte;

    public function __construct(?string $tempDir = null)
    {
        $this->latte = new Latte();
        if ($tempDir) {
            $this->latte->setTempDirectory($tempDir);
        }

        // Register common WordPress functions
        $wp_functions = [
            'do_action',
            'apply_filters',
            'esc_attr',
            'esc_html',
            'get_permalink',
            'get_the_title',
            'get_the_excerpt',
            'has_post_thumbnail',
            'get_the_post_thumbnail',
            'wp_trim_words',
            '__',
            '_e',
            'get_post_type',
            'get_post_type_object',
        ];
        foreach ($wp_functions as $func) {
            if (function_exists($func)) {
                $this->latte->addFunction($func, $func);
            }
        }
    }

    public function getName(): string
    {
        return 'latte';
    }

    public function getFileExtension(): string
    {
        return 'latte';
    }

    public function render(string $template, array $data = []): string
    {
        return $this->latte->renderToString($template, $data);
    }

    public function getLatte(): Latte
    {
        return $this->latte;
    }
}
