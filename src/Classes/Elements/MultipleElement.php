<?php

namespace Pharaonic\Laravel\SEO\Classes\Elements;

use Illuminate\Support\Str;
use Pharaonic\Laravel\SEO\Classes\BaseElement;

abstract class MultipleElement extends BaseElement
{
    /**
     * The Element Content.
     *
     * @var array|null
     */
    protected array|null $content = null;

    /**
     * Get the element value.
     *
     * @return array|null
     */
    public function get(): array|null
    {
        return $this->content;
    }

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
     * Remove a value from the element.
     * 
     * @param int $index
     * @return static
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
     *
     * @return array
     */
    public function getDirectives(): array
    {
        $name = Str::camel($this->name);

        return [
            $name => fn ($data) => '<?php seo()->' . $this->name . '->set(' . $data . '); ?>',
            Str::singular($name) => fn ($data) => '<?php seo()->' . $this->name . '->add(' . $data . '); ?>'
        ];
    }
}
