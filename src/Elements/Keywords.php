<?php

namespace Pharaonic\Laravel\SEO\Elements;

use Pharaonic\Laravel\SEO\Classes\Elements\MultipleElement;

class Keywords extends MultipleElement
{
    /**
     * Add a new Keyword.
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

        return '<meta name="keywords" content="'.e($this->joined()).'">';
    }

    /**
     * Get the rendered content by name, not escaped.
     *
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return empty($this->content) ? [] : [$this->name => $this->joined()];
    }

    /**
     * Get the keywords as the content of one tag.
     */
    private function joined(): string
    {
        return implode(', ', array_map('strval', array_filter($this->content ?? [], 'is_scalar')));
    }
}
