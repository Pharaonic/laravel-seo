<?php

namespace Pharaonic\Laravel\SEO\Contracts;

interface SEOContract
{
    /**
     * Get SEO Data: element name => value, `open_graph` and `twitter` => attribute => value.
     *
     * @return array<string, mixed>
     */
    public function seo(): array;
}
