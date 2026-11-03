<?php

namespace Pharaonic\Laravel\SEO\Classes;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Str;

abstract class BaseElement implements Renderable
{
    /**
     * The Element Name.
     */
    protected string $name;

    /**
     * Create a new Element instance.
     */
    final public function __construct(string $name, string|array|null $content)
    {
        $this->name = $name;

        if ($content) {
            call_user_func_array([$this, 'set'], [$content]);
        }
    }

    /**
     * Get the element name.
     */
    final public function getName(): string
    {
        return $this->name;
    }

    /**
     * Clear the element content.
     */
    final public function clear(): static
    {
        $this->{'content'} = null;

        return $this;
    }

    /**
     * Get the element directives.
     */
    public function getDirectives(): array
    {
        return [
            Str::camel($this->name) => fn ($data) => '<?php seo()->'.$this->name.'->set('.$data.'); ?>',
        ];
    }
}
