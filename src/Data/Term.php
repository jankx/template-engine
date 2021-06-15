<?php
namespace Jankx\TemplateEngine\Data;

use WP_Term;

class Term
{
    protected $term;

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
        return $this->term->$name;
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
}
