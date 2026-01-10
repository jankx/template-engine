<?php

namespace Jankx\TemplateEngine\Interfaces;

interface Engine
{
    public function getName(): string;

    public function render(string $template, array $data = []): string;

    public function getFileExtension(): string;

    public function setDirectory(string $directory);

    public function getDirectory(): string;
}
