<?php

use Illuminate\Contracts\Queue\Job;
use Illuminate\Queue\Events\JobProcessing;
use Pharaonic\Laravel\SEO\Classes\Manager;
use Pharaonic\Laravel\SEO\Tests\Models\Article;

// Each test covers a 2.x behavior that was wrong and is fixed from 6.0.0.

test('it escapes every value', function () {
    $payload = '"><script>alert(1)</script>';

    seo()->title->set($payload);
    seo()->description->set($payload);
    seo()->keywords->set([$payload]);
    seo()->canonical->set($payload);
    seo()->next->set($payload);
    seo()->prev->set($payload);
    seo()->alternates->add($payload, $payload);
    seo()->images->add($payload);
    seo()->openGraph->setSiteName($payload);
    seo()->twitter->setCreator($payload);

    expect(seo()->render())
        ->not->toContain('<script>')
        ->not->toContain('"><')
        ->toContain('<title>&quot;&gt;&lt;script&gt;alert(1)&lt;/script&gt;</title>');
});

test('open graph tags use the property attribute', function () {
    seo()->title->set('Home');

    expect(seo()->openGraph->render())
        ->toContain('<meta property="og:title" content="Home">')
        ->not->toContain('name="og:');
});

test('a model sets the open graph card', function () {
    // 2.x threw "Element open-graph not found."
    seo()->model(new Article(['open-graph' => ['type' => 'article']]));

    expect(seo()->openGraph->getType())->toBe('article');
});

test('a model skips null values', function () {
    // 2.x threw a TypeError.
    seo()->model(new Article(['title' => null]));

    expect(seo()->title->get())->toBeNull();
});

test('card getters return null when nothing is set', function () {
    // 2.x threw a TypeError.
    config()->set('pharaonic.seo.default', []);
    $seo = new Manager;

    expect($seo->openGraph->getType())->toBeNull()
        ->and($seo->openGraph->getSiteName())->toBeNull()
        ->and($seo->twitter->getCard())->toBeNull()
        ->and($seo->twitter->getSite())->toBeNull()
        ->and($seo->twitter->getCreator())->toBeNull();
});

test('twitter usernames get a single at sign', function () {
    // 2.x rendered `@@site`.
    seo()->twitter->setSite('@site')->setCreator('creator');

    expect(seo()->twitter->getSite())->toBe('@site')
        ->and(seo()->twitter->getCreator())->toBe('@creator');
});

test('twitter renders a single image', function () {
    // 2.x rendered `twitter:image0`, `twitter:image1`, ...
    seo()->images->add('/a.png')->add('/b.png');

    expect(seo()->twitter->render())
        ->toContain('<meta name="twitter:image" content="/a.png">')
        ->not->toContain('/b.png')
        ->not->toContain('twitter:image0');
});

test('removing a value reindexes the list', function () {
    // 2.x kept the gap, so `twitter:image` read an undefined `$images[0]`.
    seo()->images->add('/a.png')->add('/b.png')->remove(0);

    expect(seo()->images->get())->toBe(['/b.png'])
        ->and(seo()->twitter->render())->toContain('<meta name="twitter:image" content="/b.png">');
});

test('the copyright tag is named copyright', function () {
    // 2.x rendered `<meta name="copyrights">`.
    seo()->copyrights->set('Pharaonic');

    expect(seo()->render())
        ->toContain('<meta name="copyright" content="Pharaonic">')
        ->not->toContain('copyrights');
});

test('booting does not resolve the shared manager', function () {
    // 2.x resolved it while booting, so an Octane worker shared its tags with every request.
    $this->app->make('blade.compiler');

    expect($this->app->resolved('pharaonic-seo'))->toBeFalse();
});

test('each queued job starts with fresh tags', function () {
    seo()->title->set('Previous job');

    $this->app->make('events')->dispatch(new JobProcessing('sync', Mockery::mock(Job::class)));

    expect(seo()->title->get())->toBeNull();
});
