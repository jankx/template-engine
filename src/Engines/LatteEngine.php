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
