<?php

namespace Pharaonic\Laravel\SEO;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Pharaonic\Laravel\SEO\Classes\Manager;

class SEOServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('pharaonic-seo', fn () => new Manager);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Config
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'Pharaonic.seo');
        $this->publishes([
            __DIR__ . '/../config/config.php' => config_path('Pharaonic/seo.php'),
        ], ['config', 'seo', 'pharaonic']);

        // Blade Directives
        Blade::directive('seo', fn () => '<?php echo seo()->render(); ?>');
        foreach (seo()->getDirectives() as $name => $directive) {
            Blade::directive($name, $directive);
        }
    }
}
