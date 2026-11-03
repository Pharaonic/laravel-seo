<?php

namespace Pharaonic\Laravel\SEO\Tests\Compatibility;

use Illuminate\Support\ServiceProvider;
use Pharaonic\Laravel\SEO\Classes\Manager;
use Pharaonic\Laravel\SEO\SEOServiceProvider;
use Pharaonic\Laravel\SEO\Tests\Models\Article;
use Pharaonic\Laravel\SEO\Tests\TestCase;

/**
 * The deprecated 2.x names keep working until the first line that supports PHP 8.6.
 */
class DeprecatedNamesTest extends TestCase
{
    public function test_the_2x_directives_compile_like_the_canonical_ones(): void
    {
        $directives = [
            'charset', 'viewport', 'title', 'description', 'keywords', 'keyword', 'author', 'robots',
            'next', 'prev', 'alternates', 'alternate', 'images', 'image', 'canonical',
            'openGraphType', 'openGraphSiteName', 'twitterCard', 'twitterSite', 'twitterCreator',
        ];

        foreach ($directives as $directive) {
            $this->assertSame(
                $this->compile('@seo'.ucfirst($directive)."('x')"),
                $this->compile("@{$directive}('x')"),
                $directive
            );
        }

        $this->assertSame($this->compile("@seoCopyright('x')"), $this->compile("@copyrights('x')"));
    }

    public function test_the_copyrights_element_is_the_copyright_element(): void
    {
        seo()->copyrights->set('Pharaonic');

        $this->assertSame(seo()->copyright, seo()->copyrights);
        $this->assertSame('Pharaonic', seo()->copyright->get());
    }

    public function test_the_copyrights_default_and_model_key_are_still_read(): void
    {
        config()->set('pharaonic.seo.default.copyrights', 'Default');

        $this->assertSame('Default', (new Manager)->copyright->get());

        seo()->model(new Article(['copyrights' => 'Model']));

        $this->assertSame('Model', seo()->copyright->get());
    }

    public function test_the_2x_configuration_file_is_still_read(): void
    {
        // 2.x published config/Pharaonic/seo.php, loaded as `Pharaonic.seo`.
        config()->set('Pharaonic.seo', [
            'default' => [
                'title' => 'Legacy',
                'open-graph' => ['type' => 'article'],
            ],
            'elements' => [],
        ]);

        $seo = new Manager;

        $this->assertSame('Legacy', $seo->title->get());
        $this->assertSame('article', $seo->openGraph->getType());
        $this->assertNull($seo->charset->get());
    }

    public function test_the_open_graph_default_key_is_still_read(): void
    {
        config()->set('pharaonic.seo.default', ['open-graph' => ['site_name' => 'Site']]);

        $this->assertSame('Site', (new Manager)->openGraph->getSiteName());
    }

    public function test_the_2x_publish_tags_still_publish_the_configuration(): void
    {
        foreach (['config', 'seo'] as $tag) {
            $this->assertSame(
                [$this->app->configPath('pharaonic/seo.php')],
                array_values(ServiceProvider::pathsToPublish(SEOServiceProvider::class, $tag)),
                $tag
            );
        }
    }
}
