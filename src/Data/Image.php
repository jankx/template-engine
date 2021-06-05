<?php
namespace Jankx\TemplateEngine\Data;

class Image
{
    protected $post;
    protected $hihi = 'zo';

    public function __construct($post = null) {
        $this->post = $post;
    }

    public function __toString()
    {
    }

    public function __isset($name)
    {
        return true;
    }
}
