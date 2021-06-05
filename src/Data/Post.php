<?php

namespace Jankx\TemplateEngine\Data;

class Post
{
    protected $_post;

    public function __construct()
    {
        $this->_post = &$GLOBALS['post'];
    }

    public function __isset($name)
    {
        return property_exists($this->_post, $name);
    }

    public function __get($name)
    {
        return $this->_post.$name;
    }

    public function get_thumbnail()
    {
    }

    public function permalink()
    {
    }
}
