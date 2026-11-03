<?php

namespace Pharaonic\Laravel\SEO\Tests\Feature;

use Illuminate\Support\ServiceProvider;
use Pharaonic\Laravel\SEO\Classes\Manager;
use Pharaonic\Laravel\SEO\SEOServiceProvider;
use Pharaonic\Laravel\SEO\Tests\TestCase;

class ServiceProviderTest extends TestCase
{
    public function test_it_registers_the_service_provider(): void
    {
        $this->assertNotEmpty($this->app->getProviders(SEOServiceProvider::class));
    }

    public function test_it_merges_the_configuration_under_pharaonic_seo(): void
    {
        $this->assertSame('UTF-8', config('pharaonic.seo.default.charset'));
        $this->assertSame(['type' => 'website'], config('pharaonic.seo.default.open_graph'));
        $this->assertSame([], config('pharaonic.seo.elements'));
    }

    public function test_it_binds_one_manager(): void
    {
        $this->assertInstanceOf(Manager::class, seo());
        $this->assertSame(seo(), $this->app->make('pharaonic-seo'));
    }

    public function test_it_publishes_the_configuration_with_the_pharaonic_tags(): void
    {
        $source = realpath(__DIR__.'/../../config/seo.php');

        foreach (['pharaonic', 'laravel-seo', 'pharaonic-config', 'seo-config'] as $tag) {
            $paths = ServiceProvider::pathsToPublish(SEOServiceProvider::class, $tag);

            $this->assertSame([$this->app->configPath('pharaonic/seo.php')], array_values($paths), $tag);
            $this->assertSame($source, realpath((string) array_key_first($paths)), $tag);
        }
    }

    public function test_it_registers_the_canonical_directives(): void
    {
        $this->assertSame('<?php echo seo()->render(); ?>', $this->compile('@seo'));
        $this->assertSame("<?php seo()->title->set('Home'); ?>", $this->compile("@seoTitle('Home')"));
        $this->assertSame("<?php seo()->keywords->set('a, b'); ?>", $this->compile("@seoKeywords('a, b')"));
        $this->assertSame("<?php seo()->keywords->add('c'); ?>", $this->compile("@seoKeyword('c')"));
        $this->assertSame("<?php seo()->alternates->add('ar', '/ar'); ?>", $this->compile("@seoAlternate('ar', '/ar')"));
        $this->assertSame("<?php seo()->images->add('/a.png'); ?>", $this->compile("@seoImage('/a.png')"));
        $this->assertSame("<?php seo()->prev->set('/1'); ?>", $this->compile("@seoPrev('/1')"));
        $this->assertSame("<?php seo()->openGraph->setType('article'); ?>", $this->compile("@seoOpenGraphType('article')"));
        $this->assertSame("<?php seo()->openGraph->setSiteName('Site'); ?>", $this->compile("@seoOpenGraphSiteName('Site')"));
        $this->assertSame("<?php seo()->twitter->setCard('app'); ?>", $this->compile("@seoTwitterCard('app')"));
        $this->assertSame("<?php seo()->twitter->setSite('site'); ?>", $this->compile("@seoTwitterSite('site')"));
        $this->assertSame("<?php seo()->twitter->setCreator('me'); ?>", $this->compile("@seoTwitterCreator('me')"));
    }
}
