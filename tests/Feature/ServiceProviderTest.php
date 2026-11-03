<?php

use Illuminate\Support\ServiceProvider;
use Pharaonic\Laravel\SEO\Classes\Manager;
use Pharaonic\Laravel\SEO\SEOServiceProvider;

test('it registers the service provider', function () {
    expect($this->app->getProviders(SEOServiceProvider::class))->not->toBeEmpty();
});

test('it merges the configuration under pharaonic.seo', function () {
    expect(config('pharaonic.seo.default.charset'))->toBe('UTF-8')
        ->and(config('pharaonic.seo.default.open_graph'))->toBe(['type' => 'website'])
        ->and(config('pharaonic.seo.elements'))->toBe([]);
});

test('it binds one manager', function () {
    expect(seo())->toBeInstanceOf(Manager::class)
        ->and($this->app->make('pharaonic-seo'))->toBe(seo());
});

test('it publishes the configuration with the pharaonic tags', function (string $tag) {
    $paths = ServiceProvider::pathsToPublish(SEOServiceProvider::class, $tag);

    expect(array_values($paths))->toBe([$this->app->configPath('pharaonic/seo.php')])
        ->and(realpath((string) array_key_first($paths)))->toBe(realpath(__DIR__.'/../../config/seo.php'));
})->with(['pharaonic', 'laravel-seo', 'pharaonic-config', 'seo-config']);

test('it registers the canonical directives', function (string $template, string $php) {
    expect($this->compile($template))->toBe($php);
})->with([
    ['@seo', '<?php echo seo()->render(); ?>'],
    ["@seoTitle('Home')", "<?php seo()->title->set('Home'); ?>"],
    ["@seoKeywords('a, b')", "<?php seo()->keywords->set('a, b'); ?>"],
    ["@seoKeyword('c')", "<?php seo()->keywords->add('c'); ?>"],
    ["@seoAlternate('ar', '/ar')", "<?php seo()->alternates->add('ar', '/ar'); ?>"],
    ["@seoImage('/a.png')", "<?php seo()->images->add('/a.png'); ?>"],
    ["@seoPrev('/1')", "<?php seo()->prev->set('/1'); ?>"],
    ["@seoOpenGraphType('article')", "<?php seo()->openGraph->setType('article'); ?>"],
    ["@seoOpenGraphSiteName('Site')", "<?php seo()->openGraph->setSiteName('Site'); ?>"],
    ["@seoTwitterCard('app')", "<?php seo()->twitter->setCard('app'); ?>"],
    ["@seoTwitterSite('site')", "<?php seo()->twitter->setSite('site'); ?>"],
    ["@seoTwitterCreator('me')", "<?php seo()->twitter->setCreator('me'); ?>"],
]);
