<?php
namespace Jankx\Template\Engine;

interface EngineInterface
{
    public function render($templates, $data = [], $echo = true);
}
