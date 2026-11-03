<?php

use Illuminate\Database\Eloquent\Model;
use Pharaonic\Laravel\SEO\Classes\Elements\SingleElement;
use Pharaonic\Laravel\SEO\Classes\Manager;
use Pharaonic\Laravel\SEO\Tests\Models\Article;

class ThemeColor extends SingleElement
{
}

test('it reads the defaults', function () {
    config()->set('pharaonic.seo.default', [
        'title' => 'Site',
        'keywords' => 'a, b',
        'open_graph' => ['type' => 'article', 'site_name' => 'Site'],
        'twitter' => ['card' => 'summary', 'site' => 'site', 'creator' => '@me'],
    ]);

    $seo = new Manager;

    expect($seo->title->get())->toBe('Site')
        ->and($seo->keywords->get())->toBe(['a', 'b'])
        ->and($seo->openGraph->getType())->toBe('article')
        ->and($seo->openGraph->getSiteName())->toBe('Site')
        ->and($seo->twitter->getSite())->toBe('@site')
        ->and($seo->twitter->getCreator())->toBe('@me');
});

test('it adds custom elements from the configuration', function () {
    config()->set('pharaonic.seo.elements', ['theme-color' => ThemeColor::class]);
    config()->set('pharaonic.seo.default.theme-color', '#000');

    $seo = new Manager;

    expect($seo->{'theme-color'})->toBeInstanceOf(ThemeColor::class)
        ->and($seo->render())->toContain('<meta name="theme-color" content="#000">')
        ->and($seo->getDirectives())->toHaveKey('themeColor');
});

test('it rejects an element class that is not an element', function () {
    config()->set('pharaonic.seo.elements', ['bad' => stdClass::class]);

    new Manager;
})->throws(InvalidArgumentException::class, 'Class stdClass must extends SingleElement or MultipleElement.');

test('it rejects an unknown element', function () {
    seo()->missing;
})->throws(InvalidArgumentException::class, 'Element missing not found.');

test('it sets the elements and cards from a model', function () {
    seo()->model(new Article([
        'title' => 'Article',
        'description' => null,
        'keywords' => 'a, b',
        'open_graph' => ['type' => 'article', 'site_name' => 'Site'],
    ]));

    expect(seo()->title->get())->toBe('Article')
        ->and(seo()->description->get())->toBeNull()
        ->and(seo()->keywords->get())->toBe(['a', 'b'])
        ->and(seo()->openGraph->getType())->toBe('article')
        ->and(seo()->openGraph->getSiteName())->toBe('Site')
        ->and(seo()->twitter->getCard())->toBe('app');
});

test('it casts scalar model values to strings', function () {
    seo()->model(new Article(['title' => 2026]));

    expect(seo()->title->get())->toBe('2026');
});

test('it rejects a model without the contract', function () {
    seo()->model(new class extends Model
    {
    });
})->throws(InvalidArgumentException::class, 'Model must implement SEOContract');

test('it rejects an unknown model key', function () {
    seo()->model(new Article(['subtitle' => 'x']));
})->throws(InvalidArgumentException::class, 'Element subtitle not found.');
