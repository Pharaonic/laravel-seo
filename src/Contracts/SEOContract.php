<?php

namespace Pharaonic\Laravel\SEO\Contracts;

interface SEOContract
{
    /**
     * Get SEO Data
     *
     * @return array
     */
    public function seo(): array;
}
