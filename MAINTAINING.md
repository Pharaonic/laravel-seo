# Maintaining This Package

This file records package-specific maintainer facts that are safe to keep in the public repository.

## Purpose

SEO meta tags for Laravel, set through the `seo()` manager, an Eloquent model implementing `SEOContract`, or Blade directives, and rendered by `@seo`.

## Supported Laravel lines

One branch per Laravel major, from `6.x` to the newest major. Each line's PHP range, `illuminate/*` constraint, and tools follow the Pharaonic Laravel line table. `6.x` runs PHPUnit, because Pest cannot install on Laravel 6; later lines run Pest. The 2.x releases (Laravel 9+) are superseded by the lines.

## Core library

None. The package is Laravel glue only; it builds on Laravel's container, configuration, Blade, and Eloquent.

## Public API invariants

Configuration keys (`pharaonic.seo.*`), publish tags, and registered Blade, Artisan, macro, and validation names are public API.

- `seo()` resolves the `pharaonic-seo` singleton, a `Classes\Manager`. Elements are read as properties (`seo()->title`), cards as `seo()->openGraph` and `seo()->twitter`.
- Custom elements extend `SingleElement` or `MultipleElement` and are added through `pharaonic.seo.elements`; each gets `@seo<Name>` and `@<name>` directives from its `getDirectives()`.
- Every rendered value is HTML-escaped; `@seo` echoes the rendered tags unescaped.
- `toArray()` reads the elements directly, never the rendered HTML, and returns the content of the rendered tags and the `images` list, not escaped. The manager does not implement `Arrayable`: the router would turn `return seo();` into JSON, and that response is HTML.
- `pharaonic-seo` holds the tags of one request or one queued job: booting must never resolve it (the directives use a manager of their own), and `JobProcessing` forgets it.
- Canonical directives are `seo`-prefixed (`@seoTitle`). The 2.x names are deprecated aliases: unprefixed directives, the `Pharaonic.seo` key (still read before `pharaonic.seo` when an application published the 2.x file), the `open-graph` key, the `copyrights` element (an alias of `copyright`, also as `@copyrights` and the `Copyrights` class), and the `config` and `seo` publish tags. They stay on every existing line and are removed only in the first line that supports PHP 8.6.

## Package-specific constraints

- `illuminate/database` is required only for the `Model` type of `Manager::model()`. `Illuminate\Queue\Events\JobProcessing` is only an event name, so `illuminate/queue` is not required.
- Outputs that were wrong in 2.x are fixed and listed in `tests/Unit/LegacyFixesTest.php` and the changelog; `tests/Compatibility` pins the 2.x behavior that is kept.

## Branch notes

Fixes land on the oldest affected line and are ported to every later line and `develop`.

## Documentation

https://pharaonic.dev/packages/laravel/seo

Keep this file factual and package-specific. General Pharaonic engineering policy belongs in the private engineering repository, not here.
