<?php

namespace Jankx\TemplateEngine\Interfaces;

interface TemplateEngine
{
    public function render(string|array $templates, array $data = []): string;

    public function getEngine(): Engine;

    public function setEngine(Engine $engine);

    public function searchTemplate(string|array $templates): ?string;
}
