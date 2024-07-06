<?php

namespace Pharaonic\Laravel\SEO\Elements;

use Illuminate\Contracts\Support\Renderable;

class Twitter implements Renderable
{
    /**
     * The Card Type.
     *
     * @var string
     */
    protected string|null $card;

    /**
     * The Card Site.
     *
     * @var string|null
     */
    protected string|null $site;

    /**
     * The Card Creator.
     *
     * @var string|null
     */
    protected string|null $creator;

    /**
     * Create a new Twitter Card instance.
     *
     * @param array $defaults
     */
    public function __construct(array $defaults = [])
    {
        $this->card = $defaults['card'] ?? null;
        $this->site = $defaults['site'] ?? null;
        $this->creator = $defaults['creator'] ?? null;
    }

    /**
     * Get the Card Type.
     *
     * @return string
     */
    public function getCard(): string
    {
        return $this->card;
    }

    /**
     * Set the Card Type.
     *
     * @param string $card
     * @return static
     */
    public function setCard(string $card): static
    {
        $this->card = $card;

        return $this;
    }

    /**
     * Get the Card Site.
     *
     * @return string
     */
    public function getSite(): string
    {
        return $this->site;
    }

    /**
     * Set the Card Site.
     *
     * @param string $site
     * @return static
     */
    public function setSite(string $site): static
    {
        $this->site = '@' . $site;

        return $this;
    }

    /**
     * Get the Card Creator.
     *
     * @return string
     */
    public function getCreator(): string
    {
        return $this->creator;
    }

    /**
     * Set the Card Creator.
     *
     * @param string $creator
     * @return static
     */
    public function setCreator(string $creator): static
    {
        $this->creator = '@' . $creator;

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

        if ($this->card) {
            $output .= '<meta name="twitter:card" content="' . $this->card . '">' . PHP_EOL;
        }

        if ($this->site) {
            $output .= '<meta name="twitter:site" content="' . $this->site . '">' . PHP_EOL;
        }

        if ($this->creator) {
            $output .= '<meta name="twitter:creator" content="' . $this->creator . '">' . PHP_EOL;
        }

        if ($title = seo()->title?->get()) {
            $output .= '<meta name="twitter:title" content="' . $title . '">' . PHP_EOL;
        }

        if ($description = seo()->description?->get()) {
            $output .= '<meta name="twitter:description" content="' . $description . '">' . PHP_EOL;
        }

        if ($images = seo()->images?->get()) {
            if (count($images) == 1) {
                $output .= '<meta name="twitter:image" content="' . $images[0] . '">' . PHP_EOL;
            } else {
                foreach ($images as $index => $image) {
                    $output .= '<meta name="twitter:image' . $index . '" content="' . $image . '">' . PHP_EOL;
                }
            }
        }

        return $output;
    }
}
