<?php

namespace Pharaonic\Laravel\SEO\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Pharaonic\Laravel\SEO\SEOServiceProvider;

class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [SEOServiceProvider::class];
    }
}