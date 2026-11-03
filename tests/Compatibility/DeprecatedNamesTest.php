<?php

use Illuminate\Support\ServiceProvider;
use Pharaonic\Laravel\SEO\Classes\Manager;
use Pharaonic\Laravel\SEO\SEOServiceProvider;
use Pharaonic\Laravel\SEO\Tests\Models\Article;

// The deprecated 2.x names keep working until the first line that supports PHP 8.6.

test('the 2.x directives compile like the canonical ones', function () {
    $directives = [
        'charset', 'viewport', 'title', 'description', 'keywords', 'keyword', 'author', 'robots',
        'next', 'prev', 'alternates', 'alternate', 'images', 'image', 'canonical',
        'openGraphType', 'openGraphSiteName', 'twitterCard', 'twitterSite', 'twitterCreator',
    ];

    foreach ($directives as $directive) {
        expect($this->compile("@{$directive}('x')"))->toBe($this->compile('@seo'.ucfirst($directive)."('x')"));
    }

    expect($this->compile("@copyrights('x')"))->toBe($this->compile("@seoCopyright('x')"));
});

test('the copyrights element is the copyright element', function () {
    seo()->copyrights->set('Pharaonic');

    expect(seo()->copyrights)->toBe(seo()->copyright)
        ->and(seo()->copyright->get())->toBe('Pharaonic');
});

test('the copyrights default and model key are still read', function () {
    config()->set('pharaonic.seo.default.copyrights', 'Default');

    expect((new Manager)->copyright->get())->toBe('Default');

    seo()->model(new Article(['copyrights' => 'Model']));

    expect(seo()->copyright->get())->toBe('Model');
});

test('the 2.x configuration file is still read', function () {
    // 2.x published config/Pharaonic/seo.php, loaded as `Pharaonic.seo`.
    config()->set('Pharaonic.seo', [
        'default' => [
            'title' => 'Legacy',
            'open-graph' => ['type' => 'article'],
        ],
        'elements' => [],
    ]);

    $seo = new Manager;

    expect($seo->title->get())->toBe('Legacy')
        ->and($seo->openGraph->getType())->toBe('article')
        ->and($seo->charset->get())->toBeNull();
});

test('the open-graph default key is still read', function () {
    config()->set('pharaonic.seo.default', ['open-graph' => ['site_name' => 'Site']]);

    expect((new Manager)->openGraph->getSiteName())->toBe('Site');
});

test('the 2.x publish tags still publish the configuration', function (string $tag) {
    expect(array_values(ServiceProvider::pathsToPublish(SEOServiceProvider::class, $tag)))
        ->toBe([$this->app->configPath('pharaonic/seo.php')]);
})->with(['config', 'seo']);
