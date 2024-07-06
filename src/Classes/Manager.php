<?php

namespace Pharaonic\Laravel\SEO\Classes;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Model;
use Pharaonic\Laravel\SEO\Classes\Elements\{
    MultipleElement,
    SingleElement,
};
use Pharaonic\Laravel\SEO\Elements\{
    Alternates,
    Author,
    Canonical,
    Charset,
    Copyrights,
    Description,
    Images,
    Keywords,
    Next,
    OpenGraph,
    Previous,
    Robots,
    Title,
    Twitter,
    Viewport,
};

/**
 * SEO Elements Manager.
 * 
 * @version 2.0.0
 * @package Pharaonic\Laravel\SEO
 */
class Manager implements Renderable
{
    /**
     * Default Values of SEO Tags.
     *
     * @var array
     */
    protected array $defaults;

    /**
     * SEO Elements List.
     *
     * @var array
     */
    protected array $elements = [
        'charset'       => Charset::class,
        'viewport'      => Viewport::class,
        'title'         => Title::class,
        'description'   => Description::class,
        'keywords'      => Keywords::class,
        'author'        => Author::class,
        'robots'        => Robots::class,
        'copyrights'    => Copyrights::class,
        'next'          => Next::class,
        'prev'          => Previous::class,
        'alternates'    => Alternates::class,
        'images'        => Images::class,
        'canonical'     => Canonical::class,
    ];

    /**
     * Create a new SEO Manager instance.
     */
    public function __construct()
    {
        $this->loadDefaults();
        $this->loadElements();
    }

    /**
     * Get the element instance.
     *
     * @param string $name
     * @return ElementContract
     * @throws \Exception
     */
    public function __get(string $name)
    {
        if (!in_array($name, array_keys($this->elements))) {
            throw new \Exception("Element $name not found.");
        }

        return $this->elements[$name] ?? null;
    }

    /**
     * Load Defaults from Config.
     *
     * @return void
     */
    protected function loadDefaults()
    {
        $this->defaults = config('Pharaonic.seo.default', []);
    }

    /**
     * Load Elements from Config.
     *
     * @return void
     * @throws \Exception
     */
    protected function loadElements()
    {
        $this->elements = array_merge($this->elements, config('Pharaonic.seo.elements', []));

        foreach ($this->elements as $name => $class) {
            if (!is_subclass_of($class, SingleElement::class) && !is_subclass_of($class, MultipleElement::class)) {
                throw new \Exception("Class $class must extends SingleElement or MultipleElement.");
            }

            $this->elements[$name] = new $class($name, $this->defaults[$name] ?? null);
        }

        $this->elements['openGraph'] = new OpenGraph($this->defaults['open-graph'] ?? []);
        $this->elements['twitter'] = new Twitter($this->defaults['twitter'] ?? []);
    }

    /**
     * Get Elements Directives.
     *
     * @return array
     */
    final public function getDirectives(): array
    {
        $directives = [];
        $list = array_map(fn ($element) => $element->getDirectives(), $this->elements);
        array_walk_recursive($list, function ($value, $key) use (&$directives) {
            $directives[$key] = $value;
        });

        return $directives;
    }

    /**
     * Set SEO Elements from Model.
     *
     * @param Model $model
     * @return void
     */
    public function model(Model $model): void
    {
        if (!($model instanceof \Pharaonic\Laravel\SEO\Contracts\SEOContract)) {
            throw new \Exception('Model must implement SEOContract');
        }

        foreach ($model->seo() as $key => $value) {
            if (isset($this->elements[$key])) {
                if (in_array($key, ['open-graph', 'twitter'])) {
                    foreach ($value as $k => $v) {
                        $this->elements[$key]->{'set' . ucfirst($k)}($v);
                    }
                } else {
                    $this->elements[$key]->set($value);
                }
            } else {
                throw new \Exception("Element $key not found.");
            }
        }
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render(): string
    {
        $output = '';

        foreach ($this->elements as $element) {
            if ($result = $element->render()) {
                $output .= trim($result, PHP_EOL) . PHP_EOL;
            }
        }

        return trim($output, PHP_EOL);
    }
}
