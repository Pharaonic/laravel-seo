## Upgrading from 2.x

The 2.x API keeps working: every method, element, configuration key, publish tag and Blade directive is still there. The upgrade fixes output that 2.x got wrong and adds clearer names; the 2.x names stay as deprecated aliases.

### Requirements

The package now depends on `illuminate/*` components instead of `laravel/framework`, and has one line per Laravel version. Install the line of your Laravel version; this one requires PHP 8.0, 8.1 or 8.2 and Laravel 9.0+.

### Renamed APIs

| 2.x | Now |
| --- | --- |
| `@title`, `@keyword`, `@openGraphType` and the other unprefixed directives | `@seoTitle`, `@seoKeyword`, `@seoOpenGraphType` |
| `config/Pharaonic/seo.php` (`Pharaonic.seo`) | `config/pharaonic/seo.php` (`pharaonic.seo`) |
| The `open-graph` key in the defaults and models | `open_graph` |
| The `copyrights` element, key and `@copyrights` directive | `copyright`, `@seoCopyright` |
| The `config` and `seo` publish tags | `seo-config` |

A configuration file published by 2.x is still read. To move it, publish the new file, copy your values into it and delete the old one.

:::warning Deprecated Names
The 2.x names are deprecated. They keep working on every current line and are removed in the first line that supports PHP 8.6.
:::

### Changed Output

- Every value is HTML-escaped. If you passed values that were already escaped, such as `&amp;`, pass the plain text instead.
- Open Graph tags use the `property` attribute: `<meta property="og:title" ...>`.
- The copyright tag is `<meta name="copyright">` instead of `copyrights`.
- The Twitter card renders one `twitter:image`, the first image, instead of `twitter:image0`, `twitter:image1` and so on.
- Twitter usernames always get a single `@`, so `setSite('@site')` no longer renders `@@site`.

### Fixed Behavior

- `seo()->model()` sets the Open Graph values and skips `null` values.
- `getType()`, `getSiteName()`, `getCard()`, `getSite()` and `getCreator()` return `null` when nothing is set.
- `remove()` reindexes the list.
- Errors are `InvalidArgumentException`, which still extends `Exception`.
- Tags no longer leak between requests on Laravel Octane or between jobs in a queue worker.
