<?php

namespace Pharaonic\Laravel\SEO;

use Illuminate\Container\Container;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
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
        $this->mergeConfigFrom(__DIR__.'/../config/seo.php', 'pharaonic.seo');

        $this->app->singleton('pharaonic-seo', fn () => new Manager);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            // `config` and `seo` are the deprecated 2.x tags.
            $this->publishes([
                __DIR__.'/../config/seo.php' => $this->app->configPath('pharaonic/seo.php'),
            ], ['pharaonic', 'laravel-seo', 'pharaonic-config', 'seo-config', 'config', 'seo']);
        }

        // The directives come from a manager of their own, so booting never resolves `pharaonic-seo`:
        // an Octane worker boots once and would share those tags with every request.
        $this->callAfterResolving('blade.compiler', function (BladeCompiler $blade) {
            (new BladeDirectives)->register($blade, new Manager);
        });

        // A queue worker runs every job in one application, so each job starts with fresh tags.
        $this->callAfterResolving('events', function (Dispatcher $events) {
            $events->listen(JobProcessing::class, fn () => Container::getInstance()->forgetInstance('pharaonic-seo'));
        });
    }
}
