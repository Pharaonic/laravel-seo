<?php

namespace Pharaonic\Laravel\SEO\Tests;

use Pharaonic\Laravel\SEO\Tests\Models\Article;

class SEOTest extends TestCase
{
    public function testSingleElementManipulation()
    {
        $this->assertSame(
            seo()->title->set('Pharaonic Package')->get(),
            'Pharaonic Package',
        );
    }

    public function testSEORendering()
    {
        seo()->charset->set('UTF-8');
        seo()->viewport->set('width=device-width, initial-scale=1');
        seo()->title->set('Pharaonic Package');
        seo()->description->set('Pharaonic Package Description');
        seo()->keywords->set('Pharaonic, Package, SEO');
        seo()->author->set('Raggi');
        seo()->robots->set('index, follow');
        seo()->copyrights->set('Pharaonic 2024');
        seo()->next->set('https://pharaonic.dev/next');
        seo()->prev->set('https://pharaonic.dev/prev');
        seo()->alternates->add('en', 'https://pharaonic.dev/en');
        seo()->alternates->add('ar', 'https://pharaonic.dev/ar');
        seo()->images->add('https://pharaonic.dev/logo.png');
        seo()->canonical->set('https://pharaonic.dev');

        // The tags are joined with PHP_EOL, which is "\r\n" on Windows.
        $this->assertSame(
            str_replace("\n", PHP_EOL, <<<'HTML'
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Pharaonic Package</title>
            <meta name="description" content="Pharaonic Package Description">
            <meta name="keywords" content="Pharaonic, Package, SEO">
            <meta name="author" content="Raggi">
            <meta name="robots" content="index, follow">
            <meta name="copyright" content="Pharaonic 2024">
            <link rel="next" href="https://pharaonic.dev/next">
            <link rel="prev" href="https://pharaonic.dev/prev">
            <link rel="alternate" hreflang="en" href="https://pharaonic.dev/en">
            <link rel="alternate" hreflang="ar" href="https://pharaonic.dev/ar">
            <link rel="canonical" href="https://pharaonic.dev">
            <meta property="og:url" content="http://localhost">
            <meta property="og:locale" content="en">
            <meta property="og:locale:alternate" content="en">
            <meta property="og:locale:alternate" content="ar">
            <meta property="og:type" content="website">
            <meta property="og:title" content="Pharaonic Package">
            <meta property="og:description" content="Pharaonic Package Description">
            <meta property="og:image" content="https://pharaonic.dev/logo.png">
            <meta name="twitter:card" content="summary">
            <meta name="twitter:title" content="Pharaonic Package">
            <meta name="twitter:description" content="Pharaonic Package Description">
            <meta name="twitter:image" content="https://pharaonic.dev/logo.png">
            HTML),
            seo()->render()
        );
    }

    public function testSEOFromModel()
    {
        $article = new Article([
            'title' => 'Article Title',
            'description' => 'Article Description',
            'keywords' => 'Article, Keywords',
        ]);

        seo()->model($article);

        $this->assertSame(seo()->title->get(), $article->title);
        $this->assertSame(seo()->description->get(), $article->description);
        $this->assertSame(seo()->keywords->get(), explode(', ', $article->keywords));
        $this->assertSame(seo()->twitter->getCard(), 'app');
    }
}
