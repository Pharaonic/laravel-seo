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
     */
    protected string|null $site;

    /**
     * The Card Creator.
     */
    protected string|null $creator;

    /**
     * Create a new Twitter Card instance.
     *
     * @param  array<mixed>  $defaults
     */
    public function __construct(array $defaults = [])
    {
        $this->card = is_string($defaults['card'] ?? null) ? $defaults['card'] : null;
        $this->site = is_string($defaults['site'] ?? null) ? $this->handle($defaults['site']) : null;
        $this->creator = is_string($defaults['creator'] ?? null) ? $this->handle($defaults['creator']) : null;
    }

    /**
     * Get the Card Type.
     */
    public function getCard(): ?string
    {
        return $this->card;
    }

    /**
     * Set the Card Type.
     */
    public function setCard(string $card): static
    {
        $this->card = $card;

        return $this;
    }

    /**
     * Get the Card Site.
     */
    public function getSite(): ?string
    {
        return $this->site;
    }

    /**
     * Set the Card Site.
     */
    public function setSite(string $site): static
    {
        $this->site = $this->handle($site);

        return $this;
    }

    /**
     * Get the Card Creator.
     */
    public function getCreator(): ?string
    {
        return $this->creator;
    }

    /**
     * Set the Card Creator.
     */
    public function setCreator(string $creator): static
    {
        $this->creator = $this->handle($creator);

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
            $output .= $this->tag('twitter:card', $this->card);
        }

        if ($this->site) {
            $output .= $this->tag('twitter:site', $this->site);
        }

        if ($this->creator) {
            $output .= $this->tag('twitter:creator', $this->creator);
        }

        if ($title = seo()->title->get()) {
            $output .= $this->tag('twitter:title', $title);
        }

        if ($description = seo()->description->get()) {
            $output .= $this->tag('twitter:description', $description);
        }

        // A Twitter card takes a single image.
        foreach (seo()->images->get() ?? [] as $image) {
            if (is_string($image)) {
                $output .= $this->tag('twitter:image', $image);

                break;
            }
        }

        return $output;
    }

    /**
     * Prefix a Twitter username with a single `@`.
     */
    private function handle(string $username): string
    {
        return '@'.ltrim($username, '@');
    }

    /**
     * Render one Twitter card tag.
     */
    private function tag(string $name, string $content): string
    {
        return '<meta name="'.$name.'" content="'.e($content).'">'.PHP_EOL;
    }

    /**
     * Get the element directives.
     *
     * @return array<string, \Closure(?string): string>
     */
    public function getDirectives(): array
    {
        return [
            'twitterCard' => fn (?string $data) => '<?php seo()->twitter->setCard('.$data.'); ?>',
            'twitterSite' => fn (?string $data) => '<?php seo()->twitter->setSite('.$data.'); ?>',
            'twitterCreator' => fn (?string $data) => '<?php seo()->twitter->setCreator('.$data.'); ?>',
        ];
    }
}
