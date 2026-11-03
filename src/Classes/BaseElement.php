<?php

namespace Pharaonic\Laravel\SEO\Classes;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Str;

/**
 * @method static set(string|array<mixed> $value)
 */
abstract class BaseElement implements Renderable
{
    /**
     * The Element Name.
     */
    protected string $name;

    /**
     * Create a new Element instance.
     *
     * @param  string|array<mixed>|null  $content
     */
    final public function __construct(string $name, string|array|null $content)
    {
        $this->name = $name;

        if ($content) {
            $this->set($content);
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
     * Get the element directives.
     *
     * @return array<string, \Closure(?string): string>
     */
    public function getDirectives(): array
    {
        return [
            Str::camel($this->name) => fn (?string $data) => '<?php seo()->'.$this->name.'->set('.$data.'); ?>',
        ];
    }
}
