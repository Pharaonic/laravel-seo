<?php

namespace Pharaonic\Laravel\SEO\Tests\Feature;

use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Pharaonic\Laravel\SEO\Classes\Elements\SingleElement;
use Pharaonic\Laravel\SEO\Classes\Manager;
use Pharaonic\Laravel\SEO\Tests\Models\Article;
use Pharaonic\Laravel\SEO\Tests\TestCase;
use stdClass;

class ManagerTest extends TestCase
{
    public function test_it_reads_the_defaults(): void
    {
        config()->set('pharaonic.seo.default', [
            'title' => 'Site',
            'keywords' => 'a, b',
            'open_graph' => ['type' => 'article', 'site_name' => 'Site'],
            'twitter' => ['card' => 'summary', 'site' => 'site', 'creator' => '@me'],
        ]);

        $seo = new Manager;

        $this->assertSame('Site', $seo->title->get());
        $this->assertSame(['a', 'b'], $seo->keywords->get());
        $this->assertSame('article', $seo->openGraph->getType());
        $this->assertSame('Site', $seo->openGraph->getSiteName());
        $this->assertSame('@site', $seo->twitter->getSite());
        $this->assertSame('@me', $seo->twitter->getCreator());
    }

    public function test_it_adds_custom_elements_from_the_configuration(): void
    {
        config()->set('pharaonic.seo.elements', ['theme-color' => ThemeColor::class]);
        config()->set('pharaonic.seo.default.theme-color', '#000');

        $seo = new Manager;

        $this->assertInstanceOf(ThemeColor::class, $seo->{'theme-color'});
        $this->assertStringContainsString('<meta name="theme-color" content="#000">', $seo->render());
        $this->assertArrayHasKey('themeColor', $seo->getDirectives());
    }

    public function test_it_rejects_an_element_class_that_is_not_an_element(): void
    {
        config()->set('pharaonic.seo.elements', ['bad' => stdClass::class]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Class stdClass must extends SingleElement or MultipleElement.');

        new Manager;
    }

    public function test_it_rejects_an_unknown_element(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Element missing not found.');

        seo()->missing;
    }

    public function test_it_sets_the_elements_and_cards_from_a_model(): void
    {
        seo()->model(new Article([
            'title' => 'Article',
            'description' => null,
            'keywords' => 'a, b',
            'open_graph' => ['type' => 'article', 'site_name' => 'Site'],
        ]));

        $this->assertSame('Article', seo()->title->get());
        $this->assertNull(seo()->description->get());
        $this->assertSame(['a', 'b'], seo()->keywords->get());
        $this->assertSame('article', seo()->openGraph->getType());
        $this->assertSame('Site', seo()->openGraph->getSiteName());
        $this->assertSame('app', seo()->twitter->getCard());
    }

    public function test_it_casts_scalar_model_values_to_strings(): void
    {
        seo()->model(new Article(['title' => 2026]));

        $this->assertSame('2026', seo()->title->get());
    }

    public function test_it_rejects_a_model_without_the_contract(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Model must implement SEOContract');

        seo()->model(new class extends Model
        {
        });
    }

    public function test_it_rejects_an_unknown_model_key(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Element subtitle not found.');

        seo()->model(new Article(['subtitle' => 'x']));
    }
}

class ThemeColor extends SingleElement
{
}
