<?php

use Pharaonic\Laravel\SEO\Classes\Elements\MultipleElement;
use Pharaonic\Laravel\SEO\Classes\Elements\SingleElement;

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

beforeEach(function () {
    config()->set('pharaonic.seo.default', []);
});

test('it returns every rendered tag by name in the order of render', function () {
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

    expect(seo()->toArray())->toBe([
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
    ]);
});

test('it has one value for every rendered tag', function () {
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

    expect($values)->toHaveCount(count(explode(PHP_EOL, $html)));

    foreach ($values as $value) {
        expect($html)->toContain(e($value));
    }
});

test('it returns values that are not escaped', function () {
    seo()->title->set('Tom & "Jerry"');
    seo()->description->set('<b>\'x\'</b>');

    $tags = seo()->toArray();

    expect($tags['title'])->toBe('Tom & "Jerry"')
        ->and($tags['description'])->toBe('<b>\'x\'</b>');
});

test('the list tags are lists with one value', function () {
    seo()->alternates->add('ar', 'https://example.com/ar');
    seo()->images->add('https://example.com/a.png');

    $tags = seo()->toArray();

    expect($tags['alternates'])->toBe(['ar' => 'https://example.com/ar'])
        ->and($tags['og:locale:alternate'])->toBe(['ar'])
        ->and($tags['images'])->toBe(['https://example.com/a.png'])
        ->and($tags['og:image'])->toBe(['https://example.com/a.png']);
});

test('it leaves out the tags that do not render', function () {
    seo()->alternates->set([['lang' => 'ar']]);

    expect(seo()->toArray())->toBe([
        'og:url' => url()->current(),
        'og:locale' => app()->getLocale(),
        'og:locale:alternate' => ['ar'],
    ]);
});

test('it includes custom elements by name whatever they render', function () {
    config()->set('pharaonic.seo.elements', [
        'theme-color' => ToArrayThemeColor::class,
        'manifest' => ToArrayManifest::class,
        'verifications' => ToArrayVerifications::class,
    ]);

    seo()->{'theme-color'}->set('#000');
    seo()->{'manifest'}->set('/site.webmanifest');
    seo()->{'verifications'}->set(['first', 'second']);

    $tags = seo()->toArray();

    expect($tags['theme-color'])->toBe('#000')
        ->and($tags['manifest'])->toBe('/site.webmanifest')
        ->and($tags['verifications'])->toBe(['first', 'second']);
});

test('it leaves out empty custom elements', function () {
    config()->set('pharaonic.seo.elements', [
        'theme-color' => ToArrayThemeColor::class,
        'verifications' => ToArrayVerifications::class,
    ]);

    $tags = seo()->toArray();

    expect($tags)->not->toHaveKey('theme-color')
        ->and($tags)->not->toHaveKey('verifications');
});

test('it reads the values when it is called', function () {
    seo()->title->set('First');
    expect(seo()->toArray()['title'])->toBe('First');

    seo()->title->set('Second');
    expect(seo()->toArray()['title'])->toBe('Second');
});

test('a route returning the manager still responds with html', function () {
    $this->app['router']->get('seo-html', function () {
        seo()->title->set('Home');

        return seo();
    });

    $response = $this->get('seo-html');

    $response->assertOk();

    expect((string) $response->getContent())->toContain('<title>Home</title>')
        ->and((string) $response->headers->get('Content-Type'))->not->toContain('json');
});
