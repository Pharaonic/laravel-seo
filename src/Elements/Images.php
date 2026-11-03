<?php

namespace Pharaonic\Laravel\SEO\Elements;

use Pharaonic\Laravel\SEO\Classes\Elements\MultipleElement;

class Images extends MultipleElement
{
    /**
     * Add a new image.
     */
    public function add(string $url): static
    {
        $this->content[] = $url;

        return $this;
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render()
    {
        return '';
    }
}
