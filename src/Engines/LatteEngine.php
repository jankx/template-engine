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

        if (defined('WP_DEBUG') && WP_DEBUG) {
            $this->latte->setAutoRefresh(true);
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

            'get_post_type',
            'get_post_type_object',
        ];
        foreach ($wp_functions as $func) {
            if (function_exists($func)) {
                $this->latte->addFunction($func, $func);
            } else {
                // Fallback for translation functions if they don't exist (e.g. during some CLI or early boot)
                if (in_array($func, ['__', '_e'])) {
                    $this->latte->addFunction($func, function ($text, $domain = 'default') use ($func) {
                        if ($func === '_e') {
                            echo $text;
                        }
                        return $text;
                    });
                }
            }
        }

        // Force register __ and _e using closures to ensure they work and avoid "Invalid function name" issues with string callbacks
        // We check if the function is already registered to prevent errors, although in a fresh engine it shouldn't be.
        $functions_to_register = [
            '__' => function ($text, $domain = 'default') {
                return __($text, $domain);
            },
            '_e' => function ($text, $domain = 'default') {
                _e($text, $domain);
            },
            '_x' => function ($text, $context, $domain = 'default') {
                return _x($text, $context, $domain);
            },
            'esc_html__' => function ($text, $domain = 'default') {
                return esc_html__($text, $domain);
            },
            'esc_attr__' => function ($text, $domain = 'default') {
                return esc_attr__($text, $domain);
            },
            'translate' => function ($text, $domain = 'default') {
                return translate($text, $domain);
            },
        ];

        foreach ($functions_to_register as $name => $callback) {
            try {
                // In Latte 3, we can just add it. If it exists, it throws.
                // We wrap in try-catch to be safe if some other integration added it.
                $this->latte->addFunction($name, $callback);
            } catch (\Exception $e) {
                // Ignore registration errors for duplicates
            }
        }

        // Register as filters as well for pipe usage support

        try {
            $this->latte->addFilter('__', function ($text, $domain = 'default') {
                return __($text, $domain);
            });
        } catch (\Exception $e) {
        }

        try {
            $this->latte->addFilter('json', function ($data) {
                return json_encode($data);
            });
        } catch (\Exception $e) {
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
