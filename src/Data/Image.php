<?php
namespace Jankx\TemplateEngine\Data;

class Image
{
    protected $image_id;
    protected $specific_size;

    public function __construct($post = null, $size = null)
    {
        $this->image_id = get_post_thumbnail_id($post);

        if (is_null($size)) {
            $this->specific_size = $size;
        }
    }

    public function __toString()
    {
        if ($this->image_id < 1) {
            return '';
        }

        if (is_null($this->specific_size)) {
            return wp_get_attachment_image($this->image_id);
        }
        return wp_get_attachment_image($this->_image_id, $this->specific_size);
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

    public function src($size = null)
    {
        if (!is_null($size)) {
            return wp_get_attachment_image_url(
                $this->image_id,
                $size
            );
        }
        if (is_null($this->specific_size)) {
            return wp_get_attachment_image_url($this->image_id);
        }

        return wp_get_attachment_image_url($this->image_id, $this->specific_size);
    }
}
