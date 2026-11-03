<?php

namespace Pharaonic\Laravel\SEO;

use Illuminate\View\Compilers\BladeCompiler;
use Pharaonic\Laravel\SEO\Classes\Manager;

class BladeDirectives
{
    /**
     * Deprecated 2.x directive => element directive, for elements renamed in 6.0.
     */
    private const RENAMED = [
        'copyrights' => 'copyright',
    ];

    /**
     * Register `@seo` and every element directive, as `@seoTitle` and as the deprecated 2.x `@title`.
     * The 2.x names are removed in the first line that supports PHP 8.6.
     */
    public function register(BladeCompiler $blade, Manager $seo): void
    {
        $blade->directive('seo', fn () => '<?php echo seo()->render(); ?>');

        $directives = $seo->getDirectives();

        foreach ($directives as $name => $directive) {
            $blade->directive('seo'.ucfirst($name), $directive);
            $blade->directive($name, $directive);
        }

        foreach (self::RENAMED as $name => $element) {
            if (isset($directives[$element])) {
                $blade->directive($name, $directives[$element]);
            }
        }
    }
}
