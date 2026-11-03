<?php

use Illuminate\Container\Container;
use Pharaonic\Laravel\SEO\Classes\Manager;

if (! function_exists('seo')) {
    /**
     * Get the SEO instance.
     */
    function seo(): Manager
    {
        $seo = Container::getInstance()->make('pharaonic-seo');

        if (! $seo instanceof Manager) {
            throw new UnexpectedValueException('pharaonic-seo must resolve to '.Manager::class.'.');
        }

        return $seo;
    }
}
