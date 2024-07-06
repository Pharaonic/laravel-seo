<?php

namespace Pharaonic\Laravel\SEO\Elements;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\URL;

class OpenGraph implements Renderable
{
    /**
     * The Card type.
     *
     * @var string
     */
    protected string|null $type;

    /**
     * The Card Site Name.
     *
     * @var string|null
     */
    protected string|null $siteName;

    /**
     * Create a new Open-Graph Card instance.
     *
     * @param array $defaults
     */
    public function __construct(array $defaults = [])
    {
        $this->type = $defaults['type'] ?? null;
        $this->siteName = $defaults['site_name'] ?? null;
    }

    /**
     * Get the Card Type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Set the Card Type.
     *
     * @param string $type
     * @return static
     */
    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the Card Site Name.
     *
     * @return string
     */
    public function getSiteName(): string
    {
        return $this->siteName;
    }

    /**
     * Set the Card Site Name.
     *
     * @param string $site_name
     * @return static
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
        $output = '<meta name="og:url" content="' . URL::current() . '">' . PHP_EOL .
            '<meta name="og:locale" content="' . app()->getLocale() . '">' . PHP_EOL;

        if ($alternates = seo()->alternates?->get()) {
            foreach ($alternates as $alternate) {
                $output .= '<meta name="og:locale:alternate" content="' . str_replace('.', '_', $alternate['lang']) . '">' . PHP_EOL;
            }
        }

        if ($this->type) {
            $output .= '<meta name="og:type" content="' . $this->type . '">' . PHP_EOL;
        }

        if ($this->siteName) {
            $output .= '<meta name="og:site_name" content="' . $this->siteName . '">' . PHP_EOL;
        }

        if ($title = seo()->title?->get()) {
            $output .= '<meta name="og:title" content="' . $title . '">' . PHP_EOL;
        }

        if ($description = seo()->description?->get()) {
            $output .= '<meta name="og:description" content="' . $description . '">' . PHP_EOL;
        }

        if ($images = seo()->images?->get()) {
            foreach ($images as $image) {
                $output .= '<meta name="og:image" content="' . $image . '">' . PHP_EOL;
            }
        }

        return $output;
    }

    /**
     * Get the element directives.
     *
     * @return array
     */
    public function getDirectives(): array
    {
        return [
            'openGraphType' => fn ($data) => '<?php seo()->openGraph->setType(' . $data . '); ?>',
            'openGraphSiteName' => fn ($data) => '<?php seo()->openGraph->setSiteName(' . $data . '); ?>',
        ];
    }
}
