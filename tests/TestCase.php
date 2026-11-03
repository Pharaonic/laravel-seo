<?php

namespace Pharaonic\Laravel\SEO\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Pharaonic\Laravel\SEO\SEOServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [SEOServiceProvider::class];
    }

    protected function compile(string $template): string
    {
        return $this->app->make('blade.compiler')->compileString($template);
    }
}
