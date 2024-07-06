<?php

namespace Pharaonic\Laravel\SEO\Elements;

use Pharaonic\Laravel\SEO\Classes\Elements\SingleElement;

class Canonical extends SingleElement
{
    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render()
    {
        if (!$this->content) {
            return '';
        }

        return '<link rel="canonical" href="' . $this->content . '">';
    }
}
