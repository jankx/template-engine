<?php

namespace Jankx\TemplateEngine\Data;

class Post
{
    protected $post;

    public function __construct($post = null)
    {
        if (is_null($post)) {
            $this->post = &$GLOBALS['post'];
        } else {
            $this->post = getpost($post);
        }
    }

    public function __isset($name)
    {
        if (property_exists($this->post, $name)) {
            return true;
        }
        return method_exists($this, $name);
    }

    public function __get($name)
    {
        if (property_exists($this->post, $name)) {
            return $this->post->$name;
        }
        $method = array($this, $name);
        if (is_callable($method)) {
            return call_user_func($method);
        }
    }

    public function has_thumbnail()
    {
        return has_post_thumbnail($this->post);
    }

    public function thumbnail()
    {
        return new Image($this->post);
    }

    public function image()
    {
        return new Image($this->post, 'full');
    }

    public function permalink()
    {
        return get_the_permalink($this->__get('ID'));
    }
}
