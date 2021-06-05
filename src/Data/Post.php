<?php

namespace Jankx\TemplateEngine\Data;

class Post
{
    protected $_post;

    public function __construct($post = null)
    {
        if (is_null($post)) {
            $this->_post = &$GLOBALS['post'];
        } else {
            $this->_post = get_post($post);
        }
    }

    public function __isset($name)
    {
        if (property_exists($this->_post, $name)) {
            return true;
        }
        return method_exists($this, $name);
    }

    public function __get($name)
    {
        if (property_exists($this->_post, $name)) {
            return $this->_post.$name;
        }
        $method = array($this, $name);
        if (is_callable($method)) {
            return call_user_func($method);
        }
    }

    public function thumbnail()
    {
        return new Image();
    }

    public function permalink()
    {
        return get_the_permalink($this->__get('ID'));
    }
}
