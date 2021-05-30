<?php

namespace Pharaonic\Laravel\SEO;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Pharaonic\Laravel\SEO\SEO;

class SEOServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // 
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Initialization
        $this->app->instance('SEO', new SEO);


        ////////////////////////////////////////////////////// Blade


        // Generate ALL
        Blade::directive('seo', function () {
            return '<?php echo seo()->generate(); ?>';
        });

        // Generate Default
        Blade::directive('seoDefault', function () {
            return '<?php echo seo()->generate(\'default\'); ?>';
        });

        // Generate Twitter
        Blade::directive('seoTwitter', function () {
            return '<?php echo seo()->generate(\'twitter\'); ?>';
        });

        // Generate OpenGraph
        Blade::directive('seoOpenGraph', function () {
            return '<?php echo seo()->generate(\'open-graph\'); ?>';
        });

        // Tags Directive
        foreach ([
            'title', 'description', 'keywords', 'author', 'copyrights', 'robots',
            'canonical', 'prev', 'next', 'alternate',
            'meta', 'charset',
            'og', 'twitter', 'image'
        ] as $n) {
            Blade::directive($n, function ($data) use ($n) {
                return '<?php seo()->set' . ucfirst($n) . '(' . $data . '); ?>';
            });
        }
    }
}
