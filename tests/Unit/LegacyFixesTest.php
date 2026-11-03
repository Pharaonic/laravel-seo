<?php

namespace Pharaonic\Laravel\SEO\Tests\Unit;

use Illuminate\Contracts\Queue\Job;
use Illuminate\Queue\Events\JobProcessing;
use Mockery;
use Pharaonic\Laravel\SEO\Classes\Manager;
use Pharaonic\Laravel\SEO\Tests\Models\Article;
use Pharaonic\Laravel\SEO\Tests\TestCase;

/**
 * Each test covers a 2.x behavior that was wrong and is fixed from 6.0.0.
 */
class LegacyFixesTest extends TestCase
{
    public function test_it_escapes_every_value(): void
    {
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

        $html = seo()->render();

        $this->assertStringNotContainsString('<script>', $html);
        $this->assertStringNotContainsString('"><', $html);
        $this->assertStringContainsString('<title>&quot;&gt;&lt;script&gt;alert(1)&lt;/script&gt;</title>', $html);
    }

    public function test_open_graph_tags_use_the_property_attribute(): void
    {
        seo()->title->set('Home');

        $html = seo()->openGraph->render();

        $this->assertStringContainsString('<meta property="og:title" content="Home">', $html);
        $this->assertStringNotContainsString('name="og:', $html);
    }

    public function test_a_model_sets_the_open_graph_card(): void
    {
        // 2.x threw "Element open-graph not found."
        seo()->model(new Article(['open-graph' => ['type' => 'article']]));

        $this->assertSame('article', seo()->openGraph->getType());
    }

    public function test_a_model_skips_null_values(): void
    {
        // 2.x threw a TypeError.
        seo()->model(new Article(['title' => null]));

        $this->assertNull(seo()->title->get());
    }

    public function test_card_getters_return_null_when_nothing_is_set(): void
    {
        // 2.x threw a TypeError.
        config()->set('pharaonic.seo.default', []);
        $seo = new Manager;

        $this->assertNull($seo->openGraph->getType());
        $this->assertNull($seo->openGraph->getSiteName());
        $this->assertNull($seo->twitter->getCard());
        $this->assertNull($seo->twitter->getSite());
        $this->assertNull($seo->twitter->getCreator());
    }

    public function test_twitter_usernames_get_a_single_at_sign(): void
    {
        // 2.x rendered `@@site`.
        seo()->twitter->setSite('@site')->setCreator('creator');

        $this->assertSame('@site', seo()->twitter->getSite());
        $this->assertSame('@creator', seo()->twitter->getCreator());
    }

    public function test_twitter_renders_a_single_image(): void
    {
        // 2.x rendered `twitter:image0`, `twitter:image1`, ...
        seo()->images->add('/a.png')->add('/b.png');

        $html = seo()->twitter->render();

        $this->assertStringContainsString('<meta name="twitter:image" content="/a.png">', $html);
        $this->assertStringNotContainsString('/b.png', $html);
        $this->assertStringNotContainsString('twitter:image0', $html);
    }

    public function test_removing_a_value_reindexes_the_list(): void
    {
        // 2.x kept the gap, so `twitter:image` read an undefined `$images[0]`.
        seo()->images->add('/a.png')->add('/b.png')->remove(0);

        $this->assertSame(['/b.png'], seo()->images->get());
        $this->assertStringContainsString('<meta name="twitter:image" content="/b.png">', seo()->twitter->render());
    }

    public function test_the_copyright_tag_is_named_copyright(): void
    {
        // 2.x rendered `<meta name="copyrights">`.
        seo()->copyrights->set('Pharaonic');

        $this->assertStringContainsString('<meta name="copyright" content="Pharaonic">', seo()->render());
        $this->assertStringNotContainsString('copyrights', seo()->render());
    }

    public function test_booting_does_not_resolve_the_shared_manager(): void
    {
        // 2.x resolved it while booting, so an Octane worker shared its tags with every request.
        $this->app->make('blade.compiler');

        $this->assertFalse($this->app->resolved('pharaonic-seo'));
    }

    public function test_each_queued_job_starts_with_fresh_tags(): void
    {
        seo()->title->set('Previous job');

        $this->app->make('events')->dispatch(new JobProcessing('sync', Mockery::mock(Job::class)));

        $this->assertNull(seo()->title->get());
    }
}
