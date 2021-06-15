<?php

namespace Jankx\TemplateEngine\Data;

class Post
{
    protected $post;
    protected static $aliasPostFields = array(
        'author',
        'date_gmt',
        'status',
        'password',
        'name',
        'modified',
        'modified_gmt',
        'content_filtered',
        'parent',
        'type',
        'mime_type',
    );

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
        if (in_array($name, static::$aliasPostFields)) {
            return true;
        }
        if (property_exists($this->post, $name)) {
            return true;
        }
        return method_exists($this, $name);
    }

    public function __get($name)
    {
        if (in_array($name, static::$aliasPostFields)) {
            $name = sprintf('post_%s', $name);
        }
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
        return get_the_permalink($this->post);
    }

    public function title()
    {
        return apply_filters(
            'the_title',
            $this->__get('post_title')
        );
    }

    public function content()
    {
        return apply_filters(
            'the_content',
            $this->__get('post_content')
        );
    }

    public function excerpt()
    {
        return get_the_excerpt($this->post);
    }

    public function date()
    {
        return get_the_date('', $this->post);
    }
}
