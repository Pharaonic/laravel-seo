<?php

namespace Pharaonic\Laravel\SEO\Elements;

use Illuminate\Container\Container;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Contracts\Support\Renderable;

class OpenGraph implements Renderable
{
    /**
     * The Card type.
     */
    protected ?string $type;

    /**
     * The Card Site Name.
     */
    protected ?string $siteName;

    /**
     * Create a new Open-Graph Card instance.
     *
     * @param  array<mixed>  $defaults
     */
    public function __construct(array $defaults = [])
    {
        $this->type = is_string($defaults['type'] ?? null) ? $defaults['type'] : null;
        $this->siteName = is_string($defaults['site_name'] ?? null) ? $defaults['site_name'] : null;
    }

    /**
     * Get the Card Type.
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Set the Card Type.
     */
    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the Card Site Name.
     */
    public function getSiteName(): ?string
    {
        return $this->siteName;
    }

    /**
     * Set the Card Site Name.
     */
    public function setSiteName(string $siteName): static
    {
        $this->siteName = $siteName;

        return $this;
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render()
    {
        $app = Container::getInstance();
        $url = $app->make('url');

        $output = $this->tag('og:url', $url instanceof UrlGenerator ? $url->current() : '')
            .$this->tag('og:locale', $app instanceof Application ? $app->getLocale() : '');

        foreach (seo()->alternates->get() ?? [] as $alternate) {
            if (is_array($alternate) && is_string($alternate['lang'] ?? null)) {
                $output .= $this->tag('og:locale:alternate', str_replace('.', '_', $alternate['lang']));
            }
        }

        if ($this->type) {
            $output .= $this->tag('og:type', $this->type);
        }

        if ($this->siteName) {
            $output .= $this->tag('og:site_name', $this->siteName);
        }

        if ($title = seo()->title->get()) {
            $output .= $this->tag('og:title', $title);
        }

        if ($description = seo()->description->get()) {
            $output .= $this->tag('og:description', $description);
        }

        foreach (seo()->images->get() ?? [] as $image) {
            if (is_string($image)) {
                $output .= $this->tag('og:image', $image);
            }
        }

        return $output;
    }

    /**
     * Render one Open Graph tag.
     */
    private function tag(string $property, string $content): string
    {
        return '<meta property="'.$property.'" content="'.e($content).'">'.PHP_EOL;
    }

    /**
     * Get the element directives.
     *
     * @return array<string, \Closure(?string): string>
     */
    public function getDirectives(): array
    {
        return [
            'openGraphType' => fn (?string $data) => '<?php seo()->openGraph->setType('.$data.'); ?>',
            'openGraphSiteName' => fn (?string $data) => '<?php seo()->openGraph->setSiteName('.$data.'); ?>',
        ];
    }
}
