<?php

namespace Pharaonic\Laravel\SEO\Classes\Elements;

use Pharaonic\Laravel\SEO\Classes\BaseElement;

abstract class SingleElement extends BaseElement
{
    /**
     * The Element Content.
     */
    protected string|null $content = null;

    /**
     * Get the element value.
     */
    public function get(): string|null
    {
        return $this->content;
    }

    /**
     * Set the element value.
     */
    public function set(string $value): static
    {
        $this->content = $value;

        return $this;
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render()
    {
        if (! $this->content) {
            return '';
        }

        return '<meta name="'.$this->name.'" content="'.$this->content.'">';
    }
}
