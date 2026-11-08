<?php

namespace Pharaonic\Laravel\SEO\Tests\Feature;

use Pharaonic\Laravel\SEO\Classes\Elements\MultipleElement;
use Pharaonic\Laravel\SEO\Classes\Elements\SingleElement;
use Pharaonic\Laravel\SEO\Tests\TestCase;

class ToArrayTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('pharaonic.seo.default', []);
    }

    public function test_it_returns_every_rendered_tag_by_name_in_the_order_of_render(): void
    {
        seo()->charset->set('utf-8');
        seo()->viewport->set('width=device-width');
        seo()->title->set('Home');
        seo()->description->set('About');
        seo()->keywords->set(['a', 'b']);
        seo()->author->set('Me');
        seo()->robots->set('index');
        seo()->copyright->set('Pharaonic');
        seo()->next->set('https://example.com/3');
        seo()->prev->set('https://example.com/1');
        seo()->alternates->add('ar', 'https://example.com/ar');
        seo()->alternates->add('fr', 'https://example.com/fr');
        seo()->images->add('https://example.com/a.png');
        seo()->images->add('https://example.com/b.png');
        seo()->canonical->set('https://example.com/');
        seo()->openGraph->setType('website')->setSiteName('Site');
        seo()->twitter->setCard('summary')->setSite('site')->setCreator('me');

        $this->assertSame([
            'charset' => 'utf-8',
            'viewport' => 'width=device-width',
            'title' => 'Home',
            'description' => 'About',
            'keywords' => 'a, b',
            'author' => 'Me',
            'robots' => 'index',
            'copyright' => 'Pharaonic',
            'next' => 'https://example.com/3',
            'prev' => 'https://example.com/1',
            'alternates' => ['ar' => 'https://example.com/ar', 'fr' => 'https://example.com/fr'],
            'images' => ['https://example.com/a.png', 'https://example.com/b.png'],
            'canonical' => 'https://example.com/',
            'og:url' => url()->current(),
            'og:locale' => app()->getLocale(),
            'og:locale:alternate' => ['ar', 'fr'],
            'og:type' => 'website',
            'og:site_name' => 'Site',
            'og:title' => 'Home',
            'og:description' => 'About',
            'og:image' => ['https://example.com/a.png', 'https://example.com/b.png'],
            'twitter:card' => 'summary',
            'twitter:site' => '@site',
            'twitter:creator' => '@me',
            'twitter:title' => 'Home',
            'twitter:description' => 'About',
            'twitter:image' => 'https://example.com/a.png',
        ], seo()->toArray());
    }

    public function test_it_has_one_value_for_every_rendered_tag(): void
    {
        $hostile = 'A "quoted" <b> & \'single\' &amp; مرحبا > end';

        seo()->title->set($hostile);
        seo()->description->set($hostile);
        seo()->keywords->set(['a', $hostile]);
        seo()->canonical->set('https://example.com/?a=1&b="2"');
        seo()->alternates->add('ar', 'https://example.com/ar?a=1&b=2');
        seo()->images->add('https://example.com/a.png?a=1&b=2');
        seo()->openGraph->setSiteName($hostile);

        $html = seo()->render();
        $tags = seo()->toArray();
        $values = [];

        // The images have no tag of their own: the cards print them.
        unset($tags['images']);

        array_walk_recursive($tags, function (string $value) use (&$values) {
            $values[] = $value;
        });

        $this->assertCount(count(explode(PHP_EOL, $html)), $values);

        foreach ($values as $value) {
            $this->assertStringContainsString(e($value), $html);
        }
    }

    public function test_it_returns_values_that_are_not_escaped(): void
    {
        seo()->title->set('Tom & "Jerry"');
        seo()->description->set('<b>\'x\'</b>');

        $tags = seo()->toArray();

        $this->assertSame('Tom & "Jerry"', $tags['title']);
        $this->assertSame('<b>\'x\'</b>', $tags['description']);
    }

    public function test_the_list_tags_are_lists_with_one_value(): void
    {
        seo()->alternates->add('ar', 'https://example.com/ar');
        seo()->images->add('https://example.com/a.png');

        $tags = seo()->toArray();

        $this->assertSame(['ar' => 'https://example.com/ar'], $tags['alternates']);
        $this->assertSame(['ar'], $tags['og:locale:alternate']);
        $this->assertSame(['https://example.com/a.png'], $tags['images']);
        $this->assertSame(['https://example.com/a.png'], $tags['og:image']);
    }

    public function test_it_leaves_out_the_tags_that_do_not_render(): void
    {
        seo()->alternates->set([['lang' => 'ar']]);

        $this->assertSame([
            'og:url' => url()->current(),
            'og:locale' => app()->getLocale(),
            'og:locale:alternate' => ['ar'],
        ], seo()->toArray());
    }

    public function test_it_includes_custom_elements_by_name_whatever_they_render(): void
    {
        config()->set('pharaonic.seo.elements', [
            'theme-color' => ToArrayThemeColor::class,
            'manifest' => ToArrayManifest::class,
            'verifications' => ToArrayVerifications::class,
        ]);

        seo()->{'theme-color'}->set('#000');
        seo()->{'manifest'}->set('/site.webmanifest');
        seo()->{'verifications'}->set(['first', 'second']);

        $tags = seo()->toArray();

        $this->assertSame('#000', $tags['theme-color']);
        $this->assertSame('/site.webmanifest', $tags['manifest']);
        $this->assertSame(['first', 'second'], $tags['verifications']);
    }

    public function test_it_leaves_out_empty_custom_elements(): void
    {
        config()->set('pharaonic.seo.elements', [
            'theme-color' => ToArrayThemeColor::class,
            'verifications' => ToArrayVerifications::class,
        ]);

        $tags = seo()->toArray();

        $this->assertArrayNotHasKey('theme-color', $tags);
        $this->assertArrayNotHasKey('verifications', $tags);
    }

    public function test_it_reads_the_values_when_it_is_called(): void
    {
        seo()->title->set('First');
        $this->assertSame('First', seo()->toArray()['title']);

        seo()->title->set('Second');
        $this->assertSame('Second', seo()->toArray()['title']);
    }

    public function test_a_route_returning_the_manager_still_responds_with_html(): void
    {
        $this->app['router']->get('seo-html', function () {
            seo()->title->set('Home');

            return seo();
        });

        $response = $this->get('seo-html');

        $response->assertOk();
        $this->assertStringContainsString('<title>Home</title>', (string) $response->getContent());
        $this->assertStringNotContainsString('json', (string) $response->headers->get('Content-Type'));
    }
}

class ToArrayThemeColor extends SingleElement
{
}

class ToArrayManifest extends SingleElement
{
    public function render()
    {
        return $this->get() ? '<link rel="manifest" href="'.e($this->get()).'">' : '';
    }
}

class ToArrayVerifications extends MultipleElement
{
    public function render()
    {
        $tags = [];

        foreach ($this->get() ?? [] as $value) {
            $tags[] = '<meta name="verification" content="'.e((string) $value).'">';
        }

        return implode(PHP_EOL, $tags);
    }
}
