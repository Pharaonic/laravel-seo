<?php

if (!function_exists('seo')) {
    /**
     * Get the SEO instance.
     *
     * @return Pharaonic\Laravel\SEO\Classes\Manager
     */
    function seo()
    {
        return app('pharaonic-seo');
    }
}
