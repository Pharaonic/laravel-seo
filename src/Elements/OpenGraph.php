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
        $output = '';

        foreach ($this->toArray() as $property => $content) {
            foreach ((array) $content as $value) {
                $output .= $this->tag($property, $value);
            }
        }

        return $output;
    }

    /**
     * Get the rendered content by property, not escaped. `og:locale:alternate` and `og:image` are lists.
     *
     * @return array<string, string|list<string>>
     */
    public function toArray(): array
    {
        $app = Container::getInstance();
        $url = $app->make('url');

        $tags = [
            'og:url' => $url instanceof UrlGenerator ? $url->current() : '',
            'og:locale' => $app instanceof Application ? $app->getLocale() : '',
        ];

        $locales = [];

        foreach (seo()->alternates->get() ?? [] as $alternate) {
            if (is_array($alternate) && is_string($alternate['lang'] ?? null)) {
                $locales[] = str_replace('.', '_', $alternate['lang']);
            }
        }

        if ($locales) {
            $tags['og:locale:alternate'] = $locales;
        }

        if ($this->type) {
            $tags['og:type'] = $this->type;
        }

        if ($this->siteName) {
            $tags['og:site_name'] = $this->siteName;
        }

        if ($title = seo()->title->get()) {
            $tags['og:title'] = $title;
        }

        if ($description = seo()->description->get()) {
            $tags['og:description'] = $description;
        }

        $images = array_values(array_filter(seo()->images->get() ?? [], 'is_string'));

        if ($images) {
            $tags['og:image'] = $images;
        }

        return $tags;
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
