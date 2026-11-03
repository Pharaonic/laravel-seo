<?php

namespace Pharaonic\Laravel\SEO\Classes;

use Illuminate\Container\Container;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Pharaonic\Laravel\SEO\Classes\Elements\MultipleElement;
use Pharaonic\Laravel\SEO\Classes\Elements\SingleElement;
use Pharaonic\Laravel\SEO\Contracts\SEOContract;
use Pharaonic\Laravel\SEO\Elements\Alternates;
use Pharaonic\Laravel\SEO\Elements\Author;
use Pharaonic\Laravel\SEO\Elements\Canonical;
use Pharaonic\Laravel\SEO\Elements\Charset;
use Pharaonic\Laravel\SEO\Elements\Copyright;
use Pharaonic\Laravel\SEO\Elements\Description;
use Pharaonic\Laravel\SEO\Elements\Images;
use Pharaonic\Laravel\SEO\Elements\Keywords;
use Pharaonic\Laravel\SEO\Elements\Next;
use Pharaonic\Laravel\SEO\Elements\OpenGraph;
use Pharaonic\Laravel\SEO\Elements\Previous;
use Pharaonic\Laravel\SEO\Elements\Robots;
use Pharaonic\Laravel\SEO\Elements\Title;
use Pharaonic\Laravel\SEO\Elements\Twitter;
use Pharaonic\Laravel\SEO\Elements\Viewport;

/**
 * SEO Elements Manager.
 *
 * @property-read Charset $charset
 * @property-read Viewport $viewport
 * @property-read Title $title
 * @property-read Description $description
 * @property-read Keywords $keywords
 * @property-read Author $author
 * @property-read Robots $robots
 * @property-read Copyright $copyright
 * @property-read Copyright $copyrights Deprecated: use `copyright`.
 * @property-read Next $next
 * @property-read Previous $prev
 * @property-read Alternates $alternates
 * @property-read Images $images
 * @property-read Canonical $canonical
 * @property-read OpenGraph $openGraph
 * @property-read Twitter $twitter
 */
class Manager implements Renderable
{
    /**
     * Other keys of an element in the defaults, model() and property access: `open-graph` and `copyrights` are the deprecated 2.x keys.
     */
    private const ALIASES = [
        'open_graph' => 'openGraph',
        'open-graph' => 'openGraph',
        'copyrights' => 'copyright',
    ];

    /**
     * The configuration repository.
     */
    protected Repository $config;

    /**
     * Default Values of SEO Tags.
     *
     * @var array<mixed>
     */
    protected array $defaults;

    /**
     * SEO Elements List: element classes until loadElements() replaces them with instances.
     *
     * @var array<string, class-string<BaseElement>|BaseElement|OpenGraph|Twitter>
     */
    protected array $elements = [
        'charset' => Charset::class,
        'viewport' => Viewport::class,
        'title' => Title::class,
        'description' => Description::class,
        'keywords' => Keywords::class,
        'author' => Author::class,
        'robots' => Robots::class,
        'copyright' => Copyright::class,
        'next' => Next::class,
        'prev' => Previous::class,
        'alternates' => Alternates::class,
        'images' => Images::class,
        'canonical' => Canonical::class,
    ];

    /**
     * Create a new SEO Manager instance.
     */
    public function __construct(?Repository $config = null)
    {
        $config ??= Container::getInstance()->make('config');

        if (! $config instanceof Repository) {
            throw new InvalidArgumentException('The SEO manager needs the configuration repository.');
        }

        $this->config = $config;

        $this->loadDefaults();
        $this->loadElements();
    }

    /**
     * Get the element instance.
     *
     * @return BaseElement|OpenGraph|Twitter
     *
     * @throws InvalidArgumentException
     */
    public function __get(string $name)
    {
        $element = $this->elements[self::ALIASES[$name] ?? $name] ?? null;

        if (! is_object($element)) {
            throw new InvalidArgumentException("Element $name not found.");
        }

        return $element;
    }

    /**
     * Load Defaults from Config.
     *
     * @return void
     */
    protected function loadDefaults()
    {
        $this->defaults = $this->settings('default');
    }

    /**
     * Load Elements from Config.
     *
     * @return void
     *
     * @throws InvalidArgumentException
     */
    protected function loadElements()
    {
        $elements = array_merge($this->elements, $this->settings('elements'));

        foreach ($elements as $name => $class) {
            if (! is_string($name) || ! is_string($class)
                || (! is_subclass_of($class, SingleElement::class) && ! is_subclass_of($class, MultipleElement::class))) {
                throw new InvalidArgumentException('Class '.(is_string($class) ? $class : get_debug_type($class)).' must extends SingleElement or MultipleElement.');
            }

            $default = $this->defaultOf($name);

            $this->elements[$name] = new $class($name, is_string($default) || is_array($default) ? $default : null);
        }

        $openGraph = $this->defaultOf('openGraph');
        $twitter = $this->defaultOf('twitter');

        $this->elements['openGraph'] = new OpenGraph(is_array($openGraph) ? $openGraph : []);
        $this->elements['twitter'] = new Twitter(is_array($twitter) ? $twitter : []);
    }

    /**
     * Get Elements Directives.
     *
     * @return array<string, \Closure(?string): string>
     */
    final public function getDirectives(): array
    {
        $directives = [];

        foreach ($this->elements as $element) {
            if (is_object($element)) {
                $directives = array_merge($directives, $element->getDirectives());
            }
        }

        return $directives;
    }

    /**
     * Set SEO Elements from Model.
     *
     * @throws InvalidArgumentException
     */
    public function model(Model $model): void
    {
        if (! $model instanceof SEOContract) {
            throw new InvalidArgumentException('Model must implement SEOContract');
        }

        foreach ($model->seo() as $key => $value) {
            $element = $this->elements[self::ALIASES[$key] ?? $key] ?? null;

            if (! is_object($element)) {
                throw new InvalidArgumentException("Element $key not found.");
            }

            if ($value === null) {
                continue;
            }

            if (is_scalar($value)) {
                $value = (string) $value;
            }

            if ($element instanceof OpenGraph || $element instanceof Twitter) {
                foreach ((array) $value as $attribute => $content) {
                    $element->{'set'.Str::studly((string) $attribute)}($content);
                }
            } elseif (is_string($value) || is_array($value)) {
                $element->set($value);
            } else {
                throw new InvalidArgumentException("Element $key expects a string or an array.");
            }
        }
    }

    /**
     * Get the evaluated contents of the object.
     */
    public function render(): string
    {
        $output = '';

        foreach ($this->elements as $element) {
            if (is_object($element) && $result = $element->render()) {
                $output .= trim($result, PHP_EOL).PHP_EOL;
            }
        }

        return trim($output, PHP_EOL);
    }

    /**
     * Get a package setting. A 2.x application published it as `config/Pharaonic/seo.php`.
     *
     * @return array<mixed>
     */
    private function settings(string $key): array
    {
        $value = $this->config->get('Pharaonic.seo.'.$key) ?? $this->config->get('pharaonic.seo.'.$key);

        return is_array($value) ? $value : [];
    }

    /**
     * Get the default of an element from its name or any of its aliases.
     */
    private function defaultOf(string $element): mixed
    {
        foreach ([$element, ...array_keys(self::ALIASES, $element, true)] as $key) {
            if (isset($this->defaults[$key])) {
                return $this->defaults[$key];
            }
        }

        return null;
    }
}
