<?php

namespace Pharaonic\Laravel\SEO\Elements;

use Pharaonic\Laravel\SEO\Classes\Elements\MultipleElement;

class Keywords extends MultipleElement
{
    /**
     * Set the element value.
     *
     * @param array|string $value
     * @return static
     */
    public function set(array|string $value): static
    {
        $this->content = is_string($value) ? explode(', ', $value) : $value;

        return $this;
    }

    /**
     * Add a new Keyword.
     *
     * @param string $keyword
     * @return static
     */
    public function add(string $keyword): static
    {
        $this->content[] = $keyword;

        return $this;
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render()
    {
        if (empty($this->content)) {
            return '';
        }

        return '<meta name="keywords" content="' . implode(', ', $this->content) . '">';
    }
}
