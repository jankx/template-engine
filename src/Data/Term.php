<?php
namespace Jankx\TemplateEngine\Data;

use WP_Term;

class Term
{
    protected $term;
    protected $image_id;

    public function __construct($term)
    {
        $this->term = &$term;
    }

    public function __isset($name)
    {
        return isset($this->term->$name);
    }

    public function __get($name)
    {
        if (!$this->term) {
            return;
        }

        if ($name === 'ID') {
            return $this->term->term_id;
        }

        return apply_filters(
            'jankx/engine/data/term/' . $name,
            $this->term->$name
        );
    }

    public function __toString()
    {
        if (!is_a($this->term, WP_Term::class)) {
            return '';
        }

        return sprintf(
            '<a href="%1$s" title="%2$s">%2$s</a>',
            get_term_link($this->term),
            $this->term->name
        );
    }

    public function link()
    {
        return get_term_link($this->term);
    }

    public function has_thumbnail($meta_key)
    {
        if (is_null($this->image_id)) {
            $this->image_id = intval(get_term_meta($this->ID, $meta_key, true));
        }

        return $this->image_id > 0;
    }

    public function image($size = 'thumbnail', $meta_key)
    {
        if (is_null($this->image_id)) {
            $this->image_id = intval(get_term_meta($this->ID, $meta_key, true));
        }
        return new Image($this->image_id, $size);
    }
}
