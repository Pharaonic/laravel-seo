<?php

namespace Pharaonic\Laravel\SEO\Classes\Elements;

use Illuminate\Support\Str;
use Pharaonic\Laravel\SEO\Classes\BaseElement;

abstract class MultipleElement extends BaseElement
{
    /**
     * The Element Content.
     */
    protected array|null $content = null;

    /**
     * Get the element value.
     */
    public function get(): array|null
    {
        return $this->content;
    }

    /**
     * Set the element value.
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
        }

        return $this;
    }

    /**
     * Get the element directives.
     */
    public function getDirectives(): array
    {
        $name = Str::camel($this->name);

        return [
            $name => fn ($data) => '<?php seo()->'.$this->name.'->set('.$data.'); ?>',
            Str::singular($name) => fn ($data) => '<?php seo()->'.$this->name.'->add('.$data.'); ?>',
        ];
    }
}
