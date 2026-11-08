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
     * Clear the element content.
     */
    final public function clear(): static
    {
        $this->content = null;

        return $this;
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

        return '<meta name="'.e($this->name).'" content="'.e($this->content).'">';
    }

    /**
     * Get the rendered content by name, not escaped.
     *
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return $this->content ? [$this->name => $this->content] : [];
    }
}
