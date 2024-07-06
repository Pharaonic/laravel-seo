<?php

namespace Pharaonic\Laravel\SEO\Elements;

use Pharaonic\Laravel\SEO\Classes\Elements\MultipleElement;

class Alternates extends MultipleElement
{
    /**
     * Add a new Alternate.
     *
     * @param string $lang
     * @param string $url
     * @return static
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
        if (empty($this->content)) {
            return '';
        }

        return implode(
            PHP_EOL,
            array_map(
                fn ($content) => '<link rel="alternate" hreflang="' . $content['lang'] . '" href="' . $content['url'] . '">',
                $this->content
            )
        );
    }
}
