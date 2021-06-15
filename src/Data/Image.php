<?php
namespace Jankx\TemplateEngine\Data;

class Image
{
    protected $image_id;

    public function __construct($post = null)
    {
        $this->image_id = get_post_thumbnail_id($post);
    }

    public function __toString()
    {
        if ($this->image_id < 1) {
            return '';
        }

        return wp_get_attachment_image($this->image_id);
    }

    public function __isset($name)
    {
        if (property_exists($this, $name)) {
            return true;
        }
        return method_exists($this, $name);
    }

    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        } elseif (($callable = array($this, $name)) && is_callable($callable)) {
            return call_user_func($callable);
        }
    }

    public function src($size = 'thumbnail')
    {
        return wp_get_attachment_image_url(
            $this->image_id,
            $size
        );
    }
}
