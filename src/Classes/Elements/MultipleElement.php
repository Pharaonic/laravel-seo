<?php

namespace Pharaonic\Laravel\SEO\Classes\Elements;

use Illuminate\Support\Str;
use Pharaonic\Laravel\SEO\Classes\BaseElement;

abstract class MultipleElement extends BaseElement
{
    /**
     * The Element Content.
     *
     * @var array<mixed>|null
     */
    protected array|null $content = null;

    /**
     * Get the element value.
     *
     * @return array<mixed>|null
     */
    public function get(): array|null
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
     *
     * @param  array<mixed>|string  $value
     */
    public function set(array|string $value): static
    {
        $this->content = is_string($value) ? explode(', ', $value) : $value;

        return $this;
    }

    /**
     * Remove a value from the element.
     */
    public function remove(int $index): static
    {
        if (isset($this->content[$index])) {
            unset($this->content[$index]);
            $this->content = array_values($this->content);
        }

        return $this;
    }

    /**
     * Get the element directives.
     *
     * @return array<string, \Closure(?string): string>
     */
    public function getDirectives(): array
    {
        $name = Str::camel($this->name);

        return [
            $name => fn (?string $data) => '<?php seo()->'.$this->name.'->set('.$data.'); ?>',
            Str::singular($name) => fn (?string $data) => '<?php seo()->'.$this->name.'->add('.$data.'); ?>',
        ];
    }
}
