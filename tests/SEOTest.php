<?php

namespace Pharaonic\Laravel\SEO\Tests;

use Pharaonic\Laravel\SEO\Tests\Models\Article;
use Pharaonic\Laravel\SEO\Tests\TestCase;

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
        seo()->next->set('https://pharaonic.io/next');
        seo()->prev->set('https://pharaonic.io/prev');
        seo()->alternates->add('en', 'https://pharaonic.io/en');
        seo()->alternates->add('ar', 'https://pharaonic.io/ar');
        seo()->images->add('https://pharaonic.io/logo.png');
        seo()->canonical->set('https://pharaonic.io');

        $this->assertSame(
            seo()->render(),
            <<<HTML
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Pharaonic Package</title>
            <meta name="description" content="Pharaonic Package Description">
            <meta name="keywords" content="Pharaonic, Package, SEO">
            <meta name="author" content="Raggi">
            <meta name="robots" content="index, follow">
            <meta name="copyrights" content="Pharaonic 2024">
            <link rel="next" href="https://pharaonic.io/next">
            <link rel="prev" href="https://pharaonic.io/prev">
            <link rel="alternate" hreflang="en" href="https://pharaonic.io/en">
            <link rel="alternate" hreflang="ar" href="https://pharaonic.io/ar">
            <link rel="canonical" href="https://pharaonic.io">
            <meta name="og:url" content="http://localhost">
            <meta name="og:locale" content="en">
            <meta name="og:locale:alternate" content="en">
            <meta name="og:locale:alternate" content="ar">
            <meta name="og:type" content="website">
            <meta name="og:title" content="Pharaonic Package">
            <meta name="og:description" content="Pharaonic Package Description">
            <meta name="og:image" content="https://pharaonic.io/logo.png">
            <meta name="twitter:card" content="summary">
            <meta name="twitter:title" content="Pharaonic Package">
            <meta name="twitter:description" content="Pharaonic Package Description">
            <meta name="twitter:image" content="https://pharaonic.io/logo.png">
            HTML
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
