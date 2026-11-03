<?php

namespace Pharaonic\Laravel\SEO\Elements;

use Pharaonic\Laravel\SEO\Classes\Elements\MultipleElement;

class Alternates extends MultipleElement
{
    /**
     * Add a new Alternate.
     */
    public function add(string $lang, string $url): static
    {
        $this->content[] = [
            'lang' => $lang,
            'url' => $url,
        ];

        return $this;
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render()
    {
        $links = [];

        foreach ($this->content ?? [] as $alternate) {
            if (is_array($alternate) && is_string($alternate['lang'] ?? null) && is_string($alternate['url'] ?? null)) {
                $links[] = '<link rel="alternate" hreflang="'.e($alternate['lang']).'" href="'.e($alternate['url']).'">';
            }
        }

        return implode(PHP_EOL, $links);
    }
}
