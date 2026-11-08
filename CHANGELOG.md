# Changelog

All notable changes to this project are documented here.

## 12.1.0 - Unreleased

### Added

- `seo()->toArray()` returns the content of the tags that `render()` prints and the `images` list, by name and not escaped, including `og:*`, `twitter:*` and custom elements.

## 12.0.0 - 2026-11-03

### Compatibility

- Laravel 12 on PHP 8.2, 8.3, 8.4 or 8.5.
- The same API and output as 11.0.0, including the deprecated 2.x names.

## 11.0.0 - 2026-11-03

### Compatibility

- Laravel 11 on PHP 8.2, 8.3 or 8.4. PHP 8.1 is no longer supported on this line.
- The same API and output as 10.0.0, including the deprecated 2.x names.

## 10.0.0 - 2026-11-03

### Compatibility

- Laravel 10 on PHP 8.1, 8.2 or 8.3. PHP 8.0 is no longer supported on this line.
- The same API and output as 9.0.0, including the deprecated 2.x names.

## 9.0.0 - 2026-11-03

### Compatibility

- Laravel 9 on PHP 8.0, 8.1 or 8.2.
- The same API and output as 8.0.0, including the deprecated 2.x names.

## 8.0.0 - 2026-11-03

### Compatibility

- Laravel 8.75+ on PHP 8.0 or 8.1.
- The same API and output as 7.0.0, including the deprecated 2.x names.

## 7.0.0 - 2026-11-03

### Compatibility

- Laravel 7.30+ on PHP 8.0.
- The same API and output as 6.0.0, including the deprecated 2.x names.
- The tests run on Pest.

## 6.0.0 - 2026-11-03

The first release of the Laravel 6 line. It continues the 2.x code with the fixes and deprecations below.

### Added

- `seo`-prefixed Blade directives for every element, such as `@seoTitle`, `@seoKeyword` and `@seoOpenGraphType`.
- The `pharaonic.seo` configuration key, published to `config/pharaonic/seo.php` with the `pharaonic`, `laravel-seo`, `pharaonic-config` and `seo-config` tags.
- The `open_graph` key for Open Graph defaults and model data.
- The `copyright` element (`seo()->copyright`, `@seoCopyright`), rendered as `<meta name="copyright">`.
- Typed element properties on `Manager` (`seo()->title` is a `Title`) for IDEs and static analysis.
- Compatibility, feature and unit test suites, PHPStan and Laravel Pint.
- GitHub community files, Dependabot and a PHP 8.0 lowest/highest CI matrix.

### Changed

- The package requires `illuminate/container`, `illuminate/contracts`, `illuminate/database`, `illuminate/support` and `illuminate/view` instead of `laravel/framework`.
- Open Graph tags use the `property` attribute (`<meta property="og:title" ...>`), as the Open Graph protocol defines. 2.x used `name`.
- Errors throw `InvalidArgumentException` instead of `Exception`. Code that catches `Exception` still catches them.
- Configuration publishing is registered only in the console.
- The copyright tag is `<meta name="copyright">`. 2.x rendered `copyrights`, which is not a standard meta name.

### Deprecated

Each deprecated name still works. They are removed in the first line that supports PHP 8.6.

| Deprecated | Use |
|---|---|
| `@title`, `@keyword`, `@openGraphType` and the other unprefixed directives | `@seoTitle`, `@seoKeyword`, `@seoOpenGraphType` |
| The `Pharaonic.seo` key (`config/Pharaonic/seo.php`) | `pharaonic.seo` (`config/pharaonic/seo.php`) |
| The `open-graph` defaults and model key | `open_graph` |
| The `copyrights` element, defaults and model key, `@copyrights` and the `Copyrights` class | `copyright`, `@seoCopyright` and `Copyright` |
| The `config` and `seo` publish tags | `seo-config` |

### Fixed

- Every value is HTML-escaped. 2.x printed values as they were, so content from users could inject HTML.
- `seo()->model()` sets Open Graph values. 2.x threw `Element open-graph not found.`
- `seo()->model()` skips `null` values instead of throwing a `TypeError`.
- `getType()`, `getSiteName()`, `getCard()`, `getSite()` and `getCreator()` return `null` when nothing is set, instead of throwing a `TypeError`.
- `setSite('@site')` and `setCreator('@creator')` no longer render `@@site`, and the configured defaults get the `@` too.
- The Twitter card renders one `twitter:image`. 2.x rendered `twitter:image0`, `twitter:image1`, ... for several images, which Twitter does not read.
- `remove()` reindexes the list, so the Twitter image is still found after removing the first one.
- Tags no longer leak between requests on Laravel Octane: booting no longer resolves the shared manager.
- Tags no longer leak between jobs in a queue worker: each job starts with a fresh manager.
